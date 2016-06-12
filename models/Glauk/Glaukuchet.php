<?php

namespace app\models\Glauk;

use Yii;
use app\models\Fregat\Employee;
use app\models\Base\Classmkb;
use app\models\Base\Patient;

/**
 * This is the model class for table "glaukuchet".
 *
 * @property string $glaukuchet_id
 * @property string $glaukuchet_uchetbegin
 * @property integer $glaukuchet_detect
 * @property string $glaukuchet_deregdate
 * @property integer $glaukuchet_deregreason
 * @property integer $glaukuchet_stage
 * @property string $glaukuchet_operdate
 * @property integer $glaukuchet_rlocat
 * @property integer $glaukuchet_invalid
 * @property string $glaukuchet_lastvisit
 * @property string $glaukuchet_lastmetabol
 * @property string $id_patient
 * @property integer $id_employee
 * @property integer $id_class_mkb
 * @property string $glaukuchet_comment
 * @property string $glaukuchet_username
 * @property string $glaukuchet_lastchange
 *
 * @property ClassMkb $idClassMkb
 * @property Employee $idEmployee
 * @property Patient $idPatient
 * @property Glprep[] $glpreps
 */
class Glaukuchet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'glaukuchet';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['glaukuchet_uchetbegin', 'glaukuchet_detect', 'glaukuchet_stage', 'glaukuchet_lastvisit', 'id_patient', 'id_employee', 'id_class_mkb', 'glaukuchet_username'], 'required'],
            [['glaukuchet_uchetbegin', 'glaukuchet_deregdate', 'glaukuchet_operdate', 'glaukuchet_lastvisit', 'glaukuchet_lastmetabol', 'glaukuchet_lastchange'], 'safe'],
            [['glaukuchet_detect', 'glaukuchet_deregreason', 'glaukuchet_stage', 'glaukuchet_rlocat', 'glaukuchet_invalid', 'id_patient', 'id_employee', 'id_class_mkb'], 'integer'],
            [['glaukuchet_comment'], 'string', 'max' => 512],
            [['glaukuchet_username'], 'string', 'max' => 128],
            [['id_class_mkb'], 'exist', 'skipOnError' => true, 'targetClass' => ClassMkb::className(), 'targetAttribute' => ['id_class_mkb' => 'id']],
            [['id_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_employee' => 'employee_id']],
            [['id_patient'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['id_patient' => 'patient_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'glaukuchet_id' => 'Glaukuchet ID',
            'glaukuchet_uchetbegin' => 'Дата постановки на учет',
            'glaukuchet_detect' => 'Вид выявления заболевания',
            'glaukuchet_deregdate' => 'Дата снятия с учета',
            'glaukuchet_deregreason' => 'Причина снятия с учета',
            'glaukuchet_stage' => 'Стадия глаукомы',
            'glaukuchet_operdate' => 'Дата последнего оперативного лечения',
            'glaukuchet_rlocat' => 'Категория РЛО',
            'glaukuchet_invalid' => 'Группа инвалидности',
            'glaukuchet_lastvisit' => 'Дата последней явки на прием',
            'glaukuchet_lastmetabol' => 'Дата последнего курса метоболической терапии',
            'id_patient' => 'Пациент',
            'id_employee' => 'Врач',
            'id_class_mkb' => 'Диагноз',
            'glaukuchet_comment' => 'Заметка',
            'glaukuchet_username' => 'Пользователь изменивший запись',
            'glaukuchet_lastchange' => 'Дата изменения записи',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClassMkb()
    {
        return $this->hasOne(ClassMkb::className(), ['id' => 'id_class_mkb']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEmployee()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPatient()
    {
        return $this->hasOne(Patient::className(), ['patient_id' => 'id_patient']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlpreps()
    {
        return $this->hasMany(Glprep::className(), ['id_glaukuchet' => 'glaukuchet_id']);
    }
}
