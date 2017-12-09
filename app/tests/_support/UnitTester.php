<?php

use yii\base\Model;


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
class UnitTester extends \Codeception\Actor
{
    use _generated\UnitTesterActions;

   /**
    * Define custom actions here
    */

    /**
     * @param Model $model
     * @param array $attributes
     */
    public function seeErrors($model, $attributes)
    {
        foreach ((array)$attributes as $attribute) {
            $this->expect('see error for attribute ' . $attribute);
            $this->assertTrue(array_key_exists($attribute, $model->errors));
        }
    }

    /**
     * @param Model $model
     * @param array $attributes
     */
    public function dontSeeErrors($model, $attributes)
    {
        foreach ((array)$attributes as $attribute) {
            $this->expect('dont see error for attribute ' . $attribute);
            $this->assertFalse(array_key_exists($attribute, $model->errors));
        }
    }
}
