<?php
use yii\helpers\Url;


/**
 * @group NakladCest
 */
class NakladCest
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
        $I->see('Журнал требований - накладных');
    }

    /**
     * @depends openFregat
     */
    public function openSpisosnovakt(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал требований - накладных")]');
        $I->wait(2);
        $I->seeElement(['id' => 'nakladgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openSpisosnovakt
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('naklad.sql');
    }

}
