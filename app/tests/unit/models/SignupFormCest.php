<?php

use app\forms\SignupForm;

class SignupFormCest extends BaseModelCest
{
    public function withoutNameAndPasswordTest(UnitTester $I)
    {
        $I->wantTo('check create without name and password');
        $model = new SignupForm([
            'email' => 'new@gmail.com',
        ]);
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['name', 'password']);
    }

    public function wrongEmailFormatTest(UnitTester $I)
    {
        $I->wantTo('check create with wrong format email');
        $model = new SignupForm([
            'email'    => 'new_gmail.com',
            'name'     => 'John',
            'password' => '123456',
        ]);
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['email']);
        $I->dontSeeErrors($model, ['name', 'password']);
    }

    public function notUniqueEmailTest(UnitTester $I)
    {
        $I->wantTo('check create with not unique email');
        $model = new SignupForm([
            'email'    => $this->existsUser['email'],
            'name'     => 'John',
            'password' => '123456',
        ]);
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['email']);
        $I->dontSeeErrors($model, ['name', 'password']);
    }

    public function shortPasswordTest(UnitTester $I)
    {
        $I->wantTo('check create with short password');
        $model = new SignupForm([
            'email'    => 'new@gmail.com',
            'name'     => 'John',
            'password' => '123',
        ]);
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['password']);
        $I->dontSeeErrors($model, ['email', 'name']);
    }

    public function correctTest(UnitTester $I)
    {
        $I->wantTo('check create with correct email, name and password');
        $model = new SignupForm([
            'email'    => 'new@gmail.com',
            'name'     => 'John',
            'password' => '123456',
        ]);
        $I->assertTrue($model->save());
        $I->assertNotNull($model->user);
    }
}
