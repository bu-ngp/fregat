<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "spisosnovmaterials".
 *
 * @property string $spisosnovmaterials_id
 * @property string $id_mattraffic
 * @property string $id_spisosnovakt
 * @property string $spisosnovmaterials_number
 *
 * @property Mattraffic $idMattraffic
 * @property Spisosnovakt $idSpisosnovakt
 */
class Spisosnovmaterials extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spisosnovmaterials';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_mattraffic', 'id_spisosnovakt', 'spisosnovmaterials_number'], 'required'],
            [['id_mattraffic', 'id_spisosnovakt'], 'integer'],
            [['spisosnovmaterials_number'], 'number'],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_spisosnovakt'], 'exist', 'skipOnError' => true, 'targetClass' => Spisosnovakt::className(), 'targetAttribute' => ['id_spisosnovakt' => 'spisosnovakt_id']],
            [['spisosnovmaterials_number'], 'MaxNumberSpis'],
            [['id_mattraffic'], 'unique', 'targetAttribute' => ['id_mattraffic', 'id_spisosnovakt'], 'message' => 'Данная материальная ценность уже добавлена в текущую заявку'],
            ['id_mattraffic', 'CheckAccessForAddMaterial'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spisosnovmaterials_id' => 'Spisosnovmaterials ID',
            'id_mattraffic' => 'Материальная ценность',
            'id_spisosnovakt' => 'Заявка списания основных средств',
            'spisosnovmaterials_number' => 'Количество на списание',
        ];
    }

    public function MaxNumberSpis($attribute)
    {
        if (!empty($this->id_mattraffic)) {
            $currentMattraffic = Mattraffic::findOne($this->id_mattraffic);

            $query = Mattraffic::find()
                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                ->andWhere([
                    'id_material' => $currentMattraffic->id_material,
                    'id_mol' => $currentMattraffic->id_mol,
                ])
                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                ->andWhere(['m2.mattraffic_date_m2' => NULL])
                ->one();

            if (!empty($query) && $this->spisosnovmaterials_number > $query->mattraffic_number)
                $this->addError($attribute, 'Количество не может превышать ' . $query->mattraffic_number);
        }
    }

    public function CheckAccessForAddMaterial($attribute)
    {
        $errorMes = '';
        if ($this->idSpisosnovakt->id_schetuchet != $this->idMattraffic->idMaterial->id_schetuchet)
            $errorMes .= 'Материальная ценность не соответствует счету учета, заявки на списание: ' . $this->idSpisosnovakt->idSchetuchet->schetuchet_kod;

        if ($this->idSpisosnovakt->idMol->id_person != $this->idMattraffic->idMol->id_person)
            $errorMes .= (empty($errorMes) ? '' : '. ') . 'Материальная ценность не соответствует МОЛ\'у, заявки на списание: ' . $this->idSpisosnovakt->idMol->idperson->auth_user_fullname;

        if ($this->idMattraffic->idMaterial->material_tip == Material::V_KOMPLEKTE)
            $errorMes .= 'Тип материальной ценности не может быть "В комплекте"';

        if (!empty($errorMes))
            $this->addError($attribute, $errorMes);
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
    public function getIdSpisosnovakt()
    {
        return $this->hasOne(Spisosnovakt::className(), ['spisosnovakt_id' => 'id_spisosnovakt'])->from(['idSpisosnovakt' => Spisosnovakt::tableName()]);
    }
}
