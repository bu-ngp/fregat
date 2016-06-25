<?php

namespace app\models\Base;

use Yii;
use app\models\Glauk\Glaukuchet;

/**
 * This is the model class for table "patient".
 *
 * @property string $patient_id
 * @property string $patient_fam
 * @property string $patient_im
 * @property string $patient_ot
 * @property string $patient_dr
 * @property integer $patient_pol
 * @property string $id_fias
 * @property string $patient_dom
 * @property string $patient_korp
 * @property string $patient_kvartira
 * @property string $patient_username
 * @property string $patient_lastchange
 *
 * @property Glaukuchet[] $glaukuchets
 * @property Fias $idFias
 */
class Patient extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'patient';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['patient_username'], 'filter', 'filter' => function($value) {
            return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
        }],
            [['patient_fam', 'patient_im', 'patient_dr', 'patient_pol', 'patient_username'], 'required'],
            [['patient_fam', 'patient_im', 'patient_ot', 'patient_korp', 'patient_dom', 'patient_kvartira'], 'filter', 'filter' => function($value) {
            return mb_strtoupper($value, 'UTF-8');
        }],
            [['patient_dr'], 'date', 'format' => 'php:Y-m-d'],
            [['patient_dr'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение должно быть меньше или равно значения "' . Yii::$app->formatter->asDate(date('d.m.Y')) . '"'],
            [['patient_dr', 'patient_lastchange'], 'safe'],
            [['patient_pol'], 'integer'],
            [['patient_fam', 'patient_im', 'patient_ot'], 'string', 'max' => 255],
            [['id_fias'], 'string', 'max' => 36],
            [['patient_dom', 'patient_korp', 'patient_kvartira'], 'string', 'max' => 10],
            [['patient_username'], 'string', 'max' => 128],
            [['id_fias'], 'exist', 'skipOnError' => true, 'targetClass' => Fias::className(), 'targetAttribute' => ['id_fias' => 'AOGUID']],
            [['id_fias', 'patient_dom', 'patient_kvartira'], 'required', 'on' => 'streetrequired'],
            [['patient_dom', 'patient_kvartira'], 'required', 'on' => 'nostreetrequired'], // не используется
            [['patient_fam'], 'unique', 'targetAttribute' => ['patient_fam', 'patient_im', 'patient_ot', 'patient_dr', 'patient_pol'], 'message' => 'Пациент с такими ФИО, датой рождения и полом уже есть в базе данных'],
            [['patient_lastchange'], 'date', 'format' => 'php:Y-m-d H:i:s', 'type' => 'datetime'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'patient_id' => 'Patient ID',
            'patient_fam' => 'Фамилия',
            'patient_im' => 'Имя',
            'patient_ot' => 'Отчество',
            'patient_dr' => 'Дата рождения',
            'patient_pol' => 'Пол пациента',
            'id_fias' => 'Улица',
            'patient_dom' => 'Дом',
            'patient_korp' => 'Корпус',
            'patient_kvartira' => 'Квартира',
            'patient_username' => 'Пользователь изменивший запись пациента',
            'patient_lastchange' => 'Дата изменения записи пациента',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlaukuchets() {
        return $this->hasOne(Glaukuchet::className(), ['id_patient' => 'patient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFias() {
        return $this->hasOne(Fias::className(), ['AOGUID' => 'id_fias']);
    }

    public function loadWithDisabledInputs($data, $formName = NULL) {
        if (!isset($formName))
            $formName = $this->formName();

        foreach ($this->attributes() as $attr) {
            $a = '';
            $this->$attr = isset($_POST[$formName][$attr]) ? $_POST[$formName][$attr] : NULL;
        }

        return isset($_POST[$formName]);
    }

    public static function VariablesValues($attribute) {
        $values = [
            'patient_pol' => [1 => 'Мужской', 2 => 'Женский']
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
