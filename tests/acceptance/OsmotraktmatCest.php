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
use app\models\Fregat\Osmotraktmat;
use app\models\Fregat\Podraz;
use app\models\Fregat\Reason;
use app\models\Fregat\Removeakt;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrMatOsmotr;
use app\models\Fregat\TrOsnov;
use app\models\Fregat\TrRmMat;
use yii\helpers\Url;

/**
 * @group OsmotraktmatCest
 */
class OsmotraktmatCest
{
    /**
     * @depends LoginCest:login
     */
    public function openFregat(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/Fregat/fregat/mainmenu'));
        $I->wait(2);
        $I->see('Журнал осмотров материалов');
    }

    /**
     * @depends openFregat
     */
    public function openOsmotraktmat(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал осмотров материалов")]');
        $I->wait(2);
        $I->seeElement(['id' => 'osmotraktmatgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openOsmotraktmat
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('osmotraktmat.sql');
    }

    /**
     * @depends loadData
     */
    public function openCreateOsmotraktmat(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'osmotraktmat-form']);
    }

    /**
     * @depends openCreateOsmotraktmat
     */
    public function saveOsmotraktmat(AcceptanceTester $I)
    {
        $I->chooseValueFromGrid('Osmotraktmat[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'employeegrid_gw', '//div[@id="employeegrid_gw"]/descendant::td[text()="ПЕТРОВ ПЕТР ПЕТРОВИЧ"]/preceding-sibling::td/button[@title="Выбрать"]', 4);

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'tr-mat-osmotrgrid_gw']);

        $I->click('//a[contains(text(), "Добавить материал")]');
        $I->wait(2);
        $I->seeElement(['class' => 'tr-mat-osmotr-form']);

        $I->chooseValueFromGrid('TrMatOsmotr[id_tr_mat]', 'Картиридж Kard, СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, 1.000', 'trmatgrid_gw', '//div[@id="trmatgrid_gw"]/descendant::td/a[text()="Картиридж Kard" and @href="/Fregat/material/update?id=37"]/../preceding-sibling::td/button[@title="Выбрать"]', 1);
        $I->chooseValueFromSelect2('TrMatOsmotr[id_reason]', 'ТРЕБУЕТСЯ ЗАПРАВКА', 'зап');
        $I->fillField('TrMatOsmotr[tr_mat_osmotr_comment]', 'С заменой чипа');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['КАРТРИДЖ', ['link' => ['text' => 'Картиридж Kard', 'href' => '/Fregat/material/update?id=37']], '000004', '1.000', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ', ['link' => ['text' => 'HP LJ 1022', 'href' => '/Fregat/material/update?id=34']], '00001', 'ТРЕБУЕТСЯ ЗАПРАВКА', 'С заменой чипа'], 'tr-mat-osmotrgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', '1'], 'osmotraktmatgrid_gw', ['button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktmatgrid_gw', 1);
    }

    /**
     * @depends saveOsmotraktmat
     */
    public function updateOsmotraktmat(AcceptanceTester $I)
    {
        $I->click('//td[text()="ПЕТРОВ ПЕТР ПЕТРОВИЧ"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);

        $I->chooseValueFromSelect2('Osmotraktmat[id_master]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', date('d.m.Y'), 'ИВАНОВ ИВАН ИВАНОВИЧ', '1'], 'osmotraktmatgrid_gw', ['button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktmatgrid_gw', 1);
    }

    /**
     * @depends updateOsmotraktmat
     */
    public function checkExcelExportOsmotraktmat(AcceptanceTester $I)
    {
        $I->click('//div[@id="osmotraktmatgrid_gw"]/descendant::td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]/preceding-sibling::td[text()="' . date('d.m.Y') . '"]/preceding-sibling::td[text()="1"]/preceding-sibling::td/button[@title="Скачать отчет"]');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт осмотра материалов №1.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт осмотра материалов №1.xlsx'), [
            ['A', 3, 'материалов № 1 от ' . date('d.m.Y')],

            ['A', 5, '№'],
            ['B', 5, 'Вид'],
            ['C', 5, 'Наименование'],
            ['D', 5, 'Инвентарный номер'],
            ['E', 5, 'Материальная ценность в которую укомплектован материал'],
            ['F', 5, "Кол-\nво\n"],
            ['G', 5, 'Единица измерения'],
            ['H', 5, 'Причина выхода из строя'],
            ['I', 5, 'Материально-ответственное лицо'],
            ['J', 5, 'Здание, Кабинет'],

            ['A', 7, '1'],
            ['B', 7, 'КАРТРИДЖ'],
            ['C', 7, 'Картиридж Kard'],
            ['D', 7, '000004'],
            ['E', 7, 'Инв. номер: 00001, HP LJ 1022'],
            ['F', 7, '1'],
            ['G', 7, 'шт'],
            ['H', 7, 'ТРЕБУЕТСЯ ЗАПРАВКА. С заменой чипа'],
            ['I', 7, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ'],
            ['J', 7, 'ПОЛИКЛИНИКА 1, 101'],

            ['A', 9, 'Материально ответственное лицо'],
            ['D', 9, 'НЕВРОЛОГ'],
            ['H', 9, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ'],

            ['A', 10, 'Мастер'],
            ['D', 10, 'ТЕРАПЕВТ'],
            ['H', 10, 'ИВАНОВ ИВАН ИВАНОВИЧ'],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Акт осмотра материалов №1.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт осмотра материалов №1.xlsx'));
    }

    /**
     * @depends checkExcelExportOsmotraktmat
     */
    public function deleteOsmotraktmat(AcceptanceTester $I)
    {
        $I->click('//div[@id="osmotraktmatgrid_gw"]/div/div/table/tbody/tr/td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]/preceding-sibling::td/button[@title="Удалить"]');
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
        TrMatOsmotr::deleteAll();
        Osmotraktmat::deleteAll();
        TrRmMat::deleteAll();
        Removeakt::deleteAll();
        TrMat::deleteAll();
        TrOsnov::deleteAll();
        Reason::deleteAll();
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
