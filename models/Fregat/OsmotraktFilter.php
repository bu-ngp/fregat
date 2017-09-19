<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class OsmotraktFilter extends Model
{
    public $mattraffic_date_writeoff_beg;
    public $mattraffic_date_writeoff_end;
    public $osmotrakt_recoverysendakt_exists_mark;
    public $osmotrakt_recoverysendakt_not_exists_mark;
    public $osmotrakt_recoveryrecieveakt_repaired;
    public $osmotrakt_recoverysendakt_not_recieved_mark;

    public function rules()
    {
        return [
            [[
                'osmotrakt_recoverysendakt_exists_mark',
                'osmotrakt_recoverysendakt_not_exists_mark',
                'osmotrakt_recoverysendakt_not_recieved_mark',
            ], 'safe'],
            [[
                'mattraffic_date_writeoff_beg',
                'mattraffic_date_writeoff_end',
            ], 'date', 'format' => 'php:Y-m-d'],
            [[
                'osmotrakt_recoveryrecieveakt_repaired',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mattraffic_date_writeoff_beg' => 'Дата списания материальной ценности',
            'osmotrakt_recoverysendakt_exists_mark' => 'Акт содержится в журнале восстановления материальных ценностей',
            'osmotrakt_recoverysendakt_not_exists_mark' => 'Акт отсутствует в журнале восстановления материальных ценностей',
            'osmotrakt_recoveryrecieveakt_repaired' => 'Подлежит восстановлению',
            'osmotrakt_recoverysendakt_not_recieved_mark' => 'Не получены у организации',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'osmotrakt_recoveryrecieveakt_repaired' => Recoveryrecieveakt::VariablesValues('recoveryrecieveakt_repaired'),
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
