<?php
use yii\helpers\Url;


/**
 * @group RecoverysendaktCest
 */
class RecoverysendaktCest
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
        $I->see('Журнал восстановления материальных ценностей');
    }

    /**
     * @depends openFregat
     */
    public function openRecoverysendakt(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал восстановления материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'recoverysendaktgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openRecoverysendakt
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('recoverysendakt.sql');
    }

    /**
     * @depends loadData
     */
    public function openCreateRecoverysendakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'recoverysendakt-form']);
    }

}
