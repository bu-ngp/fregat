<?php

use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Cabinet;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Installakt;
use app\models\Fregat\Izmer;
use app\models\Fregat\Material;
use app\models\Fregat\Mattraffic;
use app\models\Fregat\Matvid;
use app\models\Fregat\Podraz;
use app\models\Fregat\Removeakt;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\TrRmMat;
use yii\helpers\Url;


/**
 * @group RemoveaktCest
 */
class RemoveaktCest
{
    /**
     * @depends LoginCest:login
     */
    public function openFregat(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/Fregat/fregat/mainmenu'));
        $I->wait(2);
        $I->see('Журнал снятия комплектующих с материальных ценностей');
    }

    /**
     * @depends openFregat
     */
    public function openRemoveakt(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал снятия комплектующих с материальных ценностей")]');
        $I->wait(2);
        $I->countRowsDynagridEquals('removeaktgrid_gw', 0);
    }

    /**
     * @depends openRemoveakt
     */
    public function openCreateRemoveakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'removeakt-form']);
    }

    /**
     * @depends openCreateRemoveakt
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('removeakt.sql');
    }

    /**
     * @depends loadData
     */
    public function saveRemoveakt(AcceptanceTester $I)
    {
        $I->chooseValueFromSelect2('Removeakt[id_remover]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->click('//button[@form="Removeaktform"]');
        $I->wait(2);

        $I->seeInSelect2('Removeakt[id_remover]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1');
        $I->seeInDatecontrol('Removeakt[removeakt_date]', date('d.m.Y'));
        $I->seeElement(['class' => 'removeakt-form']);
        $I->countRowsDynagridEquals('trRmMatgrid_gw', 0);
    }

    /**
     * @depends saveRemoveakt
     */
    public function createTrRmMatOne(AcceptanceTester $I)
    {
        $I->click('//div[@id="trRmMatgrid_gw"]/div/div/a[contains(text(), "Добавить материальную ценность")]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Ведро пластиковое', 'href' => '/Fregat/material/update?id=35']], '0002', '1.000', ['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', 'ПОЛИКЛИНИКА 1', '101', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trmatgrid_gw');
        $I->checkDynagridData([['link' => ['text' => 'Швабра деревянная', 'href' => '/Fregat/material/update?id=36']], '0003', '1.000', ['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', 'ПОЛИКЛИНИКА 1', '101', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'trmatgrid_gw');

        $I->clickChooseButtonFromGrid([['link' => ['text' => 'Ведро пластиковое', 'href' => '/Fregat/material/update?id=35']], '0002', '1.000', ['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', 'ПОЛИКЛИНИКА 1', '101', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trmatgrid_gw');

        $I->checkDynagridData([['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', '', 'ПОЛИКЛИНИКА 1', '101', ['link' => ['text' => 'Ведро пластиковое', 'href' => '/Fregat/material/update?id=35']], '0002', '1.000', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trRmMatgrid_gw');
    }

    /**
     * @depends createTrRmMatOne
     */
    public function createTrRmMatTwo(AcceptanceTester $I)
    {
        $I->click('//div[@id="trRmMatgrid_gw"]/div/div/a[contains(text(), "Добавить материальную ценность")]');
        $I->wait(2);
        $I->dontSeeDynagridData([['link' => ['text' => 'Ведро пластиковое', 'href' => '/Fregat/material/update?id=35']], '0002', '1.000', ['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', 'ПОЛИКЛИНИКА 1', '101', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trmatgrid_gw');
        $I->checkDynagridData([['link' => ['text' => 'Швабра деревянная', 'href' => '/Fregat/material/update?id=36']], '0003', '1.000', ['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', 'ПОЛИКЛИНИКА 1', '101', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'trmatgrid_gw');

        $I->clickChooseButtonFromGrid([['link' => ['text' => 'Швабра деревянная', 'href' => '/Fregat/material/update?id=36']], '0003', '1.000', ['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', 'ПОЛИКЛИНИКА 1', '101', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'trmatgrid_gw');

        $I->checkDynagridData([['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', '', 'ПОЛИКЛИНИКА 1', '101', ['link' => ['text' => 'Ведро пластиковое', 'href' => '/Fregat/material/update?id=35']], '0002', '1.000', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trRmMatgrid_gw');
        $I->checkDynagridData([['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', '', 'ПОЛИКЛИНИКА 1', '101', ['link' => ['text' => 'Швабра деревянная', 'href' => '/Fregat/material/update?id=36']], '0003', '1.000', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'trRmMatgrid_gw');
    }

    /**
     * @depends createTrRmMatTwo
     */
    public function checkExcelExport(AcceptanceTester $I)
    {
        $I->click(['id' => 'DownloadReport']);
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

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Акт снятия комплектующих с матер-ых цен-тей №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт снятия комплектующих с матер-ых цен-тей №1.xlsx'));
    }

    /**
     * @depends checkExcelExport
     */
    public function deleteTrRmMat(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('trRmMatgrid_gw', 'button[@title="Удалить"]', [['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', '', 'ПОЛИКЛИНИКА 1', '101', ['link' => ['text' => 'Швабра деревянная', 'href' => '/Fregat/material/update?id=36']], '0003', '1.000', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', '', 'ПОЛИКЛИНИКА 1', '101', ['link' => ['text' => 'Ведро пластиковое', 'href' => '/Fregat/material/update?id=35']], '0002', '1.000', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'trRmMatgrid_gw');
        $I->dontSeeDynagridData([['link' => ['text' => 'Шкаф для инвентаря', 'href' => '/Fregat/material/update?id=34']], '0001', '', 'ПОЛИКЛИНИКА 1', '101', ['link' => ['text' => 'Швабра деревянная', 'href' => '/Fregat/material/update?id=36']], '0003', '1.000', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'trRmMatgrid_gw');
    }

    /**
     * @depends deleteTrRmMat
     */
    public function updateRemoveakt(AcceptanceTester $I)
    {
        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'removeaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);

        $I->clickButtonDynagrid('removeaktgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ']);
        $I->wait(2);

        $I->chooseValueFromSelect2('Removeakt[id_remover]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');
        //     $I->click('//button[@form="Removeaktform"]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ'], 'removeaktgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
    }

    /**
     * @depends updateRemoveakt
     */
    public function deleteRemoveakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('removeaktgrid_gw', 'button[@title="Удалить"]', ['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ']);

        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->countRowsDynagridEquals('removeaktgrid_gw', 0);
    }

    /**
     * @depends loadData
     */
    public function destroyData()
    {
        TrRmMat::deleteAll();
        Removeakt::deleteAll();
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
        Cabinet::deleteAll();
        Build::deleteAll();
        Dolzh::deleteAll();
        Podraz::deleteAll();
    }
}
