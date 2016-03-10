<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "mattraffic".
 *
 * @property string $mattraffic_id
 * @property string $mattraffic_date
 * @property string $mattraffic_number
 * @property string $id_material
 * @property integer $id_mol
 *
 * @property Employee $idMol
 * @property Material $idMaterial
 * @property Osmotrakt[] $osmotrakts
 * @property TrMat[] $trMats
 * @property TrOsnov[] $trOsnovs
 * @property Writeoffakt[] $writeoffakts
 */
class Mattraffic extends \yii\db\ActiveRecord {

    public $recordapply;
    public $diff_number;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mattraffic';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mattraffic_date', 'id_material', 'id_mol'], 'required'],
            [['mattraffic_date'], 'safe'],
            ['mattraffic_number', 'double', 'min' => 0, 'max' => 10000000000],
            [['id_material', 'id_mol'], 'integer'],
            ['mattraffic_date', 'unique', 'targetAttribute' => ['mattraffic_date', 'id_material', 'id_mol'], 'message' => 'На эту дату уже есть запись с этой матер. цен-ю и ответств. лицом'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mattraffic_id' => 'Mattraffic ID',
            'mattraffic_date' => 'Дата операции',
            'mattraffic_number' => 'Количество (Задействованное в операции)',
            'id_material' => 'Материальная ценность',
            'id_mol' => 'Материально-ответственное лицо',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMol() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_mol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaterial() {
        return $this->hasOne(Material::className(), ['material_id' => 'id_material']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts() {
        return $this->hasMany(Osmotrakt::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMats() {
        return $this->hasMany(TrMat::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs() {
        return $this->hasMany(TrOsnov::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriteoffakts() {
        return $this->hasMany(Writeoffakt::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

}