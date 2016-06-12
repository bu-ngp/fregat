<?php

namespace app\models\Base;

use Yii;

/**
 * This is the model class for table "class_mkb".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $parent_id
 * @property string $parent_code
 * @property integer $node_count
 * @property string $additional_info
 *
 * @property Classmkb $parent
 * @property Classmkb[] $classmkbs
 * @property Glaukuchet[] $glaukuchets
 */
class Classmkb extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class_mkb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code'], 'required'],
            [['parent_id', 'node_count'], 'integer'],
            [['additional_info'], 'string'],
            [['name'], 'string', 'max' => 512],
            [['code', 'parent_code'], 'string', 'max' => 20],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classmkb::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'PK',
            'name' => 'Наименование диагноза',
            'code' => 'Код МКБ10',
            'parent_id' => 'Вышестоящий объект',
            'parent_code' => 'Код вышестоящего объекта',
            'node_count' => 'Количество вложенных в текущую ветку',
            'additional_info' => 'Дополнительные данные по диагнозу',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Classmkb::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassmkbs()
    {
        return $this->hasMany(Classmkb::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlaukuchets()
    {
        return $this->hasMany(Glaukuchet::className(), ['id_class_mkb' => 'id']);
    }
}
