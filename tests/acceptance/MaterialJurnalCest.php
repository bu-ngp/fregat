<?php
use yii\helpers\Url;

/**
 * @group MaterialJurnalCest
 */
class MaterialJurnalCest
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
        $I->see('Журнал материальных ценностей');
    }

    /**
     * @depends openFregat
     */
    public function openMaterialJurnal(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'materialgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openMaterialJurnal
     */
    public function openCreateMaterial(AcceptanceTester $I)
    {
        $I->seeLink('Составить акт прихода материальнной ценности');
        $I->click(['link' => 'Составить акт прихода материальнной ценности']);
        $I->wait(2);
        $I->seeElement(['class' => 'material-form']);
    }

    /**
     * @depends openCreateMaterial
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('material_jurnal.sql');
    }

    public function destroyData(AcceptanceTester $I)
    {
        //   $I->loadDataFromSQLFile('drop_material_jurnal.sql');
    }
}