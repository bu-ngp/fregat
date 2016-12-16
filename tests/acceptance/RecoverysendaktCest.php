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
use app\models\Fregat\Organ;
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
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(2);

        $I->seeElement('//select[@name="Recoverysendakt[id_organ]"]/following-sibling::span/span/span/span[@title="РОГА И КОПЫТА"]');
        $I->seeInField('recoverysendakt_date-recoverysendakt-recoverysendakt_date', date('d.m.Y'));
        $I->seeElement(['id' => 'recoveryrecieveaktgrid_gw']);
        $I->seeElement('//div[@id="recoveryrecieveaktgrid_gw"]/div/div/table/tbody/tr/td/div[text()="Ничего не найдено."]');
        $I->seeElement(['id' => 'recoveryrecieveaktmatgrid_gw']);
        $I->seeElement('//div[@id="recoveryrecieveaktmatgrid_gw"]/div/div/table/tbody/tr/td/div[text()="Ничего не найдено."]');
    }

    /**
     * @depends saveRecoverysendakt
     */
    public function createRecoveryrecieveakt(AcceptanceTester $I)
    {
        $I->click(['link' => 'Добавить акт осмотра']);
        $I->wait(2);
        $I->seeElement(['id' => 'osmotraktgrid_gw']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 2);
        $I->clickChooseButtonFromGrid(['2', '15.12.2016', 'Шкаф для одежды', '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw');
        $I->checkDynagridData(['2', '1000001', 'Шкаф для одежды', 'ПОЛИКЛИНИКА 1', '102', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', '', '', ''], 'recoveryrecieveaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoveryrecieveaktgrid_gw', 1);
    }

    /**
     * @depends createRecoveryrecieveakt
     */
    public function createRecoveryrecieveaktFast(AcceptanceTester $I)
    {
        $I->chooseValueFromSelect2('Osmotrakt[osmotrakt_id]', 'Акт №1, 1000002, Кухонный стол, ПОЛИКЛИНИКА 1, 101', '002');
        $I->click(['id' => 'addrecoveryrecieveakt']);
        $I->wait(2);
        $I->checkDynagridData(['1', '1000002', 'Кухонный стол', 'ПОЛИКЛИНИКА 1', '101', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', '', '', ''], 'recoveryrecieveaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', '1000001', 'Шкаф для одежды', 'ПОЛИКЛИНИКА 1', '102', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', '', '', ''], 'recoveryrecieveaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoveryrecieveaktgrid_gw', 2);
    }

    /**
     * @depends createRecoveryrecieveaktFast
     */
    public function updateRecoveryrecieveakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('recoveryrecieveaktgrid_gw', 'a[@title="Обновить"]', ['1', '1000002', 'Кухонный стол', 'ПОЛИКЛИНИКА 1', '101', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', '', '', '']);
        $I->seeElement(['class' => 'recoveryrecieveakt-form']);
        $I->checkDynagridData(['1', '1000002', 'Кухонный стол', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'ПОЛИКЛИНИКА 1', '101', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'osmotrakt_rra_grid_gw');
        $I->fillField('Recoveryrecieveakt[recoveryrecieveakt_result]', 'Заменено');
        $I->chooseValueFromSelect2('Recoveryrecieveakt[recoveryrecieveakt_repaired]', 'Восстановлено');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->checkDynagridData(['1', '1000002', 'Кухонный стол', 'ПОЛИКЛИНИКА 1', '101', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'Заменено', 'Восстановлено', date('d.m.Y')], 'recoveryrecieveaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', '1000001', 'Шкаф для одежды', 'ПОЛИКЛИНИКА 1', '102', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', '', '', ''], 'recoveryrecieveaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoveryrecieveaktgrid_gw', 2);
    }

    /**
     * @depends updateRecoveryrecieveakt
     */
    public function createRecoveryrecieveaktmat(AcceptanceTester $I)
    {
        $I->click(['link' => 'Добавить материал для восстановления']);
        $I->wait(2);
        $I->seeElement(['id' => 'trmatosmotrgrid_gw']);
        $I->countRowsDynagridEquals('trmatosmotrgrid_gw', 1);
        $I->clickChooseButtonFromGrid(['Картридж А12', '1000004', '1.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ПОЛИКЛИНИКА 2', 'HP LJ 1022', '1000003', '1', '15.12.2016', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'ТРЕБУЕТСЯ ЗАПРАВКА', ''], 'trmatosmotrgrid_gw');
        $I->checkDynagridData(['1', '15.12.2016', '1000004', 'Картридж А12', '1.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ПОЛИКЛИНИКА 2', 'ТРЕБУЕТСЯ ЗАПРАВКА', '', '', '', ''], 'recoveryrecieveaktmatgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoveryrecieveaktmatgrid_gw', 1);
    }

    /**
     * @depends createRecoveryrecieveaktmat
     */
    public function updateRecoveryrecieveaktmat(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('recoveryrecieveaktmatgrid_gw', 'a[@title="Обновить"]', ['1', '15.12.2016', '1000004', 'Картридж А12', '1.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ПОЛИКЛИНИКА 2', 'ТРЕБУЕТСЯ ЗАПРАВКА', '', '', '', '']);
        $I->seeElement(['class' => 'recoveryrecieveaktmat-form']);
        $I->checkDynagridData(['1', '1000004', 'Картридж А12', '1.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ПОЛИКЛИНИКА 2', 'ТРЕБУЕТСЯ ЗАПРАВКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'osmotrakt_rramat_grid_gw');
        $I->fillField('Recoveryrecieveaktmat[recoveryrecieveaktmat_result]', 'Заменен чип');
        $I->chooseValueFromSelect2('Recoveryrecieveaktmat[recoveryrecieveaktmat_repaired]', 'Восстановлено');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->checkDynagridData(['1', '15.12.2016', '1000004', 'Картридж А12', '1.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ПОЛИКЛИНИКА 2', 'ТРЕБУЕТСЯ ЗАПРАВКА', '', 'Заменен чип', 'Восстановлено', date('d.m.Y')], 'recoveryrecieveaktmatgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoveryrecieveaktmatgrid_gw', 1);
    }

    /**
     * @depends updateRecoveryrecieveaktmat
     */
    public function checkExcelExportRecoverysendakt(AcceptanceTester $I)
    {
        $I->click(['id' => 'DownloadReport']);
        $I->wait(1);
        $I->click('//a[contains(text(),"Акт передачи материальных ценностей сторонней организации")]');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт передачи матер-ных цен-тей сторонней организации №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт передачи матер-ных цен-тей сторонней организации №1.xlsx'), [
            ['A', 3, 'сторонней организации № 1 от ' . date('d.m.Y')],

            ['C', 4, 'РОГА И КОПЫТА'],

            ['A', 7, '№'],
            ['B', 7, 'Вид'],
            ['C', 7, 'Наименование'],
            ['D', 7, 'Инвентарный номер'],
            ['E', 7, 'Серийный номер'],
            ['F', 7, "Кол-\nво\n"],
            ['G', 7, 'Единица измерения'],
            ['H', 7, 'Причина выхода из строя'],
            ['I', 7, 'Здание'],
            ['J', 7, 'Кабинет'],

            ['A', 9, '1'],
            ['B', 9, 'ШКАФ'],
            ['C', 9, 'Шкаф для одежды'],
            ['D', 9, '1000001'],
            ['E', 9, 'ABCD123'],
            ['F', 9, '1'],
            ['G', 9, 'шт'],
            ['H', 9, 'СЛОМАНА ПОЛКА. '],
            ['I', 9, 'ПОЛИКЛИНИКА 1'],
            ['J', 9, '102'],

            ['A', 10, '2'],
            ['B', 10, 'СТОЛ'],
            ['C', 10, 'Кухонный стол'],
            ['D', 10, '1000002'],
            ['E', 10, ''],
            ['F', 10, '1'],
            ['G', 10, 'шт'],
            ['H', 10, 'СЛОМАНА НОЖКА. '],
            ['I', 10, 'ПОЛИКЛИНИКА 1'],
            ['J', 10, '101'],

            ['A', 12, 'Материально ответственное лицо'],
            ['D', 12, 'ТЕРАПЕВТ'],
            ['H', 12, 'ИВАНОВ ИВАН ИВАНОВИЧ'],

            ['A', 13, 'Материально ответственное лицо'],
            ['D', 13, 'ПРОГРАММИСТ'],
            ['H', 13, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],

            ['A', 14, 'Представитель организации'],
            ['D', 14, ''],
            ['H', 14, ''],
        ]);
    }

    /**
     * @depends checkExcelExportRecoverysendakt
     */
    public function checkExcelExportRecoverysendaktmat(AcceptanceTester $I)
    {
        $I->click(['id' => 'DownloadReport']);
        $I->wait(1);
        $I->click('//a[contains(text(),"Акт передачи материалов сторонней организации")]');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт передачи материалов сторонней организации №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт передачи материалов сторонней организации №1.xlsx'), [
            ['A', 3, 'сторонней организации № 1 от ' . date('d.m.Y')],

            ['C', 4, 'РОГА И КОПЫТА'],

            ['A', 7, '№'],
            ['B', 7, 'Вид'],
            ['C', 7, 'Наименование'],
            ['D', 7, 'Инвентарный номер'],
            ['E', 7, 'Тип'],
            ['F', 7, "Кол-\nво\n"],
            ['G', 7, 'Единица измерения'],
            ['H', 7, 'Причина выхода из строя'],
            ['I', 7, 'Материально-ответственное лицо'],
            ['J', 7, 'Здание, Кабинет'],

            ['A', 9, '1'],
            ['B', 9, 'КАРТРИДЖ'],
            ['C', 9, 'Картридж А12'],
            ['D', 9, '1000004'],
            ['E', 9, 'Материал'],
            ['F', 9, '1'],
            ['G', 9, 'шт'],
            ['H', 9, 'ТРЕБУЕТСЯ ЗАПРАВКА. '],
            ['I', 9, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ'],
            ['J', 9, 'ПОЛИКЛИНИКА 2, 103'],

            ['A', 11, 'Материально ответственное лицо'],
            ['D', 11, 'НЕВРОЛОГ'],
            ['H', 11, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ'],
        ]);
    }

    /**
     * @depends checkExcelExportRecoverysendaktmat
     */
    public function checkExcelExportRecoveryrecieveakt(AcceptanceTester $I)
    {
        $I->click(['id' => 'DownloadReport']);
        $I->wait(1);
        $I->click('//a[contains(text(),"Акт получения материальных ценностей от сторонней организации")]');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт получения матер-ных цен-тей от сторонней организации №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт получения матер-ных цен-тей от сторонней организации №1.xlsx'), [

            ['F', 4, 'РОГА И КОПЫТА'],
            ['F', 5, '1 от ' . date('d.m.Y')],
            ['F', 6, date('d.m.Y')],

            ['A', 9, '№'],
            ['B', 9, 'Вид'],
            ['C', 9, 'Наименование'],
            ['D', 9, 'Инвентарный номер'],
            ['E', 9, 'Серийный номер'],
            ['F', 9, "Кол-\nво\n"],
            ['G', 9, 'Единица измерения'],
            ['H', 9, 'Результат'],
            ['I', 9, 'Здание'],
            ['J', 9, 'Кабинет'],
            ['K', 9, 'Дата получения'],

            ['A', 11, '1'],
            ['B', 11, 'СТОЛ'],
            ['C', 11, 'Кухонный стол'],
            ['D', 11, '1000002'],
            ['E', 11, ''],
            ['F', 11, '1'],
            ['G', 11, 'шт'],
            ['H', 11, 'Заменено'],
            ['I', 11, 'ПОЛИКЛИНИКА 1'],
            ['J', 11, '101'],
            ['K', 11, date('d.m.Y')],

            ['A', 14, '№'],
            ['B', 14, 'Вид'],
            ['C', 14, 'Наименование'],
            ['D', 14, 'Инвентарный номер'],
            ['E', 14, 'Серийный номер'],
            ['F', 14, "Кол-\nво\n"],
            ['G', 14, 'Единица измерения'],
            ['H', 14, 'Результат'],
            ['I', 14, 'Здание'],
            ['J', 14, 'Кабинет'],
            ['K', 14, 'Дата получения'],

            ['A', 16, '1'],
            ['B', 16, 'ШКАФ'],
            ['C', 16, 'Шкаф для одежды'],
            ['D', 16, '1000001'],
            ['E', 16, 'ABCD123'],
            ['F', 16, '1'],
            ['G', 16, 'шт'],
            ['H', 16, ''],
            ['I', 16, 'ПОЛИКЛИНИКА 1'],
            ['J', 16, '102'],
            ['K', 16, ''],

            ['A', 19, 'Материально ответственное лицо'],
            ['D', 19, 'ТЕРАПЕВТ'],
            ['H', 19, 'ИВАНОВ ИВАН ИВАНОВИЧ'],

            ['A', 20, 'Материально ответственное лицо'],
            ['D', 20, 'ПРОГРАММИСТ'],
            ['H', 20, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],

            ['A', 21, 'Представитель организации'],
            ['D', 21, ''],
            ['H', 21, ''],
        ]);
    }

    /**
     * @depends checkExcelExportRecoveryrecieveakt
     */
    public function checkExcelExportRecoveryrecieveaktmat(AcceptanceTester $I)
    {
        $I->click(['id' => 'DownloadReport']);
        $I->wait(1);
        $I->click('//a[contains(text(),"Акт получения материалов от сторонней организации")]');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт получения материалов у сторонней организации №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт получения материалов у сторонней организации №1.xlsx'), [

            ['F', 4, 'РОГА И КОПЫТА'],
            ['F', 5, '1 от ' . date('d.m.Y')],
            ['F', 6, date('d.m.Y')],

            ['A', 9, '№'],
            ['B', 9, 'Вид'],
            ['C', 9, 'Наименование'],
            ['D', 9, 'Инвентарный номер'],
            ['E', 9, 'Тип'],
            ['F', 9, "Кол-\nво\n"],
            ['G', 9, 'Единица измерения'],
            ['H', 9, 'Результат'],
            ['I', 9, 'Материально-ответственное лицо'],
            ['J', 9, 'Здание, Кабинет'],
            ['K', 9, 'Дата получения'],

            ['A', 11, '1'],
            ['B', 11, 'КАРТРИДЖ'],
            ['C', 11, 'Картридж А12'],
            ['D', 11, '1000004'],
            ['E', 11, 'Материал'],
            ['F', 11, '1'],
            ['G', 11, 'шт'],
            ['H', 11, 'Заменен чип'],
            ['I', 11, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ'],
            ['J', 11, 'ПОЛИКЛИНИКА 2, 103'],
            ['K', 11, date('d.m.Y')],

            ['A', 14, '№'],
            ['B', 14, 'Вид'],
            ['C', 14, 'Наименование'],
            ['D', 14, 'Инвентарный номер'],
            ['E', 14, 'Тип'],
            ['F', 14, "Кол-\nво\n"],
            ['G', 14, 'Единица измерения'],
            ['H', 14, 'Результат'],
            ['I', 14, 'Материально-ответственное лицо'],
            ['J', 14, 'Здание, Кабинет'],
            ['K', 14, 'Дата получения'],

            ['A', 18, 'Материально ответственное лицо'],
            ['D', 18, 'НЕВРОЛОГ'],
            ['H', 18, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ'],

            ['A', 19, 'Представитель организации'],
            ['D', 19, ''],
            ['H', 19, ''],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Акт передачи матер-ных цен-тей сторонней организации №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт передачи матер-ных цен-тей сторонней организации №1.xlsx'));

        if (file_exists($I->convertOSFileName('web/files/' . 'Акт передачи материалов сторонней организации №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт передачи материалов сторонней организации №1.xlsx'));

        if (file_exists($I->convertOSFileName('web/files/' . 'Акт получения матер-ных цен-тей от сторонней организации №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт получения матер-ных цен-тей от сторонней организации №1.xlsx'));

        if (file_exists($I->convertOSFileName('web/files/' . 'Акт получения материалов у сторонней организации №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт получения материалов у сторонней организации №1.xlsx'));
    }

    /**
     * @depends checkExcelExportRecoveryrecieveakt
     */
    public function updateRecoverysendakt(AcceptanceTester $I)
    {
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->checkDynagridData(['1', date('d.m.Y'), 'РОГА И КОПЫТА'], 'recoverysendaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoverysendaktgrid_gw', 1);
        $I->clickButtonDynagrid('recoverysendaktgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'РОГА И КОПЫТА']);
        $I->chooseValueFromSelect2('Recoverysendakt[id_organ]', 'ФИРМА', 'фир');

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->checkDynagridData(['1', date('d.m.Y'), 'ФИРМА'], 'recoverysendaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoverysendaktgrid_gw', 1);
    }

    /**
     * @depends updateRecoverysendakt
     */
    public function deleteRecoveryrecieveakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('recoverysendaktgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'ФИРМА']);

        $I->click('//div[@id="recoveryrecieveaktgrid_gw"]/div/div/table/tbody/tr/td[text()="Шкаф для одежды"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->checkDynagridData(['1', '1000002', 'Кухонный стол', 'ПОЛИКЛИНИКА 1', '101', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'Заменено', 'Восстановлено', date('d.m.Y')], 'recoveryrecieveaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('recoveryrecieveaktgrid_gw', 1);
    }

    /**
     * @depends deleteRecoveryrecieveakt
     */
    public function deleteRecoveryrecieveaktmat(AcceptanceTester $I)
    {
        $I->click('//div[@id="recoveryrecieveaktmatgrid_gw"]/div/div/table/tbody/tr/td[text()="Картридж А12"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->countRowsDynagridEquals('recoveryrecieveaktmatgrid_gw', 0);

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
    }

    /**
     * @depends deleteRecoveryrecieveaktmat
     */
    public function deleteRecoverysendakt(AcceptanceTester $I)
    {
        $I->click('//div[@id="recoverysendaktgrid_gw"]/div/div/table/tbody/tr/td[text()="ФИРМА"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->see('Ничего не найдено.');
    }

    /**
     * @depends loadData
     */
    public function destroyData()
    {
        Recoveryrecieveaktmat::deleteAll();
        Recoveryrecieveakt::deleteAll();
        Recoverysendakt::deleteAll();
        TrMatOsmotr::deleteAll();
        Osmotraktmat::deleteAll();
        Osmotrakt::deleteAll();
        Reason::deleteAll();
        Organ::deleteAll();
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
