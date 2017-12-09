<?php

use app\forms\LoginForm;

class LoginFormCest extends BaseModelCest
{
    public function wrongEmailFormatTest(UnitTester $I)
    {
        $I->wantTo('check login with incorrect email format');
        $model = new LoginForm([
            'email'    => 'not_existing_username___gmail.com',
            'password' => 'not_existing_password',
        ]);
        $I->assertFalse($model->login());
    }

    public function incorrectEmailAndPasswordTest(UnitTester $I)
    {
        $I->wantTo('check login with incorrect email and password');
        $model = new LoginForm([
            'email'    => 'not_existing_username@gmail.com',
            'password' => 'not_existing_password',
        ]);
        $I->assertFalse($model->login());
    }

    public function incorrectPasswordTest(UnitTester $I)
    {
        $I->wantTo('check login with incorrect password');
        $model = new LoginForm([
            'email'    => $this->existsUser['email'],
            'password' => 'not_existing_password',
        ]);
        $I->assertFalse($model->login());
    }

    public function correctEmailAndPasswordTest(UnitTester $I)
    {
        $I->wantTo('check login with correct email and password');
        $model = new LoginForm([
            'email'    => $this->existsUser['email'],
            'password' => '123456',//hash in fixtures
        ]);
        $I->assertTrue($model->login());
    }

}
