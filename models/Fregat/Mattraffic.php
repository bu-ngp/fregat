<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "mattraffic".
 *
 * @property string $mattraffic_id
 * @property string $mattraffic_date
 * @property string $mattraffic_number
 * @property string $id_material
 * @property integer $id_mol
 *
 * @property Employee $idMol
 * @property Material $idMaterial
 * @property Osmotrakt[] $osmotrakts
 * @property TrMat[] $trMats
 * @property TrOsnov[] $trOsnovs
 * @property Writeoffakt[] $writeoffakts
 */
class Mattraffic extends \yii\db\ActiveRecord {

    public $recordapply;
    public $diff_number;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'mattraffic';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['mattraffic_username', 'filter', 'filter' => function($value) {
                    return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
                }],
            ['mattraffic_username', 'filter', 'filter' => function($value) {
                    return 'IMPORT';
                }, 'on' => 'import1c'],
            ['mattraffic_date', 'date', 'format' => 'yyyy-MM-dd'],
            [['mattraffic_date', 'id_material', 'id_mol', 'mattraffic_username', 'mattraffic_tip', 'mattraffic_lastchange'], 'required'],
            ['mattraffic_number', 'double', 'min' => 0, 'max' => 10000000000],
            [['id_material', 'id_mol'], 'integer'],
            ['mattraffic_date', 'unique', 'targetAttribute' => ['mattraffic_date', 'id_material', 'id_mol'], 'message' => 'На эту дату уже есть запись с этой матер. цен-ю и ответств. лицом'],
            [['mattraffic_username'], 'string', 'max' => 128],
            ['mattraffic_lastchange', 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['mattraffic_tip'], 'integer', 'min' => 1, 'max' => 2], // 1 - Приход, 2 - Списание
            [['mattraffic_forimport'], 'integer', 'min' => 1, 'max' => 1], // 1 - У сотрудника не найден материал в фале excel, NULL по умолчанию
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'mattraffic_id' => 'Mattraffic ID',
            'mattraffic_date' => 'Дата операции',
            'mattraffic_number' => 'Количество (Задействованное в операции)',
            'id_material' => 'Материальная ценность',
            'id_mol' => 'Материально-ответственное лицо',
            'mattraffic_username' => 'Пользователь изменивший запись',
            'mattraffic_lastchange' => 'Дата изменения записи',
            'mattraffic_tip' => 'Тип операции',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMol() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_mol']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaterial() {
        return $this->hasOne(Material::className(), ['material_id' => 'id_material']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts() {
        return $this->hasMany(Osmotrakt::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMats() {
        return $this->hasMany(TrMat::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs() {
        return $this->hasMany(TrOsnov::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriteoffakts() {
        return $this->hasMany(Writeoffakt::className(), ['id_mattraffic' => 'mattraffic_id']);
    }

    public function beforeValidate() {
        if ((empty($this->mattraffic_lastchange) || empty($this->mattraffic_forimport)) && $this->isAttributeRequired('mattraffic_lastchange'))
            $this->mattraffic_lastchange = date('Y-m-d H:i:s');

        return parent::beforeValidate();
    }

    public function beforeSave($insert) {
        if (empty($this->mattraffic_lastchange) || empty($this->mattraffic_forimport))
            $this->mattraffic_lastchange = date('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public function selectinput($params) {
        $query = self::find()
                ->select([self::primaryKey()[0] . ' AS id', 'CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text'])
                ->joinWith([
                    'idMol' => function($query) {
                        $query->from(['idMol' => 'employee']);
                        $query->joinWith([
                            'idperson' => function($query) {
                                $query->from(['idperson' => 'auth_user']);
                            },
                                    'iddolzh' => function($query) {
                                $query->from(['iddolzh' => 'dolzh']);
                            },
                                    'idpodraz' => function($query) {
                                $query->from(['idpodraz' => 'podraz']);
                            },
                                    'idbuild' => function($query) {
                                $query->from(['idbuild' => 'build']);
                            },
                                ]);
                            }
                                ])
                                ->where(['like', 'idperson.auth_user_fullname', $params['q']])
                                ->limit(20)
                                ->asArray()
                                ->all();

                        return $query;
                    }

                }
                