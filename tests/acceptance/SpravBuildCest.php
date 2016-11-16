<?php
use yii\helpers\Url;

/**
 * @group SpravBuildCest
 */
class SpravBuildCest
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

        $I->seeElement(['id' => 'buildgrid_gw']);
        $I->see('ПОЛИКЛИНИКА 1');
    }

    /**
     * @depends saveCreateBuild
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
        $I->click('//td[text()="ПОЛИКЛИНИКА 1"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->fillField('Build[build_name]', 'Взрослая поликлиника 1');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->seeElement(['id' => 'buildgrid_gw']);
        $I->see('ВЗРОСЛАЯ ПОЛИКЛИНИКА 1');
    }

    /**
     * @depends openUpdateBuild
     */
    public function deleteBuild(AcceptanceTester $I)
    {
        $I->click('//td[text()="ВЗРОСЛАЯ ПОЛИКЛИНИКА 1"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSee('ВЗРОСЛАЯ ПОЛИКЛИНИКА 1');
        $I->see('Ничего не найдено');
    }
}
