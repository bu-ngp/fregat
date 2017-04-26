<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "spismat".
 *
 * @property string $spismat_id
 * @property integer $id_mol
 * @property string $spismat_date
 *
 * @property Employee $idMol
 * @property Spismatmaterials[] $spismatmaterials
 */
class Spismat extends \yii\db\ActiveRecord
{
    public $spismat_spisinclude;
    public $period;
    public $period_beg;
    public $period_end;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'spismat';
    }

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'period_beg',
            'period_end',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_mol', 'spismat_date'], 'required'],
            [['id_mol', 'spismat_spisinclude'], 'integer'],
            [['spismat_date', 'period_beg', 'period_end'], 'date', 'format' => 'yyyy-MM-dd'],
            [['spismat_date', 'period_beg', 'period_end'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение {attribute} должно быть меньше или равно значения «' . Yii::$app->formatter->asDate(date('Y-m-d')) . '».'],
            //   [['period_beg'], 'compare', 'compareAttribute' => 'period_end', 'operator' => '<=', 'message' => 'Дата начала периода должна быть меньше или равно даты окончания периода'],
            [['period_beg', 'period_end'], 'DateRangeValid'],
            [['id_mol'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_mol' => 'employee_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spismat_id' => 'Номер ведомости',
            'id_mol' => 'Материально-ответственное лицо',
            'spismat_date' => 'Дата ведомости',
            'spismat_spisinclude' => 'Включить в ведомость списанные материалы',
            'period' => 'Включить в ведомость материалы, установленные в данный период',
            'period_beg' => 'Начало перода',
            'period_end' => 'Окончание периода',
        ];
    }

    public function DateRangeValid($attribute)
    {
        if (empty($this->period_beg))
            $this->addError($attribute, 'Заполните дату начала периода');

        if (empty($this->period_end))
            $this->addError($attribute, 'Заполните дату окончания периода');

        if (!empty($this->period_beg) && !empty($this->period_end)) {
            if ($this->period_beg > $this->period_end)
                $this->addError($attribute, 'Дата начала периода не может быть больше даты окончания');
        }
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
    public function getSpismatmaterials()
    {
        return $this->hasMany(Spismatmaterials::className(), ['id_spismat' => 'spismat_id'])->from(['spismatmaterials' => Spismatmaterials::tableName()]);
    }
}
