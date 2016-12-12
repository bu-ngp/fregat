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

    }

}
