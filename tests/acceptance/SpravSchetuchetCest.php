<?php
use yii\helpers\Url;

/**
 * @group SpravSchetuchetCest
 */
class SpravSchetuchetCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    /**
     * @depends LoginCest:login
     */
    public function openFregat(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/Fregat/fregat/mainmenu'));
        $I->wait(2);
        $I->see('Справочники');
    }

    /**
     * @depends openFregat
     */
    public function openSprav(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Справочники")]');
        $I->wait(2);
        $I->see('Организации');
    }

    /**
     * @depends openSprav
     */
    public function openSchetuchet(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Счета учета")]');
        $I->wait(2);
        $I->seeElement(['id' => 'schetuchetgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openSchetuchet
     */
    public function openCreateSchetuchet(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'schetuchet-form']);
    }

    /**
     * @depends openCreateSchetuchet
     */
    public function saveCreateSchetuchet(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Счет учета».');
        $I->see('Необходимо заполнить «Расшифровка счета учета».');
        $I->fillField('Schetuchet[schetuchet_kod]', '101.34');
        $I->fillField('Schetuchet[schetuchet_name]', 'Имя счета');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'schetuchetgrid_gw']);
        $I->see('101.34');
        $I->see('ИМЯ СЧЕТА');
    }

    /**
     * @depends saveCreateSchetuchet
     */
    public function checkUniqueSchetuchet(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'schetuchet-form']);

        $I->fillField('Schetuchet[schetuchet_kod]', '101.34');
        $I->fillField('Schetuchet[schetuchet_name]', 'Имя счета');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Счет учета = 101.34 уже существует');
        $I->click(['id' => 'backbutton']);
        $I->wait(2);
        $I->seeElement(['id' => 'schetuchetgrid_gw']);
    }

    /**
     * @depends checkUniqueSchetuchet
     */
    public function openUpdateSchetuchet(AcceptanceTester $I)
    {
        $I->click('//td[text()="101.34"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->fillField('Schetuchet[schetuchet_kod]', '101.35');
        $I->fillField('Schetuchet[schetuchet_name]', 'Измененный счет');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'schetuchetgrid_gw']);
        $I->see('101.35');
        $I->see('ИЗМЕНЕННЫЙ СЧЕТ');
    }

    /**
     * @depends openUpdateSchetuchet
     */
    public function deleteSchetuchet(AcceptanceTester $I)
    {
        $I->click('//td[text()="ИЗМЕНЕННЫЙ СЧЕТ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('101.35');
        $I->dontSee('ИЗМЕНЕННЫЙ СЧЕТ');
        $I->see('Ничего не найдено');
    }
}
