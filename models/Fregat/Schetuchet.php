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
            //  [['schetuchet_kod'], 'unique', 'targetAttribute' => ['schetuchet_kod', 'schetuchet_name'], 'message' => '{attribute} = {value} уже существует'],
            [['schetuchet_name'], 'UniqueSchet'],
            [['schetuchet_kod', 'schetuchet_name'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
            [['schetuchet_kod', 'schetuchet_name'], 'filter', 'filter' => function ($value) {
                return mb_strtoupper($value, 'UTF-8');
            }],
        ];
    }

    // Проверка на уникальность счета учета
    public function UniqueSchet($attribute)
    {
        $result = 0;
        if ($this->isNewRecord)
            $result = self::find()
                ->andWhere(['in', 'schetuchet_kod', $this->schetuchet_kod])
                ->andWhere(['in', 'schetuchet_name', $this->schetuchet_name,])
                ->count();
        if ($result > 0)
            $this->addError($attribute, 'Такой счет учета уже имеется в базе данных.');
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
