<?php

namespace app\models\Glauk;

use Yii;
use app\models\Base\Preparat;

/**
 * This is the model class for table "glprep".
 *
 * @property integer $glprep_id
 * @property string $id_glaukuchet
 * @property integer $id_preparat
 *
 * @property Glaukuchet $idGlaukuchet
 * @property Preparat $idPreparat
 */
class Glprep extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'glprep';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id_glaukuchet', 'id_preparat'], 'required'],
            [['id_glaukuchet', 'id_preparat', 'glprep_rlocat'], 'integer'],
            [['id_glaukuchet'], 'exist', 'skipOnError' => true, 'targetClass' => Glaukuchet::className(), 'targetAttribute' => ['id_glaukuchet' => 'glaukuchet_id']],
            [['id_preparat'], 'exist', 'skipOnError' => true, 'targetClass' => Preparat::className(), 'targetAttribute' => ['id_preparat' => 'preparat_id']],
            ['id_glaukuchet', 'unique', 'targetAttribute' => ['id_glaukuchet', 'id_preparat'], 'message' => 'Этот препарат уже есть у глаукомного пациента'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'glprep_id' => 'Glprep ID',
            'id_glaukuchet' => 'Карта глаукомного больного',
            'id_preparat' => 'Препарат',
            'glprep_rlocat' => 'Категория льготного лекарственного обеспечения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdGlaukuchet() {
        return $this->hasOne(Glaukuchet::className(), ['glaukuchet_id' => 'id_glaukuchet']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPreparat() {
        return $this->hasOne(Preparat::className(), ['preparat_id' => 'id_preparat']);
    }

    public static function VariablesValues($attribute, $value = NULL) {
        $values = [
            'glprep_rlocat' => [1 => 'Федеральная', 2 => 'Региональная'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
