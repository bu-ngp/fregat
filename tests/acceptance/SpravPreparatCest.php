<?php
use yii\helpers\Url;

/**
 * @group SpravPreparatCest
 */
class SpravPreparatCest
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
        $I->see('Препараты');
    }

    /**
     * @depends openSprav
     */
    public function openPreparat(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Препараты")]');
        $I->wait(2);
        $I->seeElement(['id' => 'preparatgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openPreparat
     */
    public function openCreatePreparat(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'preparat-form']);
    }

    /**
     * @depends openCreatePreparat
     */
    public function saveCreatePreparat(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Наименование препарата».');
        $I->fillField('Preparat[preparat_name]', 'Препарат');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'preparatgrid_gw']);
        $I->see('ПРЕПАРАТ');
    }

    /**
     * @depends saveCreatePreparat
     */
    public function checkUniquePreparat(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'preparat-form']);
        $I->fillField('Preparat[preparat_name]', 'Препарат');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Наименование препарата = Препарат уже существует');
        $I->click(['id' => 'backbutton']);
        $I->wait(2);
        $I->seeElement(['id' => 'preparatgrid_gw']);
    }

    /**
     * @depends checkUniquePreparat
     */
    public function openUpdatePreparat(AcceptanceTester $I)
    {
        $I->click('//td[text()="ПРЕПАРАТ"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->fillField('Preparat[preparat_name]', 'Лекарственный препарат');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'preparatgrid_gw']);
        $I->see('ЛЕКАРСТВЕННЫЙ ПРЕПАРАТ');
    }

    /**
     * @depends openUpdatePreparat
     */
    public function deletePreparat(AcceptanceTester $I)
    {
        $I->click('//td[text()="ЛЕКАРСТВЕННЫЙ ПРЕПАРАТ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('ЛЕКАРСТВЕННЫЙ ПРЕПАРАТ');
        $I->see('Ничего не найдено');
    }
}
