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
use app\models\Fregat\Schetuchet;
use app\models\Fregat\Spisosnovakt;
use app\models\Fregat\Spisosnovmaterials;
use app\models\Fregat\TrOsnov;
use yii\helpers\Url;

/**
 * @group SpisosnovaktCest
 */
class SpisosnovaktCest
{
    /**
     * @depends LoginCest:login
     */
    public function openFregat(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/Fregat/fregat/mainmenu'));
        $I->wait(2);
        $I->see('Журнал списания основных средств');
    }

    /**
     * @depends openFregat
     */
    public function openSpisosnovakt(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал списания основных средств")]');
        $I->wait(2);
        $I->seeElement(['id' => 'spisosnovaktgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openSpisosnovakt
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('spisosnovakt.sql');
    }

    /**
     * @depends loadData
     */
    public function openCreateSpisosnovakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'spisosnovakt-form']);
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Счет учета».');
        $I->see('Необходимо заполнить «Материально-ответственное лицо».');

        $I->chooseValueFromSelect2('Spisosnovakt[id_schetuchet]', '101.34, НОВЫЙ СЧЕТ', '101');
        $I->chooseValueFromSelect2('Spisosnovakt[id_mol]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Spisosnovakt[id_employee]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ', 'фед');

        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(2);

        $I->seeInField('Spisosnovakt[spisosnovakt_id]', '1');
        $I->seeElement('//input[@name="Spisosnovakt[spisosnovakt_id]" and @disabled]');

        $I->seeInDatecontrol('Spisosnovakt[spisosnovakt_date]', date('d.m.Y'));
        $I->seeInSelect2('Spisosnovakt[id_schetuchet]', '101.34, НОВЫЙ СЧЕТ', true);
        $I->seeInSelect2('Spisosnovakt[id_mol]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', true);
        $I->seeInSelect2('Spisosnovakt[id_employee]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ');

        $I->seeElement(['id' => 'spisosnovmaterialsgrid_gw']);
        $I->countRowsDynagridEquals('spisosnovmaterialsgrid_gw', 0);
    }

    /**
     * @depends openCreateSpisosnovakt
     */
    public function createSpisosnovmaterials(AcceptanceTester $I)
    {
        $I->click('//a[contains(text(),"Добавить материальную ценность")]');
        $I->wait(2);
        $I->chooseValueFromGrid('Spisosnovmaterials[id_mattraffic]', '1000003, СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', 'mattrafficgrid_gw', '//td/a[text()="HP LJ 1022" and @href="/Fregat/material/update?id=36"]/../preceding-sibling::td/button[@title="Выбрать"]', 3);
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(1);
        $I->see('Материальная ценность не соответствует МОЛ\'у, заявки на списание: ПЕТРОВ ПЕТР ПЕТРОВИЧ');

        $I->chooseValueFromSelect2('Spisosnovmaterials[id_mattraffic]', '1000001, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', '001');
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(1);
        $I->see('Материальная ценность не соответствует счету учета, заявки на списание: 101.34');

        $I->chooseValueFromSelect2('Spisosnovmaterials[id_mattraffic]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', '002');
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '', '01.05.2010', '1.000', '15000.00'], 'spisosnovmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spisosnovmaterialsgrid_gw', 1);
    }

    /**
     * @depends createSpisosnovmaterials
     */
    public function deleteSpisosnovmaterials(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spisosnovmaterialsgrid_gw', 'button[@title="Удалить"]', [['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '', '01.05.2010', '1.000', '15000.00']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->countRowsDynagridEquals('spisosnovmaterialsgrid_gw', 0);
    }

    /**
     * @depends deleteSpisosnovmaterials
     */
    public function createSpisosnovmaterialsFast(AcceptanceTester $I)
    {
        $I->cantChooseValueFromSelect2('Mattraffic[mattraffic_id]', '1000001, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', '001');
        $I->cantChooseValueFromSelect2('Mattraffic[mattraffic_id]', '1000003, СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', '003');
        $I->chooseValueFromSelect2('Mattraffic[mattraffic_id]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', '002');

        $I->click(['id' => 'addspisosnovmaterials']);
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '', '01.05.2010', '1.000', '15000.00'], 'spisosnovmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spisosnovmaterialsgrid_gw', 1);

        $I->cantChooseValueFromSelect2('Mattraffic[mattraffic_id]', '1000001, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', '001');
        $I->cantChooseValueFromSelect2('Mattraffic[mattraffic_id]', '1000003, СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', '003');
        $I->cantChooseValueFromSelect2('Mattraffic[mattraffic_id]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', '002');
    }

    /**
     * @depends createSpisosnovmaterialsFast
     */
    public function updateSpisosnovmaterials(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spisosnovmaterialsgrid_gw', 'a[@title="Обновить"]', [['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '', '01.05.2010', '1.000', '15000.00']);
        $I->wait(2);

        $I->fillField('Spisosnovmaterials[spisosnovmaterials_number]', '0.500');

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '', '01.05.2010', '0.500', '15000.00'], 'spisosnovmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spisosnovmaterialsgrid_gw', 1);

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ', '101.34', 'НОВЫЙ СЧЕТ'], 'spisosnovaktgrid_gw', ['button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spisosnovaktgrid_gw', 1);
    }

    /**
     * @depends updateSpisosnovmaterials
     */
    public function updateSpisosnovakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spisosnovaktgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ', '101.34', 'НОВЫЙ СЧЕТ']);
        $I->wait(2);
        $I->clearSelect2('Spisosnovakt[id_employee]');

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', '', '', '101.34', 'НОВЫЙ СЧЕТ'], 'spisosnovaktgrid_gw', ['button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spisosnovaktgrid_gw', 1);
    }

    /**
     * @depends updateSpisosnovakt
     */
    public function checkExcelExportSpisosnovakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spisosnovaktgrid_gw', 'button[@title="Скачать отчет"]', ['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', '', '', '101.34', 'НОВЫЙ СЧЕТ']);
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Заявка на списание основных средств №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Заявка на списание основных средств №1.xlsx'), [
            ['A', 3, 'основных средств № 1 от ' . date('d.m.Y')],

            ['C', 4, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ'],
            ['C', 5, 'АУП'],
            ['C', 6, '101.34, НОВЫЙ СЧЕТ'],

            ['A', 9, '№'],
            ['B', 9, 'Наименование'],
            ['D', 9, 'Инвентарный номер'],
            ['E', 9, 'Серийный номер'],
            ['F', 9, 'Дата выпуска'],
            ['G', 9, "Кол-\nво\n"],
            ['H', 9, 'Срок службы'],
            ['I', 9, 'Стоимость'],

            ['A', 11, '1'],
            ['B', 11, 'Кухонный стол'],
            ['D', 11, '1000002'],
            ['E', 11, '-'],
            ['F', 11, '01.05.2010'],
            ['G', 11, '0.5'],
            ['I', 11, '15000'],

            ['A', 13, 'Материально ответственное лицо'],
            ['D', 13, 'ПРОГРАММИСТ'],
            ['F', 13, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Заявка на списание основных средств №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Заявка на списание основных средств №1.xlsx'));
    }

    /**
     * @depends checkExcelExportSpisosnovakt
     */
    public function deleteSpisosnovakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spisosnovaktgrid_gw', 'button[@title="Удалить"]', ['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', '', '', '101.34', 'НОВЫЙ СЧЕТ']);
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
        Spisosnovmaterials::deleteAll();
        Spisosnovakt::deleteAll();
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
