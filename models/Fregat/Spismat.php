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
