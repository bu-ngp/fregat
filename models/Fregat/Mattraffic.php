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
            [['mattraffic_date'], 'unique', 'message' => 'На эту дату уже есть запись с этой матер. цен-ю и ответств. лицом', 'targetAttribute' => ['mattraffic_date', 'id_material', 'id_mol'], 'when' => function ($model) {
                return in_array($model->mattraffic_tip, [1, 2]);
            }],
            [['mattraffic_username'], 'string', 'max' => 128],
            ['mattraffic_lastchange', 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['mattraffic_tip'], 'integer', 'min' => 1, 'max' => 4], // 1 - Приход, 2 - Списание, 3 - Движение между кабинетами, 4 - Движение, как состовная часть мат ценности
            [['mattraffic_forimport'], 'integer', 'min' => 1, 'max' => 1], // 1 - У сотрудника не найден материал в фале excel, NULL по умолчанию
            ['mattraffic_number', 'MaxNumberMove', 'on' => 'traffic'],
            //  [['mattraffic_id'], 'safe'],
        ];
    }

    // Определяем максимальное кол-во мат. цен-ти для перемещения (Основное средство - кол-во всегда не более 1, Материал - кол-во не более (Общее кол-во материала МОЛ'а - кол-во перемещенного материала МОЛ'а))
    public function MaxNumberMove($attribute)
    {
        $idinstallakt = (string)filter_input(INPUT_GET, 'idinstallakt');

        if (empty($idinstallakt))
            throw new \Exception('Отсутствует ID акта установки');
        else {
            $query = self::find()
                ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1,2) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tos.id_installakt = ' . $idinstallakt . ' )')
                ->andWhere('mattraffic_number > 0')
                ->andWhere([
                    'id_material' => $this->id_material,
                    'id_mol' => $this->id_mol,
                ])
                ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                ->andWhere(['m2.mattraffic_date_m2' => NULL])
                ->andWhere(['or', ['tr_osnov.id_mattraffic' => NULL], ['idMaterial.material_tip' => 2]])
                ->one();

            $max_number = self::GetMaxNumberMattrafficForInstallAkt($query->mattraffic_id, ['idinstallakt' => $idinstallakt]);

            if (!empty($query) && $this->mattraffic_number > $max_number)
                $this->addError($attribute, 'Количество не может превышать ' . $max_number);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
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

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name) AS text']))
            ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1,2) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tos.id_installakt = ' . $params['idinstallakt'] . ' )')//and tr_osnov.id_installakt = '.$params['dopparams']['idinstallakt'])
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->andWhere(['or', ['tr_osnov.id_mattraffic' => NULL], ['idMaterial.material_tip' => 2]])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public function selectinputforosmotrakt($params)
    {

        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['mattraffic_id AS id'], ['CONCAT_WS(", ", idMaterial.material_inv, idperson.auth_user_fullname, iddolzh.dolzh_name, idpodraz.podraz_name, idbuild.build_name, idMaterial.material_name) AS text']))
            ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)')
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild',])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['idmaterial.material_tip' => 1])
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->andWhere(['tr_osnov.id_mattraffic' => NULL])
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
            ->join('LEFT JOIN', 'tr_mat', 'material_tip in (2) and tr_mat.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_mat tmat on tmat.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tmat.id_installakt = ' . $params['idinstallakt'] . ' )')//and tr_osnov.id_installakt = '.$params['dopparams']['idinstallakt'])
            ->joinWith(['idMol.idperson', 'idMol.iddolzh', 'idMol.idpodraz', 'idMol.idbuild'])
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->andWhere(['tr_mat.id_mattraffic' => NULL])
            ->andWhere(['idMaterial.material_tip' => 2])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    // Выводит максимально возможное количество материальной ценноси для перемещения
    public static function GetMaxNumberMattrafficForInstallAkt($mattraffic_id, $dopparams = null)
    {
        if (!is_array($dopparams))
            $dopparams = [];

        $mattraffic = self::findOne($mattraffic_id);
        $mattraffic_number = $mattraffic->mattraffic_number;

        $os = self::findOne($mattraffic_id)->idMaterial->material_tip === 1;

        $mattraffic_number_remove = self::find()
            ->joinWith(['idMaterial', 'trOsnovs'])
            ->andWhere(array_merge([
                'id_material' => $mattraffic->id_material,
                'id_mol' => $mattraffic->id_mol,
                'mattraffic_tip' => 3,
            ]/* , isset($dopparams['idinstallakt']) ? [] : ['trosnovs.id_installakt' => $dopparams['idinstallakt']] */))
            ->sum('mattraffic_number');


        if ($os && !isset($mattraffic_number_remove))
            $mattraffic_number_remove = 0;

        $mattraffic_number = $os ? 1 : ($mattraffic_number - $mattraffic_number_remove);

        return $mattraffic_number;
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

    public static function VariablesValues($attribute)
    {
        $values = [
            'mattraffic_tip' => [1 => 'Приход', 2 => 'Списание', 3 => 'Перемещение', 4 => 'Включен в состав'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
                                                                        