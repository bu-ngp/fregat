<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_mat_osmotr".
 *
 * @property string $tr_mat_osmotr_id
 * @property string $id_tr_mat
 * @property string $id_osmotraktmat
 *
 * @property Recoveryrecieveaktmat[] $recoveryrecieveaktmats
 * @property Osmotraktmat $idOsmotraktmat
 * @property TrMat $idTrMat
 */
class TrMatOsmotr extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_mat_osmotr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tr_mat', 'id_osmotraktmat'], 'required'],
            [['id_tr_mat', 'id_osmotraktmat'], 'integer'],
            [['id_osmotraktmat'], 'exist', 'skipOnError' => true, 'targetClass' => Osmotraktmat::className(), 'targetAttribute' => ['id_osmotraktmat' => 'osmotraktmat_id']],
            [['id_tr_mat'], 'exist', 'skipOnError' => true, 'targetClass' => TrMat::className(), 'targetAttribute' => ['id_tr_mat' => 'tr_mat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_mat_osmotr_id' => 'Tr Mat Osmotr ID',
            'id_tr_mat' => 'Материал',
            'id_osmotraktmat' => 'Акт осмотра материала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveaktmats()
    {
        return $this->hasMany(Recoveryrecieveaktmat::className(), ['id_tr_mat_osmotr' => 'tr_mat_osmotr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOsmotraktmat()
    {
        return $this->hasOne(Osmotraktmat::className(), ['osmotraktmat_id' => 'id_osmotraktmat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrMat()
    {
        return $this->hasOne(TrMat::className(), ['tr_mat_id' => 'id_tr_mat']);
    }
}
