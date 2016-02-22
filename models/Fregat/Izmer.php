<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "izmer".
 *
 * @property integer $izmer_id
 * @property string $izmer_name
 *
 * @property Material[] $materials
 */
class Izmer extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'izmer';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['izmer_name'], 'required'],
            [['izmer_name'], 'string', 'max' => 255],
            [['izmer_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['izmer_name'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'izmer_id' => 'Izmer ID',
            'izmer_name' => 'Единица измерения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials() {
        return $this->hasMany(Material::className(), ['id_izmer' => 'izmer_id']);
    }

}
