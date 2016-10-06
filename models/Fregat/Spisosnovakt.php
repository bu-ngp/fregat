<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "spisosnovakt".
 *
 * @property string $spisosnovakt_id
 * @property string $spisosnovakt_date
 * @property integer $id_schetuchet
 * @property integer $id_mol
 * @property integer $id_employee
 *
 * @property Employee $idMol
 * @property Employee $idEmployee
 * @property Schetuchet $idSchetuchet
 * @property Spisosnovmaterials[] $spisosnovmaterials
 */
class Spisosnovakt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spisosnovakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spisosnovakt_date', 'id_schetuchet', 'id_mol'], 'required'],
            [['spisosnovakt_date'], 'safe'],
            [['id_schetuchet', 'id_mol', 'id_employee'], 'integer'],
            [['id_mol'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_mol' => 'employee_id']],
            [['id_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_employee' => 'employee_id']],
            [['id_schetuchet'], 'exist', 'skipOnError' => true, 'targetClass' => Schetuchet::className(), 'targetAttribute' => ['id_schetuchet' => 'schetuchet_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spisosnovakt_id' => 'Номер заявки на списания основных средств',
            'spisosnovakt_date' => 'Дата заявки',
            'id_schetuchet' => 'Счет учета',
            'id_mol' => 'Материально-ответственное лицо',
            'id_employee' => 'Иное ответственное лицо',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMol()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_mol'])->from(['idMol' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEmployee()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_employee'])->from(['idEmployee' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSchetuchet()
    {
        return $this->hasOne(Schetuchet::className(), ['schetuchet_id' => 'id_schetuchet'])->from(['idSchetuchet' => Schetuchet::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpisosnovmaterials()
    {
        return $this->hasMany(Spisosnovmaterials::className(), ['id_spisosnovakt' => 'spisosnovakt_id'])->from(['spisosnovmaterials' => Spisosnovmaterials::tableName()]);
    }
}
