<?php
use yii\helpers\Url;

/**
 * @group SpravOrganCest
 */
class SpravOrganCest
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
        $I->see('Организации');
    }

    /**
     * @depends openSprav
     */
    public function openOrgan(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Организации")]');
        $I->wait(2);
        $I->seeElement(['id' => 'organgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openOrgan
     */
    public function openCreateOrgan(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'organ-form']);
    }

    /**
     * @depends openCreateOrgan
     */
    public function saveCreateOrgan(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Организация».');
        $I->seeElement('//div[contains(@class,"field-organ-organ_email has-success")]');
        $I->seeElement('//div[contains(@class,"field-organ-organ_phones has-success")]');
        $I->fillField('Organ[organ_name]', 'Рога и копыта');
        $I->fillField('Organ[organ_email]', 'invalidemail.ru');
        $I->fillField('Organ[organ_phones]', '89224452356');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement('//div[contains(@class,"field-organ-organ_name required has-success")]');
        $I->see('Значение «Электронная почта организации» не является правильным email адресом.');
        $I->fillField('Organ[organ_email]', 'valid@email.ru');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['id' => 'organgrid_gw']);
        $I->see('Рога и копыта');
        $I->see('valid@email.ru');
        $I->see('89224452356');
    }

    /**
     * @depends saveCreateOrgan
     */
    public function checkUniqueOrgan(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'organ-form']);
        $I->fillField('Organ[organ_name]', 'Рога и копыта');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Организация = Рога и копыта уже существует');
        $I->click(['id' => 'backbutton']);
        $I->wait(2);
        $I->seeElement(['id' => 'organgrid_gw']);
    }

    /**
     * @depends checkUniqueOrgan
     */
    public function openUpdateOrgan(AcceptanceTester $I)
    {
        $I->click('//td[text()="РОГА И КОПЫТА"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->fillField('Organ[organ_name]', 'Рога и копыта2');
        $I->fillField('Organ[organ_email]', 'valid@email2.ru');
        $I->fillField('Organ[organ_phones]', '89224452357');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'organgrid_gw']);
        $I->see('Рога и копыта2');
        $I->see('valid@email2.ru');
        $I->see('89224452357');
    }

    /**
     * @depends openUpdateOrgan
     */
    public function deleteOrgan(AcceptanceTester $I)
    {
        $I->click('//td[text()="РОГА И КОПЫТА2"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('РОГА И КОПЫТА2');
        $I->see('Ничего не найдено');
    }
}
