<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "{{%cabinet}}".
 *
 * @property integer $cabinet_id
 * @property integer $id_build
 * @property string $cabinet_name
 *
 * @property Build $idBuild
 * @property TrOsnov[] $trOsnovs
 */
class Cabinet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cabinet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_build'], 'integer'],
            [['cabinet_name'], 'string', 'max' => 255],
            [['id_build', 'cabinet_name'], 'unique', 'targetAttribute' => ['id_build', 'cabinet_name'], 'message' => 'В здании уже имеется этот кабинет.'],
            [['id_build'], 'exist', 'skipOnError' => true, 'targetClass' => Build::className(), 'targetAttribute' => ['id_build' => 'build_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cabinet_id' => 'Cabinet ID',
            'id_build' => 'Здание',
            'cabinet_name' => 'Кабинет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdBuild()
    {
        return $this->hasOne(Build::className(), ['build_id' => 'id_build']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs()
    {
        return $this->hasMany(TrOsnov::className(), ['id_cabinet' => 'cabinet_id']);
    }

    public static function getCabinetByID($ID)
    {
        return $query = self::findOne($ID)->cabinet_name;
    }
}
