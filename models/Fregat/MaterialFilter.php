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
    public $material_attachfiles_mark;
    public $not_material_attachfiles_mark;
    public $material_attachphoto_mark;
    public $material_attachdoc_mark;
    public $material_comment_mark;
    public $tr_osnov_kab_current;
    public $tr_osnov_kab_always;
    public $tr_osnov_install_mark;
    public $tr_osnov_uninstall_mark;
    public $tr_mat_install_mark;
    public $tr_mat_uninstall_mark;
    public $mattraffic_username;
    public $mattraffic_lastchange_beg;
    public $mattraffic_lastchange_end;
    public $material_working_mark;
    public $material_recovery_attachfiles_mark;


    public function rules()
    {
        return [
            [[
                'mol_fullname_material',
                'mol_fullname_material_not',
                'mat_id_grupa',
                'mat_id_grupa_not',
                'mattraffic_username',
                'tr_osnov_kab_current',
                'tr_osnov_kab_always',
                'material_working_mark',
                'material_recovery_attachfiles_mark',
                'material_attachfiles_mark',
                'not_material_attachfiles_mark',
                'material_attachphoto_mark',
                'material_attachdoc_mark',
                'material_comment_mark',
                'tr_osnov_install_mark',
                'tr_osnov_uninstall_mark',
                'tr_mat_install_mark',
                'tr_mat_uninstall_mark',
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
            'material_attachfiles_mark' => 'Прикреплены файлы в карте материальной ценности',
            'not_material_attachfiles_mark' => 'Файлы в карте материальной ценности не прикреплены',
            'material_attachphoto_mark' => 'Прикреплены картинки в карте материальной ценности',
            'material_attachdoc_mark' => 'Прикреплены документы в карте материальной ценности',
            'material_comment_mark' => 'Содержит заметку',
            'tr_osnov_kab_current' => 'Кабинет в котором уставновлена материальная ценность на текущий момент',
            'tr_osnov_kab_always' => 'Кабинет в котором уставновлена материальная ценность за все время',
            'mattraffic_username' => 'Пользователь, последний изменивший движение материальной ценности',
            'mattraffic_lastchange_beg' => 'Дата изменения движения мат-ой ценности',
            'material_working_mark' => 'Материальные ценности в рабочем состоянии',
            'material_recovery_attachfiles_mark' => 'В актах восстановления прикреплены файлы',
            'tr_osnov_install_mark' => 'Имеется акт установки, как перемещенная материальная ценность',
            'tr_osnov_uninstall_mark' => 'Отсутствует акт установки, как перемещенная материальная ценность',
            'tr_mat_install_mark' => 'Имеется акт установки, как укомплектованная материальная ценность',
            'tr_mat_uninstall_mark' => 'Отсутствует акт установки, как укомплектованная материальная ценность',
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
