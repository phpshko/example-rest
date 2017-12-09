<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param string|array $attributes
     */
    public function seeErrorsInResponse($attributes)
    {
        foreach ((array)$attributes as $attribute) {
            $this->expect('see error for attribute ' . $attribute);
            $this->seeResponseJsonMatchesJsonPath('$.errors.' . $attribute);
        }
    }

    /**
     * @param string|array $attributes
     */
    public function dontSeeErrorsInResponse($attributes)
    {
        foreach ((array)$attributes as $attribute) {
            $this->expect('dont see error for attribute ' . $attribute);
            $this->dontSeeResponseJsonMatchesJsonPath('$.errors.' . $attribute);
        }
    }

    /**
     * @param string $email
     * @param string $password
     */
    public function loginAs($email, $password)
    {
        $this->logout();
        $this->sendPOST('/login', ['email' => $email, 'password' => $password]);
        $this->seeResponseContainsJson(['success' => 1]);

        $token = json_decode($this->grabResponse(), true)['token'] ?? '';
        $this->assertGreaterThan(20, strlen($token));

        $this->amBearerAuthenticated($token);
    }

    public function logout()
    {
        $this->deleteHeader('Authorization');
    }
}
