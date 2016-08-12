<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_rm_mat".
 *
 * @property string $tr_rm_mat_id
 * @property string $id_removeakt
 * @property string $id_mattraffic
 *
 * @property Mattraffic $idMattraffic
 * @property Removeakt $idRemoveakt
 */
class TrRmMat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_rm_mat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_removeakt', 'id_mattraffic'], 'required'],
            [['id_removeakt', 'id_mattraffic'], 'integer'],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_removeakt'], 'exist', 'skipOnError' => true, 'targetClass' => Removeakt::className(), 'targetAttribute' => ['id_removeakt' => 'removeakt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_rm_mat_id' => 'Tr Rm Mat ID',
            'id_removeakt' => 'Акт демонтирования материальной ценности',
            'id_mattraffic' => 'Материальная ценность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRemoveakt()
    {
        return $this->hasOne(Removeakt::className(), ['removeakt_id' => 'id_removeakt']);
    }
}
