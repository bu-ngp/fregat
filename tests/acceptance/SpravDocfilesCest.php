<?php
use app\models\Fregat\Docfiles;
use yii\helpers\Url;

/**
 * @group SpravDocfilesCest
 */
class SpravDocfilesCest
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
    public function loadData(AcceptanceTester $I)
    {
        $I->loadDataFromSQLFile('docfiles.sql');
    }

    /**
     * @depends loadData
     */
    public function openDocfiles(AcceptanceTester $I)
    {
        $I->click('//div[contains(text(), "Загруженные документы")]');
        $I->wait(2);
        $I->seeElement(['id' => 'docfilesgrid_gw']);
        $I->see('Ничего не найдено');
        $I->see('Загрузить файл');
        $I->seeElement('//div[contains(@class, "kv-fileinput-caption")]');
        $I->seeElement('//div[contains(@class, "btn-file")]');
    }

    /**
     * @depends openDocfiles
     */
    public function addnewDocfiles(AcceptanceTester $I)
    {
        // excel
        $I->attachFile('UploadDocFile[docFile]', 'files/excel.xlsx');
        $I->wait(2);
        $I->click('//a[contains(@title, "Загрузить выбранные файлы")]');
        $I->wait(2);
        $I->seeElement('//td/a[contains(text(),"excel.xlsx")]/preceding::td/span/i[@class="fa fa-file-excel-o"]/preceding::td/button[@title="Удалить"]');
        $I->dontSeeElement('//span[@style="text-decoration: line-through" and text() = "excel.xlsx"]');

        // word
        $I->attachFile('UploadDocFile[docFile]', 'files/word.docx');
        $I->wait(2);
        $I->click('//a[contains(@title, "Загрузить выбранные файлы")]');
        $I->wait(2);
        $I->seeElement('//td/a[contains(text(),"word.docx")]/preceding::td/span/i[@class="fa fa-file-word-o"]/preceding::td/button[@title="Удалить"]');
        $I->dontSeeElement('//span[@style="text-decoration: line-through" and text() = "word.docx"]');

        // jpg
        $I->attachFile('UploadDocFile[docFile]', 'files/jpg.jpg');
        $I->wait(2);
        $I->click('//a[contains(@title, "Загрузить выбранные файлы")]');
        $I->wait(2);
        $I->seeElement('//td/a[contains(text(),"jpg.jpg")]/preceding::td/span/i[@class="fa fa-file-image-o"]/preceding::td/button[@title="Удалить"]');
        $I->dontSeeElement('//span[@style="text-decoration: line-through" and text() = "jpg.jpg"]');

        // png
        $I->attachFile('UploadDocFile[docFile]', 'files/png.png');
        $I->wait(2);
        $I->click('//a[contains(@title, "Загрузить выбранные файлы")]');
        $I->wait(2);
        $I->seeElement('//td/a[contains(text(),"png.png")]/preceding::td/span/i[@class="fa fa-file-image-o"]/preceding::td/button[@title="Удалить"]');
        $I->dontSeeElement('//span[@style="text-decoration: line-through" and text() = "png.png"]');

        //txt
        $I->attachFile('UploadDocFile[docFile]', 'files/text.txt');
        $I->wait(2);
        $I->click('//a[contains(@title, "Загрузить выбранные файлы")]');
        $I->wait(2);
        $I->seeElement('//td/a[contains(text(),"text.txt")]/preceding::td/span/i[@class="fa fa-file-text-o"]/preceding::td/button[@title="Удалить"]');
        $I->dontSeeElement('//span[@style="text-decoration: line-through" and text() = "text.txt"]');

        //unknown
        $I->attachFile('UploadDocFile[docFile]', 'files/unknown.unk');
        $I->wait(2);
        $I->see('Разрешена загрузка файлов только со следующими расширениями: png, jpg, jpeg, tiff, pdf, xls, xlsx, doc, docx, txt.');
        $I->click('//a[contains(@title, "Загрузить выбранные файлы")]');
        $I->wait(2);
    }

    /**
     * @depends addnewDocfiles
     */
    public function deleteDocfiles(AcceptanceTester $I)
    {
        // excel
        $I->click('//td/a[contains(text(),"excel.xlsx")]/preceding::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        // word
        $I->click('//td/a[contains(text(),"word.docx")]/preceding::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        // jpg
        $I->click('//td/a[contains(text(),"jpg.jpg")]/preceding::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        // png
        $I->click('//td/a[contains(text(),"png.png")]/preceding::td/button[@title="Удалить"]');
        $I->wait(2);
        $I->see('Вы уверены, что хотите удалить запись?');
        $I->click('button[data-bb-handler="confirm"]');
        $I->wait(2);

        // txt
        $I->click('//td/a[contains(text(),"text.txt")]/preceding::td/button[@title="Удалить"]');
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
        Docfiles::deleteAll();
    }
}
