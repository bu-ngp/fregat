<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "grupavid".
 *
 * @property integer $grupavid_id
 * @property integer $grupavid_main
 * @property integer $id_grupa
 * @property integer $id_matvid
 *
 * @property Grupa $idGrupa
 * @property Matvid $idMatv
 */
class Grupavid extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'grupavid';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['grupavid_main', 'id_grupa', 'id_matvid'], 'integer'],
            [['id_grupa', 'id_matvid'], 'required'],
            ['id_grupa', 'unique', 'targetAttribute' => ['id_grupa', 'id_matvid']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'grupavid_id' => 'Grupavid ID',
            'grupavid_main' => 'Основная',
            'id_grupa' => 'Id Grupa',
            'id_matvid' => 'Id Matvid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdgrupa() {
        return $this->hasOne(Grupa::className(), ['grupa_id' => 'id_grupa'])->from(['idgrupa' => Grupa::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdmatvid() {
        return $this->hasOne(Matvid::className(), ['matvid_id' => 'id_matvid'])->from(['idmatvid' => Matvid::tableName()]);
    }

    public static function VariablesValues($attribute) {
        $values = [
            'grupavid_main' => [0 => 'Нет', 1 => 'Да'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
