<?php
namespace tests\codeception\_pages;

use yii\codeception\BasePage;

/**
 * Represents login page
 * @property \AcceptanceTester|\FunctionalTester $actor
 */
class LoginPage extends BasePage
{
    public $route = 'site/login';

    /**
     * Method representing user submitting login form.
     *
     * @param string $name     username|email
     * @param string $password
     */
    public function login($name, $password)
    {
        // if 'Login With Email' is true use email field, otherwise use username
        $field = (\Yii::$app->params['lwe']) ? 'email' : 'username' ;

        $this->actor->fillField('input[name="LoginForm['.$field.']"]', $name);
        $this->actor->fillField('input[name="LoginForm[password]"]', $password);
        $this->actor->click('login-button');
    }
}
