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
                while (($subject = fgets($handle, 4096)) !== false) {
                    $dbh = new PDO(Yii::$app->db->dsn, Yii::$app->db->username, Yii::$app->db->password);
                    if ($dbh->exec($subject) === false)
                        $result = false;
                }
                fclose($handle);
            } else
                return false;
            return $result;
        }
    }

    public function checkDatePicker($nameDatePicker)
    {
        $this->click('//input[@name="' . $nameDatePicker . '"]');
        $this->seeElement('//div[contains(@class, "datepicker")]');
        $this->seeElement('//th[contains(text(), "Вс")]');
        $this->seeElement(['class' => 'kv-date-remove']);
    }

    public function chooseValueFromSelect2($attributeName, $resultValue, $inputValue = '')
    {
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::span[contains(@class, "select2-container")]/span/span[contains(@class, "select2-selection")]');
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

    public function chooseValueFromGrid($attributeName, $resultValue, $gridID, $chooseXPath = '')
    {
        $this->click('//select[@name="' . $attributeName . '"]/following-sibling::div/a[@class="btn btn-success"]');
        $this->wait(2);
        $this->seeElement(['id' => $gridID]);
        $this->click(empty($chooseXPath) ? '//td[text()="' . $resultValue . '"]/preceding-sibling::td/button[@title="Выбрать"]' : $chooseXPath);
        $this->wait(2);
        $this->seeElement('//select[@name="' . $attributeName . '"]/following-sibling::span/span/span/span[@title="' . $resultValue . '"]');
    }

    public function checkDynagridData($arrayData, $dynaGridID = '', $arrayButtons = [])
    {
        if (is_array($arrayData) && count($arrayData) > 0) {

            $strbegin = empty($dynaGridID) ? '//' : '//div[@id="' . $dynaGridID . '"]/div/div/table/tbody/tr/';

            $path = $strbegin . 'td[text()="' . $arrayData[0] . '"]';

            $firstElement = $arrayData[0];

            unset($arrayData[0]);

            foreach ($arrayData as $value)
                $path .= '/following-sibling::td[text()="' . $value . '"]';

            $this->seeElement($path);

            if (is_array($arrayButtons) && count($arrayButtons) > 0) {
                foreach ($arrayButtons as $button) {
                    if (is_string($button)) {
                        //file_put_contents('ttt.txt',$strbegin . 'td/' . $button . '/following-sibling::td[text()="' . $firstElement . '"]');
                        $this->seeElement($strbegin . 'td/' . $button . '/../following-sibling::td[text()="' . $firstElement . '"]');
                    }

                }
            }
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
                $cellValue = $objPHPExcel->getActiveSheet()->getCell($cell[0] . $cell[1])->getValue();
                if ($cellValue != $cell[2]) {
                    $this->fail('Значение в файле "' . $fileNameOutput . '" не совпадает с заданным: ячейка "' . $cell[0] . $cell[1] . '", "' . $cellValue . '" <> "' . $cell[2] . '"');
                }
            }
        }
    }

}
