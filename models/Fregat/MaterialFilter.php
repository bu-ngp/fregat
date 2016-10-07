<?php

namespace app\models\Fregat;

use app\models\Config\Authuser;
use Yii;
use yii\base\Model;

class MaterialFilter extends Model
{

    public $mol_fullname_material;
    public $mol_fullname_material_not;
    public $material_writeoff;
    public $mat_id_grupa;
    public $mat_id_grupa_not;
    public $mol_id_build;
    public $mol_id_build_not;
    public $tr_osnov_kab;
    public $mattraffic_username;
    public $mattraffic_lastchange_beg;
    public $mattraffic_lastchange_end;

    public function rules()
    {
        return [
            [[
                'mol_fullname_material',
                'mol_fullname_material_not',
                'mat_id_grupa',
                'mat_id_grupa_not',
                'mattraffic_username',
                'tr_osnov_kab',
            ], 'safe'],
            [[
                'mattraffic_lastchange_beg',
                'mattraffic_lastchange_end',
            ], 'date', 'format' => 'php:Y-m-d'],
            [[
                'mol_id_build',
                'mol_id_build_not',
                'material_writeoff',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'mol_fullname_material' => 'Текущее материально-ответственное лицо',
            'mol_id_build' => 'Здание в котором установлена материальная ценность',
            'material_writeoff' => 'Списан',
            'mat_id_grupa' => 'Группа',
            'tr_osnov_kab' => 'Кабинет в котором установлена материальная ценность',
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
            'mol_id_build' => [$value => Build::getBuildByID($value)],
            'mol_fullname_material' => [$value => Authuser::getAuthuserByID($value)],
            'material_writeoff' => Material::VariablesValues($attribute),
            'mat_id_grupa' => [$value => Grupa::getGrupaByID($value)],
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
             'glprep_id_preparat' => [$value => Preparat::getPreparatByID($value)],
             'glprep_rlocat' => Glprep::VariablesValues($attribute),*/
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
