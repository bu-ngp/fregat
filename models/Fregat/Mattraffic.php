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
 * @property Nakladmaterials[] $nakladmaterials
 * @property Spismatmaterials[] $spismatmaterials
 */
class Mattraffic extends \yii\db\ActiveRecord
{

    public $recordapply;
    public $diff_number;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mattraffic';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['mattraffic_username', 'filter', 'filter' => function ($value) {
                return Yii::$app->user->isGuest ? NULL : Yii::$app->user->identity->auth_user_login;
            }],
            ['mattraffic_username', 'filter', 'filter' => function ($value) {
                return 'IMPORT';
            }, 'on' => 'import1c'],
            ['mattraffic_date', 'date', 'format' => 'yyyy-MM-dd'],
            ['mattraffic_number', 'default', 'value' => 1],
            [['mattraffic_date', 'id_material', 'id_mol', 'mattraffic_username', 'mattraffic_tip', 'mattraffic_lastchange', 'mattraffic_number'], 'required'],
            ['mattraffic_number', 'double', 'min' => 0, 'max' => 10000000000],
            [['id_material', 'id_mol'], 'integer'],
            /*   [['mattraffic_date'], 'unique', 'message' => 'На эту дату уже есть запись с этой матер. цен-ю и ответств. лицом', 'targetAttribute' => ['mattraffic_date', 'id_material', 'id_mol'], 'when' => function ($model) {
                   return in_array($model->mattraffic_tip, [1, 2]);
               }],*/
            [['mattraffic_date'], 'UniqueChangeMol'],
            [['mattraffic_username'], 'string', 'max' => 128],
            ['mattraffic_lastchange', 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['mattraffic_tip'], 'integer', 'min' => 1, 'max' => 4], // 1 - Приход, 2 - Списание, 3 - Движение между кабинетами, 4 - Движение, как состовная часть мат ценности
            [['mattraffic_forimport'], 'integer', 'min' => 1, 'max' => 1], // 1 - У сотрудника не найден материал в фале excel, NULL по умолчанию
            ['mattraffic_number', 'MaxNumberMove', 'on' => 'traffic'],
            [['mattraffic_number'], 'MaxNumberMoveMat', 'on' => 'trafficmat'],
            [['mattraffic_number'], 'FoldDevision'],
            //  [['mattraffic_id'], 'safe'],
        ];
    }

    public function FoldDevision($attribute)
    {
        if (isset($this->idMaterial) && !(ctype_digit(strval(round($this->$attribute, 3))) && in_array($this->idMaterial->material_tip, [1, 3]) || in_array($this->idMaterial->material_tip, [2])))
            $this->addError($attribute, 'Количество должно быть целым числом');
    }

    // Определяем максимальное кол-во мат. цен-ти для перемещения (Основное средство - кол-во всегда не более 1, Материал - кол-во не более (Общее кол-во материала МОЛ'а - кол-во перемещенного материала МОЛ'а))
    public function MaxNumberMove($attribute)
    {
        $idinstallakt = (string)filter_input(INPUT_GET, 'idinstallakt');

        if (empty($idinstallakt))
            throw new \Exception('Отсутствует ID акта установки');
        else {
            if (!$this->isNewRecord && $this->mattraffic_tip == 3) {
                $query = $this;
                $mattraffic_id = $this->getMattrafficIDByMaterialAndMol($query->id_material, $query->id_mol);
            } else {
                $query = self::find()
                    ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
                    ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                    ->join('LEFT JOIN', 'tr_osnov', 'tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tos.id_installakt = ' . $idinstallakt . ' )')
                    ->andWhere('((mattraffic_number > 0 and idMaterial.material_tip = 1) or (mattraffic_number >= 0 and idMaterial.material_tip in (2,3)))')
                    ->andWhere([
                        'id_material' => $this->id_material,
                        'id_mol' => $this->id_mol,
                    ])
                    ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere(['or', ['tr_osnov.id_mattraffic' => NULL], ['in', 'idMaterial.material_tip', [2, 3]]])
                    ->one();
                $mattraffic_id = $query->mattraffic_id;
            }

            $max_number = self::GetMaxNumberMattrafficForInstallAkt($mattraffic_id, ['idinstallakt' => $idinstallakt]);

            if (!empty($query) && $this->mattraffic_number > $max_number)
                $this->addError($attribute, 'Количество не может превышать ' . $max_number);
        }
    }

    public function MaxNumberMoveMat($attribute)
    {
        if ($this->mattraffic_tip == 4 && !empty($this->id_material) && !empty($this->id_mol)) {
            $query = Mattraffic::find()
                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                ->andWhere([
                    'id_material' => $this->id_material,
                    'id_mol' => $this->id_mol,
                ])
                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                ->andWhere(['m2.mattraffic_date_m2' => NULL])
                ->one();

            if (!empty($query) && $this->mattraffic_number > $query->mattraffic_number)
                $this->addError($attribute, 'Количество не может превышать ' . $query->mattraffic_number);
        }
    }

    // Проверка на уникальность, при смене МОЛ материальной ценности
    public function UniqueChangeMol($attribute)
    {
        $result = 0;
        if ($this->isNewRecord)
            $result = self::find()
                ->andWhere([
                    'id_material' => $this->id_material,
                    'id_mol' => $this->id_mol,
                    'mattraffic_date' => $this->mattraffic_date,
                    'mattraffic_tip' => $this->mattraffic_tip,
                ])
                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                ->count();
        if ($result > 0)
            $this->addError($attribute, 'На эту дату уже есть запись с этой матер. цен-ю и ответств. лицом');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mattraffic_id' => 'ИД операции',
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
    public function getIdMol()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_mol'])->from(['idMol' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaterial()
    {
        return $this->hasOne(Material::className(), ['material_id' => 'id_material'])->from(['idMaterial' => Material::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMats()
    {
        return $this->hasMany(TrMat::className(), ['id_mattraffic' => 'mattraffic_id'])->from(['trMats' => TrMat::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs()
    {
        return $this->hasMany(TrOsnov::className(), ['id_mattraffic' => 'mattraffic_id'])->from(['trOsnovs' => TrOsnov::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWriteoffakts()
    {
        return $this->hasMany(Writeoffakt::className(), ['id_mattraffic' => 'mattraffic_id'])->from(['writeoffakts' => Writeoffakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpisosnovmaterials()
    {
        return $this->hasMany(Spisosnovmaterials::className(), ['id_mattraffic' => 'mattraffic_id'])->from(['spisosnovmaterials' => Spisosnovmaterials::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNakladmaterials()
    {
        return $this->hasMany(Nakladmaterials::className(), ['id_mattraffic' => 'mattraffic_id'])->from(['nakladmaterials' => Nakladmaterials::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpismatmaterials()
    {
        return $this->hasMany(Spismatmaterials::className(), ['id_mattraffic' => 'mattraffic_id'])->from(['spismatmaterials' => Spismatmaterials::tableName()]);
    }


    public function beforeValidate()
    {
        if ((empty($this->mattraffic_lastchange) || empty($this->mattraffic_forimport)) && $this->isAttributeRequired('mattraffic_lastchange'))
            $this->mattraffic_lastchange = date('Y-m-d H:i:s');

        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {
        if (empty($this->mattraffic_lastchange) || empty($this->mattraffic_forimport))
            $this->mattraffic_lastchange = date('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public function selectinput($params)
    {
        $query = self::find()
            ->select([self::primaryKey()[0] . ' AS id', 'CONCAT_WS(", ", idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text'])
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild'])
            ->where(['like', 'idperson.auth_user_fullname', $params['q']])
            ->limit(20)
            ->asArray()
            ->all();

        return $query;
    }

    public function selectinputfortrosnov($params)
    {

        $method = isset($params['init']) ? 'one' : 'all';

        if ($method == 'one') {
            $query = self::find()
                ->select(['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text'])
                ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild', 'idMaterial'])
                ->where(['mattraffic_id' => $params['q']])
                ->asArray()
                ->one();
        } else {
            $query = self::find()
                ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                //   ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1,2) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tos.id_installakt = ' . $params['idinstallakt'] . ' )')//and tr_osnov.id_installakt = '.$params['dopparams']['idinstallakt'])
                ->join('LEFT JOIN', '(select mt1.id_material from mattraffic mt1 inner join tr_osnov to1 on mt1.mattraffic_id = to1.id_mattraffic where to1.id_installakt = ' . $params['idinstallakt'] . ') tmp1', 'tmp1.id_material = mattraffic.id_material')
                ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild', 'idMaterial'])
                ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
                ->andWhere('((mattraffic_number > 0 and idMaterial.material_tip = 1) or (mattraffic_number >= 0 and idMaterial.material_tip in (2,3)))')
                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
                ->andWhere(['or', ['tmp1.id_material' => NULL], ['in', 'idMaterial.material_tip', [2, 3]]])
                ->groupBy(['mattraffic.mattraffic_id'])// Костыль на баг debug выдает одну запись, $query две с одним mattraffic_id
                ->limit(20)
                ->asArray()
                ->$method();
        }


        return $query;
    }

    public function selectinputforosmotrakt($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name, idMaterial.material_name) AS text']))
            //  ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            //   ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)')
            // ->join('LEFT JOIN', 'tr_osnov', 'tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)')
            //  ->join('LEFT JOIN', '(select mt1.id_material, mt1.id_mol from mattraffic mt1 inner join tr_osnov to1 on mt1.mattraffic_id = to1.id_mattraffic) tmp1', 'tmp1.id_material = mattraffic.id_material and tmp1.id_mol = mattraffic.id_mol')
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild', 'idMaterial'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('((mattraffic_number > 0 and idMaterial.material_tip = 1) or (mattraffic_number >= 0 and idMaterial.material_tip in (2,3)))')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            // ->andWhere(['idmaterial.material_tip' => 1])
            ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
            //  ->andWhere(['tmp1.id_material' => NULL])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputfortrmat_parent($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idbuild.build_name, CONCAT("каб. ",trOsnovs.tr_osnov_kab), idMaterial.material_inv, idMaterial.material_name) AS text']))
            ->joinWith(['trOsnovs', 'idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild', 'idMaterial'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [3]])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputfortrmat_child($params)
    {

        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->join('LEFT JOIN', 'tr_mat', 'material_tip in (1,2) and tr_mat.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_mat tmat on tmat.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tmat.id_installakt = ' . $params['idinstallakt'] . (empty($params['id_parent']) ? '' : ' and tmat.id_parent = ' . $params['id_parent']) . ' )')
            ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1,2) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt left join tr_osnov tosn on tosn.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material' . (empty($params['id_parent']) ? '' : ' and mt.mattraffic_id <> ' . $params['id_parent']) . ' )')
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
            ->andWhere([
                'or',
                ['and', ['tr_mat.id_mattraffic' => NULL], ['idMaterial.material_tip' => 2]],
                ['and', ['not', ['tr_osnov.id_mattraffic' => NULL]], ['idMaterial.material_tip' => 1]],
            ])
            ->andWhere(['in', 'idMaterial.material_tip', [1, 2]])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputforspisosnovakt($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild', 'idMaterial'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('(mattraffic_number > 0 and idMaterial.material_tip in (1,3))')
            ->andWhere(['in', 'mattraffic_tip', [1]])
            ->andWhere(isset($params['init']) ? [] : [
                'idMaterial.material_writeoff' => 0,
            ])
            ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputforspisosnovaktFast($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $Spisosnovakt = Spisosnovakt::findOne($params['spisosnovakt_id']);

        if (!empty($Spisosnovakt)) {
            $query = self::find()
                ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild', 'idMaterial', 'spisosnovmaterials'])
                ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
                ->andWhere('(mattraffic_number > 0 and idMaterial.material_tip in (1,3))')
                ->andWhere(['in', 'mattraffic_tip', [1]])
                ->andWhere([
                    'm2.mattraffic_date_m2' => NULL,
                    'idMaterial.material_writeoff' => 0,
                ])
                ->andWhere(['id_mol' => $Spisosnovakt->id_mol])
                ->andWhere(['idMaterial.id_schetuchet' => $Spisosnovakt->id_schetuchet])
                ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
                ->andWhere(['or', ['not', ['spisosnovmaterials.id_spisosnovakt' => $params['spisosnovakt_id']]], ['spisosnovmaterials.id_spisosnovakt' => NULL]])
                ->limit(20)
                ->asArray()
                ->$method();

            return $query;
        }

        return [];

    }

    public function selectinputfornakladmaterials($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idbuild.build_name, material_name) AS text']))
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idbuild', 'idMaterial'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere(array_merge(isset($params['init']) ? [] : ['idMaterial.material_writeoff' => 0]))
            ->andWhere(array_merge(isset($params['init']) ? ['in', 'mattraffic_tip', [1, 2]] : ['in', 'mattraffic_tip', [1]]))
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
            ->orderBy(['idMaterial.material_inv' => SORT_ASC])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public static function GetSumMattraffic_Number($mattraffic_id)
    {
        $mattraffic = self::findOne($mattraffic_id);

        $mattraffic_date = $mattraffic->mattraffic_date;

        $SumMN = Mattraffic::find()
            ->leftJoin('mattraffic mt', "mt.id_material = mattraffic.id_material and mt.id_mol = mattraffic.id_mol and mt.mattraffic_tip in (1,2) and mt.mattraffic_date <= '$mattraffic_date' and mattraffic.mattraffic_date < mt.mattraffic_date")
            ->joinWith('idMaterial')
            ->andWhere([
                'mattraffic.id_material' => $mattraffic->id_material,
                'mt.mattraffic_id' => NULL,
            ])
            ->andWhere(['in', 'mattraffic.mattraffic_tip', [1, 2]])
            ->andWhere("mattraffic.mattraffic_date <= '$mattraffic_date'")
            ->sum('mattraffic.mattraffic_number');

        return $SumMN;
    }

    // Выводит максимально возможное количество материальной ценноси для перемещения
    public static function GetMaxNumberMattrafficForInstallAkt($mattraffic_id, $dopparams = null)
    {
        if (!is_array($dopparams))
            $dopparams = [];

        $os = self::findOne($mattraffic_id)->idMaterial->material_tip === 1;
        // $SumMT = self::GetSumMattraffic_Number($mattraffic_id);
        $SumMT = !$os ? self::GetSumMattraffic_Number($mattraffic_id) : self::findOne($mattraffic_id)->mattraffic_number;
        $SumMT = (!$os && $SumMT == 0) ? 1 : $SumMT;

        return $os ? 1 : $SumMT;
    }

    public static function GetPreviousMattrafficByInstallaktMaterial($installakt_id, $material_id)
    {
        /*   $mattr_prev_max = self::find()
               ->innerJoin('(select mattraffic.mattraffic_id as idd from mattraffic left join tr_osnov on tr_osnov.id_mattraffic = mattraffic.mattraffic_id where id_material = ' . $material_id . ' and id_installakt = ' . $installakt_id . ' and mattraffic_tip in (1,2,3) ) aa', 'mattraffic_id < aa.idd')
               ->andWhere(['id_material' => $material_id])
               ->max('mattraffic_id');*/
        $mattr_prev_max = self::find()
            ->innerJoin('(select mattraffic.mattraffic_id as idd from mattraffic left join tr_osnov on tr_osnov.id_mattraffic = mattraffic.mattraffic_id where id_material = ' . $material_id . ' and id_installakt = ' . $installakt_id . ' and mattraffic_tip in (1,2,3) ) aa', 'mattraffic_id < aa.idd')
            ->andWhere(['id_material' => $material_id])
            ->orderBy(['mattraffic_tip' => SORT_DESC, 'mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC])
            ->limit(1)
            ->one();

        return $mattr_prev_max;
    }

    public static function CanIsDelete($Mattraffic_id)
    {
        $status = '';
        if (!empty($Mattraffic_id)) {
            $Mattraffic = Mattraffic::findOne($Mattraffic_id);
            if (!empty($Mattraffic)) {
                $First = self::find()
                    ->andWhere([
                        'id_material' => $Mattraffic->id_material,
                        'id_mol' => $Mattraffic->id_mol,
                    ])
                    ->andWhere('mattraffic_id IN (SELECT mattraffic_id FROM mattraffic WHERE id_material = ' . $Mattraffic->id_material . ' GROUP BY id_material HAVING min(mattraffic_date))')
                    ->count('mattraffic_id');

                if (empty($First)) {
                    $CountForDelete = self::find()
                        ->andWhere([
                            'id_material' => $Mattraffic->id_material,
                            'id_mol' => $Mattraffic->id_mol,
                        ])
                        ->andWhere('NOT EXISTS (SELECT mt.mattraffic_id FROM mattraffic mt WHERE mt.id_material = ' . $Mattraffic->id_material . ' AND mt.id_mol = ' . $Mattraffic->id_mol . ' AND mt.mattraffic_tip IN (3,4) AND mt.mattraffic_date >= mattraffic.mattraffic_date)')
                        ->andWhere('mattraffic_date <= \'' . $Mattraffic->mattraffic_date . '\'')
                        ->count('mattraffic_id');

                    if (empty($CountForDelete))
                        $status = 'Данную запись удалить нельзя, т.к. она используется при перемещении материальной ценности.';
                } else
                    $status = 'Материально-ответственное лицо, назначенное материалу первым, удалить нельзя.';
            }
        }
        return $status;
    }

    public function getMattrafficIDByMaterialAndMol($material_id, $mol_id)
    {
        $query = Mattraffic::find()
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->andWhere([
                'id_material' => $material_id,
                'id_mol' => $mol_id,
            ])
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->one();

        return $query->primaryKey;
    }

    public static function VariablesValues($attribute)
    {
        $values = [
            'mattraffic_tip' => [1 => 'Приход', 2 => 'Списание', 3 => 'Перемещение', 4 => 'Включен в состав'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
                                                                        