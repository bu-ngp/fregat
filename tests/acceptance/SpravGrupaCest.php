<?php


use app\models\Fregat\Grupa;
use app\models\Fregat\Grupavid;
use app\models\Fregat\Matvid;
use yii\helpers\Url;

/**
 * @group SpravGrupaCest
 */
class SpravGrupaCest
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
        $I->see('Справочники');
    }

    /**
     * @depends openFregat
     */
    public function openSprav(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Справочники")]');
        $I->wait(2);
        $I->see('Группы материальных ценностей');
    }

    /**
     * @depends openSprav
     */
    public function openGrupa(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Группы материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'grupagrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openGrupa
     */
    public function openCreateGrupa(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'grupa-form']);
    }

    /**
     * @depends openCreateGrupa
     */
    public function saveCreateGrupa(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Группа материальной ценности».');
        $I->fillField('Grupa[grupa_name]', 'Мебель');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement(['id' => 'grupavidgrid_gw']);
        $I->seeInField('Grupa[grupa_name]', 'МЕБЕЛЬ');
        $I->seeElement('//button[contains(text(), "Обновить")]');
    }

    /**
     * @depends saveCreateGrupa
     */
    public function loadData(AcceptanceTester $I)
    {
        $matvid = new Matvid;
        $matvid->matvid_name = 'Шкаф';
        $matvid->save();
        $matvid = new Matvid;
        $matvid->matvid_name = 'Стол';
        $matvid->save();
    }

    /**
     * @depends loadData
     */
    public function openCreateGrupavidOne(AcceptanceTester $I)
    {
        $I->seeLink('Добавить вид материальной ценности');
        $I->click(['link' => 'Добавить вид материальной ценности']);
        $I->wait(2);
        $I->checkDynagridData(['ШКАФ'], 'matvidgrid_gw');
        $I->checkDynagridData(['СТОЛ'], 'matvidgrid_gw');

        $I->click('//td[text()="СТОЛ"]/preceding-sibling::td/button[@title="Выбрать"]');
        $I->wait(2);
        $I->see('Да', '//td[text()="СТОЛ"]/following-sibling::td');
    }

    /**
     * @depends openCreateGrupavidOne
     */
    public function openCreateGrupavidTwo(AcceptanceTester $I)
    {
        $I->seeLink('Добавить вид материальной ценности');
        $I->click(['link' => 'Добавить вид материальной ценности']);
        $I->wait(2);
        $I->checkDynagridData(['ШКАФ'], 'matvidgrid_gw');
        $I->checkDynagridData(['СТОЛ'], 'matvidgrid_gw');

        $I->click('//td[text()="ШКАФ"]/preceding-sibling::td/button[@title="Выбрать"]');
        $I->wait(2);
        $I->see('Да', '//td[text()="СТОЛ"]/following-sibling::td');
        $I->see('Нет', '//td[text()="ШКАФ"]/following-sibling::td');
    }

    /**
     * @depends openCreateGrupavidTwo
     */
    public function changeMainGrupavid(AcceptanceTester $I)
    {
        $I->click('//td[text()="ШКАФ"]/preceding-sibling::td/button[@title="Сделать главной"]');
        $I->wait(2);
        $I->see('Сделать вид материальной ценности основным?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->see('Нет', '//td[text()="СТОЛ"]/following-sibling::td');
        $I->see('Да', '//td[text()="ШКАФ"]/following-sibling::td');
    }

    /**
     * @depends changeMainGrupavid
     */
    public function deleteMainGrupavid(AcceptanceTester $I)
    {
        $I->click('//td[text()="ШКАФ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->see('Ошибка удаления. Данный вид материальной ценности является основным в группе.');
        $I->click('button[data-bb-handler="ok"]');
        $I->wait(2);
        $I->see('Нет', '//td[text()="СТОЛ"]/following-sibling::td');
        $I->see('Да', '//td[text()="ШКАФ"]/following-sibling::td');
    }

    /**
     * @depends deleteMainGrupavid
     */
    public function deleteNotMainGrupavid(AcceptanceTester $I)
    {
        $I->click('//td[text()="СТОЛ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('СТОЛ');
        $I->see('Да', '//td[text()="ШКАФ"]/following-sibling::td');
    }

    /**
     * @depends deleteNotMainGrupavid
     */
    public function deleteLastSingleGrupavid(AcceptanceTester $I)
    {
        $I->click('//td[text()="ШКАФ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('СТОЛ');
        $I->dontSee('ШКАФ');
        $I->see('Ничего не найдено');
    }

    /**
     * @depends deleteLastSingleGrupavid
     */
    public function fillOneGrupavid(AcceptanceTester $I)
    {
        $I->seeLink('Добавить вид материальной ценности');
        $I->click(['link' => 'Добавить вид материальной ценности']);
        $I->wait(2);
        $I->checkDynagridData(['ШКАФ'], 'matvidgrid_gw');
        $I->checkDynagridData(['СТОЛ'], 'matvidgrid_gw');

        $I->click('//td[text()="СТОЛ"]/preceding-sibling::td/button[@title="Выбрать"]');
        $I->wait(2);
        $I->see('Да', '//td[text()="СТОЛ"]/following-sibling::td');
    }

    /**
     * @depends fillOneGrupavid
     */
    public function updateButtonGrupa(AcceptanceTester $I)
    {
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->see('МЕБЕЛЬ');
    }

    /**
     * @depends updateButtonGrupa
     */
    public function openUpdateGrupa(AcceptanceTester $I)
    {
        $I->click('//td[text()="МЕБЕЛЬ"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->see('СТОЛ');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);
        $I->see('МЕБЕЛЬ');
    }

    /**
     * @depends openUpdateGrupa
     */
    public function deleteGrupa(AcceptanceTester $I)
    {
        $I->click('//td[text()="МЕБЕЛЬ"]/preceding-sibling::td/button[@title="Удалить"]');
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
        Grupavid::deleteAll();
        Grupa::deleteAll();
        Matvid::deleteAll();
    }
}
