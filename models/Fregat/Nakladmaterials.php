<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "nakladmaterials".
 *
 * @property string $nakladmaterials_id
 * @property string $id_naklad
 * @property string $id_mattraffic
 * @property string $nakladmaterials_number
 *
 * @property Mattraffic $idMattraffic
 * @property Naklad $idNaklad
 */
class Nakladmaterials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nakladmaterials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_naklad', 'id_mattraffic', 'nakladmaterials_number'], 'required'],
            [['id_naklad', 'id_mattraffic'], 'integer'],
            ['nakladmaterials_number', 'double', 'min' => 0, 'max' => 10000000000],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_naklad'], 'exist', 'skipOnError' => true, 'targetClass' => Naklad::className(), 'targetAttribute' => ['id_naklad' => 'naklad_id']],
            [['id_mattraffic'], 'unique', 'targetAttribute' => ['id_naklad', 'id_mattraffic'], 'message' => 'Данная материальная ценность уже имеется в требовании-накладной.'],
            ['nakladmaterials_number', 'MaxNumberMaterial'],
            ['id_mattraffic', 'AccessAddMaterial'],
        ];
    }

    // Проверяет количество осмотреного материала, которое не должно превышать количество перемещеного материала в рамках одного МОЛа
    public function MaxNumberMaterial($attribute)
    {
        if ($this->nakladmaterials_number > Mattraffic::findOne($this->id_mattraffic)->mattraffic_number)
            $this->addError($attribute, 'Максимально допустимое количество у этого МОЛ равно ' . Mattraffic::findOne($this->id_mattraffic)->mattraffic_number);
    }

    public function AccessAddMaterial($attribute)
    {
        if ($this->idNaklad->id_mol_release != $this->idMattraffic->id_mol)
            $this->addError($attribute, 'Материальная ценность не соответствует МОЛ требования-накладной: ' . $this->idNaklad->idMolRelease->idperson->shortName . ', ' . $this->idNaklad->idMolRelease->iddolzh->dolzh_name . ', ' . $this->idNaklad->idMolRelease->idpodraz->podraz_name . ', ' . $this->idNaklad->idMolRelease->idbuild->build_name);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nakladmaterials_id' => 'ИД',
            'id_naklad' => 'Требование-накладная',
            'id_mattraffic' => 'Материальная ценность',
            'nakladmaterials_number' => 'Количество',
            'nakladmaterials_sum' => 'Сумма',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->from(['idMattraffic' => Mattraffic::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdNaklad()
    {
        return $this->hasOne(Naklad::className(), ['naklad_id' => 'id_naklad'])->from(['idNaklad' => Naklad::tableName()]);
    }

    public function getNakladmaterials_sum()
    {
        $mult = $this->idMattraffic->idMaterial->material_price * $this->nakladmaterials_number;
        return !empty($mult) ? $mult : '';
    }
}
