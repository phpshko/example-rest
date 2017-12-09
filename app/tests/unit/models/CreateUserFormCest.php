<?php

use app\forms\CreateUserForm;

class CreateUserFormCest extends BaseModelCest
{
    public function withoutNameAndPasswordTest(UnitTester $I)
    {
        $I->wantTo('check create without name and password');
        $model = new CreateUserForm([
            'email' => 'new@gmail.com',
        ]);
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['name', 'password']);
    }

    public function wrongFormatEmailTest(UnitTester $I)
    {
        $I->wantTo('check create with wrong format email');
        $model = new CreateUserForm([
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
        $model = new CreateUserForm([
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
        $model = new CreateUserForm([
            'email'    => 'new@gmail.com',
            'name'     => 'John',
            'password' => '123',
        ]);
        $I->assertFalse($model->save());
        $I->seeErrors($model, ['password']);
        $I->dontSeeErrors($model, ['email', 'name']);
    }

    public function correctDataTest(UnitTester $I)
    {
        $I->wantTo('check create with correct email, name and password');
        $model = new CreateUserForm([
            'email'    => 'new@gmail.com',
            'name'     => 'John',
            'password' => '123456',
        ]);
        $I->assertTrue($model->save());
        $I->assertNotNull($model->user);
    }
}
