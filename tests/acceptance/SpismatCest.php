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
use app\models\Fregat\Spismat;
use app\models\Fregat\Spismatmaterials;
use app\models\Fregat\Spisosnovakt;
use app\models\Fregat\Spisosnovmaterials;
use app\models\Fregat\TrMat;
use app\models\Fregat\TrOsnov;
use yii\helpers\Url;

/**
 * @group SpismatCest
 */
class SpismatCest
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
        $I->see('Журнал списания основных средств');
    }

    /**
     * @depends openFregat
     */
    public function openSpismat(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Журнал списания материалов")]');
        $I->wait(2);
        $I->seeElement(['id' => 'spismatgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openSpismat
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('spismat.sql');
    }

    /**
     * @depends loadData
     */
    public function openCreateSpismat(AcceptanceTester $I)
    {
        $I->seeLink('Добавить вручную');
        $I->click(['link' => 'Добавить вручную']);
        $I->wait(2);
        $I->seeElement(['class' => 'spismat-form']);
        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Материально-ответственное лицо».');

        $I->chooseValueFromSelect2('Spismat[id_mol]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');

        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(2);

        $I->seeInField('Spismat[spismat_id]', '1');
        $I->seeElement('//input[@name="Spismat[spismat_id]" and @disabled]');

        $I->seeInDatecontrol('Spismat[spismat_date]', date('d.m.Y'));
        $I->seeInSelect2('Spismat[id_mol]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', true);

        $I->seeElement(['id' => 'spismatmaterialsgrid_gw']);
        $I->countRowsDynagridEquals('spismatmaterialsgrid_gw', 0);
    }

    /**
     * @depends openCreateSpismat
     */
    public function createSpismatmaterials(AcceptanceTester $I)
    {
        $I->click('//a[contains(text(),"Добавить материал")]');
        $I->wait(2);

        $I->seeElement(['id' => 'mattrafficgrid_gw']);
        $I->countRowsDynagridEquals('mattrafficgrid_gw', 5);
        $I->clickChooseButtonFromGrid([['link' => ['text' => 'Картридж 36A', 'href' => '/Fregat/material/update?id=38']], '1000005', '2.000', ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', ['link' => ['text' => '5', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'mattrafficgrid_gw');
        $I->wait(2);

        $I->seeElement(['id' => 'spismatmaterialsgrid_gw']);
        $I->checkDynagridData([['link' => ['text' => 'Картридж 36A', 'href' => '/Fregat/material/update?id=38']], '1000005', '2.000', ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', ['link' => ['text' => '5', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'spismatmaterialsgrid_gw', ['button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spismatmaterialsgrid_gw', 1);
    }

    /**
     * @depends createSpismatmaterials
     */
    public function deleteSpismatmaterials(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spismatmaterialsgrid_gw', 'button[@title="Удалить"]', [['link' => ['text' => 'Картридж 36A', 'href' => '/Fregat/material/update?id=38']], '1000005', '2.000', ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', ['link' => ['text' => '5', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        $I->countRowsDynagridEquals('spismatmaterialsgrid_gw', 0);

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);
    }

    /**
     * @depends deleteSpismatmaterials
     */
    public function updateSpismat(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spismatgrid_gw', 'a[@title="Обновить"]', ['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП']);
        $I->wait(2);

        $I->fillDatecontrol('Spismat[spismat_date]', '31.01.2017');

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['1', '31.01.2017', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП'], 'spismatgrid_gw', ['button[@title="Скачать ведомость"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spismatgrid_gw', 1);
    }

    /**
     * @depends updateSpismat
     */
    public function openCreateSpismatByInstallakt(AcceptanceTester $I)
    {
        $I->seeLink('Добавить по актам перемещения');
        $I->click(['link' => 'Добавить по актам перемещения']);
        $I->wait(2);
        $I->seeElement(['class' => 'spismat-form']);
        $I->seeElement('//button[@id="spismat_create" and contains(@class,"disabled") and @disabled]');

        $I->chooseValueFromSelect2('Spismat[id_mol]', 'ИВАНОВ ИВАН ИВАНОВИЧ, ТЕРАПЕВТ, ТЕРАПЕВТИЧЕСКОЕ, ПОЛИКЛИНИКА 1', 'ива');

        $I->fillDatecontrol('Spismat[period_beg]', '15.04.2017');
        $I->fillDatecontrol('Spismat[period_end]', '14.04.2017');
        $I->wait(1);

        $I->see('Дата начала периода не может быть больше даты окончания');

        $I->fillDatecontrol('Spismat[period_beg]', date('d.m.Y'));
        $I->fillDatecontrol('Spismat[period_end]', date('d.m.Y'));

        $I->wait(2);

        $I->seeElement('//div[@id="spismat_alert" and text()="0"]');
        $I->seeElement('//button[@id="spismat_create" and contains(@class,"disabled") and @disabled]');

        $I->chooseValueFromSelect2('Spismat[id_mol]', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ, ПРОГРАММИСТ, АУП, ПОЛИКЛИНИКА 1', 'пет');

        $I->wait(2);
        $I->seeElement('//div[@id="spismat_alert" and text()="5"]');
        $I->dontSeeElement('//button[@id="spismat_create" and contains(@class,"disabled") and @disabled]');

        $I->click('//button[contains(text(),"Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'spismatmaterialsgrid_gw']);
        $I->checkDynagridData([['link' => ['text' => 'Картридж 36A', 'href' => '/Fregat/material/update?id=38']], '1000005', '2.000', ['link' => ['text' => 'Кухонный стол', 'href' => '/Fregat/material/update?id=35']], '1000002', ['link' => ['text' => '5', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'spismatmaterialsgrid_gw', ['button[@title="Удалить"]']);
        $I->checkDynagridData([['link' => ['text' => 'Картридж А12', 'href' => '/Fregat/material/update?id=38']], '1000004', '1.000', ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', ['link' => ['text' => '4', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'СИДОРОВ ЕВГЕНИЙ АНАТОЛЬЕВИЧ', 'НЕВРОЛОГ'], 'spismatmaterialsgrid_gw', ['button[@title="Удалить"]']);
        $I->checkDynagridData([['link' => ['text' => 'Картридж А12', 'href' => '/Fregat/material/update?id=37']], '1000004', '2.000', ['link' => ['text' => 'Шкаф для медикаментов', 'href' => '/Fregat/material/update?id=36']], '1000003', ['link' => ['text' => '3', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ФЕДОТОВ ФЕДОР ФЕДОРОВИЧ', 'ТЕРАПЕВТ'], 'spismatmaterialsgrid_gw', ['button[@title="Удалить"]']);
        $I->checkDynagridData([['link' => ['text' => 'Лопата', 'href' => '/Fregat/material/update?id=37']], '1000006', '1.000', ['link' => ['text' => 'Шкаф для одежды', 'href' => '/Fregat/material/update?id=34']], '1000001', ['link' => ['text' => '2', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'spismatmaterialsgrid_gw', ['button[@title="Удалить"]']);
        $I->checkDynagridData([['link' => ['text' => 'Лопата', 'href' => '/Fregat/material/update?id=39']], '1000006', '1.000', ['link' => ['text' => 'Шкаф для медикаментов', 'href' => '/Fregat/material/update?id=36']], '1000003', ['link' => ['text' => '2', 'href' => '/Fregat/installakt/update?id=5']], date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ'], 'spismatmaterialsgrid_gw', ['button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spismatmaterialsgrid_gw', 5);
    }

    /**
     * @depends openCreateSpismatByInstallakt
     */
    public function checkZipInstallakts(AcceptanceTester $I)
    {
        $I->click('//button[contains(text(),"Скачать акты установки")]');
        $I->wait(4);

        $I->seeFileFound($I->convertOSFileName('Акты установки для ведомости №2.zip'), 'web/files');
        $I->checkZipFile($I->convertOSFileName('Акты установки для ведомости №2.zip'), [
            'Акт перемещения матер-ых цен-тей №2.xlsx',
            'Акт перемещения матер-ых цен-тей №3.xlsx',
            'Акт перемещения матер-ых цен-тей №4.xlsx',
            'Акт перемещения матер-ых цен-тей №5.xlsx',
        ]);
    }

    public function deleteZipFile(AcceptanceTester $I)
    {
        if (file_exists($I->convertOSFileName('web/files/' . 'Акты установки для ведомости №2.zip')))
            $I->deleteFile($I->convertOSFileName('web/files/' . 'Акты установки для ведомости №2.zip'));
    }

    /**
     * @depends updateSpisosnovakt
     */
    public function checkExcelExportSpismat(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spisosnovaktgrid_gw', 'button[@title="Скачать ведомость"]', ['1', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП', '', '', '101.34', 'НОВЫЙ СЧЕТ']);
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
     * @depends checkExcelExportSpismat
     */
    public function deleteSpismat(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('spismatgrid_gw', 'button[@title="Удалить"]', ['2', date('d.m.Y'), 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->checkDynagridData(['1', '31.01.2017', 'ПЕТРОВ ПЕТР ПЕТРОВИЧ', 'ПРОГРАММИСТ', 'АУП'], 'spismatgrid_gw', ['button[@title="Скачать ведомость"]', 'a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('spismatgrid_gw', 1);
    }

    /**
     * @depends loadData
     */
    public function destroyData()
    {
        /*    Spismatmaterials::deleteAll();
            Spismat::deleteAll();
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
            Podraz::deleteAll();*/
    }
}
