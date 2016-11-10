<?php


use yii\helpers\Url;

class LoginCest
{
    public function getScriptName()
    {
        return basename($_SERVER["SCRIPT_FILENAME"]);
    }

    public function _before(AcceptanceTester $I)
    {
    }

    public function _after()
    {
    }

    public function login(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
        $I->see('Введите логин и пароль для входа в систему:');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin');
        $I->click('login-button');
        $I->wait(2); // wait for button to be clicked
        $I->see('Главное меню');
    }

/*   public function openFregat(AcceptanceTester $I)
    {
        $I->see('Система "Фрегат"');
        $I->click('//div[contains(text(), "Фрегат")]');
        $I->wait(2);
        $I->see('Журнал материальных ценностей');
        $I->see('Журнал перемещений материальных ценностей');
        $I->see('Журнал снятия комплектующих с материальных ценностей');
        $I->see('Журнал осмотров материальных ценностей');
        $I->see('Журнал осмотров материалов');
        $I->see('Журнал восстановления материальных ценностей');
        $I->see('Журнал списания основных средств');
        $I->see('Импорт данных');
        $I->see('Справочники');
    }

    public function openJurnalMatCen(AcceptanceTester $I)
    {
        $I->see('Журнал материальных ценностей');
        $I->click('//div[contains(text(), "Журнал материальных ценностей")]');
        $I->wait(2);
        $I->seeElement(['id' => 'materialgrid_gw']);
        $I->see('Ничего не найдено.');
    }

    public function openNewMatcen(AcceptanceTester $I)
    {
        $I->see('Составить акт прихода материальнной ценности');
        $I->click(['link' => 'Составить акт прихода материальнной ценности']);
        $I->wait(2);
        $I->seeElement(['id' => 'Materialform']);
    }

    public function openMatvid(AcceptanceTester $I)
    {
        $I->see('Вид');
        $I->seeElement('span', ['aria-labelledby' => 'select2-material-id_matvid-container']);
        $I->seeElement(['css' => 'div.input-group-btn>a[href="/' . $this->getScriptName() . '?r=Fregat%2Fmatvid%2Findex&foreignmodel=Material&url=Fregat%2Fmaterial%2Fcreate&field=id_matvid"]']);
        $I->click(['css' => 'div.input-group-btn>a[href="/' . $this->getScriptName() . '?r=Fregat%2Fmatvid%2Findex&foreignmodel=Material&url=Fregat%2Fmaterial%2Fcreate&field=id_matvid"]']);
        $I->wait(2);
        $I->seeElement(['id' => 'matvidgrid_gw']);
        $I->see('Ничего не найдено.');
    }

    public function openNewMatvid(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'matvid-form']);
    }

    public function addNewMatvid(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->waitForText('Необходимо заполнить «Вид материальной ценности».');
        $I->fillField('Matvid[matvid_name]', 'Монитор');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
        $I->seeElement(['id' => 'matvidgrid_gw']);
        $I->see('Монитор');
        $I->seeElement('button', ['title' => 'Удалить']);
        $I->seeElement('a', ['title' => 'Обновить']);
        $I->seeElement('button', ['title' => 'Выбрать']);
    }

    public function chooseMatvid(AcceptanceTester $I)
    {
        $I->click(['css' => 'button[title="Выбрать"]']);
        $I->wait(2);
        $I->selectOption('Material[id_matvid]', 'Монитор');
    }*/
}
