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
}
