<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_rm_mat".
 *
 * @property string $tr_rm_mat_id
 * @property string $id_removeakt
 * @property string $id_tr_mat
 *
 * @property TrMat $idTrMat
 * @property Removeakt $idRemoveakt
 */
class TrRmMat extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'tr_rm_mat';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_removeakt', 'id_tr_mat'], 'required'],
            [['id_removeakt', 'id_tr_mat'], 'integer'],
            [['id_tr_mat'], 'exist', 'skipOnError' => true, 'targetClass' => TrMat::className(), 'targetAttribute' => ['id_tr_mat' => 'tr_mat_id']],
            [['id_removeakt'], 'exist', 'skipOnError' => true, 'targetClass' => Removeakt::className(), 'targetAttribute' => ['id_removeakt' => 'removeakt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'tr_rm_mat_id' => 'Tr Rm Mat ID',
            'id_removeakt' => 'Акт демонтирования материальной ценности',
            'id_tr_mat' => 'Материальная ценность',
        ];
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
    public function getIdRemoveakt() {
        return $this->hasOne(Removeakt::className(), ['removeakt_id' => 'id_removeakt']);
    }

}
