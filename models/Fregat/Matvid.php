<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "matvid".
 *
 * @property integer $matvid_id
 * @property string $matvid_name
 *
 * @property Grupavid[] $grupavs
 * @property Importmaterial[] $importmaterials
 * @property Material[] $materials
 */
class Matvid extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'matvid';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['matvid_name'], 'required'],
            [['matvid_name'], 'string', 'max' => 255],
            [['matvid_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['matvid_name'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'matvid_id' => 'Matvid ID',
            'matvid_name' => 'Вид материальной ценности',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupavids() {
        return $this->hasMany(Grupavid::className(), ['id_matvid' => 'matvid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImportmaterials() {
        return $this->hasMany(Importmaterial::className(), ['id_matvid' => 'matvid_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials() {
        return $this->hasMany(Material::className(), ['id_matvid' => 'matvid_id']);
    }

}
