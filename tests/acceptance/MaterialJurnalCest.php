<?php
use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Material;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Matvid;
use app\models\Fregat\Podraz;
use app\models\Fregat\Schetuchet;
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



    public function destroyData()
    {
        Mattraffic::deleteAll();
        Material::deleteAll();
        Employee::deleteAll();
        Matvid::deleteAll();
        Schetuchet::deleteAll();
        Authuser::deleteAll();
        Build::deleteAll();
        Dolzh::deleteAll();
        Podraz::deleteAll();
    }
}