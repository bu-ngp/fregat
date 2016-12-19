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
 * @property string $employee_username
 * @property string $employee_lastchange
 * @property string $employee_dateinactive
 * @property integer $employee_forinactive
 * @property integer $employee_importdo
 *
 * @property AuthUser $idPerson
 * @property Build $idBuild
 * @property Dolzh $idDolzh
 * @property Podraz $idPodraz
 * @property Glaukuchet[] $glaukuchets
 * @property Impemployee[] $impemployees
 * @property Installakt[] $installakts
 * @property Mattraffic[] $mattraffics
 * @property Osmotrakt[] $osmotrakts
 * @property Osmotrakt[] $osmotrakts0
 * @property Osmotraktmat[] $osmotraktmats
 * @property Removeakt[] $removeakts
 * @property Spisosnovakt[] $spisosnovakts
 * @property Spisosnovakt[] $spisosnovakts0
 * @property Naklad[] $gotNaklads
 * @property Naklad[] $releaseNaklads
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
            ['employee_username', 'filter', 'filter' => function ($value) {
                return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
            }],
            ['employee_username', 'filter', 'filter' => function ($value) {
                return 'IMPORT';
            }, 'on' => 'import1c'],
            [['id_dolzh', 'id_podraz', 'id_person', 'employee_username', 'employee_lastchange'], 'required'],
            [['employee_username'], 'string', 'max' => 128],
            [['employee_lastchange'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['employee_dateinactive'], 'date', 'format' => 'yyyy-MM-dd'],
            [['id_dolzh', 'id_podraz', 'id_build', 'id_person'], 'integer'],
            [['id_person', 'id_dolzh'], 'unique', 'targetAttribute' => ['id_person', 'id_dolzh', 'id_podraz', 'id_build'], 'message' => 'На этого сотрудника уже есть такая специальность'],
            [['employee_importdo'], 'integer', 'min' => 0, 'max' => 1], // 0 - Специальность при импорте не изменяется, 1 - Специальность может быть изменена при импорте  
            [['employee_forinactive'], 'integer', 'min' => 1, 'max' => 1], // 1 - Сотрудник не найдет в файле импорта сотрудников, т.е. не работает по данной специальности, NULL по умолчанию
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => 'Код',
            'id_dolzh' => 'Должность',
            'id_podraz' => 'Подразделение',
            'id_build' => 'Здание',
            'id_person' => 'Сотрудник',
            'employee_username' => 'Пользователь изменивший запись',
            'employee_lastchange' => 'Дата изменения записи',
            'employee_dateinactive' => 'Дата с которой специальность неактивна',
            'employee_importdo' => 'Изменять данные специальности при импорте из 1С',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdperson()
    {
        return $this->hasOne(Authuser::className(), ['auth_user_id' => 'id_person'])->from(['idperson' => Authuser::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdbuild()
    {
        return $this->hasOne(Build::className(), ['build_id' => 'id_build'])->from(['idbuild' => Build::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIddolzh()
    {
        return $this->hasOne(Dolzh::className(), ['dolzh_id' => 'id_dolzh'])->from(['iddolzh' => Dolzh::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdpodraz()
    {
        return $this->hasOne(Podraz::className(), ['podraz_id' => 'id_podraz'])->from(['idpodraz' => Podraz::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getimpemployees()
    {
        return $this->hasMany(Impemployee::className(), ['id_employee' => 'employee_id'])->from(['impemployees' => Impemployee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstallakts()
    {
        return $this->hasMany(Installakt::className(), ['id_installer' => 'employee_id'])->from(['installakts' => Installakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMattraffics()
    {
        return $this->hasMany(Mattraffic::className(), ['id_mol' => 'employee_id'])->from(['mattraffics' => Mattraffic::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts()
    {
        return $this->hasMany(Osmotrakt::className(), ['id_user' => 'employee_id'])->from(['osmotraktsuser' => Osmotrakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts0()
    {
        return $this->hasMany(Osmotrakt::className(), ['id_master' => 'employee_id'])->from(['osmotraktsmaster' => Osmotrakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemoveakts()
    {
        return $this->hasMany(Removeakt::className(), ['id_remover' => 'employee_id'])->from(['removeakts' => Removeakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpisosnovaktsmol()
    {
        return $this->hasMany(Spisosnovakt::className(), ['id_mol' => 'employee_id'])->from(['spisosnovaktsmol' => Spisosnovakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReleaseNaklads()
    {
        return $this->hasMany(Naklad::className(), ['id_mol_release' => 'employee_id'])->from(['releaseNaklads' => Naklad::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGotNaklads()
    {
        return $this->hasMany(Naklad::className(), ['id_mol_got' => 'employee_id'])->from(['gotNaklads' => Naklad::tableName()]);
    }

    public function getEmployeeName()
    {
        return "{$this->idperson->auth_user_fullname}, {$this->iddolzh->dolzh_name}, {$this->idpodraz->podraz_name}" . (empty($this->id_build) ? '' : ", {$this->idbuild->build_name}");
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpisosnovaktsemp()
    {
        return $this->hasMany(Spisosnovakt::className(), ['id_employee' => 'employee_id'])->from(['spisosnovaktsemp' => Spisosnovakt::tableName()]);
    }

    public function beforeValidate()
    {
        if ((empty($this->employee_lastchange) || empty($this->employee_forinactive)) && $this->isAttributeRequired('employee_lastchange'))
            $this->employee_lastchange = date('Y-m-d H:i:s');

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (empty($this->employee_lastchange) || empty($this->employee_forinactive))
            $this->employee_lastchange = date('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public function selectinput($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild'])
            ->where(['like', isset($params['init']) ? 'employee_id' : 'idperson.auth_user_fullname', $params['q'] . (isset($params['init']) ? '' : '%'), false])
            ->orderBy('idperson.auth_user_fullname')
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputwithmaterials($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild'])
            ->innerJoinWith('mattraffics')
            ->where(['like', isset($params['init']) ? 'employee_id' : 'idperson.auth_user_fullname', $params['q'] . (isset($params['init']) ? '' : '%'), false])
            ->andWhere(['in', 'mattraffics.mattraffic_tip', [1, 2]])
            ->groupBy('employee_id')
            ->orderBy('idperson.auth_user_fullname')
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputnaklad($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild'])
            ->innerJoinWith('mattraffics')
            ->where(['like', isset($params['init']) ? 'employee_id' : 'idperson.auth_user_fullname', $params['q'] . (isset($params['init']) ? '' : '%'), false])
            ->andWhere(['in', 'mattraffics.mattraffic_tip', [1]])
            ->groupBy('employee_id')
            ->orderBy('idperson.auth_user_fullname')
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputactive($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild'])
            ->where(['like', isset($params['init']) ? 'employee_id' : 'idperson.auth_user_fullname', $params['q'] . (isset($params['init']) ? '' : '%'), false])
            ->andWhere(isset($params['init']) ? [] : ['employee_dateinactive' => NULL])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public static function getEmployeeByID($IDEmployee)
    {
        $query = self::find()
            ->select(['CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text'])
            ->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild'])
            ->where(['employee_id' => $IDEmployee])
            ->asArray()
            ->one();

        return $query['text'];
    }

    public static function VariablesValues($attribute)
    {
        $values = [
            'employee_importdo' => [0 => 'Нет', 1 => 'Да'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
                        