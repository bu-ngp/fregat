<?php
use app\models\Config\Authuser;
use app\models\Fregat\Build;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Employee;
use app\models\Fregat\Podraz;
use yii\helpers\Url;

/**
 * @group SpravEmployeeCest
 */
class SpravEmployeeCest
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
        $I->see('Сотрудники');
    }

    /**
     * @depends openSprav
     */
    public function openAuthuser(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Сотрудники")]');
        $I->wait(2);
        $I->seeElement(['id' => 'authusergrid_gw']);
        $I->see('Ничего не найдено');
    }

    /**
     * @depends openAuthuser
     */
    public function openCreateAuthuser(AcceptanceTester $I)
    {
        $I->seeLink('Добавить');
        $I->click(['link' => 'Добавить']);
        $I->wait(2);
        $I->seeElement(['class' => 'authuser-form']);
    }

    /**
     * @depends openCreateAuthuser
     */
    public function saveCreateAuthuser(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «ФИО сотрудника».');
        $I->see('Необходимо заполнить «Логин сотрудника».');
        $I->see('Необходимо заполнить «Пароль».');
        $I->see('Необходимо заполнить «Подтвердите новый пароль».');

        $I->fillField('Authuser[auth_user_fullname]', 'Иванов Иван Иванович');
        $I->fillField('Authuser[auth_user_login]', 'IvanovII');
        $I->fillField('Authuser[auth_user_password]', '123');
        $I->fillField('Authuser[auth_user_password2]', '321');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Подтверждение пароля не совпадает');
        $I->fillField('Authuser[auth_user_password]', '123');
        $I->fillField('Authuser[auth_user_password2]', '123');

        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->seeElement(['class' => 'authuser-form']);
        $I->seeInField('Authuser[auth_user_fullname]', 'ИВАНОВ ИВАН ИВАНОВИЧ');
        $I->seeInField('Authuser[auth_user_login]', 'IvanovII');
        $I->seeElement('//div[@id="employeeauthusergrid_gw"]/descendant::div[text()="Ничего не найдено."]');
    }

    /**
     * @depends saveCreateAuthuser
     */
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('employee.sql');
    }

    /**
     * @depends loadData
     */
    public function openCreateEmployee(AcceptanceTester $I)
    {
        $I->seeLink('Добавить специальность');
        $I->click(['link' => 'Добавить специальность']);
        $I->wait(2);
        $I->seeElement(['class' => 'employee-form']);
    }

    /**
     * @depends openCreateEmployee
     */
    public function checkFormCreateEmployee(AcceptanceTester $I)
    {
        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('Необходимо заполнить «Должность».');
        $I->see('Необходимо заполнить «Подразделение».');
        $I->seeElement('//div[contains(@class, "field-employee-id_build has-success")]');
        $I->seeElement('//div[contains(@class, "field-employee-employee_importdo has-success")]');

        $I->checkDatePicker('employee_dateinactive-employee-employee_dateinactive');
    }

    /**
     * @depends checkFormCreateEmployee
     */
    public function addCreateEmployeeFromSelect2(AcceptanceTester $I)
    {
        file_put_contents('web\test.txt', 'test = ' . print_r(Dolzh::find()->count(), true) . PHP_EOL, FILE_APPEND);
        $I->chooseValueFromSelect2('Employee[id_dolzh]', 'КАРДИОЛОГ', 'кар');
        $I->chooseValueFromSelect2('Employee[id_podraz]', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'общ');
        $I->chooseValueFromSelect2('Employee[id_build]', 'ПОЛИКЛИНИКА 1', 'пол');

        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);
    }

    /**
     * @depends addCreateEmployeeFromSelect2
     */
    public function checkAddedEmployeeFromSelect2(AcceptanceTester $I)
    {
        $I->seeElement(['id' => 'employeeauthusergrid_gw']);
        $I->seeElement('//td[text()="ПОЛИКЛИНИКА 1"]/preceding-sibling::td[text()="ОБЩЕПОЛИКЛИНИЧЕСКОЕ"]/preceding-sibling::td[text()="КАРДИОЛОГ"]/preceding-sibling::td/button[@title="Удалить"]/preceding-sibling::a[@title="Обновить"]');
    }

    /**
     * @depends checkAddedEmployeeFromSelect2
     */
    public function addCreateEmployeeFromGrids(AcceptanceTester $I)
    {
        $I->seeLink('Добавить специальность');
        $I->click(['link' => 'Добавить специальность']);
        $I->wait(2);
        $I->seeElement(['class' => 'employee-form']);

        $I->chooseValueFromGrid('Employee[id_dolzh]', 'КАРДИОЛОГ', 'dolzhgrid_gw');
        $I->chooseValueFromGrid('Employee[id_podraz]', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'podrazgrid_gw');
        $I->chooseValueFromGrid('Employee[id_build]', 'ПОЛИКЛИНИКА 1', 'buildgrid_gw');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(1);
        $I->see('На этого сотрудника уже есть такая специальность');

        $I->chooseValueFromGrid('Employee[id_build]', 'ПОЛИКЛИНИКА 2', 'buildgrid_gw');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['КАРДИОЛОГ', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'ПОЛИКЛИНИКА 1'], 'employeeauthusergrid_gw', ['button[@title="Удалить"]', 'a[@title="Обновить"]']);
        $I->checkDynagridData(['КАРДИОЛОГ', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'ПОЛИКЛИНИКА 2'], 'employeeauthusergrid_gw', ['button[@title="Удалить"]', 'a[@title="Обновить"]']);
    }

    /**
     * @depends addCreateEmployeeFromGrids
     */
    public function updateEmployee(AcceptanceTester $I)
    {
        $I->click('//td[text()="ПОЛИКЛИНИКА 2"]/preceding-sibling::td[text()="ОБЩЕПОЛИКЛИНИЧЕСКОЕ"]/preceding-sibling::td[text()="КАРДИОЛОГ"]/preceding-sibling::td/a[@title="Обновить"]');
        $I->wait(2);
        $I->seeElement(['class' => 'employee-form']);

        $I->chooseValueFromGrid('Employee[id_build]', 'ПОЛИКЛИНИКА 3', 'buildgrid_gw');

        $I->see('Обновить');
        $I->click('//button[contains(text(), "Обновить")]');
        $I->wait(2);

        $I->checkDynagridData(['КАРДИОЛОГ', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'ПОЛИКЛИНИКА 1'], 'employeeauthusergrid_gw', ['button[@title="Удалить"]', 'a[@title="Обновить"]']);
        $I->checkDynagridData(['КАРДИОЛОГ', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'ПОЛИКЛИНИКА 3'], 'employeeauthusergrid_gw', ['button[@title="Удалить"]', 'a[@title="Обновить"]']);
    }

    /**
     * @depends updateEmployee
     */
    public function deleteOneEmployee(AcceptanceTester $I)
    {
        $I->click('//td[text()="ПОЛИКЛИНИКА 1"]/preceding-sibling::td[text()="ОБЩЕПОЛИКЛИНИЧЕСКОЕ"]/preceding-sibling::td[text()="КАРДИОЛОГ"]/preceding-sibling::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);
        $I->dontSeeElement('//td[text()="ПОЛИКЛИНИКА 1"]/preceding-sibling::td[text()="ОБЩЕПОЛИКЛИНИЧЕСКОЕ"]/preceding-sibling::td[text()="КАРДИОЛОГ"]/preceding-sibling::td/button[@title="Удалить"]/preceding-sibling::a[@title="Обновить"]');
        $I->seeElement('//td[text()="ПОЛИКЛИНИКА 3"]/preceding-sibling::td[text()="ОБЩЕПОЛИКЛИНИЧЕСКОЕ"]/preceding-sibling::td[text()="КАРДИОЛОГ"]/preceding-sibling::td/button[@title="Удалить"]/preceding-sibling::a[@title="Обновить"]');
    }

    /**
     * @depends deleteOneEmployee
     */
    public function addCreateEmployeeWithoutBuild(AcceptanceTester $I)
    {
        $I->seeLink('Добавить специальность');
        $I->click(['link' => 'Добавить специальность']);
        $I->wait(2);
        $I->seeElement(['class' => 'employee-form']);

        $I->chooseValueFromGrid('Employee[id_dolzh]', 'НЕВРОЛОГ', 'dolzhgrid_gw');
        $I->chooseValueFromGrid('Employee[id_podraz]', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'podrazgrid_gw');

        $I->see('Создать');
        $I->click('//button[contains(text(), "Создать")]');
        $I->wait(2);

        $I->checkDynagridData(['КАРДИОЛОГ', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', 'ПОЛИКЛИНИКА 3', ''], 'employeeauthusergrid_gw', ['button[@title="Удалить"]', 'a[@title="Обновить"]']);
        $I->checkDynagridData(['НЕВРОЛОГ', 'ОБЩЕПОЛИКЛИНИЧЕСКОЕ', '', ''], 'employeeauthusergrid_gw', ['button[@title="Удалить"]', 'a[@title="Обновить"]']);
    }

    /**
     * @depends addCreateEmployeeWithoutBuild
     */
    public function deleteAuthuser(AcceptanceTester $I)
    {
        $I->click('//button[contains(text(),"Обновить")]');
        $I->wait(2);
        $I->click('//td[text()="ИВАНОВ ИВАН ИВАНОВИЧ"]/preceding-sibling::td/button[@title="Удалить"]');
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
        Employee::deleteAll();
        Authuser::deleteAll('auth_user_id <> 1');
        Dolzh::deleteAll();
        Podraz::deleteAll();
        Build::deleteAll();
    }
}
