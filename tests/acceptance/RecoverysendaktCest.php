<?php
use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Installakt;
use app\models\Fregat\Izmer;
use app\models\Fregat\Material;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Matvid;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\Podraz;
use app\models\Fregat\Reason;
use app\models\Fregat\Recoveryrecieveakt;
use app\models\Fregat\Recoveryrecieveaktmat;
use app\models\Fregat\Recoverysendakt;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrMatOsmotr;
use app\models\Fregat\TrOsnov;
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

    /**
     * @depends openCreateRecoverysendakt
     */
    public function saveRecoverysendakt(AcceptanceTester $I)
    {
        $I->chooseValueFromSelect2('Recoverysendakt[id_organ]', 'РОГА И КОПЫТА', 'рог');
        $I->click('//button[@form="recoverysendakt-form"]');
        $I->wait(2);

        $I->seeElement('//select[@name="Recoverysendakt[id_organ]"]/following-sibling::span/span/span/span[@title="РОГА И КОПЫТА"]');
        $I->seeInField('recoverysendakt_date-recoverysendakt-recoverysendakt_date', date('d.m.Y'));
        $I->seeElement(['id' => 'recoveryrecieveaktgrid_gw']);
        $I->seeElement('//div[@id="recoveryrecieveaktgrid_gw"]/div/div/table/tbody/tr/td/div[text()="Ничего не найдено."]');
        $I->seeElement(['id' => 'recoveryrecieveaktmatgrid_gw']);
        $I->seeElement('//div[@id="recoveryrecieveaktmatgrid_gw"]/div/div/table/tbody/tr/td/div[text()="Ничего не найдено."]');
    }


    /**
     * @depends loadData
     */
    public function destroyData()
    {
       /* Recoveryrecieveaktmat::deleteAll();
        Recoveryrecieveakt::deleteAll();
        Recoverysendakt::deleteAll();
        TrMatOsmotr::deleteAll();
        Osmotraktmat::deleteAll();
        Osmotrakt::deleteAll();
        Reason::deleteAll();
        TrMat::deleteAll();
        TrOsnov::deleteAll();
        Installakt::deleteAll();
        Mattraffic::deleteAll();
        Material::deleteAll();
        Employee::deleteAll();
        Matvid::deleteAll();
        Izmer::deleteAll();
        Schetuchet::deleteAll();
        Authuser::deleteAll('auth_user_id <> 1');
        Build::deleteAll();
        Dolzh::deleteAll();
        Podraz::deleteAll();*/
    }
}
