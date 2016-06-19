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

    public function rules() {
        return [
            [['patient_fam',
            'patient_im',
            'patient_ot',
            'patient_vozrast_znak',
                /*   '',
                  '',
                  '',
                  '',
                  '',
                  '',
                  '', */
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
        ];
    }

    public function __construct($config = array()) {
        $config['patient_vozrast_znak'] = '=';
        parent::__construct($config);
    }

}
