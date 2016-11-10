<?php


class LoginCest
{
    private $cookie = null;

    public function _before(\FunctionalTester $I)
    {

    }

    public function _after(\FunctionalTester $I)
    {
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->see('Введите логин и пароль для входа в систему:');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        //  $I->expectTo('see validations errors');
        $I->see('Неверный логин или пароль.');

    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin',
        ]);
        $I->see('Главное меню');
        $I->see('Система "Фрегат"');
        $I->see('Регистр глаукомных пациентов');
        $I->see('Настройки портала');
        $I->see('Сменить пароль');
        $I->dontSeeElement('form#login-form');
     /*   $I->canSeeCookie('test');
        $this->cookie = $I->grabCookie('test');*/
    }

    /**
     * @depends loginSuccessfully
     */
    public function openFregat(\FunctionalTester $I)
    {
       // $I->setCookie('test', $this->cookie);

        //  $I->click('Fregat/fregat/mainmenu');
        $I->amOnRoute('site/index');
        $I->see('Система "Фрегат"');
        /* $I->click('//div[contains(text(), "Фрегат")]');
         $I->see('Журнал материальных ценностей');
         $I->see('Журнал перемещений материальных ценностей');
         $I->see('Журнал снятия комплектующих с материальных ценностей');
         $I->see('Журнал осмотров материальных ценностей');
         $I->see('Журнал осмотров материалов');
         $I->see('Журнал восстановления материальных ценностей');
         $I->see('Журнал списания основных средств');
         $I->see('Импорт данных');
         $I->see('Справочники');*/
    }

    /* public function openSprav(\FunctionalTester $I) {
         $I->amOnRoute('Fregat/fregat/sprav');
         $I->see('Виды материальных ценностей');
     }*/

}
