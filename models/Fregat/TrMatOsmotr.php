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
class TrMatOsmotr extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tr_mat_osmotr';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_tr_mat', 'id_osmotraktmat', 'tr_mat_osmotr_number'], 'required'],
            [['id_tr_mat', 'id_osmotraktmat', 'id_reason'], 'integer'],
            [['tr_mat_osmotr_comment'], 'string', 'max' => 400],
            [['tr_mat_osmotr_number'], 'default', 'value' => 1],
            [['tr_mat_osmotr_number'], 'double', 'min' => 0, 'max' => 10000000000],
            [['id_osmotraktmat'], 'exist', 'skipOnError' => true, 'targetClass' => Osmotraktmat::className(), 'targetAttribute' => ['id_osmotraktmat' => 'osmotraktmat_id']],
            [['id_tr_mat'], 'exist', 'skipOnError' => true, 'targetClass' => TrMat::className(), 'targetAttribute' => ['id_tr_mat' => 'tr_mat_id']],
            [['id_reason'], 'exist', 'skipOnError' => true, 'targetClass' => Reason::className(), 'targetAttribute' => ['id_reason' => 'reason_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tr_mat_osmotr_id' => 'Tr Mat Osmotr ID',
            'id_tr_mat' => 'Материал',
            'tr_mat_osmotr_comment' => 'Описание причины неисправности',
            'id_reason' => 'Причина неисправности',
            'id_osmotraktmat' => 'Акт осмотра материала',
            'tr_mat_osmotr_number' => 'Количество осмотренного материала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveaktmats() {
        return $this->hasMany(Recoveryrecieveaktmat::className(), ['id_tr_mat_osmotr' => 'tr_mat_osmotr_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOsmotraktmat() {
        return $this->hasOne(Osmotraktmat::className(), ['osmotraktmat_id' => 'id_osmotraktmat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrMat() {
        return $this->hasOne(TrMat::className(), ['tr_mat_id' => 'id_tr_mat']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReason() {
        return $this->hasOne(Reason::className(), ['reason_id' => 'id_reason']);
    }

    public static function getMolsByTrMatOsmotr($Osmotraktmat_id) {
        if (is_integer($Osmotraktmat_id)) {
            return self::find()
                            ->select(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
                            ->joinWith([
                            ])
                            ->andWhere(['id_osmotraktmat' => $Osmotraktmat_id])
                            ->groupBy(['idMol.id_person', 'idMol.id_dolzh'])
                            ->asArray()
                            ->all();
        }
    }

}
