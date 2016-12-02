<?php


use yii\helpers\Url;

/**
 * @group SpravMatvidCest
 */
class SpravMatvidCest
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
        $I->see('Виды материальных ценностей');
    }

    /**
     * @depends openSprav
     */
    public function openMatvid(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Виды материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'matvidgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openMatvid
     */
    public function openCreateMatvid(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'matvid-form']);
    }

    /**
     * @depends openCreateMatvid
     */
    public function saveCreateMatvid(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Вид материальной ценности».');
        $I->fillField('Matvid[matvid_name]', 'Монитор');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->checkDynagridData(['МОНИТОР'], 'matvidgrid_gw', ['a[@title="Обновить"]']);
    }

    /**
     * @depends saveCreateMatvid
     */
    public function openUpdateMatvid(AcceptanceTester $I)
    {
        $I->seeElement('a', ['title' => 'Обновить']);
        $I->click(['css' => 'a[title="Обновить"]']);
        $I->wait(2);
        $I->seeElement(['class' => 'matvid-form']);
    }

    /**
     * @depends openUpdateMatvid
     */
    public function saveUpdateMatvid(AcceptanceTester $I)
    {
        $I->seeInField(['name' => 'Matvid[matvid_name]'], 'МОНИТОР');
        $I->fillField('Matvid[matvid_name]', 'Монитор ЖК');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->checkDynagridData(['МОНИТОР ЖК'], 'matvidgrid_gw', ['button[@title="Удалить"]']);
    }

    /**
     * @depends saveUpdateMatvid
     */
    public function deleteMatvid(AcceptanceTester $I)
    {
        $I->click(['css' => 'button[title="Удалить"]']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->see('Ничего не найдено');
    }
}
