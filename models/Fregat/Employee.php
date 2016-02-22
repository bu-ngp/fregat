<?php

namespace app\models\Fregat;

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
            [['employee_fio'], 'string', 'max' => 255],
            [['employee_fio'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Код',
            'employee_fio' => 'Фамилия Имя Отчество',
            'id_dolzh' => 'Должность',
            'id_podraz' => 'Подразделение',
            'id_build' => 'Здание',
        ];
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
    public function getIddolzh()
    {
        return $this->hasOne(Dolzh::className(), ['dolzh_id' => 'id_dolzh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdpodraz()
    {
        return $this->hasOne(Podraz::className(), ['podraz_id' => 'id_podraz']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getimpemployees()
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
