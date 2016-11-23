<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "naklad".
 *
 * @property string $naklad_id
 * @property string $naklad_date
 * @property integer $id_mol_release
 * @property integer $id_mol_got
 *
 * @property Employee $idMolRelease
 * @property Employee $idMolGot
 * @property Nakladmaterials[] $nakladmaterials
 */
class Naklad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'naklad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['naklad_date', 'id_mol_release', 'id_mol_got'], 'required'],
            ['naklad_date', 'date', 'format' => 'yyyy-MM-dd'],
            [['id_mol_release', 'id_mol_got'], 'integer'],
            [['id_mol_release'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_mol_release' => 'employee_id']],
            [['id_mol_got'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_mol_got' => 'employee_id']],
            [['naklad_date'], 'unique', 'targetAttribute' => ['naklad_date', 'id_mol_release', 'id_mol_got'], 'message' => 'На эту дату уже имеется Требование-накладная с этими МОЛ\'ами'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'naklad_id' => 'Номер требования-накладной',
            'naklad_date' => 'Дата требования-накладной',
            'id_mol_release' => 'МОЛ, кто отпустил',
            'id_mol_got' => 'МОЛ, кто затребовал',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMolRelease()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_mol_release'])->from(['idMolRelease' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMolGot()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_mol_got'])->from(['idMolGot' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNakladmaterials()
    {
        return $this->hasMany(Nakladmaterials::className(), ['id_naklad' => 'naklad_id'])->from(['nakladmaterials' => Nakladmaterials::tableName()]);
    }
}
