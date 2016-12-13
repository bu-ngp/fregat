<?php
use yii\helpers\Url;

/**
 * @group OsmotraktCest
 */
class OsmotraktCest
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
        $I->see('Журнал осмотров материальных ценностей');
    }

    /**
     * @depends openFregat
     */
    public function openOsmotrakt(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал осмотров материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'osmotraktgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openOsmotrakt
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('osmotrakt.sql');
    }

    /**
     * @depends loadData
     */
    public function openCreateOsmotrakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'osmotrakt-form']);
    }

    /**
     * @depends openCreateOsmotrakt
     */
    public function saveOsmotrakt(AcceptanceTester $I)
    {
        $I->chooseValueFromGrid('Osmotrakt[id_tr_osnov]', '1000002, каб. 101, ПОЛИКЛИНИКА 1, Кухонный стол', 'tr-osnovgrid_gw', '', 1);
        $I->seeInField('Material[material_name]', 'Кухонный стол');
        $I->seeInField('Material[material_inv]', '1000002');
        $I->seeInField('Material[material_serial]', '');
        $I->seeInField('Build[build_name]', 'ПОЛИКЛИНИКА 1');
        $I->seeInField('TrOsnov[tr_osnov_kab]', '101');
        $I->seeInField('Authuser[auth_user_fullname]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ПРОГРАММИСТ');

        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', 'сид');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА НОЖКА', 'нож');
        $I->fillField('Osmotrakt[osmotrakt_comment]', 'Образовалась трещина');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
    }

    /**
     * @depends saveOsmotrakt
     */
    public function saveOsmotraktWithInstallakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'osmotrakt-form']);

        $I->click('//a[@data-toggle="collapse"]');
        $I->wait(2);

        $I->chooseValueFromGrid('InstallTrOsnov[id_mattraffic]', '1000003, ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, Шкаф для медикаментов', 'mattrafficgrid_gw', '', 3);

        $I->seeInField('Material[material_name]', 'Шкаф для медикаментов');
        $I->seeInField('Material[material_writeoff]', 'Нет');
        $I->seeInField('Authuser[auth_user_fullname]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ТЕРАПЕВТ');
        $I->seeInField('Build[build_name]', '');
        $I->seeInField('InstallTrOsnov[mattraffic_number]', '1.000');

        $I->fillField('InstallTrOsnov[tr_osnov_kab]', '102');
        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ', 'фед');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА ПОЛКА', 'пол');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->see('У материально ответственного лица "ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ" не заполнено "Здание", в которое устанавливается материальная ценность');

        $I->chooseValueFromSelect2('InstallTrOsnov[id_mattraffic]', '1000001, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1, Шкаф для одежды', '001');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', Yii::$app->formatter->asDate(date('d.m.Y')), 'Шкаф для одежды', '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
    }

    /**
     * @depends saveOsmotraktWithInstallakt
     */
    public function saveOsmotraktWithInstallaktAndChangeMOL(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'osmotrakt-form']);

        $I->click('//a[@data-toggle="collapse"]');
        $I->wait(2);

        $I->chooseValueFromGrid('InstallTrOsnov[id_mattraffic]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1, Кухонный стол', 'mattrafficgrid_gw', '', 3);
        $I->seeInField('Authuser[auth_user_fullname]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ПРОГРАММИСТ');
        $I->seeInField('Build[build_name]', 'ПОЛИКЛИНИКА 1');

        $I->click('//button[contains(text(),"Сменить материально-ответственное лицо")]');
        $I->wait(2);
        $I->seeElement(['class' => 'mattraffic-form']);

        $I->seeInField('Material[material_inv]', '1000002');
        $I->seeElement(['id' => 'mattraffic_mols_grid_gw']);

        $I->click('//select[@name="Mattraffic[id_mol]"]/following-sibling::div/a[@class="btn btn-success"]');
        $I->wait(2);
        $I->seeElement(['id' => 'employeegrid_gw']);

        $I->click('//button[contains(text(), "Добавить")]');
        $I->wait(2);

        $I->click('//td[contains(text(), "ИВАНОВ ИВАН ИВАНОВИЧ")]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);

        $I->checkDynagridData(['1175', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', '101', 'ПОЛИКЛИНИКА 1'], 'employeeauthusergrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('employeeauthusergrid_gw', 1);

        $I->click('//a[contains(text(), "Добавить специальность")]');
        $I->wait(2);

        $I->seeElement(['class' => 'employee-form']);
        $I->chooseValueFromSelect2('Employee[id_dolzh]', 'ТЕРАПЕВТ', 'тер');
        $I->chooseValueFromSelect2('Employee[id_podraz]', 'ТЕРАПЕВТИЧЕСКОЕ', 'тер');
        $I->chooseValueFromSelect2('Employee[id_build]', 'ПОЛИКЛИНИКА 2', 'пол');

        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->countRowsDynagridEquals('employeeauthusergrid_gw', 2);

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->click('//a[@id="backbutton"]');
        $I->wait(2);

        $I->countRowsDynagridEquals('employeegrid_gw', 5);
        $I->clickChooseButtonFromGrid(['1179', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПОЛИКЛИНИКА 2'], 'employeegrid_gw');
        $I->click('//button[contains(text(), "Сменить")]');

        $I->chooseValueFromSelect2('InstallTrOsnov[id_mattraffic]', '1000002, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2, Кухонный стол', '002');
        $I->wait(2);
        $I->seeInField('Authuser[auth_user_fullname]', 'ИВАНОВ ИВАН ИВАНОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ТЕРАПЕВТ');
        $I->seeInField('Build[build_name]', 'ПОЛИКЛИНИКА 2');

        $I->fillField('InstallTrOsnov[tr_osnov_kab]', '103');
        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', 'сид');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА НОЖКА', 'нож');

        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', Yii::$app->formatter->asDate(date('d.m.Y')), 'Шкаф для одежды', '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['3', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 3);
    }

    /**
     * @depends saveOsmotraktWithInstallaktAndChangeMOL
     */
    public function updateOsmotrakt(AcceptanceTester $I)
    {
        $I->click('//td[text()="3"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);

        $I->fillField('Osmotrakt[osmotrakt_comment]', 'Неисправна');

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['3', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 3);
    }

    /**
     * @depends updateOsmotrakt
     */
    public function checkExcelExportOsmotrakt(AcceptanceTester $I)
    {
        $I->click('//');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт снятия комплектующих с матер-ых цен-тей №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт снятия комплектующих с матер-ых цен-тей №1.xlsx'), [
            ['A', 3, 'комплектующих № 1 от ' . date('d.m.Y')],

            ['A', 7, '№'],
            ['B', 7, 'Вид'],
            ['C', 7, 'Наименование'],
            ['D', 7, 'Инвентарный номер'],
            ['E', 7, 'Серийный номер'],
            ['F', 7, 'Год выпуска'],
            ['G', 7, 'Стоимость'],
            ['H', 7, 'Здание'],
            ['I', 7, 'Кабинет'],
            ['J', 7, 'Материально-ответственное лицо'],
            ['K', 7, 'Тип'],

            ['A', 9, '1'],
            ['B', 9, 'ШКАФ'],
            ['C', 9, 'Шкаф для инвентаря'],
            ['D', 9, '0001'],
            ['E', 9, ''],
            ['F', 9, ''],
            ['G', 9, '1.00'],
            ['H', 9, 'ПОЛИКЛИНИКА 1'],
            ['I', 9, '101'],
            ['J', 9, 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ'],
            ['K', 9, 'Основное средство'],

            ['A', 11, '№'],
            ['B', 11, 'Вид'],
            ['C', 11, 'Наименование'],
            ['D', 11, 'Инвентарный номер'],
            ['E', 11, 'Серийный номер'],
            ['F', 11, 'Кол-во'],
            ['G', 11, 'Единица измерения'],
            ['H', 11, 'Год выпуска'],
            ['I', 11, 'Стоимость'],
            ['J', 11, 'Материально-ответственное лицо'],
            ['K', 11, 'Тип'],

            ['A', 13, '1'],
            ['B', 13, 'ШВАБРА'],
            ['C', 13, 'Швабра деревянная'],
            ['D', 13, '0003'],
            ['E', 13, ''],
            ['F', 13, '1'],
            ['G', 13, 'шт'],
            ['H', 13, ''],
            ['I', 13, '1'],
            ['J', 13, 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ'],
            ['K', 13, 'Материал'],

            ['A', 14, '2'],
            ['B', 14, 'ВЕДРО'],
            ['C', 14, 'Ведро пластиковое'],
            ['D', 14, '0002'],
            ['E', 14, ''],
            ['F', 14, '1'],
            ['G', 14, 'шт'],
            ['H', 14, ''],
            ['I', 14, '1'],
            ['J', 14, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ'],
            ['K', 14, 'Материал'],

            ['A', 17, 'Материально ответственное лицо'],
            ['D', 17, 'ПРОГРАММИСТ'],
            ['H', 17, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],

            ['A', 18, 'Материально ответственное лицо'],
            ['D', 18, 'ТЕРАПЕВТ'],
            ['H', 18, 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ'],

            ['A', 19, 'Демонтажник'],
            ['D', 19, 'ПРОГРАММИСТ'],
            ['H', 19, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],
        ]);
    }

    /**
     * @depends updateOsmotrakt
     */
    public function deleteOsmotrakt(AcceptanceTester $I)
    {
        $I->click('//div[@id="osmotraktgrid_gw"]/div/div/table/tbody/tr/td[text()="2"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['3', Yii::$app->formatter->asDate(date('d.m.Y')), 'Кухонный стол', '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 2);
    }


}
