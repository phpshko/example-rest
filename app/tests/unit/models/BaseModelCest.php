<?php

use app\tests\fixtures\UserFixture;

class BaseModelCest
{
    protected $existsUser;
    protected $photoData;

    public function _before(UnitTester $I)
    {
        $I->haveFixtures(['users' => UserFixture::class]);
        $this->existsUser = $I->grabFixture('users')->data['user1'];
        $this->photoData = base64_encode(file_get_contents(__DIR__ . '/../../_data/photo1.jpg'));
    }

    public function _after(UnitTester $I)
    {
    }
}