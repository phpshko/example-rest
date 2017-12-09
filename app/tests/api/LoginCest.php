<?php


class LoginCest extends BaseApiCest
{
    public function incorrectEmailTest(ApiTester $I)
    {
        $I->wantTo('login with incorrect email');
        $I->sendPOST('/login', ['email' => 'not_exist_sadmin@gmail.com', 'password' => '123456']);
        $I->seeResponseContainsJson(['success' => 0]);
    }

    public function incorrectPasswordTest(ApiTester $I)
    {
        $I->wantTo('login with incorrect password');
        $I->sendPOST('/login', ['email' => $this->userEmail, 'password' => '123456___']);
        $I->seeResponseContainsJson(['success' => 0]);
    }

    public function correctPasswordTest(ApiTester $I)
    {
        $I->wantTo('login with correct password');
        $I->loginAs($this->userEmail, $this->userPassword);
    }
}
