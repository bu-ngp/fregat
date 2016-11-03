<?php


class LoginCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
    }

    public function _after(\FunctionalTester $I)
    {
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Введите логин и пароль для входа в систему:');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        //  $I->expectTo('see validations errors');
        $I->see('Неверный логин или пароль.');

    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin',
        ]);
        $I->see('Главное меню');
        $I->dontSeeElement('form#login-form');
    }
}
