<?php

namespace app\models\Base;

use Yii;
use yii\base\Model;
use app\models\Glauk\Glaukuchet;
use app\models\Glauk\Glprep;
use app\models\Fregat\Employee;
use app\models\Fregat\Dolzh;
use app\models\Fregat\Podraz;
use app\models\Fregat\Build;

class PatientFilter extends Model
{

    public $patient_fam;
    public $patient_im;
    public $patient_ot;
    public $patient_dr;
    public $patient_vozrast_znak;
    public $patient_vozrast;
    public $patient_pol;
    public $patient_pol_not;
    public $fias_city;
    public $fias_street;
    public $patient_dom;
    public $patient_korp;
    public $patient_kvartira;
    public $is_glauk_mark;
    public $glaukuchet_uchetbegin_beg;
    public $glaukuchet_uchetbegin_end;
    public $glaukuchet_detect;
    public $glaukuchet_detect_not;
    public $is_glaukuchet_mark;
    public $glaukuchet_deregreason;
    public $glaukuchet_deregreason_not;
    public $glaukuchet_deregdate_beg;
    public $glaukuchet_deregdate_end;
    public $glaukuchet_stage;
    public $glaukuchet_stage_not;
    public $glaukuchet_operdate_beg;
    public $glaukuchet_operdate_end;
    public $glaukuchet_not_oper_mark;
    public $glaukuchet_invalid;
    public $glaukuchet_invalid_not;
    public $glaukuchet_not_invalid_mark;
    public $glaukuchet_lastvisit_beg;
    public $glaukuchet_lastvisit_end;
    public $glaukuchet_lastmetabol_beg;
    public $glaukuchet_lastmetabol_end;
    public $glaukuchet_not_lastmetabol_mark;
    public $glaukuchet_id_employee;
    public $glaukuchet_id_employee_not;
    public $employee_id_dolzh;
    public $employee_id_dolzh_not;
    public $employee_id_podraz;
    public $employee_id_podraz_not;
    public $employee_id_build;
    public $employee_id_build_not;
    public $glprep_id_preparat;
    public $glprep_id_preparat_not;
    public $glprep_rlocat;
    public $glprep_rlocat_not;
    public $glprep_not_preparat_mark;
    public $glprep_preparat_mark;
    public $glaukuchet_comment_mark;
    public $glaukuchet_comment;
    public $patient_username;
    public $patient_lastchange_beg;
    public $patient_lastchange_end;
    public $glaukuchet_username;
    public $glaukuchet_lastchange_beg;
    public $glaukuchet_lastchange_end;

    public function rules()
    {
        return [
            [['patient_fam',
                'patient_im',
                'patient_ot',
                'patient_vozrast_znak',
                'fias_city',
                'fias_street',
                'patient_dom',
                'patient_korp',
                'patient_kvartira',
                'patient_username',
                'glaukuchet_username',
                'is_glauk_mark',
                'is_glaukuchet_mark',
                'glaukuchet_not_oper_mark',
                'glaukuchet_not_invalid_mark',
                'glaukuchet_not_lastmetabol_mark',
                'glprep_not_preparat_mark',
                'glprep_preparat_mark',
                'glaukuchet_comment_mark',
                'glaukuchet_comment',
                'patient_pol_not',
                'glaukuchet_detect_not',
                'glaukuchet_deregreason_not',
                'glaukuchet_stage_not',
                'glaukuchet_invalid_not',
                'glprep_rlocat_not',
                'glprep_id_preparat_not',
                'glaukuchet_id_employee_not',
                'employee_id_dolzh_not',
                'employee_id_podraz_not',
                'employee_id_build_not',
            ], 'safe'],
            [[
                'patient_dr',
                'glaukuchet_uchetbegin_beg',
                'glaukuchet_uchetbegin_end',
                'glaukuchet_deregdate_beg',
                'glaukuchet_deregdate_end',
                'glaukuchet_operdate_beg',
                'glaukuchet_operdate_end',
                'glaukuchet_lastvisit_beg',
                'glaukuchet_lastvisit_end',
                'glaukuchet_lastmetabo_beg',
                'glaukuchet_lastmetabol_end',
                'patient_lastchange_beg',
                'patient_lastchange_end',
                'glaukuchet_lastchange_beg',
                'glaukuchet_lastchange_end',
            ], 'date', 'format' => 'php:Y-m-d'],
            [['patient_vozrast'], 'integer', 'min' => 1, 'max' => 120],
            [[
                'patient_pol',
                'glaukuchet_detect',
                'glaukuchet_deregreason',
                'glaukuchet_stage',
                'glaukuchet_invalid',
                'glaukuchet_id_employee',
                'employee_id_dolzh',
                'employee_id_podraz',
                'employee_id_build',
                'glprep_id_preparat',
                'glprep_rlocat',
            ], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'patient_fam' => 'Фамилия пациента',
            'patient_im' => 'Имя пациента',
            'patient_ot' => 'Отчество пациента',
            'patient_dr' => 'Дата рождения пациента',
            'patient_vozrast' => 'Возраст пациента',
            'patient_pol' => 'Пол пациента',
            'fias_city' => 'Населенный пункт',
            'fias_street' => 'Улица',
            'patient_dom' => 'Дом',
            'patient_korp' => 'Корпус',
            'patient_kvartira' => 'Квартира',
            'is_glauk_mark' => 'Пациент содержится в регистре глаукомных пациентов',
            'glaukuchet_uchetbegin_beg' => 'Дата постановки на учет',
            'glaukuchet_detect' => 'Вид выявления заболевания',
            'is_glaukuchet_mark' => 'Пациент состоит на учете в регистре',
            'glaukuchet_deregreason' => 'Причина снятия с учета',
            'glaukuchet_deregdate_beg' => 'Дата снятия с учета',
            'glaukuchet_stage' => 'Стадия глаукомы',
            'glaukuchet_operdate_beg' => 'Дата последнего оперативного лечения',
            'glaukuchet_not_oper_mark' => 'Отсутствует оперативное лечение глаукомы',
            'glaukuchet_invalid' => 'Группа инвалидности',
            'glaukuchet_not_invalid_mark' => 'Отсутствует инвалидность',
            'glaukuchet_lastvisit_beg' => 'Дата последней явки на прием',
            'glaukuchet_lastmetabol_beg' => 'Дата последнего курса метоболической терапии',
            'glaukuchet_not_lastmetabol_mark' => 'Отсутствует курс метоболической терапии',
            'glaukuchet_id_employee' => 'Врач',
            'employee_id_dolzh' => 'Должность',
            'employee_id_podraz' => 'Подразделение',
            'employee_id_build' => 'Здание',
            'glprep_id_preparat' => 'Препарат',
            'glprep_rlocat' => 'Категория льготного лекарственного обеспечения',
            'glprep_not_preparat_mark' => 'Отсутствует потребность в медикаментозной терапии',
            'glprep_preparat_mark' => 'Пациенту требуется медикаментозная терапия',
            'glaukuchet_comment_mark' => 'Содержит заметку',
            'glaukuchet_comment' => 'Текст заметки',
            'patient_username' => 'Пользователь изменивший запись паспорта пациента',
            'patient_lastchange_beg' => 'Дата изменения записи паспорта пациента',
            'glaukuchet_username' => 'Пользователь изменивший запись карты глаукомного пациента',
            'glaukuchet_lastchange_beg' => 'Дата изменения записи карты глаукомного пациента',
        ];
    }

    public function __construct($config = array())
    {
        $config['patient_vozrast_znak'] = '=';
        parent::__construct($config);
    }

    public static function VariablesValues($attribute, $value = NULL)
    {
        $values = [
            'patient_pol' => Patient::VariablesValues($attribute),
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
            'glprep_rlocat' => Glprep::VariablesValues($attribute),
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
