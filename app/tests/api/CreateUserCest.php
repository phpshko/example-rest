<?php


use Codeception\Util\HttpCode;

class CreateUserCest extends BaseApiCest
{
    protected $photoData;

    public function _before(ApiTester $I)
    {
        parent::_before($I);

        $this->photoData = base64_encode(file_get_contents(__DIR__ . '/../_data/photo1.jpg'));

        $I->loginAs($this->adminEmail, $this->adminPassword);
    }

    public function accessUnauthorizedTest(ApiTester $I)
    {
        $I->logout();

        $I->wantTo('check that cant access to method unauthorized users');

        $I->sendPOST('/users');
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function accessFromUserTest(ApiTester $I)
    {
        $I->logout();
        $I->loginAs($this->userEmail, $this->userPassword);

        $I->wantTo('check that cant access to method with User role');

        $I->sendPOST('/users');
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function withoutDataTest(ApiTester $I)
    {
        $I->wantTo('create user without data');
        $I->sendPOST('/users');
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['email', 'name', 'password']);

        $I->wantTo('create user with wrong email format');
        $I->sendPOST('/users', [
            'email' => 'not_exists____gmail.com',
            'name'  => 'John'
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['email', 'password']);
        $I->dontSeeErrorsInResponse(['name', 'age']);
    }

    public function withoutNameTest(ApiTester $I)
    {
        $I->wantTo('create user without name');
        $I->sendPOST('/users', [
            'email'    => 'not_exists@gmail.com',
            'password' => '123456'
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['name']);
        $I->dontSeeErrorsInResponse(['email']);
    }

    public function notUniqueEmailTest(ApiTester $I)
    {
        $I->wantTo('create user with not unique email');
        $I->sendPOST('/users', [
            'email'    => $this->userEmail,
            'name'     => 'sdfsdf',
            'password' => '123456'
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['email']);
        $I->dontSeeErrorsInResponse(['name', 'age']);

        $I->wantTo('create user with short password');
        $I->sendPOST('/users', [
            'email'    => 'not_exists@gmail.com',
            'name'     => 'sdfsdf',
            'password' => '123'
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['password']);
        $I->dontSeeErrorsInResponse(['email', 'name']);
    }

    public function lessAgeTest(ApiTester $I)
    {
        $I->wantTo('create user with wrong (less) age');
        $I->sendPOST('/users', [
            'email' => 'not_exists@gmail.com',
            'name'  => 'John',
            'age'   => 12
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['age', 'password']);
    }

    public function greaterAgeTest(ApiTester $I)
    {
        $I->wantTo('create user with wrong (greater) age');
        $I->sendPOST('/users', [
            'email' => 'not_exists@gmail.com',
            'name'  => 'John',
            'age'   => 250
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['age', 'password']);
    }

    public function wrongGenderTest(ApiTester $I)
    {
        $I->wantTo('create user with wrong gender');
        $I->sendPOST('/users', [
            'email'    => 'not_exists@gmail.com',
            'password' => '123456',
            'name'     => 'John',
            'gender'   => 3
        ]);
        $I->seeResponseContainsJson(['success' => 0]);
        $I->seeErrorsInResponse(['gender']);
    }

    public function withImageTest(ApiTester $I)
    {
        $email = $this->getUniqueEmail();
        $name = 'John';

        $I->wantTo('create user with image');
        $I->sendPOST('/users', [
            'email'    => $email,
            'password' => '123456',
            'name'     => $name,
            'photo'    => $this->photoData
        ]);
        $I->seeResponseContainsJson(['success' => 1]);
        $I->seeResponseContainsJson(['model' => ['email' => $email]]);
        $I->seeResponseContainsJson(['model' => ['name' => $name]]);

        $response = json_decode($I->grabResponse(), true);
        $model = $response['model'];

        //Wait while rabbit processing
        $waitRabbit = function ($seconds = 10) use ($I, $model) {
            for ($i = 0; $i < $seconds; $i++) {
                $I->sendGET('/users/' . $model['id']);
                $response = json_decode($I->grabResponse(), true);

                if (
                    strpos($response['photo_origin_path'], 'uploads') !== false &&
                    strpos($response['photo_small_path'], 'uploads') !== false &&
                    strpos($response['photo_preview_path'], 'uploads') !== false
                ) {
                    $I->wantTo('check that photo save correctly');
                    $I->sendGET($response['photo_origin_path']);
                    $remoteEncode = base64_encode($I->grabResponse());

                    $I->assertTrue($this->photoData === $remoteEncode);

                    $I->wantTo('check size for resize image');
                    $I->sendGET($response['photo_small_path']);
                    $remoteSmall = \yii\imagine\Image::getImagine()->load($I->grabResponse());

                    $I->assertLessOrEquals(self::PHOTO_MAX_SIDE_SIZE, $remoteSmall->getSize()->getWidth());
                    $I->assertLessOrEquals(self::PHOTO_MAX_SIDE_SIZE, $remoteSmall->getSize()->getHeight());

                    $I->wantTo('check size for preview image');
                    $I->sendGET($response['photo_preview_path']);
                    $remoteSmall = \yii\imagine\Image::getImagine()->load($I->grabResponse());

                    $I->assertEquals(self::PHOTO_PREVIEW_SIZE, $remoteSmall->getSize()->getWidth());
                    $I->assertEquals(self::PHOTO_PREVIEW_SIZE, $remoteSmall->getSize()->getHeight());

                    return true;
                }

                sleep(1);
            }
            return false;
        };

        $I->wantTo('see what the rabbit processed photo');
        $I->assertTrue($waitRabbit());
    }


    public function createAndLoginTest(ApiTester $I)
    {
        $email = $this->getUniqueEmail();
        $name = 'John';
        $password = '123456';

        $I->wantTo('create user and login');
        $I->sendPOST('/users', [
            'email'    => $email,
            'name'     => $name,
            'password' => $password
        ]);
        $I->seeResponseContainsJson(['success' => 1]);

        $I->loginAs($email, $password);

        $I->sendGET('/users/' . $this->userId);
        $I->dontSeeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

}
