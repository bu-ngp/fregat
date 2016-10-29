<?php
namespace app\tests\unit;

use app\func\ImportData\Exec\EmployeeParseFactory;
use Codeception\Test\Unit;

class ExampleTest extends Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     * @var \app\func\ImportData\Exec\EmployeeParseObject
     */
    private $parseObject;

    protected function _before()
    {
        $initString = 'Исаева Ирина Владимировна|Поликлиника профилактических осмотров|Санитарка|Паспорт гражданина Российской Федерации|67 06|882218|Отделением УФМС России по ХМАО-Югре в Нижневартовском районе|06.02.2005 0:00:00|628616, Ханты-Мансийский Автономный округ - Югра АО, , Нижневартовск г, , Интернациональная ул,70,А,180|I862002355555|127-164-258 12|833-222|#001234|Ж|01.10.1955 0:00:00|#40817810700000123456|Ф-Л ЗС ПАО "ХАНТЫ-МАНСИЙСКИЙ БАНК ОТКРЫТИЕ"||';
        $this->parseObject = EmployeeParseFactory::employee($initString);
    }

    protected function _after()
    {

    }

    // tests
    public function testValues()
    {
        $this->assertTrue($this->parseObject->auth_user_fullname === 'Исаева Ирина Владимировна');
        $this->assertTrue($this->parseObject->dolzh_name === 'Санитарка');
        $this->assertTrue($this->parseObject->podraz_name === 'Поликлиника профилактических осмотров');
        $this->assertTrue($this->parseObject->build_name === 'Поликлиника профилактических осмотров');
        $this->assertTrue($this->parseObject->profile_dr === '01.10.1955');
        $this->assertTrue($this->parseObject->profile_pol === 'Ж');
        $this->assertTrue($this->parseObject->profile_inn === '862002355555');
        $this->assertTrue($this->parseObject->profile_snils === '127-164-258 12');
        $this->assertTrue($this->parseObject->profile_address === '628616, Ханты-Мансийский Автономный округ - Югра АО, , Нижневартовск г, , Интернациональная ул,70,А,180');
    }
}