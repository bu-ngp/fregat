<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "schetuchet".
 *
 * @property integer $schetuchet_id
 * @property string $schetuchet_kod
 * @property string $schetuchet_name
 *
 * @property Spisosnovakt[] $spisosnovakts
 */
class Schetuchet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schetuchet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schetuchet_kod', 'schetuchet_name'], 'required'],
            [['schetuchet_kod'], 'string', 'max' => 50],
            [['schetuchet_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schetuchet_id' => 'Schetuchet ID',
            'schetuchet_kod' => 'Счет учета',
            'schetuchet_name' => 'Расшифровка счета учета',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpisosnovakts()
    {
        return $this->hasMany(Spisosnovakt::className(), ['id_schetuchet' => 'schetuchet_id'])->from(['spisosnovakts' => Spisosnovakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials()
    {
        return $this->hasMany(Material::className(), ['id_schetuchet' => 'schetuchet_id'])->from(['spisosnovakts' => Material::tableName()]);
    }
}
