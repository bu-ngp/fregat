<?php


class SpravMatvidCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function openFregat(AcceptanceTester $I)
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
}
