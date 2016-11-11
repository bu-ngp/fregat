<?php


use yii\helpers\Url;

/**
 * @group SpravReasonCest
 */
class SpravReasonCest
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
        $I->see('Шаблоны актов осмотра материальной ценности');
    }

    /**
     * @depends openSprav
     */
    public function openReason(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Шаблоны актов осмотра материальной ценности")]');
        $I->wait(2);
        $I->seeElement(['id' => 'reasongrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openReason
     */
    public function openCreateReason(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'reason-form']);
    }

    /**
     * @depends openCreateReason
     */
    public function saveCreateReason(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Причина поломки».');
        $I->fillField('Reason[reason_text]', 'Неисправен вал');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement(['id' => 'reasongrid_gw']);
        $I->see('НЕИСПРАВЕН ВАЛ');
        $I->seeElement('a', ['title' => 'Обновить']);
    }

    /**
     * @depends saveCreateReason
     */
    public function openUpdateReason(AcceptanceTester $I)
    {
        $I->seeElement('a', ['title' => 'Обновить']);
        $I->click(['css' => 'a[title="Обновить"]']);
        $I->wait(2);
        $I->seeElement(['class' => 'reason-form']);
    }

    /**
     * @depends openUpdateReason
     */
    public function saveUpdateReason(AcceptanceTester $I)
    {
        $I->seeInField(['name' => 'Reason[reason_text]'], 'НЕИСПРАВЕН ВАЛ');
        $I->fillField('Reason[reason_text]', 'Неисправен вал принтера');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->seeElement(['id' => 'reasongrid_gw']);
        $I->see('НЕИСПРАВЕН ВАЛ ПРИНТЕРА');
    }

    /**
     * @depends saveUpdateReason
     */
    public function uniqueCreateReasonCheck(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'reason-form']);

        $I->fillField('Reason[reason_text]', 'Неисправен вал принтера');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Причина поломки = Неисправен вал принтера уже существует');
        $I->click('#backbutton');
        $I->wait(2);
        $I->seeElement(['id' => 'reasongrid_gw']);
        $I->seeElement('button', ['title' => 'Удалить']);
    }

    /**
     * @depends uniqueCreateReasonCheck
     */
    public function deleteReason(AcceptanceTester $I)
    {
        $I->click(['css' => 'button[title="Удалить"]']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->see('Ничего не найдено');
    }
}
