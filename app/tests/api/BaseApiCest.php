<?php

class BaseApiCest
{
    const PHOTO_MAX_SIDE_SIZE = 900;
    const PHOTO_PREVIEW_SIZE  = 100;

    protected $userEmail;
    protected $userId;
    protected $userPassword = '123456';

    protected $adminEmail;
    protected $adminId;
    protected $adminPassword = '123456';

    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');

        $fixtureUser = getFixtures('user')['user1'];
        $this->userId = $I->haveInDatabase('user', $fixtureUser);
        $this->userEmail = $fixtureUser['email'];

        $fixtureAdmin = getFixtures('user')['admin1'];
        $this->adminId = $I->haveInDatabase('user', $fixtureAdmin);
        $this->adminEmail = $fixtureAdmin['email'];
    }

    public function _after(ApiTester $I)
    {
    }

    /**
     * @return string
     */
    protected function getUniqueEmail()
    {
        return 'unique' . uniqid('', true) . '@email.com';
    }
}