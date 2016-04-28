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
            ['mattraffic_number', 'default', 'value' => 1],
            [['mattraffic_date', 'id_material', 'id_mol', 'mattraffic_username', 'mattraffic_tip', 'mattraffic_lastchange', 'mattraffic_number'], 'required'],
            ['mattraffic_number', 'double', 'min' => 0, 'max' => 10000000000],
            [['id_material', 'id_mol'], 'integer'],
            ['mattraffic_date', 'unique', 'targetAttribute' => ['mattraffic_date', 'id_material', 'id_mol'], 'message' => 'На эту дату уже есть запись с этой матер. цен-ю и ответств. лицом', 'when' => function ($model) {
            return in_array($model->mattraffic_tip, [1, 2]);
        }],
            [['mattraffic_username'], 'string', 'max' => 128],
            ['mattraffic_lastchange', 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['mattraffic_tip'], 'integer', 'min' => 1, 'max' => 3], // 1 - Приход, 2 - Списание, 3 - Движение между кабинетами
            [['mattraffic_forimport'], 'integer', 'min' => 1, 'max' => 1], // 1 - У сотрудника не найден материал в фале excel, NULL по умолчанию
            ['mattraffic_number', 'MaxNumberMove', 'on' => 'traffic'],
        ];
    }

    public function MaxNumberMove($attribute) {
        $query = self::find()
                ->join('LEFT JOIN', 'material', 'material.material_id = mattraffic.id_material')
                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                ->join('LEFT JOIN', 'tr_osnov', 'material_tip = 1 and tr_osnov.id_mattraffic in (select mattraffic_id from mattraffic mt where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)')
                ->andWhere('mattraffic_number > 0')
                ->andWhere([
                    'id_material' => $this->id_material,
                    'id_mol' => $this->id_mol,
                ])
                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                ->andWhere(['m2.mattraffic_date_m2' => NULL])
                ->andWhere(['tr_osnov.id_mattraffic' => NULL])
                ->one();

        $max_number = self::GetMaxNumberMattrafficForInstallAkt($query->mattraffic_id);

        if (!empty($query) && $this->mattraffic_number > $max_number)
            $this->addError($attribute, 'Количество не может превышать ' . $max_number);
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
            'mattraffic_lastchange' => 'Дата изменения записи операции',
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

                    public function selectinputfortrosnov($params) {

                        $method = isset($params['init']) ? 'one' : 'all';

                        $query = self::find()
                                ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
                                ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
                                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                                ->join('LEFT JOIN', 'tr_osnov', 'material_tip = 1 and tr_osnov.id_mattraffic in (select mattraffic_id from mattraffic mt where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)')
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
                                            },
                                                ])
                                                ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
                                                ->andWhere('mattraffic_number > 0')
                                                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                                                ->andWhere(['m2.mattraffic_date_m2' => NULL])
                                                ->andWhere(['tr_osnov.id_mattraffic' => NULL])
                                                ->limit(20)
                                                ->asArray()
                                                ->$method();

                                        return $query;
                                    }

                                    // Выводит максимально возможное количество материальной ценноси для перемещения
                                    public static function GetMaxNumberMattrafficForInstallAkt($mattraffic_id) {
                                        $mattraffic = self::findOne($mattraffic_id);
                                        $mattraffic_number = $mattraffic->mattraffic_number;

                                        $os = self::findOne($mattraffic_id)->idMaterial->material_tip === 1;


                                        $mattraffic_number_remove = self::find()
                                                ->joinWith(['idMaterial' => function($query) {
                                                        $query->from(['idMaterial' => 'material']);
                                                    }])
                                                        ->andWhere([
                                                            'id_material' => $mattraffic->id_material,
                                                            'id_mol' => $mattraffic->id_mol,
                                                            'mattraffic_tip' => 3,
                                                        ])
                                                        ->sum('mattraffic_number');


                                                if ($os && !isset($mattraffic_number_remove))
                                                    $mattraffic_number_remove = 0;

                                                $mattraffic_number = $mattraffic_number - $mattraffic_number_remove;

                                                return $mattraffic_number;
                                            }

                                        }
                                        