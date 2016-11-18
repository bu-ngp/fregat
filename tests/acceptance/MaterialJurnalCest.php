<?php
use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Izmer;
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

    /**
     * @depends loadData
     */
    public function checkFormCreateMaterial(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);

        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="Основное средство"]');
        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="Материал"]');
        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="Групповой учет"]');

        $I->see('Необходимо заполнить «Наименование».');
        $I->see('Необходимо заполнить «Инвентарный номер».');
        $I->see('Необходимо заполнить «Материально-ответственное лицо».');

        $I->checkDatePicker('material_release-material-material_release');
        $I->checkDatePicker('mattraffic_date-mattraffic-mattraffic_date');
    }

    /**
     * @depends checkFormCreateMaterial
     */
    public function addCreateMaterialFromSelect2(AcceptanceTester $I)
    {
        $I->executeJS('window.scrollTo(0,0);');
        $I->chooseValueFromSelect2('Material[id_matvid]', 'ШКАФ', 'шка');
        $I->fillField('Material[material_name]', 'Шкаф для одежды');
        $I->fillField('Material[material_inv]', '1000001');
        $I->seeElement('//input[@name="Material[material_number]" and @disabled]');
        $I->chooseValueFromGrid('Material[id_izmer]', 'шт', 'izmergrid_gw');
        $I->fillField('Material[material_price]', '1200.15');
        $I->fillField('Material[material_serial]', 'ABCD123');
        $I->fillField('material_release-material-material_release', '01.01.2005');
        $I->executeJS('window.scrollTo(0,200);');
        $I->chooseValueFromSelect2('Material[id_schetuchet]', '101.34, НОВЫЙ СЧЕТ', '101');
        $I->chooseValueFromSelect2('Mattraffic[id_mol]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement('//td[text()="ПОЛИКЛИНИКА 1"]'
            . '/preceding-sibling::td[text()="ТЕРАПЕВТ"]'
            . '/preceding-sibling::td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]'
            . '/preceding-sibling::td[text()="1.000"]'
            . '/preceding-sibling::td[text()="Приход"]'
            . '/preceding-sibling::td/button[@title="Удалить"]');

        $I->see('Обновить');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'materialgrid_gw']);
        $I->seeElement('//td[text()="Нет"]'
            . '/preceding-sibling::td[text()="1200.15"]'
            . '/preceding-sibling::td[text()="шт"]'
            . '/preceding-sibling::td[text()="1.000"]'
            . '/preceding-sibling::td[text()="1000001"]'
            . '/preceding-sibling::td[text()="Шкаф для одежды"]'
            . '/preceding-sibling::td[text()="ШКАФ"]'
            . '/preceding-sibling::td[text()="Основное средство"]'
            . '/preceding-sibling::td/a[@title="Карта материальной ценности"]');

    }

    /**
     * @depends addCreateMaterialFromSelect2
     */
    public function addCreateMaterialFromGrids(AcceptanceTester $I)
    {
        $I->seeLink('Составить акт прихода материальнной ценности');
        $I->click(['link' => 'Составить акт прихода материальнной ценности']);
        $I->wait(2);
        $I->seeElement(['class' => 'material-form']);
        $I->chooseValueFromSelect2('Material[material_tip]', 'Материал');
        $I->chooseValueFromGrid('Material[id_matvid]', 'СТОЛ', 'matvidgrid_gw');

        $I->fillField('Material[material_name]', 'Кухонный стол');
        $I->fillField('Material[material_inv]', '1000002');
        $I->dontSeeElement('//input[@name="Material[material_number]" and @disabled]');
        $I->seeElement('//input[@name="Material[material_number]"]');
        $I->fillField('Material[material_number]', '5.000');
        $I->chooseValueFromGrid('Material[id_izmer]', 'шт', 'izmergrid_gw');
        $I->fillField('Material[material_price]', '15000');
        //  $I->executeJS('window.scrollTo(0,200);');
        $I->chooseValueFromGrid('Material[id_schetuchet]', '101.34, НОВЫЙ СЧЕТ', 'schetuchetgrid_gw', '//td[text()="НОВЫЙ СЧЕТ"]'
            . '/preceding-sibling::td[text()="101.34"]'
            . '/preceding-sibling::td/button[@title="Выбрать"]');

        $I->uncheckOption('Запись изменяема при импортировании из 1С');

        $I->chooseValueFromGrid('Mattraffic[id_mol]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'employeegrid_gw', '//td[text()="ПОЛИКЛИНИКА 1"]'
            . '/preceding-sibling::td[text()="ТЕРАПЕВТИЧЕСКОЕ"]'
            . '/preceding-sibling::td[text()="ТЕРАПЕВТ"]'
            . '/preceding-sibling::td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]'
            . '/preceding-sibling::td[text()="1175"]'
            . '/preceding-sibling::td/button[@title="Выбрать"]');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement('//td[text()="ПОЛИКЛИНИКА 1"]'
            . '/preceding-sibling::td[text()="ТЕРАПЕВТ"]'
            . '/preceding-sibling::td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]'
            . '/preceding-sibling::td[text()="5.000"]'
            . '/preceding-sibling::td[text()="Приход"]'
            . '/preceding-sibling::td/button[@title="Удалить"]');

        $I->see('Движение материальной ценности');
        $I->seeElement(['id' => 'mattraffic_karta_grid_gw']);

        $I->see('Обновить');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'materialgrid_gw']);
        $I->seeElement('//td[text()="Нет"]'
            . '/preceding-sibling::td[text()="15000.00"]'
            . '/preceding-sibling::td[text()="шт"]'
            . '/preceding-sibling::td[text()="5.000"]'
            . '/preceding-sibling::td[text()="1000002"]'
            . '/preceding-sibling::td[text()="Кухонный стол"]'
            . '/preceding-sibling::td[text()="СТОЛ"]'
            . '/preceding-sibling::td[text()="Материал"]'
            . '/preceding-sibling::td/a[@title="Карта материальной ценности"]');
    }

    /**
     * @depends loadData
     */
    public function destroyData()
    {
        Mattraffic::deleteAll();
        Material::deleteAll();
        Employee::deleteAll();
        Matvid::deleteAll();
        Izmer::deleteAll();
        Schetuchet::deleteAll();
        Authuser::deleteAll('auth_user_id <> 1');
        Build::deleteAll();
        Dolzh::deleteAll();
        Podraz::deleteAll();
    }
}