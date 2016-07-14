<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "osmotrakt".
 *
 * @property string $osmotrakt_id
 * @property string $osmotrakt_comment
 * @property integer $id_reason
 * @property integer $id_user
 * @property integer $id_master
 * @property string $id_mattraffic
 *
 * @property Employee $idUser
 * @property Employee $idMaster
 * @property Mattraffic $idMattraffic
 * @property Reason $idReason
 * @property Recoveryrecieveakt[] $recoveryrecieveakts
 */
class Osmotrakt extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'osmotrakt';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_reason', 'id_user', 'id_master', 'id_tr_osnov'], 'integer'],
            [['id_tr_osnov'], 'required', 'except' => 'forosmotrakt'],
            [['id_user', 'id_master'], 'required'],
            [['osmotrakt_comment'], 'string', 'max' => 400],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_user' => 'employee_id']],
            [['id_master'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_master' => 'employee_id']],
            [['id_tr_osnov'], 'exist', 'skipOnError' => true, 'targetClass' => TrOsnov::className(), 'targetAttribute' => ['id_tr_osnov' => 'tr_osnov_id']],
            [['id_reason'], 'exist', 'skipOnError' => true, 'targetClass' => Reason::className(), 'targetAttribute' => ['id_reason' => 'reason_id']],
            [['osmotrakt_date'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'osmotrakt_id' => 'Номер акта осмотра',
            'osmotrakt_comment' => 'Описание причины',
            'id_reason' => 'Причина поломки',
            'id_user' => 'Пользователь оборудования',
            'id_master' => 'Составитель акта',
            'id_tr_osnov' => 'Материальная ценность',
            'osmotrakt_date' => 'Дата осмотра материальной ценности',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_user'])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaster() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_master'])->inverseOf('osmotrakts0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrosnov() {
        return $this->hasOne(TrOsnov::className(), ['tr_osnov_id' => 'id_tr_osnov'])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReason() {
        return $this->hasOne(Reason::className(), ['reason_id' => 'id_reason'])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveakts() {
        return $this->hasMany(Recoveryrecieveakt::className(), ['id_osmotrakt' => 'osmotrakt_id'])->inverseOf('idOsmotrakt');
    }

}
