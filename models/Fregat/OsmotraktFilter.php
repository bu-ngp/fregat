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

    public function rules()
    {
        return [
            [[
                'osmotrakt_recoverysendakt_exists_mark',
                'osmotrakt_recoverysendakt_not_exists_mark',
            ], 'safe'],
            [[
                'mattraffic_date_writeoff_beg',
                'mattraffic_date_writeoff_end',
            ], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mattraffic_date_writeoff_beg' => 'Дата списания материальной ценности',
            'osmotrakt_recoverysendakt_exists_mark' => 'Акт содержится в журнале восстановления материальных ценностей',
            'osmotrakt_recoverysendakt_not_exists_mark' => 'Акт отсутствует в журнале восстановления материальных ценностей',
        ];
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [

        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
