<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "spismatmaterials".
 *
 * @property string $spismatmaterials_id
 * @property string $id_spismat
 * @property string $id_mattraffic
 *
 * @property Mattraffic $idMattraffic
 * @property Spismat $idSpismat
 */
class Spismatmaterials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spismatmaterials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_spismat', 'id_mattraffic'], 'required'],
            [['id_spismat', 'id_mattraffic'], 'integer'],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_spismat'], 'exist', 'skipOnError' => true, 'targetClass' => Spismat::className(), 'targetAttribute' => ['id_spismat' => 'spismat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spismatmaterials_id' => 'Spismatmaterials ID',
            'id_spismat' => 'Id Spismat',
            'id_mattraffic' => 'Материал',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->from(['idMattraffic' => Mattraffic::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSpismat()
    {
        return $this->hasOne(Spismat::className(), ['spismat_id' => 'id_spismat'])->from(['idSpismat' => Spismat::tableName()]);
    }
}
