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
                ], 'safe'],
            [['patient_vozrast'], 'exist', 'targetAttribute' => ['patient_vozrast_znak', 'patient_vozrast']],
            [['patient_dr'], 'date', 'format' => 'php:Y-m-d'],
            [['patient_vozrast'], 'integer', 'min' => 1, 'max' => 120],
            [['patient_pol'], 'integer'],
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
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
