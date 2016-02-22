<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "importmaterial".
 *
 * @property integer $importmaterial_id
 * @property string $importmaterial_combination
 * @property integer $id_matvid
 *
 * @property Matvid $idMatv
 */
class Importmaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'importmaterial';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['importmaterial_combination', 'id_matvid'], 'required'],
            [['id_matvid'], 'integer'],
            [['importmaterial_combination'], 'string', 'max' => 255],
            [['importmaterial_combination'], 'unique', 'message' => '{attribute} = {value} уже существует'],            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'importmaterial_id' => 'Importmaterial ID',
            'importmaterial_combination' => 'Словосочетание',
            'id_matvid' => 'Вид',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdmatvid()
    {
        return $this->hasOne(Matvid::className(), ['matvid_id' => 'id_matvid']);
    }
}
