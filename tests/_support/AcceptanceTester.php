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
        $this->seeElement('//select[@name="' . $attributeName . '"]/following-sibling::span/span/span/span[@title="' . $resultValue . '"]');
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

}
