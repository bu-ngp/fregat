<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;

class MaterialFilter extends Model
{

    public $mattraffic_username;
    public $mattraffic_lastchange_beg;
    public $mattraffic_lastchange_end;

    public function rules()
    {
        return [
            [['mattraffic_username',
            ], 'safe'],
            [[
                'mattraffic_lastchange_beg',
                'mattraffic_lastchange_end',
            ], 'date', 'format' => 'php:Y-m-d'],
            /*  [[
                  'patient_pol',
              ], 'integer'],*/
        ];
    }

    public function attributeLabels()
    {
        return [
            'mattraffic_username' => 'Пользователь, последний изменивший движение материальной ценности',
            'mattraffic_lastchange_beg' => 'Дата изменения движения мат-ой ценности',
        ];
    }

    public function __construct($config = array())
    {
        //   $config['patient_vozrast_znak'] = '=';
        parent::__construct($config);
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            /* 'patient_pol' => Patient::VariablesValues($attribute),
             'fias_city' => [$value => Fias::GetCityByAOGUID($value)],
             'fias_street' => [$value => Fias::GetStreetByAOGUID($value)],
             'glaukuchet_detect' => Glaukuchet::VariablesValues($attribute),
             'glaukuchet_deregreason' => Glaukuchet::VariablesValues($attribute),
             'glaukuchet_stage' => Glaukuchet::VariablesValues($attribute),
             'glaukuchet_invalid' => Glaukuchet::VariablesValues($attribute),
             'glaukuchet_id_employee' => [$value => Employee::getEmployeeByID($value)],
             'employee_id_dolzh' => [$value => Dolzh::getDolzhByID($value)],
             'employee_id_podraz' => [$value => Podraz::getPodrazByID($value)],
             'employee_id_build' => [$value => Build::getBuildByID($value)],
             'glprep_id_preparat' => [$value => Preparat::getPreparatByID($value)],
             'glprep_rlocat' => Glprep::VariablesValues($attribute),*/
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
