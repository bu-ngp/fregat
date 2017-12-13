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
use app\models\Fregat\Organ;
use app\models\Fregat\Osmotrakt;
use app\models\Fregat\Podraz;
use app\models\Fregat\Reason;
use app\models\Fregat\Schetuchet;
use app\models\Fregat\TrOsnov;
use yii\helpers\Url;

/**
 * @group OsmotraktCest
 */
class OsmotraktCest
{
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
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 0);
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
        $I->chooseValueFromGrid('Osmotrakt[id_tr_osnov]', '1000002, каб. 101, ПОЛИКЛИНИКА 1, Кухонный стол', 'tr-osnovgrid_gw', '//div[@id="tr-osnovgrid_gw"]/descendant::td/a[text()="Кухонный стол" and @href="/Fregat/material/update?id=35"]/../preceding-sibling::td/button[@title="Выбрать"]', 1);
        $I->seeInField('Material[material_name]', 'Кухонный стол');
        $I->seeInField('Material[material_inv]', '1000002');
        $I->seeInField('Material[material_serial]', '');
        $I->seeInField('Build[build_name]', 'ПОЛИКЛИНИКА 1');
        $I->seeInField('Cabinet[cabinet_name]', '101');
        $I->seeInField('Authuser[auth_user_fullname]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ПРОГРАММИСТ');

        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', 'сид');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА НОЖКА', 'нож');
        $I->fillField('Osmotrakt[osmotrakt_comment]', 'Образовалась трещина');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
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

        $I->chooseValueFromGrid('InstallTrOsnov[id_mattraffic]', '1000003, ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, Шкаф для медикаментов', 'mattrafficgrid_gw', '//div[@id="mattrafficgrid_gw"]/descendant::td/a[text()="Шкаф для медикаментов" and @href="/Fregat/material/update?id=36"]/../preceding-sibling::td/button[@title="Выбрать"]', 3);

        $I->seeInField('Material[material_name]', 'Шкаф для медикаментов');
        $I->seeInField('Material[material_writeoff]', 'Нет');
        $I->seeInField('Authuser[auth_user_fullname]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ТЕРАПЕВТ');
        $I->seeInField('Build[build_name]', '');
        $I->seeInField('InstallTrOsnov[mattraffic_number]', '1.000');

        $I->seeInSelect2('InstallTrOsnov[id_cabinet]', '', true);
        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ', 'фед');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА ПОЛКА', 'пол');

//        $I->see('Создать');
//        $I->click('//button[contains(text(), "Создать")]');
//        $I->wait(2);
//
//        $I->see('У материально ответственного лица "ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ" не заполнено "Здание", в которое устанавливается материальная ценность');

        $I->executeJS('window.scrollTo(0,0);');
        $I->chooseValueFromSelect2('InstallTrOsnov[id_mattraffic]', '1000001, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1, Шкаф для одежды', '001');
        $I->chooseValueFromSelect2('InstallTrOsnov[id_cabinet]', 'ПОЛИКЛИНИКА 1, каб. 102', '102');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
    }

    /**
     * @depends saveOsmotraktWithInstallakt
     */
    public function saveOsmotraktWithInstallaktAndChangeMOL(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('osmotraktgrid_gw', 'button[@title="Удалить"]', ['1', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ']);

        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'osmotrakt-form']);

        $I->click('//a[@data-toggle="collapse"]');
        $I->wait(2);

        $I->chooseValueFromGrid('InstallTrOsnov[id_mattraffic]', '1000002, ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1, Кухонный стол', 'mattrafficgrid_gw', '//div[@id="mattrafficgrid_gw"]/descendant::td/a[text()="Кухонный стол" and @href="/Fregat/material/update?id=35"]/../preceding-sibling::td/button[@title="Выбрать"]', 3);
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

        $I->click('//a[contains(text(), "Добавить")]');
        $I->wait(2);

        $I->click('//td[contains(text(), "ИВАНОВ ИВАН ИВАНОВИЧ")]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);

        $I->checkDynagridData(['1175', 'ТЕРАПЕВТ', 'ТЕРАПЕВТИЧЕСКОЕ', 'ПОЛИКЛИНИКА 1'], 'employeeauthusergrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
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

        $I->executeJS('window.scrollTo(0,200);');
        $I->wait(1);
        $I->chooseValueFromSelect2('InstallTrOsnov[id_mattraffic]', '1000002, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2, Кухонный стол', '002');
        $I->wait(2);
        $I->seeInField('Authuser[auth_user_fullname]', 'ИВАНОВ ИВАН ИВАНОВИЧ');
        $I->seeInField('Dolzh[dolzh_name]', 'ТЕРАПЕВТ');
        $I->seeInField('Build[build_name]', 'ПОЛИКЛИНИКА 2');

        $I->chooseValueFromSelect2('InstallTrOsnov[id_cabinet]', 'ПОЛИКЛИНИКА 2, каб. 103', '103');
        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2', 'сид');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА НОЖКА', 'нож');

        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        //  $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['3', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 2);
    }

    /**
     * @depends saveOsmotraktWithInstallaktAndChangeMOL
     */
    public function updateOsmotrakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('osmotraktgrid_gw', 'a[@title="Обновить"]', ['3', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ']);
        $I->wait(2);

        $I->fillField('Osmotrakt[osmotrakt_comment]', 'Неисправна');

        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['3', date('d.m.Y'), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 2);
    }

    /**
     * @depends updateOsmotrakt
     */
    public function checkExcelExportOsmotrakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('osmotraktgrid_gw', 'button[@title="Скачать отчет"]', ['3', date('d.m.Y'), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ']);
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акт осмотра №3.xlsx'), 'web/files');
        $I->checkExcelFile($I->convertOSFileName('Акт осмотра №3.xlsx'), [
            ['A', 3, 'вышедшей из строя № 3 от ' . date('d.m.Y')],

            ['C', 5, 'СТОЛ'],
            ['C', 6, 'Кухонный стол'],
            ['C', 7, '1000002'],
            ['C', 8, ''],
            ['C', 9, 'ПОЛИКЛИНИКА 2, 103'],
            ['C', 10, 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ, НЕВРОЛОГ'],
            ['C', 11, 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ'],
            ['C', 12, 'СЛОМАНА НОЖКА. Неисправна'],

            ['C', 14, 'ПРОГРАММИСТ'],
            ['D', 14, 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'],
        ]);
    }

    public function deleteExcelFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Акт осмотра №3.xlsx')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акт осмотра №3.xlsx'));
    }

    /**
     * @depends updateOsmotrakt
     */
   /* public function checkInstallUniqueCabinet(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'osmotrakt-form']);

        $I->click('//a[@data-toggle="collapse"]');
        $I->wait(2);

        $I->chooseValueFromSelect2('InstallTrOsnov[id_mattraffic]', '1000003, ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 2, Шкаф для медикаментов', '003');

        $I->chooseValueFromSelect2('InstallTrOsnov[id_cabinet]', 'ПОЛИКЛИНИКА 2, каб. 103', '103');
        $I->chooseValueFromSelect2('Osmotrakt[id_user]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');
        $I->chooseValueFromSelect2('Osmotrakt[id_master]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');
        $I->chooseValueFromSelect2('Osmotrakt[id_reason]', 'СЛОМАНА НОЖКА', 'нож');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->see('Данная материальная ценность "Кухонный стол" уже установлена в кабинет "103" в акте установки №3 от ' . date('d.m.Y') . '.');

        $I->chooseValueFromSelect2('InstallTrOsnov[id_cabinet]', 'ПОЛИКЛИНИКА 2, каб. 102', '102');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['2', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['3', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['4', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '102', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 4);
    }*/

    /**
     * @depends updateOsmotrakt
     */
    public function deleteOsmotrakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('osmotraktgrid_gw', 'button[@title="Удалить"]', ['2', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', '102', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА ПОЛКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ']);

        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

      //  $I->checkDynagridData(['1', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '101', 'ПОЛИКЛИНИКА 1', 'СЛОМАНА НОЖКА', 'Образовалась трещина', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->checkDynagridData(['3', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
  //      $I->checkDynagridData(['4', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '102', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', '', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ'], 'osmotraktgrid_gw', ['a[@title="Отправить акт в организацию по электронной почте"]', 'button[@title="Скачать отчет"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('osmotraktgrid_gw', 1);
    }

    /**
     * @depends deleteOsmotrakt
     */
    public function sendEmailOsmotrakt(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('osmotraktgrid_gw', 'a[@title="Отправить акт в организацию по электронной почте"]', ['3', date('d.m.Y'), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ']);

        $I->wait(2);
        $I->click('//button[@id="SendOsmotraktDialog_apply"]');
        $I->seeElement('//div[@class="errordialog" and text()="Не выбрана организация"]');
        $I->wait(1);
        $I->click('//button[@id="SendOsmotraktDialog_close"]');
        $I->wait(1);

        $I->clickButtonDynagrid('osmotraktgrid_gw', 'a[@title="Отправить акт в организацию по электронной почте"]', ['3', Yii::$app->formatter->asDate(date('d.m.Y')), ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', '103', 'ПОЛИКЛИНИКА 2', 'СЛОМАНА НОЖКА', 'Неисправна', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ']);
        $I->wait(2);
        $I->chooseValueFromSelect2('Organ[organ_id]', 'ФИРМА', 'фир');

        $I->click('//button[@id="SendOsmotraktDialog_apply"]');
        $I->wait(1);
        $I->seeElement('//div[@class="errordialog" and text()="Не заполнен Email у организации"]');

        $I->chooseValueFromSelect2('Organ[organ_id]', 'РОГА И КОПЫТА', 'рог');
        $I->click('//button[@id="SendOsmotraktDialog_apply"]');
        $I->wait(3);

        $I->dontSeeElement(['class' => 'osmotraktsendfilter-form']);
    }

    /**
     * @depends loadData
     */
    public function destroyData()
    {
        Organ::deleteAll();
        Osmotrakt::deleteAll();
        Reason::deleteAll();
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
