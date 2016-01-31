<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employee".
 *
 * @property integer $employee_id
 * @property string $employee_fio
 * @property integer $id_dolzh
 * @property integer $id_podraz
 * @property integer $id_build
 *
 * @property Build $idBuild
 * @property Dolzh $idDolzh
 * @property Podraz $idPodraz
 * @property Impemployee[] $impemployees
 * @property Installakt[] $installakts
 * @property Mattraffic[] $mattraffics
 * @property Osmotrakt[] $osmotrakts
 * @property Osmotrakt[] $osmotrakts0
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_fio', 'id_dolzh', 'id_podraz'], 'required'],
            [['id_dolzh', 'id_podraz', 'id_build'], 'integer'],
            [['employee_fio'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Employee ID',
            'employee_fio' => 'Employee Fio',
            'id_dolzh' => 'Id Dolzh',
            'id_podraz' => 'Id Podraz',
            'id_build' => 'Id Build',
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
    public function getIdDolzh()
    {
        return $this->hasOne(Dolzh::className(), ['dolzh_id' => 'id_dolzh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPodraz()
    {
        return $this->hasOne(Podraz::className(), ['podraz_id' => 'id_podraz']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImpemployees()
    {
        return $this->hasMany(Impemployee::className(), ['id_employee' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstallakts()
    {
        return $this->hasMany(Installakt::className(), ['id_installer' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMattraffics()
    {
        return $this->hasMany(Mattraffic::className(), ['id_mol' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts()
    {
        return $this->hasMany(Osmotrakt::className(), ['id_user' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts0()
    {
        return $this->hasMany(Osmotrakt::className(), ['id_master' => 'employee_id']);
    }
}
