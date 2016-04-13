<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "material".
 *
 * @property string $material_id
 * @property string $material_name
 * @property string $material_name1c
 * @property string $material_1c
 * @property string $material_inv
 * @property string $material_serial
 * @property string $material_release
 * @property string $material_number
 * @property string $material_price
 * @property integer $material_tip
 * @property integer $material_writeoff
 * @property integer $id_matvid
 * @property integer $id_izmer
 *
 * @property Izmer $idIzmer
 * @property Matvid $idMatv
 * @property Mattraffic[] $mattraffics
 * @property TrMat[] $trMats
 */
class Material extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'material';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['material_username'], 'filter', 'filter' => function($value) {
            return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
        }],
            [['material_username'], 'filter', 'filter' => function($value) {
            return 'IMPORT';
        }, 'on' => 'import1c'],
            [['material_name','material_number','material_price','material_name1c', 'material_tip', 'id_matvid', 'id_izmer', 'material_username'], 'required'],
            [['material_inv'], 'required', 'except' => 'import1c'],
            [['material_release'], 'safe'],
            [['material_number', 'material_price'], 'number'],
            [['material_writeoff', 'id_matvid', 'id_izmer'], 'integer'],
            [['material_name', 'material_name1c'], 'string', 'max' => 400],
            [['material_1c'], 'string', 'max' => 20],
            [['material_inv'], 'string', 'max' => 50],
            [['material_serial'], 'string', 'max' => 255],
            [['material_tip'], 'integer', 'min' => 1, 'max' => 2], // 1 - Основное средство, 2 - Материалы
            [['material_name', 'material_name1c', 'material_1c', 'material_inv', 'material_release'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
            ['material_inv', 'unique', 'targetAttribute' => ['material_inv', 'material_tip'], 'message' => '"{value}" - такой инвентарный номер уже есть у данного типа материальнной ценности'],
            [['material_1c'], 'required', 'on' => 'import1c'],
            [['material_serial'], 'match', 'pattern' => '/^null$|^б\/н$|^б\н$|^б\/н\.$|^б\н\.$|^-$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "null", "б/н", "б\н", "б/н.", "б\н.", "-"'],
            ['material_price', 'double', 'min' => 0, 'max' => 1000000000],
            ['material_number', 'double', 'min' => 0, 'max' => 10000000000],
            ['material_release', 'date', 'format' => 'yyyy-MM-dd'],
            [['material_username'], 'string', 'max' => 128],
            [['material_lastchange'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['material_importdo'], 'integer', 'min' => 0, 'max' => 1], // 0 - Материальная ценность при импорте не изменяется, 1 - Материальная ценность может быть изменена при импорте
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'material_id' => 'Material ID',
            'material_name' => 'Наименование',
            'material_name1c' => 'Наименование (Из 1С)',
            'material_1c' => 'Код 1С',
            'material_inv' => 'Инвентарный номер',
            'material_serial' => 'Серийный номер',
            'material_release' => 'Дата выпуска',
            'material_number' => 'Количество',
            'material_price' => 'Стоимость',
            'material_tip' => 'Тип',
            'material_writeoff' => 'Списан',
            'id_matvid' => 'Вид',
            'id_izmer' => 'Единица измерения',
            'material_username' => 'Пользователь изменивший запись',
            'material_lastchange' => 'Дата изменения записи',
            'material_importdo' => 'Запись изменяема при импортировании из 1С',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdIzmer() {
        return $this->hasOne(Izmer::className(), ['izmer_id' => 'id_izmer']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMatv() {
        return $this->hasOne(Matvid::className(), ['matvid_id' => 'id_matvid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMattraffics() {
        return $this->hasMany(Mattraffic::className(), ['id_material' => 'material_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMats() {
        return $this->hasMany(TrMat::className(), ['id_parent' => 'material_id']);
    }

}
