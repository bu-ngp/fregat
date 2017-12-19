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
use app\models\Fregat\Naklad;
use app\models\Fregat\Nakladmaterials;
use app\models\Fregat\Podraz;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\TrOsnov;
use yii\helpers\Url;

/**
 * @group NakladCest
 */
class NakladCest
{
    /**
     * @depends LoginCest:login
     */
    public function openFregat(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/Fregat/fregat/mainmenu'));
        $I->wait(2);
        $I->see('Журнал требований - накладных');
    }

    /**
     * @depends openFregat
     */
    public function openNaklad(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал требований - накладных")]');
        $I->wait(2);
        $I->countRowsDynagridEquals('nakladgrid_gw', 0);
    }

    /**
     * @depends openNaklad
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('naklad.sql');
    }

    /**
     * @depends loadData
     */
    public function createNaklad(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'naklad-form']);
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «МОЛ, кто отпустил».');
        $I->see('Необходимо заполнить «МОЛ, кто затребовал».');

        $I->clickGridButtonBySelect2('Naklad[id_mol_release]');
        $I->checkDynagridData(['1176', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', 'ПОЛИКЛИНИКА 1'], 'employeegrid_gw', ['button[@title="Выбрать"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['1178', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПОЛИКЛИНИКА 2'], 'employeegrid_gw', ['button[@title="Выбрать"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('employeegrid_gw', 2);
        $I->clickChooseButtonFromGrid(['1176', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', 'ПОЛИКЛИНИКА 1'], 'employeegrid_gw');

        $I->clickGridButtonBySelect2('Naklad[id_mol_got]');
        $I->checkDynagridData(['1175', 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПОЛИКЛИНИКА 1'], 'employeegrid_gw', ['button[@title="Выбрать"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['1176', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', 'ПОЛИКЛИНИКА 1'], 'employeegrid_gw', ['button[@title="Выбрать"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['1177', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', ''], 'employeegrid_gw', ['button[@title="Выбрать"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['1178', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПОЛИКЛИНИКА 2'], 'employeegrid_gw', ['button[@title="Выбрать"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('employeegrid_gw', 4);
        $I->clickChooseButtonFromGrid(['1177', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', ''], 'employeegrid_gw');

        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(2);

        $I->seeInField('Naklad[naklad_id]', '1');
        $I->seeElement('//input[@name="Naklad[naklad_id]" and @disabled]');

        $I->seeInDatecontrol('Naklad[naklad_date]', date('d.m.Y'));
        $I->seeInSelect2('Naklad[id_mol_release]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', true);
        $I->seeInSelect2('Naklad[id_mol_got]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ');

        $I->seeElement(['id' => 'nakladmaterialsgrid_gw']);
        $I->countRowsDynagridEquals('nakladmaterialsgrid_gw', 0);
    }

    /**
     * @depends createNaklad
     */
    public function createNakladmaterials(AcceptanceTester $I)
    {
        $I->click('//a[contains(text(),"Добавить материальную ценность")]');
        $I->wait(2);

        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Материальная ценность».');

        $I->chooseValueFromGrid('Nakladmaterials[id_mattraffic]', '1000003, СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ПОЛИКЛИНИКА 2, HP LJ 1022', 'mattrafficgrid_gw', '//td/a[text()="HP LJ 1022" and contains(@href,"/Fregat/material/update?id=36")]/../preceding-sibling::td/button[@title="Выбрать"]', 3);
        $I->fillField('Nakladmaterials[nakladmaterials_number]', '2.000');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);
        $I->see('Материальная ценность не соответствует МОЛ требования-накладной: ПЕТРОВ П. П., ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1');
        $I->see('Максимально допустимое количество у этого МОЛ равно 1.000');

        $I->chooseValueFromGrid('Nakladmaterials[id_mattraffic]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, ПОЛИКЛИНИКА 1, Кухонный стол', 'mattrafficgrid_gw', '//td/a[text()="Кухонный стол" and contains(@href,"/Fregat/material/update?id=35")]/../preceding-sibling::td/button[@title="Выбрать"]', 3);
        $I->fillField('Nakladmaterials[nakladmaterials_number]', '1.000');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', 'шт', '796', '15000.00', '1.000', '15000'], 'nakladmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('nakladmaterialsgrid_gw', 1);
    }

    /**
     * @depends createNakladmaterials
     */
    public function updateNakladmaterials(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('nakladmaterialsgrid_gw', 'a[@title="Обновить"]', [['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', 'шт', '796', '15000.00', '1.000', '15000']);
        $I->wait(2);

        $I->fillField('Nakladmaterials[nakladmaterials_number]', '0.500');

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', 'шт', '796', '15000.00', '0.500', '7500'], 'nakladmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('nakladmaterialsgrid_gw', 1);
    }

    /**
     * @depends updateNakladmaterials
     */
    public function createNakladmaterialsSelect2(AcceptanceTester $I)
    {
        $I->click('//a[contains(text(),"Добавить материальную ценность")]');
        $I->wait(2);

        $I->chooseValueFromSelect2('Nakladmaterials[id_mattraffic]', '1000001, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, ПОЛИКЛИНИКА 1, Шкаф для одежды', '001');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->checkDynagridData([['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', 'шт', '796', '15000.00', '0.500', '7500'], 'nakladmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData([['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', 'шт', '796', '1200.15', '1.000', '1200.15'], 'nakladmaterialsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('nakladmaterialsgrid_gw', 2);

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП'], 'nakladgrid_gw', ['button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('nakladgrid_gw', 1);
    }


    /**
     * @depends createNakladmaterialsSelect2
     */
    public function updateNaklad(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('nakladgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП']);
        $I->chooseValueFromSelect2('Naklad[id_mol_got]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП'], 'nakladgrid_gw', ['button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('nakladgrid_gw', 1);
    }

    /**
     * @depends updateNaklad
     */
    public function checkExcelExportNaklad(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('nakladgrid_gw', 'button[@title="Скачать отчет"]', ['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП']);
        $I->wait(6);

        $I->seeFileFound($I->convertOSFileName('Требование-накладная №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Требование-накладная №1.xlsx'), [
            ['BA', 4, '1'],

            ['AK', 7, date('d')],
            ['AO', 7, Yii::$app->formatter->asDate(date('M'), 'php:F')],
            ['BB', 7, date('y')],

            ['CL', 7, date('d.m.Y')],

            ['O', 8, 'БУ "Нижневартовская городская поликлиника"'],
            ['O', 10, 'АУП'],
            ['O', 12, 'ТЕРАПЕВТИЧЕСКОЕ'],

            ['H', 15, 'ТЕРАПЕВТ'],
            ['W', 15, 'ИВАНОВ И. И.'],
            ['BA', 15, 'ГЛАВНЫЙ ВРАЧ'],
            ['CC', 15, 'БЛЮСОВА М. Е.'],

            ['A', 23, 'Кухонный стол'],
            ['R', 23, '1000002'],
            ['AD', 23, 'шт'],
            ['AJ', 23, '796'],
            ['AP', 23, '15000'],
            ['AY', 23, '0.5'],
            ['BE', 23, '0.5'],
            ['BK', 23, '7500'],

            ['A', 24, 'Шкаф для одежды'],
            ['R', 24, '1000001'],
            ['AD', 24, 'шт'],
            ['AJ', 24, '796'],
            ['AP', 24, '1200.15'],
            ['AY', 24, '1'],
            ['BE', 24, '1'],
            ['BK', 24, '1200.15'],

            ['BK', 25, '=SUM(BK23:BK24)'],

            ['A', 31, 'ПРОГРАММИСТ'],
            ['S', 31, 'ПЕТРОВ П. П.'],
            ['AF', 31, 'ГЛАВНЫЙ ВРАЧ'],
            ['AX', 31, 'БЛЮСОВА М. Е.'],

            ['B', 34, date('d')],
            ['E', 34, Yii::$app->formatter->asDate(date('M'), 'php:F')],
            ['Q', 34, date('Y')],

            ['AG', 34, date('d')],
            ['AJ', 34, Yii::$app->formatter->asDate(date('M'), 'php:F')],
            ['AV', 34, date('Y')],

            ['G', 36, 'ТЕРАПЕВТ'],
            ['AI', 36, 'ИВАНОВ И. И.'],

            ['B', 38, date('d')],
            ['E', 38, Yii::$app->formatter->asDate(date('M'), 'php:F')],
            ['Q', 38, date('Y')],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Требование-накладная №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Требование-накладная №1.xlsx'));
    }

    /**
     * @depends checkExcelExportNaklad
     */
    public function deleteNakladmaterials(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('nakladgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП']);

        $I->clickButtonDynagrid('nakladmaterialsgrid_gw', 'button[@title="Удалить"]', [['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', 'шт', '796', '15000.00', '0.500', '7500']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->countRowsDynagridEquals('nakladmaterialsgrid_gw', 1);

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);
    }

    /**
     * @depends checkExcelExportNaklad
     */
    public function deleteNaklad(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('nakladgrid_gw', 'button[@title="Удалить"]', ['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->countRowsDynagridEquals('nakladgrid_gw', 0);
    }

    /**
     * @depends deleteNaklad
     */
    public function destroyData(AcceptanceTester $I)
    {
        Nakladmaterials::deleteAll();
        Naklad::deleteAll();
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
