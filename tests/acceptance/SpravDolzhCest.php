<?php
use yii\helpers\Url;

/**
 * @group SpravDolzhCest
 */
class SpravDolzhCest
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
        $I->see('Должности');
    }

    /**
     * @depends openSprav
     */
    public function openDolzh(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Должности")]');
        $I->wait(2);
        $I->seeElement(['id' => 'dolzhgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openDolzh
     */
    public function openCreateDolzh(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'dolzh-form']);
    }

    /**
     * @depends openCreateDolzh
     */
    public function saveCreateDolzh(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Должность».');
        $I->fillField('Dolzh[dolzh_name]', 'Медсестра');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'dolzhgrid_gw']);
        $I->see('МЕДСЕСТРА');
    }

    /**
     * @depends saveCreateDolzh
     */
    public function checkUniqueDolzh(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'dolzh-form']);
        $I->fillField('Dolzh[dolzh_name]', 'Медсестра');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Должность = Медсестра уже существует');
        $I->click(['id' => 'backbutton']);
        $I->wait(2);
        $I->seeElement(['id' => 'dolzhgrid_gw']);
    }

    /**
     * @depends checkUniqueDolzh
     */
    public function openUpdateDolzh(AcceptanceTester $I)
    {
        $I->click('//td[text()="МЕДСЕСТРА"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->fillField('Dolzh[dolzh_name]', 'Старшая медсестра');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'dolzhgrid_gw']);
        $I->see('СТАРШАЯ МЕДСЕСТРА');
    }

    /**
     * @depends openUpdateDolzh
     */
    public function deleteDolzh(AcceptanceTester $I)
    {
        $I->click('//td[text()="СТАРШАЯ МЕДСЕСТРА"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('СТАРШАЯ МЕДСЕСТРА');
        $I->see('Ничего не найдено');
    }
}
