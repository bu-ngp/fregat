<?php

namespace app\models\Fregat;

use Yii;
use app\models\Config\Authuser;

/**
 * This is the model class for table "employee".
 *
 * @property integer $employee_id
 * @property integer $id_dolzh
 * @property integer $id_podraz
 * @property integer $id_build
 * @property integer $id_person
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
class Employee extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['employee_username', 'filter', 'filter' => function($value) {
                    return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
                }],
            ['employee_username', 'filter', 'filter' => function($value) {
                    return 'IMPORT';
                }, 'on' => 'import1c'],
            [['id_dolzh', 'id_podraz', 'id_person', 'employee_username'], 'required'],
            [['employee_username'], 'string', 'max' => 128],
            [['employee_lastchange'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['employee_dateinactive'], 'date', 'format' => 'dd.MM.yyyy'],
            ['employee_dateinactive', 'filter', 'filter' => function($value) {
                    return preg_replace('/(\d{2})\.(\d{2})\.(\d{4})/', '$3-$2-$1', $value);
                }],
            //  [['employee_dateinactive'], 'date', 'format' => 'yyyy-MM-dd'],
            //    [['id_person'], 'required', 'on' => ['importnewuser']],
            [['id_dolzh', 'id_podraz', 'id_build', 'id_person'], 'integer'],
            //   [['employee_fio'], 'string', 'max' => 255],
            //  [['employee_fio'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
            ['id_person', 'unique', 'targetAttribute' => ['id_person', 'id_dolzh', 'id_podraz', 'id_build'], 'message' => 'На этого сотрудника уже есть такая специальность'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'employee_id' => 'Код',
            'id_dolzh' => 'Должность',
            'id_podraz' => 'Подразделение',
            'id_build' => 'Здание',
            'id_person' => 'Сотрудник',
            'employee_username' => 'Пользователь изменивший запись',
            'employee_lastchange' => 'Дата изменения записи',
            'employee_dateinactive' => 'Дата с которой специальность неактивна',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdperson() {
        return $this->hasOne(Authuser::className(), ['auth_user_id' => 'id_person']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdbuild() {
        return $this->hasOne(Build::className(), ['build_id' => 'id_build']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIddolzh() {
        return $this->hasOne(Dolzh::className(), ['dolzh_id' => 'id_dolzh']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdpodraz() {
        return $this->hasOne(Podraz::className(), ['podraz_id' => 'id_podraz']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getimpemployees() {
        return $this->hasMany(Impemployee::className(), ['id_employee' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstallakts() {
        return $this->hasMany(Installakt::className(), ['id_installer' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMattraffics() {
        return $this->hasMany(Mattraffic::className(), ['id_mol' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts() {
        return $this->hasMany(Osmotrakt::className(), ['id_user' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts0() {
        return $this->hasMany(Osmotrakt::className(), ['id_master' => 'employee_id']);
    }

}
