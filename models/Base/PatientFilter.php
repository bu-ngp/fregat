<?php

namespace app\models\Base;

use Yii;
use yii\base\Model;

class PatientFilter extends Model {

    public $patient_fam;
    public $patient_im;
    public $patient_ot;
    public $patient_dr;
    public $patient_vozrast_znak;
    public $patient_vozrast;
    public $patient_pol;
    public $fias_city;
    public $fias_street;
    public $patient_dom;
    public $patient_korp;
    public $patient_kvartira;
    public $glaukuchet_uchetbegin_beg;
    public $glaukuchet_uchetbegin_end;
    public $glaukuchet_detect;
    public $glaukuchet_deregdate_beg;
    public $glaukuchet_deregdate_end;
    public $glaukuchet_deregreason;
    public $glaukuchet_stage;
    public $glaukuchet_operdate_beg;
    public $glaukuchet_operdate_end;
    public $glaukuchet_rlocat;
    public $glaukuchet_invalid;
    public $glaukuchet_lastvisit_beg;
    public $glaukuchet_lastvisit_end;
    public $glaukuchet_lastmetabol_beg;
    public $glaukuchet_lastmetabol_end;
    public $glaukuchet_id_employee;
    public $employee_id_dolzh;
    public $employee_id_podraz;
    public $employee_id_build;
    public $patient_username;
    public $patient_lastchange_beg;
    public $patient_lastchange_end;
    public $glaukuchet_username;
    public $glaukuchet_lastchange_beg;
    public $glaukuchet_lastchange_end;

    public function rules() {
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
                ], 'safe'],
            [['patient_vozrast'], 'exist', 'targetAttribute' => ['patient_vozrast_znak', 'patient_vozrast']],
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
            'glaukuchet_rlocat',
            'glaukuchet_invalid',
            'glaukuchet_id_employee',
            'employee_id_dolzh',
            'employee_id_podraz',
            'employee_id_build',
                ], 'integer'],
        ];
    }

    public function attributeLabels() {
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
            'glaukuchet_uchetbegin_beg' => 'Дата постановки на учет',
            'glaukuchet_detect' => 'Вид выявления заболевания',
            'glaukuchet_deregdate_beg' => 'Дата снятия с учета',
            'glaukuchet_deregreason' => 'Причина снятия с учета',
            'glaukuchet_stage' => 'Стадия глаукомы',
            'glaukuchet_operdate_beg' => 'Дата последнего оперативного лечения',
            'glaukuchet_rlocat' => 'Категория льготного лекарственного обеспечения',
            'glaukuchet_invalid' => 'Группа инвалидности',
            'glaukuchet_lastvisit_beg' => 'Дата последней явки на прием',
            'glaukuchet_lastmetabol_beg' => 'Дата последнего курса метоболической терапии',
            'glaukuchet_id_employee' => 'Врач',
            'employee_id_dolzh' => 'Должность',
            'employee_id_podraz' => 'Подразделение',
            'employee_id_build' => 'Здание',
            'patient_username' => 'Пользователь изменивший запись паспорта пациента',
            'patient_lastchange_beg' => 'Дата изменения записи паспорта пациента',
            'glaukuchet_username' => 'Пользователь изменивший запись карты глаукомного пациента',
            'glaukuchet_lastchange_beg' => 'Дата изменения записи карты глаукомного пациента',
        ];
    }

    public function __construct($config = array()) {
        $config['patient_vozrast_znak'] = '=';
        parent::__construct($config);
    }

    public static function VariablesValues($attribute, $value = NULL) {
        $values = [
            'patient_pol' => [1 => 'Мужской', 2 => 'Женский'],
            'fias_city' => [$value => Fias::GetCityByAOGUID($value)],
            'fias_street' => [$value => Fias::GetStreetByAOGUID($value)],
            'glaukuchet_detect' => [1 => 'При обращении за лечением', 2 => 'При обращении по диспансеризации'],
            'glaukuchet_deregreason' => [1 => 'Смерть', 2 => 'Миграция', 3 => 'Другое'],
            'glaukuchet_stage' => [1 => 'I стадия', 2 => 'II стадия', 3 => 'III стадия', 4 => 'IV стадия'],
            'glaukuchet_rlocat' => [1 => 'Федеральная', 2 => 'Региональная'],
            'glaukuchet_invalid' => [1 => 'I группа', 2 => 'II группа', 3 => 'III группа'],
            'glaukuchet_id_employee' => [$value => \app\models\Fregat\Employee::getEmployeeByID($value)],
            'employee_id_dolzh' => [$value => \app\models\Fregat\Dolzh::getDolzhByID($value)],
            'employee_id_podraz' => [$value => \app\models\Fregat\Podraz::getPodrazByID($value)],
            'employee_id_build' => [$value => \app\models\Fregat\Build::getBuildByID($value)],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}