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
class Osmotrakt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'osmotrakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_reason', 'id_user', 'id_master', 'id_mattraffic'], 'integer'],
            [['id_user', 'id_master', 'id_mattraffic'], 'required'],
            [['osmotrakt_comment'], 'string', 'max' => 400],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_user' => 'employee_id']],
            [['id_master'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_master' => 'employee_id']],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_reason'], 'exist', 'skipOnError' => true, 'targetClass' => Reason::className(), 'targetAttribute' => ['id_reason' => 'reason_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'osmotrakt_id' => 'Osmotrakt ID',
            'osmotrakt_comment' => 'Описание причины',
            'id_reason' => 'Причина поломки',
            'id_user' => 'Пользователь оборудования',
            'id_master' => 'Составитель акта',
            'id_mattraffic' => 'Id Mattraffic',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_user'])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaster()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_master'])->inverseOf('osmotrakts0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReason()
    {
        return $this->hasOne(Reason::className(), ['reason_id' => 'id_reason'])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveakts()
    {
        return $this->hasMany(Recoveryrecieveakt::className(), ['id_osmotrakt' => 'osmotrakt_id'])->inverseOf('idOsmotrakt');
    }
}
