<?php


use yii\helpers\Url;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->see('Введите логин и пароль для входа в систему:');
        $I->fillField('LoginForm[username]', 'admin2');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');
        $I->wait(2); // wait for button to be clicked
        $I->see('Главное меню');
    }
}
