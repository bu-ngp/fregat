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
            [['patient_fam', 'patient_im', 'patient_dr', 'patient_pol', 'patient_username'], 'required'],
            [['patient_dr', 'patient_lastchange'], 'safe'],
            [['patient_pol'], 'integer'],
            [['patient_fam', 'patient_im', 'patient_ot'], 'string', 'max' => 255],
            [['id_fias'], 'string', 'max' => 36],
            [['patient_dom', 'patient_korp', 'patient_kvartira'], 'string', 'max' => 10],
            [['patient_username'], 'string', 'max' => 128],
            [['id_fias'], 'exist', 'skipOnError' => true, 'targetClass' => Fias::className(), 'targetAttribute' => ['id_fias' => 'AOGUID']],
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
            'patient_pol' => 'Пол',
            'id_fias' => 'Улица',
            'patient_dom' => 'Дом',
            'patient_korp' => 'Корпус',
            'patient_kvartira' => 'Квартира',
            'patient_username' => 'Пользователь изменивший запись',
            'patient_lastchange' => 'Дата изменения записи',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlaukuchets() {
        return $this->hasMany(Glaukuchet::className(), ['id_patient' => 'patient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFias() {
        return $this->hasOne(Fias::className(), ['AOGUID' => 'id_fias']);
    }

}
