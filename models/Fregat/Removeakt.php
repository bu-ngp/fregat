<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "removeakt".
 *
 * @property string $removeakt_id
 * @property string $removeakt_date
 * @property integer $id_remover
 *
 * @property Employee $idRemover
 * @property TrRmMat[] $trRmMats
 */
class Removeakt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'removeakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['removeakt_date', 'id_remover'], 'required'],
            [['removeakt_date'], 'safe'],
            [['id_remover'], 'integer'],
            [['id_remover'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_remover' => 'employee_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'removeakt_id' => 'Removeakt ID',
            'removeakt_date' => 'Дата снятия материала',
            'id_remover' => 'Демонтировщик',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRemover()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_remover']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrRmMats()
    {
        return $this->hasMany(TrRmMat::className(), ['id_removeakt' => 'removeakt_id']);
    }
}
