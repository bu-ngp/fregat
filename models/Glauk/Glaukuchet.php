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
class Glaukuchet extends \yii\db\ActiveRecord {
    
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'glaukuchet';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['glaukuchet_username'], 'filter', 'filter' => function($value) {
            return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
        }],
            [['glaukuchet_uchetbegin', 'glaukuchet_detect', 'glaukuchet_stage', 'glaukuchet_lastvisit', 'id_employee', 'id_class_mkb', 'glaukuchet_username'], 'required'],
            [['id_patient'], 'required', 'except' => 'forvalidatewithout_id_patient'],
            [['glaukuchet_uchetbegin', 'glaukuchet_deregdate', 'glaukuchet_operdate', 'glaukuchet_lastvisit', 'glaukuchet_lastmetabol', 'glaukuchet_lastchange'], 'safe'],
            [['glaukuchet_detect', 'glaukuchet_deregreason', 'glaukuchet_stage', 'glaukuchet_invalid', 'id_patient', 'id_employee', 'id_class_mkb'], 'integer'],
            [['glaukuchet_comment'], 'string', 'max' => 512],
            [['glaukuchet_username'], 'string', 'max' => 128],
            [['id_class_mkb'], 'exist', 'skipOnError' => true, 'targetClass' => ClassMkb::className(), 'targetAttribute' => ['id_class_mkb' => 'id']],
            [['id_employee'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_employee' => 'employee_id']],
            [['id_patient'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['id_patient' => 'patient_id']],
            ['id_employee', 'CheckBuildByVrach'],
            [['glaukuchet_uchetbegin', 'glaukuchet_lastvisit', 'glaukuchet_operdate', 'glaukuchet_lastmetabol', 'glaukuchet_deregdate'], 'date', 'format' => 'php:Y-m-d'],
            [['glaukuchet_uchetbegin', 'glaukuchet_lastvisit', 'glaukuchet_operdate', 'glaukuchet_lastmetabol'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение должно быть меньше или равно значения "' . Yii::$app->formatter->asDate(date('d.m.Y')) . '"'],
            [['glaukuchet_lastchange'], 'date', 'format' => 'php:Y-m-d H:i:s', 'type' => 'datetime'],
            [['glaukuchet_deregdate'], 'compare', 'compareAttribute' => 'glaukuchet_uchetbegin', 'operator' => '>=', 'message' => 'Дата снятия с учета меньше даты постановки на учет'],
            [['glaukuchet_deregdate', 'glaukuchet_deregreason'], 'CheckDereg'],
        ];
    }

    public function CheckBuildByVrach($attribute, $params) {
        if (is_string($attribute)) {
            $ssf2 = Employee::findOne($this->$attribute)->idperson->auth_user_fullname;
            if (!isset(Employee::findOne($this->$attribute)->id_build))
                $this->addError($attribute, 'У врача "' . Employee::findOne($this->$attribute)->idperson->auth_user_fullname . '" не заполнено здание, к которому врач относится');
        }
    }

    // Проверяет заполнены ли атрибуты "Дата снятия с учета" и "Причина снятия с учета" при снятии с учета регистра
    public function CheckDereg($attribute, $params) {
        if (!(empty($this->glaukuchet_deregdate) && empty($this->glaukuchet_deregreason))) {
            if (empty($this->glaukuchet_deregdate))
                $this->addError('glaukuchet_deregdate', 'При снятии с учета пациента должны быть заполнены "Дата снятия с учета" и "Причина снятия с учета"');
            elseif (empty($this->glaukuchet_deregreason))
                $this->addError('glaukuchet_deregreason', 'При снятии с учета пациента должны быть заполнены "Дата снятия с учета" и "Причина снятия с учета"');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'glaukuchet_id' => 'Glaukuchet ID',
            'glaukuchet_uchetbegin' => 'Дата постановки на учет',
            'glaukuchet_detect' => 'Вид выявления заболевания',
            'glaukuchet_deregdate' => 'Дата снятия с учета',
            'glaukuchet_deregreason' => 'Причина снятия с учета',
            'glaukuchet_stage' => 'Стадия глаукомы',
            'glaukuchet_operdate' => 'Дата последнего оперативного лечения',
            'glaukuchet_invalid' => 'Группа инвалидности',
            'glaukuchet_lastvisit' => 'Дата последней явки на прием',
            'glaukuchet_lastmetabol' => 'Дата последнего курса метоболической терапии',
            'id_patient' => 'Пациент',
            'id_employee' => 'Врач',
            'id_class_mkb' => 'Диагноз',
            'glaukuchet_comment' => 'Заметка',
            'glaukuchet_username' => 'Пользователь изменивший запись карты глаукомного пациента',
            'glaukuchet_lastchange' => 'Дата изменения записи карты глаукомного пациента',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdClassMkb() {
        return $this->hasOne(ClassMkb::className(), ['id' => 'id_class_mkb']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdEmployee() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_employee']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdPatient() {
        return $this->hasOne(Patient::className(), ['patient_id' => 'id_patient']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlpreps() {
        return $this->hasMany(Glprep::className(), ['id_glaukuchet' => 'glaukuchet_id']);
    }

}
