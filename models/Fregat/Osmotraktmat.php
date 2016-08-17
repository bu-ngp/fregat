<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "osmotraktmat".
 *
 * @property string $osmotraktmat_id
 * @property string $osmotraktmat_comment
 * @property string $osmotraktmat_date
 * @property integer $id_reason
 * @property string $id_tr_mat
 * @property integer $id_master
 *
 * @property Employee $idMaster
 * @property Reason $idReason
 * @property TrMat $idTrMat
 * @property TrMatOsmotr[] $trMatOsmotrs
 */
class Osmotraktmat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'osmotraktmat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['osmotraktmat_date', 'id_tr_mat', 'id_master'], 'required'],
            [['osmotraktmat_date'], 'safe'],
            [['id_reason', 'id_tr_mat', 'id_master'], 'integer'],
            [['osmotraktmat_comment'], 'string', 'max' => 400],
            [['id_master'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_master' => 'employee_id']],
            [['id_reason'], 'exist', 'skipOnError' => true, 'targetClass' => Reason::className(), 'targetAttribute' => ['id_reason' => 'reason_id']],
            [['id_tr_mat'], 'exist', 'skipOnError' => true, 'targetClass' => TrMat::className(), 'targetAttribute' => ['id_tr_mat' => 'tr_mat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'osmotraktmat_id' => 'Номер акта осмотра',
            'osmotraktmat_comment' => 'Описание причины неисправности',
            'osmotraktmat_date' => 'Дата осмотра материала',
            'id_reason' => 'Причина неисправности',
            'id_tr_mat' => 'Материал',
            'id_master' => 'Составитель акта',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaster()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_master']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReason()
    {
        return $this->hasOne(Reason::className(), ['reason_id' => 'id_reason']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrMat()
    {
        return $this->hasOne(TrMat::className(), ['tr_mat_id' => 'id_tr_mat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMatOsmotrs()
    {
        return $this->hasMany(TrMatOsmotr::className(), ['id_osmotraktmat' => 'osmotraktmat_id']);
    }
}
