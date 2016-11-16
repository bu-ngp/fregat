<?php
use yii\helpers\Url;

/**
 * @group SpravPodrazCest
 */
class SpravPodrazCest
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
        $I->see('Подразделения');
    }

    /**
     * @depends openSprav
     */
    public function openPodraz(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Подразделения")]');
        $I->wait(2);
        $I->seeElement(['id' => 'podrazgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openPodraz
     */
    public function openCreatePodraz(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'podraz-form']);
    }

    /**
     * @depends openCreatePodraz
     */
    public function saveCreatePodraz(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Подразделение».');
        $I->fillField('Podraz[podraz_name]', 'Стационар');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'podrazgrid_gw']);

        $I->seeElement('//td[text()="СТАЦИОНАР"]'); // $I->see('СТАЦИОНАР'); НЕ РАБОТАЕТ
    }

    /**
     * @depends saveCreatePodraz
     */
    public function checkUniquePodraz(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'podraz-form']);
        $I->fillField('Podraz[podraz_name]', 'Стационар');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Подразделение = Стационар уже существует');
        $I->click(['id' => 'backbutton']);
        $I->wait(2);
        $I->seeElement(['id' => 'podrazgrid_gw']);
    }

    /**
     * @depends checkUniquePodraz
     */
    public function openUpdatePodraz(AcceptanceTester $I)
    {
        $I->click('//td[text()="СТАЦИОНАР"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->fillField('Podraz[podraz_name]', 'Дневной стационар');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'podrazgrid_gw']);
        $I->seeElement('//td[text()="ДНЕВНОЙ СТАЦИОНАР"]'); // $I->see('ДНЕВНОЙ СТАЦИОНАР'); НЕ РАБОТАЕТ
    }

    /**
     * @depends openUpdatePodraz
     */
    public function deletePodraz(AcceptanceTester $I)
    {
        $I->click('//td[text()="ДНЕВНОЙ СТАЦИОНАР"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('ДНЕВНОЙ СТАЦИОНАР');
        $I->see('Ничего не найдено');
    }
}
