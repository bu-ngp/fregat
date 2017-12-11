<?php

use yii\helpers\Url;

/**
 * @group SpravBuildCest
 */
class SpravBuildCest
{
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
        $I->see('Здания');
    }

    /**
     * @depends openSprav
     */
    public function openBuild(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Здания")]');
        $I->wait(2);
        $I->seeElement(['id' => 'buildgrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openBuild
     */
    public function openCreateBuild(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'build-form']);
    }

    /**
     * @depends openCreateBuild
     */
    public function saveCreateBuild(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Здание».');
        $I->fillField('Build[build_name]', 'Поликлиника 1');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeInField('Build[build_name]', 'ПОЛИКЛИНИКА 1');
        $I->seeElement(['id' => 'buildcabinetsgrid_gw']);
        $I->countRowsDynagridEquals('buildcabinetsgrid_gw', 0);
    }

    /**
     * @depends saveCreateBuild
     */
    public function createCabinet(AcceptanceTester $I)
    {
        $I->click('//a[contains(text(),"Добавить кабинет")]');
        $I->wait(2);

        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Кабинет».');

        $I->fillField('Cabinet[cabinet_name]', '101');
        $I->click('//button[contains(text(),"Добавить")]');
        $I->wait(2);

        $I->checkDynagridData(['101'], 'buildcabinetsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('buildcabinetsgrid_gw', 1);
    }

    /**
     * @depends createCabinet
     */
    public function updateCabinet(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('buildcabinetsgrid_gw', 'a[@title="Обновить"]', ['101']);
        $I->wait(2);

        $I->fillField('Cabinet[cabinet_name]', '102');

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['102'], 'buildcabinetsgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('nakladmaterialsgrid_gw', 1);

        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);
    }

    /**
     * @depends updateCabinet
     */
    public function checkUniqueBuild(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'build-form']);
        $I->fillField('Build[build_name]', 'Поликлиника 1');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Здание = Поликлиника 1 уже существует');
        $I->click(['id' => 'backbutton']);
        $I->wait(2);
        $I->seeElement(['id' => 'buildgrid_gw']);
    }

    /**
     * @depends checkUniqueBuild
     */
    public function openUpdateBuild(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('buildgrid_gw', 'a[@title="Обновить"]', ['ПОЛИКЛИНИКА 1']);

        $I->fillField('Build[build_name]', 'Взрослая поликлиника 1');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['ВЗРОСЛАЯ ПОЛИКЛИНИКА 1'], 'buildgrid_gw', ['a[@title="Обновить"]', 'button[@title="Удалить"]']);
        $I->countRowsDynagridEquals('buildgrid_gw', 1);
    }

    /**
     * @depends openUpdateBuild
     */
    public function deleteBuild(AcceptanceTester $I)
    {
        $I->clickButtonDynagrid('buildgrid_gw', 'button[@title="Удалить"]', ['ВЗРОСЛАЯ ПОЛИКЛИНИКА 1']);
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->countRowsDynagridEquals('buildgrid_gw', 0);
    }
}
