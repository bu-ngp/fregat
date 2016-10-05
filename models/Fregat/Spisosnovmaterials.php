<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "spisosnovmaterials".
 *
 * @property string $spisosnovmaterials_id
 * @property string $id_mattraffic
 * @property string $id_spisosnovakt
 * @property string $spisosnovmaterials_number
 *
 * @property Mattraffic $idMattraffic
 * @property Spisosnovakt $idSpisosnovakt
 */
class Spisosnovmaterials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spisosnovmaterials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_mattraffic', 'id_spisosnovakt', 'spisosnovmaterials_number'], 'required'],
            [['id_mattraffic', 'id_spisosnovakt'], 'integer'],
            [['spisosnovmaterials_number'], 'number'],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_spisosnovakt'], 'exist', 'skipOnError' => true, 'targetClass' => Spisosnovakt::className(), 'targetAttribute' => ['id_spisosnovakt' => 'spisosnovakt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spisosnovmaterials_id' => 'Spisosnovmaterials ID',
            'id_mattraffic' => 'Материальная ценность',
            'id_spisosnovakt' => 'Заявка списания основных средств',
            'spisosnovmaterials_number' => 'Количество на списание',
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
    public function getIdSpisosnovakt()
    {
        return $this->hasOne(Spisosnovakt::className(), ['spisosnovakt_id' => 'id_spisosnovakt'])->from(['idSpisosnovakt' => Spisosnovakt::tableName()]);
    }
}
