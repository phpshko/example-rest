<?php

use app\models\User;

class UserCest extends BaseModelCest
{
    public function wrongEmailFormatTest(UnitTester $I)
    {
        $I->wantTo('check save with wrong email format');
        $model = new User();
        $model->email = 'fsdfd__fsd.dsf';
        $model->name = 'John';
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['email']);
        $I->dontSeeErrors($model, ['name']);
    }

    public function withoutNameTest(UnitTester $I)
    {
        $I->wantTo('check save without name');
        $model = new User();
        $model->email = 'new@gmail.com';
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['name']);
        $I->dontSeeErrors($model, ['email']);
    }

    public function notUniqueEmailTest(UnitTester $I)
    {
        $I->wantTo('check save with not unique email');
        $model = new User();
        $model->email = $this->existsUser['email'];
        $model->name = 'John';
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['email']);
        $I->dontSeeErrors($model, ['name']);
    }

    public function wrongGenderTest(UnitTester $I)
    {
        $I->wantTo('check save with wrong gender');
        $model = new User();
        $model->email = 'new@gmail.com';
        $model->name = 'John';
        $model->gender = 5;
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['gender']);
        $I->dontSeeErrors($model, ['email', 'name']);
    }

    public function lessAgeTest(UnitTester $I)
    {
        $I->wantTo('check save with wrong age');
        $model = new User();
        $model->email = 'new@gmail.com';
        $model->name = 'John';
        $model->age = 6;
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['age']);
        $I->dontSeeErrors($model, ['gender', 'email', 'name']);
    }

    public function greaterAgeTest(UnitTester $I)
    {
        $I->wantTo('check save with wrong age');
        $model = new User();
        $model->email = 'new@gmail.com';
        $model->name = 'John';
        $model->age = 200;
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['age']);
        $I->dontSeeErrors($model, ['gender', 'email', 'name']);
    }

    public function correctTest(UnitTester $I)
    {
        $I->wantTo('check save with correct data');
        $model = new User();
        $model->email = 'new@gmail.com';
        $model->role = User::ROLE_USER;
        $model->password = '123456';
        $model->name = 'John';
        $model->age = 25;
        $model->gender = User::GENDER_MALE;
        $I->assertTrue($model->save());
    }

    public function jwtTest(UnitTester $I)
    {
        $I->wantTo('check generation JWT');

        $model = User::findIdentity($this->existsUser['id']);
        $token = $model->getJWT();

        $I->assertGreaterThan(20, strlen($token));
        $findUser = User::findIdentityByAccessToken($token);
        $I->assertNotNull($findUser);
        $I->assertEquals($findUser->id, $model->id);
    }
}
