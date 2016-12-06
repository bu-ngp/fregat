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
use app\models\Fregat\Podraz;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use yii\helpers\Url;

/**
 * @group InstallaktCest
 */
class InstallaktCest
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
        $I->see('Журнал перемещений материальных ценностей');
    }

    /**
     * @depends openFregat
     */
    public function openInstallakt(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал перемещений материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'installaktgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openInstallakt
     */
    public function openCreateInstallakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'installakt-form']);
    }

    /**
     * @depends openCreateInstallakt
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('installakt.sql');
    }

    /**
     * @depends loadData
     */
    public function saveInstallakt(AcceptanceTester $I)
    {
        $I->chooseValueFromSelect2('Installakt[id_installer]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->click('//button[@form="Installaktform"]');
        $I->wait(2);

        $I->seeElement('//select[@name="Installakt[id_installer]"]/following-sibling::span/span/span/span[@title="ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1"]');
        $I->seeInField('installakt_date-installakt-installakt_date', date('d.m.Y'));
        $I->seeElement(['class' => 'installakt-form']);
        $I->seeElement(['id' => 'trOsnovgrid_gw']);
        $I->seeElement('//div[@id="trOsnovgrid_gw"]/div/div/table/tbody/tr/td/div[text()="Ничего не найдено."]');
        $I->seeElement(['id' => 'trMatgrid_gw']);
        $I->seeElement('//div[@id="trMatgrid_gw"]/div/div/table/tbody/tr/td/div[text()="Ничего не найдено."]');
    }

    /**
     * @depends saveInstallakt
     */
    public function createTrosnovSelect2(AcceptanceTester $I)
    {
        $I->click('//div[@id="trOsnovgrid_gw"]/div/div/a[contains(text(), "Добавить материальную ценность")]');
        $I->wait(2);

        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Инвентарный номер».');
        $I->see('Необходимо заполнить «Количество (Задействованное в операции)».');
        $I->see('Необходимо заполнить «Кабинет».');

        $I->seeInField('Material[material_name]', '');
        $I->chooseValueFromSelect2('TrOsnov[id_mattraffic]', '1000003, ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ', '003');
        $I->seeOptionIsSelected("Material[material_tip]", 'Основное средство');
        $I->seeInField("Material[material_name]", 'Шкаф для медикаментов');
        $I->seeOptionIsSelected("Material[material_writeoff]", 'Нет');

        $I->seeInField("Authuser[auth_user_fullname]", 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ');
        $I->seeInField("Dolzh[dolzh_name]", 'ТЕРАПЕВТ');
        $I->seeInField("Podraz[podraz_name]", 'ТЕРАПЕВТИЧЕСКОЕ');
        $I->seeInField("Build[build_name]", '');
        $I->wait(1);
        $I->seeElement('//span[@id="mattraffic_number_max" and text()="Не более 1"]');
        $I->fillField('Mattraffic[mattraffic_number]', '2.000');
        $I->fillField('TrOsnov[tr_osnov_kab]', '101');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);

        $I->see('Количество не может превышать 1');
        $I->fillField('Mattraffic[mattraffic_number]', '1.000');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);

        $I->see('У материально ответственного лица "ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ" не заполнено "Здание", в которое устанавливается материальная ценность');
        $I->chooseValueFromSelect2('TrOsnov[id_mattraffic]', '1000001, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', '001');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->seeElement(['class' => 'installakt-form']);
        $I->seeElement(['id' => 'trOsnovgrid_gw']);

        $I->checkDynagridData(['Шкаф для одежды', '1000001', '1.000', 'ПОЛИКЛИНИКА 1', '101', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ'], 'trOsnovgrid_gw', ['button[@title="Удалить"]']);
    }

    /**
     * @depends createTrosnovSelect2
     */
    public function createTrosnovGrids(AcceptanceTester $I)
    {
        $I->click('//div[@id="trOsnovgrid_gw"]/div/div/a[contains(text(), "Добавить материальную ценность")]');
        $I->wait(2);
        $I->executeJS('window.scrollTo(0,0);');

        $I->seeInField('Material[material_name]', '');

        $I->chooseValueFromGrid('TrOsnov[id_mattraffic]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'mattrafficgrid_gw', '//td[text()="Кухонный стол"]/preceding-sibling::td/button[@title="Выбрать"]');

        $I->seeOptionIsSelected("Material[material_tip]", 'Основное средство');
        $I->seeInField("Material[material_name]", 'Кухонный стол');
        $I->seeOptionIsSelected("Material[material_writeoff]", 'Нет');

        $I->seeInField("Authuser[auth_user_fullname]", 'ПЕТРОВ ПЕТР ПЕТРОВИЧ');
        $I->seeInField("Dolzh[dolzh_name]", 'ПРОГРАММИСТ');
        $I->seeInField("Podraz[podraz_name]", 'АУП');
        $I->seeInField("Build[build_name]", 'ПОЛИКЛИНИКА 1');
        $I->wait(1);
        $I->seeElement('//span[@id="mattraffic_number_max" and text()="Не более 1"]');
        $I->fillField('Mattraffic[mattraffic_number]', '1.000');
        $I->fillField('TrOsnov[tr_osnov_kab]', '102');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->seeElement(['class' => 'installakt-form']);
        $I->seeElement(['id' => 'trOsnovgrid_gw']);

        $I->checkDynagridData(['Кухонный стол', '1000002', '1.000', 'ПОЛИКЛИНИКА 1', '102', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trOsnovgrid_gw', ['button[@title="Удалить"]']);
    }

    /**
     * @depends createTrosnovGrids
     */
    public function createTrmatSelect2(AcceptanceTester $I)
    {
        $I->click('//div[@id="trMatgrid_gw"]/div/div/a[contains(text(), "Добавить материальную ценность")]');
        $I->wait(2);

        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Комплектуемая материальная ценность».');
        $I->see('Необходимо заполнить «Перемещаемая материальная ценность».');
        $I->see('Необходимо заполнить «Количество (Задействованное в операции)».');

        $I->chooseValueFromSelect2('TrMat[id_parent]', 'ПОЛИКЛИНИКА 1, каб. 101, 1000001, Шкаф для одежды', '001');
        $I->chooseValueFromSelect2('TrMat[id_mattraffic]', '1000004, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', '004');

        $I->wait(1);
        $I->seeElement('//span[@id="mattraffic_number_max" and text()="Не более 5.000"]');
        $I->fillField('Mattraffic[mattraffic_number]', '6.000');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);

        $I->see('Количество не может превышать 5.000');
        $I->fillField('Mattraffic[mattraffic_number]', '3.000');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->seeElement(['class' => 'installakt-form']);
        $I->seeElement(['id' => 'trMatgrid_gw']);

        // $I->executeJS('window.scrollTo(0,200);');
        $I->checkDynagridData(['Шкаф для одежды', '1000001', 'ПОЛИКЛИНИКА 1', '101', 'Картридж А12', '1000004', '3.000', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ'], 'trMatgrid_gw', ['button[@title="Удалить"]']);
    }

    /**
     * @depends createTrmatSelect2
     */
    public function createTrmatGrids(AcceptanceTester $I)
    {
        $I->click('//div[@id="trMatgrid_gw"]/div/div/a[contains(text(), "Добавить материальную ценность")]');
        $I->wait(2);

        $I->chooseValueFromGrid('TrMat[id_parent]', 'ПОЛИКЛИНИКА 1, каб. 101, 1000001, Шкаф для одежды', 'mattrafficgrid_gw', '//td[text()="Шкаф для одежды"]/preceding-sibling::td/button[@title="Выбрать"]');
        $I->chooseValueFromGrid('TrMat[id_mattraffic]', '1000005, СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', 'mattrafficgrid_gw', '//td[text()="Картридж 36A"]/preceding-sibling::td/button[@title="Выбрать"]');

        $I->wait(1);
        $I->seeElement('//span[@id="mattraffic_number_max" and text()="Не более 4.000"]');
        $I->fillField('Mattraffic[mattraffic_number]', '2.000');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->seeElement(['class' => 'installakt-form']);
        $I->seeElement(['id' => 'trMatgrid_gw']);

        $I->checkDynagridData(['Шкаф для одежды', '1000001', 'ПОЛИКЛИНИКА 1', '101', 'Картридж 36A', '1000005', '2.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ'], 'trMatgrid_gw', ['button[@title="Удалить"]']);
        $I->checkDynagridData(['Шкаф для одежды', '1000001', 'ПОЛИКЛИНИКА 1', '101', 'Картридж А12', '1000004', '3.000', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ'], 'trMatgrid_gw', ['button[@title="Удалить"]']);
    }

    /**
     * @depends createTrmatGrids
     */
    public function checkExcelExport(AcceptanceTester $I)
    {
        $I->click(['id' => 'DownloadReport']);
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт перемещения матер-ых цен-тей №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт перемещения матер-ых цен-тей №1.xlsx'), [
            ['A', 3, 'материальных ценностей № 1 от ' . date('d.m.Y')],

            ['A', 6, '№'],
            ['B', 6, 'Вид'],
            ['C', 6, 'Наименование'],
            ['D', 6, 'Инвентарный номер'],
            ['E', 6, 'Серийный номер'],
            ['F', 6, 'Кол-во'],
            ['G', 6, 'Единица измерения'],
            ['H', 6, 'Лицо отправитель'],
            ['I', 6, 'Здание, кабинет, откуда перемещено'],
            ['J', 6, 'Лицо получатель'],
            ['K', 6, 'Здание, кабинет, куда перемещено'],

            ['A', 8, '1'],
            ['B', 8, 'ШКАФ'],
            ['C', 8, 'Шкаф для одежды'],
            ['D', 8, '1000001'],
            ['E', 8, 'ABCD123'],
            ['F', 8, '1'],
            ['G', 8, 'шт'],
            ['H', 8, 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ'],
            ['I', 8, 'ПОЛИКЛИНИКА 1, Приход'],
            ['J', 8, 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ'],
            ['K', 8, 'ПОЛИКЛИНИКА 1, 101'],

            ['A', 9, '2'],
            ['B', 9, 'СТОЛ'],
            ['C', 9, 'Кухонный стол'],
            ['D', 9, '1000002'],
            ['E', 9, ''],
            ['F', 8, '1'],
            ['G', 8, 'шт'],
            ['H', 9, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ'],
            ['I', 9, 'ПОЛИКЛИНИКА 1, Приход'],
            ['H', 9, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ'],
            ['K', 9, 'ПОЛИКЛИНИКА 1, 102'],

            ['A', 15, '1'],
            ['B', 15, 'ШКАФ'],
            ['C', 15, 'Шкаф для одежды'],
            ['D', 15, '1000001'],
            ['E', 15, 'ABCD123'],
            ['F', 15, '2005'],
            ['G', 15, '1200.15'],
            ['H', 15, 'ПОЛИКЛИНИКА 1'],
            ['I', 15, '101'],
            ['J', 15, 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ'],
            ['K', 15, 'Основное средство'],

            ['A', 19, '1'],
            ['B', 19, 'КАРТРИДЖ'],
            ['C', 19, 'Картридж А12'],
            ['D', 19, '1000004'],
            ['E', 19, ''],
            ['F', 19, '3'],
            ['G', 19, 'шт'],
            ['H', 19, ''],
            ['I', 19, '900'],
            ['J', 19, 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ'],
            ['K', 19, 'Материал'],

            ['A', 20, '2'],
            ['B', 20, 'КАРТРИДЖ'],
            ['C', 20, 'Картридж 36A'],
            ['D', 20, '1000005'],
            ['E', 20, ''],
            ['F', 20, '2'],
            ['G', 20, 'шт'],
            ['H', 20, ''],
            ['I', 20, '1500'],
            ['J', 20, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ'],
            ['K', 20, 'Материал'],

            ['A', 23, 'Материально ответственное лицо'],
            ['D', 23, 'ТЕРАПЕВТ'],
            ['H', 23, 'ИВАНОВ ИВАН ИВАНОВИЧ'],

            ['A', 24, 'Материально ответственное лицо'],
            ['D', 24, 'ПРОГРАММИСТ'],
            ['H', 24, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],

            ['A', 25, 'Материально ответственное лицо'],
            ['D', 25, 'НЕВРОЛОГ'],
            ['H', 25, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ'],

            ['A', 26, 'Мастер'],
            ['D', 26, 'ПРОГРАММИСТ'],
            ['H', 26, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Акт перемещения матер-ых цен-тей №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт перемещения матер-ых цен-тей №1.xlsx'));
    }

    /**
     * @depends checkExcelExport
     */
    public function deleteTrmat(AcceptanceTester $I)
    {
        $I->click('//div[@id="trMatgrid_gw"]/div/div/table/tbody/tr/td[text()="Картридж А12"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSeeElement('//div[@id="trMatgrid_gw"]/div/div/table/tbody/tr/td[text()="Картридж А12"]/preceding-sibling::td/button[@title="Удалить"]');
    }

    /**
     * @depends deleteTrmat
     */
    public function deleteTrosnov(AcceptanceTester $I)
    {
        $I->click('//div[@id="trOsnovgrid_gw"]/div/div/table/tbody/tr/td[text()="Кухонный стол"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSeeElement('//div[@id="trOsnovgrid_gw"]/div/div/table/tbody/tr/td[text()="Кухонный стол"]/preceding-sibling::td/button[@title="Удалить"]');
    }

    /**
     * @depends deleteTrosnov
     */
    public function updateInstallakt(AcceptanceTester $I)
    {
        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);
        $I->click('//div[@id="installaktgrid_gw"]/div/div/table/tbody/tr/td[text()="ПЕТРОВ ПЕТР ПЕТРОВИЧ"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);

        $I->chooseValueFromSelect2('Installakt[id_installer]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');
        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'installaktgrid_gw']);
        $I->seeElement('//div[@id="installaktgrid_gw"]/div/div/table/tbody/tr/td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]/preceding-sibling::td/button[@title="Удалить"]');
    }

    /**
     * @depends updateInstallakt
     */
    public function deleteInstallakt(AcceptanceTester $I)
    {

        $I->click('//div[@id="installaktgrid_gw"]/div/div/table/tbody/tr/td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends loadData
     */
    public function destroyData()
    {
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
        Podraz::deleteAll();
    }
}
