<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */

    private function getDsnParams()
    {
        $res = [];
        array_walk(explode(';', Yii::$app->db->dsn), function ($val) use (&$res) {
            $m = explode('=', $val);
            $res[$m[0]] = $m[1];
        });
        return $res;
    }

    public function loadDataFromSQLFile($sqlFileName)
    {
        if (is_string($sqlFileName)) {
            $sqlFileName = dirname(dirname(__DIR__)) . '/tests/_data/' . $sqlFileName;
            $handle = @fopen($sqlFileName, "r");
            $result = true;
            if ($handle) {
                $dbh = new PDO(Yii::$app->db->dsn, Yii::$app->db->username, Yii::$app->db->password);
                $dbh->beginTransaction();
                while (($subject = fgets($handle, 4096)) !== false) {
                    if ($dbh->exec($subject) === false)
                        $result = false;
                }
                $dbh->commit();
                fclose($handle);
            } else
                return false;
            return $result;
        }
    }

    public function checkDatePicker($nameDatePicker)
    {
        $this->wait(1);
        $this->click('//input[@name="' . $nameDatePicker . '"]/../div/input');
        $this->wait(1);
        $this->seeElement('//div[contains(@class, "datepicker")]');
        $this->seeElement('//th[contains(text(), "Вс")]');
        $this->seeElement(['class' => 'kv-date-remove']);
    }

    public function chooseValueFromSelect2($attributeName, $resultValue, $inputValue = '')
    {
        $this->wait(1);
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span[contains(@class, "select2-container")]/span/span[contains(@class, "select2-selection")]/*[contains(@class, "select2-selection__rendered")]');
        if (!empty($inputValue)) {

            $this->fillField('//input[@class="select2-search__field"]', $inputValue);
            $this->wait(1);
        }
        $this->click('//li[contains(text(),"' . $resultValue . '")]');

        try {
            // single choose
            $this->seeElement('//select[@name="' . $attributeName . '"]/following-sibling::span/span/span/span[@title="' . $resultValue . '"]');
        } catch (Exception $e) {
            // multi chooses
            $this->seeElement('//select[@name="' . $attributeName . '"]/following-sibling::span/span/span/ul/li[@title="' . $resultValue . '"]');
        }
    }

    public function cantChooseValueFromSelect2($attributeName, $resultValue, $inputValue = '')
    {
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span[contains(@class, "select2-container")]/span/span[contains(@class, "select2-selection")]');
        if (!empty($inputValue)) {
            $this->fillField('//input[@class="select2-search__field"]', $inputValue);
            $this->wait(1);
        }
        $this->dontSeeElement('//li[contains(text(),"' . $resultValue . '")]');
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span[contains(@class, "select2-container")]/span/span[contains(@class, "select2-selection")]');
    }

    public function clearSelect2($attributeName)
    {
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span/span/span/span/span[@class="select2-selection__clear"]');
        $this->wait(1);
    }

    public function chooseValueFromGrid($attributeName, $resultValue, $gridID, $chooseXPath = '', $countRecordsGrid = NULL)
    {
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::div/a[@class="btn btn-success"]');
        $this->wait(2);
        $this->seeElement(['id' => $gridID]);
        if ($countRecordsGrid !== NULL)
            $this->countRowsDynagridEquals($gridID, $countRecordsGrid);
        $this->click(empty($chooseXPath) ? '//td[text()="' . $resultValue . '"]/preceding-sibling::td/button[@title="Выбрать"]' : $chooseXPath);
        $this->wait(2);
        $this->seeElement('//select[@name="' . $attributeName . '"]/following-sibling::span/span/span/span[@title="' . $resultValue . '"]');
    }

    public function clickChooseButtonFromGrid($arrayData, $gridID)
    {
        $this->seeElement(['id' => $gridID]);

        $path = '/';

        foreach ($arrayData as $value)
            $path .= $value === '' ? '/following-sibling::td[not(normalize-space())]' : '/following-sibling::' . $this->convertGridCell($value);

        $path .= '/preceding-sibling::td/button[@title="Выбрать"]';
        // file_put_contents('test.txt', $path . PHP_EOL, FILE_APPEND);
        $this->click($path);

        $this->wait(2);
    }

    public function checkDynagridData($arrayData, $dynaGridID = '', $arrayButtons = [])
    {
        if (is_array($arrayData) && count($arrayData) > 0) {

            $strbegin = empty($dynaGridID) ? '//' : '//div[@id="' . $dynaGridID . '"]/div/div/table/tbody/tr/';

            $path = $strbegin . ($arrayData[0] === '' ? 'td[not(normalize-space())]' : $this->convertGridCell($arrayData[0]));

            $firstElement = $arrayData[0];

            unset($arrayData[0]);

            foreach ($arrayData as $value)
                $path .= $value === '' ? '/following-sibling::td[not(normalize-space())]' : '/following-sibling::' . $this->convertGridCell($value);

            // file_put_contents('test.txt', $path . PHP_EOL, FILE_APPEND);
            $this->seeElement($path);

            if (is_array($arrayButtons) && count($arrayButtons) > 0) {
                foreach ($arrayButtons as $button) {
                    if (is_string($button)) {
                        //file_put_contents('test.txt', $strbegin . 'td/' . $button . '/following-sibling::td[text()="' . $firstElement . '"]' . PHP_EOL, FILE_APPEND);
                        $this->seeElement($strbegin . 'td/' . $button . '/../following-sibling::' . $this->convertGridCell($firstElement));
                    }

                }
            }
        }
    }

    private function convertGridCell($element)
    {
        if (is_array($element)) {
            if (isset($element['link'])) {
                return 'td/a[text()="' . $element['link']['text'] . '" and contains(@href,"' . $element['link']['href'] . '")]/..';
            }
        } else
            return 'td[text()="' . $element . '"]';
    }

    public function dontSeeDynagridData($arrayData, $dynaGridID = '')
    {
        if (is_array($arrayData) && count($arrayData) > 0) {

            $strbegin = empty($dynaGridID) ? '//' : '//div[@id="' . $dynaGridID . '"]/div/div/table/tbody/tr/';

            $path = $strbegin . ($arrayData[0] === '' ? 'td[not(normalize-space())]' : $this->convertGridCell($arrayData[0]));

            unset($arrayData[0]);

            foreach ($arrayData as $value)
                $path .= $value === '' ? '/following-sibling::td[not(normalize-space())]' : '/following-sibling::' . $this->convertGridCell($value);

            $this->dontSeeElement($path);
        }
    }

    public function existsInFilterTab($gridID, $arrayData)
    {
        if (is_string($gridID) && !empty($gridID) && is_array($arrayData) && count($arrayData) > 0) {
            $path = '//div[@id="' . $gridID . '"]/div/div[@id="' . $gridID . '-container"]/div[@class="panel panel-warning"]/div[contains(text(),"' . $arrayData[0] . '")';

            unset($arrayData[0]);

            foreach ($arrayData as $value)
                $path .= ' and contains(text(),"' . $value . '")';

            $path .= ']';

            $this->seeElement($path);
        }
    }

    public function convertOSFileName($FileName)
    {
        return DIRECTORY_SEPARATOR === '/' ? $FileName : mb_convert_encoding($FileName, 'Windows-1251', 'UTF-8');
    }

    public function checkExcelFile($fileName, $dataArray)
    {
        $fileNameOutput = DIRECTORY_SEPARATOR === '/' ? $fileName : mb_convert_encoding($fileName, 'UTF-8', 'Windows-1251');
        $objPHPExcel = \PHPExcel_IOFactory::load(Yii::$app->basePath . '/web/files/' . $fileName);
        if (is_string($fileName) && !empty($fileName) && is_array($dataArray) && count($dataArray) > 0) {
            foreach ($dataArray as $cell) {
                $sheetIndex = isset($cell[3]) ? isset($cell[3]) : 0;

                $cellValue = $objPHPExcel->getSheet($sheetIndex)->getCell($cell[0] . $cell[1])->getValue();
                if ($cellValue != $cell[2]) {
                    $this->fail('Значение в файле "' . $fileNameOutput . '" не совпадает с заданным: ячейка "' . $cell[0] . $cell[1] . '", "' . $cellValue . '" <> "' . $cell[2] . '"');
                }
            }
        }
    }

    public function checkZipFile($fileName, $filesArray)
    {
        $fileNameOutput = DIRECTORY_SEPARATOR === '/' ? $fileName : mb_convert_encoding($fileName, 'UTF-8', 'Windows-1251');

        $zip = new ZipArchive();
        if ($zip->open(Yii::$app->basePath . '/web/files/' . $fileName) !== true)
            $this->fail('Не существует архив ' . $fileNameOutput);

        $filesFromZip = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            //$filesFromZip[] = mb_convert_encoding($zip->getNameIndex($i), 'UTF-8', 'CP866');
            $filesFromZip[] = $zip->getNameIndex($i);
        }

        $result = array_diff($filesArray, $filesFromZip);

        if (!empty($result))
            $this->fail('Отсутствуют файлы в архиве: ' . implode(',', $result));

        $zip->close();
    }

    public function countRowsDynagridEquals($dynaGridID, $needCount)
    {
        try {
            $gridCount = str_replace(' ', '', $this->grabTextFrom('//div[@id="' . $dynaGridID . '"]/descendant::div[@class="summary"]/b[2]'));
        } catch (Exception $e) {
            $gridCount = 0;
        }

        if ($needCount == 0 && $gridCount != 0)
            $this->fail('Количество записей Dynagrid не равно 0' . $needCount . '. Всего записей ' . $gridCount);

        if ($gridCount != $needCount)
            $this->fail('Количество записей Dynagrid не равно ' . $needCount . '. Всего записей ' . $gridCount);
    }

    public function clickButtonDynagrid($dynaGridID, $Button, $arrayData)
    {
        if (is_array($arrayData) && count($arrayData) > 0) {

            $arrayData = array_reverse($arrayData);

            $path = ($arrayData[0] === '' ? '//div[@id="' . $dynaGridID . '"]/descendant::td[not(normalize-space())]' : '//div[@id="' . $dynaGridID . '"]/descendant::' . $this->convertGridCell($arrayData[0]));
            unset($arrayData[0]);

            foreach ($arrayData as $value) {
                $path .= ($value == '' ? '/preceding-sibling::td[not(normalize-space())]' : '/preceding-sibling::' . $this->convertGridCell($value));
            }

            $path .= '/preceding-sibling::td/' . $Button;
            //  file_put_contents('test.txt', $path . PHP_EOL, FILE_APPEND);
            $this->click($path);
            $this->wait(2);
        }
    }

    public function seeInSelect2($name, $value, $disabled = false)
    {
        $this->seeElement('//select[@name="' . $name . '"]/following-sibling::span/span/span/span[@class="select2-selection__rendered" and normalize-space(text()[1])="' . $value . '"]');
        if ($disabled)
            $this->seeElement('//select[@name="' . $name . '"]/following-sibling::span[contains(@class,"select2-container--disabled")]');
    }

    public function clickGridButtonBySelect2($nameSelect2)
    {
        $this->click('//select[@name="' . $nameSelect2 . '"]/following-sibling::div/a[@class="btn btn-success"]');
        $this->wait(2);
    }

    public function seeInDatecontrol($attributeName, $value)
    {
        $this->seeElement('//input[@name="' . $attributeName . '"]/../div/input[@value="' . $value . '"]');
    }


    public function fillDatecontrol($attributeName, $value)
    {
        $this->click('//input[@name="' . $attributeName . '"]/../div/input');
        $this->wait(1);
        $this->fillField('//input[@name="' . $attributeName . '"]/../div/input', $value);
        $this->pressKey('//input[@name="' . $attributeName . '"]/../div/input', WebDriverKeys::ENTER);
        $this->wait(1);
        /*   $this->click('//body');
           $this->wait(1);*/
    }

    public function seeSelect2Options($attributeName, $inputValue, array $optionsResult)
    {
        $this->wait(1);
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span[contains(@class, "select2-container")]/span/span[contains(@class, "select2-selection")]/*[contains(@class, "select2-selection__rendered")]');
        if (!empty($inputValue)) {
            $select2Class = $this->grabAttributeFrom('span.select2-search.select2-search--dropdown', 'class');

            switch ($select2Class) {
                case "select2-search select2-search--dropdown select2-search--hide":
                    break;
                case "select2-search select2-search--dropdown":
                    $this->fillField('//input[@class="select2-search__field"]', $inputValue);
                    $this->wait(1);
                    break;
            }
        }

        $strBegin = '//span[@class="select2-results"]/ul[@class="select2-results__options"]';
        $path = '/';

        foreach ($optionsResult as $key => $value) {
            $path .= ($key === 0 ? '/' : '/following-sibling::') . 'li[contains(@class, "select2-results__option") and text()="' . $value . '"]';
        }

        if (empty($optionsResult)) {
            $this->seeElement($strBegin . $path . 'li[@class="select2-results__option select2-results__message" and text()="Совпадений не найдено"]');
        } else {
            $this->seeElement($strBegin . $path);
        }

        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span[contains(@class, "select2-container")]/span/span[contains(@class, "select2-selection")]/*[contains(@class, "select2-selection__rendered")]');
    }
}
