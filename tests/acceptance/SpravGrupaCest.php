<?php


use yii\helpers\Url;

/**
 * @group SpravGrupaCest
 */
class SpravGrupaCest
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
        $I->see('Группы материальных ценностей');
    }

    /**
     * @depends openSprav
     */
    public function openGrupa(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Группы материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'grupagrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openGrupa
     */
    public function openCreateGrupa(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'grupa-form']);
    }

    /**
     * @depends openCreateGrupa
     */
    public function saveCreateGrupa(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Группа материальной ценности».');
        $I->fillField('Grupa[grupa_name]', 'Мебель');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement(['id' => 'grupavidgrid_gw']);
        $I->seeInField('Grupa[grupa_name]', 'МЕБЕЛЬ');
        $I->seeElement('a', ['title' => 'Обновить']);
    }

    /**
     * @depends saveCreateGrupa
     */
    public function openCreateMatvid(AcceptanceTester $I)
    {
        $I->seeLink('Добавить вид материальной ценности');
        $I->click(['link' => 'Добавить вид материальной ценности']);
        $I->wait(2);
        $I->seeElement(['id' => 'matvidgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openCreateMatvid
     */
    public function addMatvid(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'matvid-form']);
        $I->see('Создать');
        $I->fillField('Matvid[matvid_name]', 'Шкаф');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement(['id' => 'matvidgrid_gw']);
        $I->see('Шкаф');

        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'matvid-form']);
        $I->see('Создать');
        $I->fillField('Matvid[matvid_name]', 'Стол');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement(['id' => 'matvidgrid_gw']);
        $I->see('Стол');
    }

    /**
     * @depends addMatvid
     */
    public function chooseMatvid(AcceptanceTester $I)
    {
        $I->seeElement('button', ['title' => 'Выбрать']); /* ид */
    }
}
