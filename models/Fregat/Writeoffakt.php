<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "writeoffakt".
 *
 * @property string $writeoffakt_id
 * @property string $id_mattraffic
 *
 * @property Mattraffic $idMattraffic
 */
class Writeoffakt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'writeoffakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_mattraffic'], 'required'],
            [['id_mattraffic'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'writeoffakt_id' => 'Writeoffakt ID',
            'id_mattraffic' => 'Id Mattraffic',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic']);
    }
}
