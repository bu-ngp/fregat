<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "importemployee".
 *
 * @property integer $importemployee_id
 * @property string $importemployee_combination
 * @property integer $id_build
 * @property integer $id_podraz
 *
 * @property Impemployee[] $impemployees
 * @property Build $idBuild
 * @property Podraz $idPodraz
 */
class Importemployee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'importemployee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['importemployee_combination', 'id_podraz'], 'required'],
            [['id_build', 'id_podraz'], 'integer'],
            [['importemployee_combination'], 'string', 'max' => 255],
            ['importemployee_combination', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'importemployee_id' => 'Importemployee ID',
            'importemployee_combination' => 'Словосочетание',
            'id_build' => 'Здание',
            'id_podraz' => 'Подразделение',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImpemployees()
    {
        return $this->hasMany(Impemployee::className(), ['id_importemployee' => 'importemployee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdbuild()
    {
        return $this->hasOne(Build::className(), ['build_id' => 'id_build']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdpodraz()
    {
        return $this->hasOne(Podraz::className(), ['podraz_id' => 'id_podraz']);
    }
}
