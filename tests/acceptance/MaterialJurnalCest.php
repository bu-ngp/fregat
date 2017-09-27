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
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('material_jurnal.sql');
    }

    /**
     * @depends loadData
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
    public function checkFormCreateMaterial(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);

        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="Групповой учет"]');
        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="Основное средство (Р)"]');
        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="Материал (Р)"]');
        $I->seeElementInDOM('//select[@name="Material[material_tip]"]/option[text()="В комплекте"]');

        $I->see('Необходимо заполнить «Наименование».');
        $I->see('Необходимо заполнить «Материально-ответственное лицо».');

        $I->checkDatePicker('Material[material_release]');
        $I->checkDatePicker('Mattraffic[mattraffic_date]');
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
        $I->chooseValueFromSelect2('Material[id_izmer]', 'шт', 'шт');
        $I->fillField('Material[material_price]', '1200.15');
        $I->fillField('Material[material_serial]', 'ABCD123');
        $I->fillDatecontrol('Material[material_release]', '01.01.2005');
        $I->executeJS('window.scrollTo(0,200);');
        $I->chooseValueFromSelect2('Material[id_schetuchet]', '101.34, НОВЫЙ СЧЕТ', '101');
        $I->chooseValueFromSelect2('Mattraffic[id_mol]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData([Yii::$app->formatter->asDate(date('d.m.Y')), 'Приход', '1.000', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ПОЛИКЛИНИКА 1'], 'mattraffic_karta_grid_gw', ['button[@title="Удалить"]']);

        $I->see('Обновить');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['Основное средство (Р)', 'ШКАФ', ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', '1.000', 'шт', '1200.15', 'Нет'], 'materialgrid_gw', ['a[@title="Карта материальной ценности"]']);
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
        $I->chooseValueFromSelect2('Material[material_tip]', 'Материал (Р)');
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
        $I->fillField('Material[material_comment]', 'Заметка');

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

        $I->checkDynagridData([Yii::$app->formatter->asDate(date('d.m.Y')), 'Приход', '5.000', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ПОЛИКЛИНИКА 1'], 'mattraffic_karta_grid_gw', ['button[@title="Удалить"]']);

        $I->see('Движение материальной ценности');
        $I->seeElement(['id' => 'mattraffic_karta_grid_gw']);

        $I->see('Обновить');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['Материал (Р)', 'СТОЛ', ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '5.000', 'шт', '15000.00', 'Нет'], 'materialgrid_gw', ['a[@title="Карта материальной ценности"]']);
    }

    /**
     * @depends addCreateMaterialFromGrids
     */
    public function applyFilter(AcceptanceTester $I)
    {
        $dateNow = date('d.m.Y');

        $I->seeElement('//a[@title="Дополнительный фильтр"]');
        $I->click('//a[@title="Дополнительный фильтр"]');
        $I->wait(4);

        $I->chooseValueFromSelect2('MaterialFilter[mol_fullname_material][]', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ива');
        $I->chooseValueFromSelect2('MaterialFilter[material_writeoff]', 'Нет');

        $I->fillDatecontrol('MaterialFilter[mattraffic_lastchange_beg]', $dateNow);
        $I->fillDatecontrol('MaterialFilter[mattraffic_lastchange_end]', $dateNow);
        $I->fillField('MaterialFilter[mattraffic_username]', 'admin');
        $I->checkOption('Материальные ценности в рабочем состоянии');

        $I->wait(1);
        $I->click(['id' => 'MaterialFilter_apply']);
        $I->wait(2);

        $I->existsInFilterTab('materialgrid_gw', ['ИВАНОВ ИВАН ИВАНОВИЧ', 'ADMIN', 'Дата изменения движения мат-ой ценности С ' . Yii::$app->formatter->asDate($dateNow) . ' ПО ' . Yii::$app->formatter->asDate($dateNow) . ';', 'Материальные ценности в рабочем состоянии']);
        $I->checkDynagridData(['Материал (Р)', 'СТОЛ', ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '5.000', 'шт', '15000.00', 'Нет']);
        $I->checkDynagridData(['Основное средство (Р)', 'ШКАФ', ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', '1.000', 'шт', '1200.15', 'Нет']);

        $I->click('//a[@title="Дополнительный фильтр"]');
        $I->wait(4);

        $I->seeElement('//select[@name="MaterialFilter[mol_fullname_material][]"]/following-sibling::span/span/span/ul/li[@title="ИВАНОВ ИВАН ИВАНОВИЧ"]');
        $I->seeElement('//select[@name="MaterialFilter[material_writeoff]"]/following-sibling::span/span/span/span[@title="Нет"]');
        $I->seeInField('MaterialFilter[mattraffic_username]', 'ADMIN');
        $I->seeInDatecontrol('MaterialFilter[mattraffic_lastchange_beg]', $dateNow);
        $I->seeInDatecontrol('MaterialFilter[mattraffic_lastchange_end]', $dateNow);
        $I->seeCheckboxIsChecked('MaterialFilter[material_working_mark]');
        $I->click(['id' => 'MaterialFilter_close']);
        $I->wait(2);

        $I->click('//a[@title="Дополнительный фильтр"]');
        $I->wait(4);
        $I->checkOption('В актах восстановления прикреплены файлы');
        $I->wait(1);
        $I->click(['id' => 'MaterialFilter_apply']);
        $I->wait(2);
        $I->see('Ничего не найдено');

        $I->click(['id' => 'MaterialFilter_resetfilter']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите сбросить дополнительный фильтр?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSeeElement('//div[@id="materialgrid_gw"]/div/div[@id="materialgrid_gw-container"]/div[@class="panel panel-warning"]');
    }

    /**
     * @depends applyFilter
     */
    public function checkExcelExport(AcceptanceTester $I)
    {
        $I->click(['id' => 'Materialexcel']);
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Список материальных ценностей.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Список материальных ценностей.xlsx'), [
            ['A', 2, 'Дата: ' . date('d.m.Y')],
            ['A', 5, '№'],
            ['B', 5, 'Тип'],
            ['C', 5, 'Вид материальной ценности'],
            ['D', 5, 'Наименование'],
            ['E', 5, 'Инвентарный номер'],
            ['F', 5, 'Количество'],
            ['G', 5, 'Единица измерения'],
            ['H', 5, 'Стоимость'],
            ['I', 5, 'Списан'],

            ['A', 6, '1'],
            ['B', 6, 'Материал (Р)'],
            ['C', 6, 'СТОЛ'],
            ['D', 6, 'Кухонный стол'],
            ['E', 6, '1000002'],
            ['F', 6, '5'],
            ['G', 6, 'шт'],
            ['H', 6, '15000'],
            ['I', 6, 'Нет'],

            ['A', 7, '2'],
            ['B', 7, 'Основное средство (Р)'],
            ['C', 7, 'ШКАФ'],
            ['D', 7, 'Шкаф для одежды'],
            ['E', 7, '1000001'],
            ['F', 7, '1'],
            ['G', 7, 'шт'],
            ['H', 7, '1200.15'],
            ['I', 7, 'Нет'],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Список материальных ценностей.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Список материальных ценностей.xlsx'));
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