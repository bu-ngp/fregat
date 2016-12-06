<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Mattraffic;
use app\func\Proc;

/**
 * MattrafficSearch represents the model behind the search form about `app\models\Fregat\Mattraffic`.
 */
class MattrafficSearch extends Mattraffic
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMaterial.material_tip',
            'idMaterial.idMatv.matvid_name',
            'idMaterial.material_name',
            'idMaterial.material_inv',
            'idMaterial.material_serial',
            'idMaterial.material_release',
            'idMaterial.material_number',
            'idMaterial.idIzmer.izmer_name',
            'idMaterial.material_price',
            'idMol.employee_id',
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
            'idMol.idbuild.build_name',
            'idMol.employee_dateinactive',
            'idMaterial.material_writeoff',
            'idMaterial.material_username',
            'idMaterial.material_lastchange',
            'idMaterial.material_importdo',
            'idMol.employee_username',
            'idMol.employee_lastchange',
            'idMol.employee_importdo',
            'trOsnovs.tr_osnov_kab',
            'trMats.idParent.idMaterial.material_inv',
            'idMaterial.idSchetuchet.schetuchet_kod',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mattraffic_id', 'id_material', 'id_mol', 'mattraffic_tip', 'idMaterial.material_tip', 'idMol.employee_id', 'idMaterial.material_writeoff', 'idMaterial.material_importdo'], 'integer'],
            [['mattraffic_date', 'mattraffic_username', 'mattraffic_lastchange',
                'idMaterial.idMatv.matvid_name',
                'idMaterial.material_name',
                'idMaterial.material_inv',
                'idMaterial.material_serial',
                'idMaterial.material_release',
                'idMaterial.idIzmer.izmer_name',
                'idMol.idperson.auth_user_fullname',
                'idMol.iddolzh.dolzh_name',
                'idMol.idpodraz.podraz_name',
                'idMol.idbuild.build_name',
                'idMol.employee_dateinactive',
                'idMaterial.material_username',
                'idMaterial.material_lastchange',
                'idMol.employee_username',
                'idMol.employee_lastchange',
                'idMol.employee_importdo',
                'mattraffic_username',
                'mattraffic_lastchange',
                'trOsnovs.tr_osnov_kab',
                'trMats.idParent.idMaterial.material_inv',
                'mattraffic_number',
                'idMaterial.material_number',
                'idMaterial.material_price',
                'idMaterial.idSchetuchet.schetuchet_kod',
            ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function baseRelations(&$query)
    {
        $query->joinWith([
            'idMaterial.idMatv',
            'idMaterial.idIzmer',
            'idMol.idperson',
            'idMol.iddolzh',
            'idMol.idpodraz',
            'idMol.idbuild',
        ]);
    }

    private function baseFilter(&$query)
    {
        $query->andFilterWhere([
            'mattraffic_id' => $this->mattraffic_id,
            'mattraffic_tip' => $this->mattraffic_tip,
            'id_material' => $this->id_material,
            'id_mol' => $this->id_mol,
        ]);

        $query->andFilterWhere(['LIKE', 'idMaterial.material_tip', $this->getAttribute('idMaterial.material_tip')]);
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMaterial.idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idMaterial.material_serial')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMaterial.material_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMaterial.material_release', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMaterial.material_number'));
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idMaterial.idIzmer.izmer_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMaterial.material_price'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMol.employee_id'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idMol.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMol.idbuild.build_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMol.employee_dateinactive', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_writeoff', $this->getAttribute('idMaterial.material_writeoff')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_username', $this->getAttribute('idMaterial.material_username')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMaterial.material_lastchange', Proc::DateTime));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_importdo', $this->getAttribute('idMaterial.material_importdo')]);
        $query->andFilterWhere(['LIKE', 'idMol.employee_username', $this->getAttribute('idMol.employee_username')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMol.idbuild.build_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMol.employee_lastchange', Proc::DateTime));
        $query->andFilterWhere(['LIKE', 'idMol.employee_importdo', $this->getAttribute('idMol.employee_importdo')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'mattraffic_date', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'mattraffic_username', $this->getAttribute('mattraffic_username')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'mattraffic_lastchange', Proc::DateTime));
    }

    private function baseSort(&$dataProvider)
    {
        Proc::AssignRelatedAttributes($dataProvider, [
            'idMaterial.material_tip',
            'idMaterial.idMatv.matvid_name',
            'idMaterial.material_name',
            'idMaterial.material_inv',
            'idMaterial.material_serial',
            'idMaterial.material_release',
            'idMaterial.material_number',
            'idMaterial.idIzmer.izmer_name',
            'idMaterial.material_price',
            'idMol.employee_id',
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
            'idMol.idbuild.build_name',
            'idMol.employee_dateinactive',
            'idMaterial.material_writeoff',
            'idMaterial.material_username',
            'idMaterial.material_lastchange',
            'idMaterial.material_importdo',
            'idMol.employee_username',
            'idMol.employee_lastchange',
            'idMol.employee_importdo',
        ]);
    }

    public function search($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchforinstallakt($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $query->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            // ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1,2) and tr_osnov.id_mattraffic in (select mattraffic_id from mattraffic mt where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)');
            //  ->join('LEFT JOIN', 'tr_osnov', 'material_tip in (1,2) and tr_osnov.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_osnov tos on tos.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tos.id_installakt = ' . $params['idinstallakt'] . ' )');
            ->join('LEFT JOIN', '(select mt1.id_material from mattraffic mt1 inner join tr_osnov to1 on mt1.mattraffic_id = to1.id_mattraffic where to1.id_installakt = ' . $params['idinstallakt'] . ') tmp1', 'tmp1.id_material = mattraffic.id_material');

        $this->baseRelations($query);

        $query->andWhere('((mattraffic_number > 0 and idMaterial.material_tip = 1) or (mattraffic_number >= 0 and idMaterial.material_tip in (2,3)))')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->andWhere(['or', ['tmp1.id_material' => NULL], ['in', 'idMaterial.material_tip', [2, 3]]]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchforosmotrakt($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $query->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)');
        //        ->join('LEFT JOIN', 'tr_osnov', 'material_tip = 1 and tr_osnov.id_mattraffic in (select mattraffic_id from mattraffic mt where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)');
        // ->join('LEFT JOIN', 'tr_osnov', 'tr_osnov.id_mattraffic in (select mattraffic_id from mattraffic mt where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)');
        //  ->join('LEFT JOIN', '(select mt1.id_material, mt1.id_mol from mattraffic mt1 inner join tr_osnov to1 on mt1.mattraffic_id = to1.id_mattraffic) tmp1', 'tmp1.id_material = mattraffic.id_material and tmp1.id_mol = mattraffic.id_mol');
        $this->baseRelations($query);

        $query->andWhere('((mattraffic_number > 0 and idMaterial.material_tip = 1) or (mattraffic_number >= 0 and idMaterial.material_tip in (2,3)))')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['m2.mattraffic_date_m2' => NULL]);
        // ->andWhere(['tmp1.id_material' => NULL]);
        //   ->andWhere(['idMaterial.material_tip' => 1]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchforinstallakt_matparent($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);
        $query->joinWith(['trOsnovs']);

        $query->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [3]]);
        $query->andFilterWhere(['LIKE', 'trOsnovs.tr_osnov_kab', $this->getAttribute('trOsnovs.tr_osnov_kab')]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);

        $this->baseSort($dataProvider);
        Proc::AssignRelatedAttributes($dataProvider, [
            'trOsnovs.tr_osnov_kab',
        ]);

        return $dataProvider;
    }

    public function searchforinstallakt_mat($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $query->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
            ->join('LEFT JOIN', 'tr_mat', 'material_tip in (2) and tr_mat.id_mattraffic in (select mt.mattraffic_id from mattraffic mt inner join tr_mat tmat on tmat.id_mattraffic = mt.mattraffic_id where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material and tmat.id_installakt = ' . $params['idinstallakt'] . ' )'); //and tr_osnov.id_installakt = '.$params['dopparams']['idinstallakt'])


        $this->baseRelations($query);

        $query->andWhere('mattraffic_number > 0')
            ->andWhere(['in', 'mattraffic_tip', [1, 2]])
            ->andWhere(['m2.mattraffic_date_m2' => NULL])
            ->andWhere(['tr_mat.id_mattraffic' => NULL])
            ->andWhere(['idMaterial.material_tip' => 2]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchformaterialmattraffic($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);
        $query->joinWith(['trOsnovs', 'trMats.idParent.idMaterial matparent']);

        $query->andWhere(['mattraffic.id_material' => $params['id']]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $query->andFilterWhere(['LIKE', 'trOsnovs.tr_osnov_kab', $this->getAttribute('trOsnovs.tr_osnov_kab')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('trMats.idParent.idMaterial.material_inv')]);

        $this->baseSort($dataProvider);
        Proc::AssignRelatedAttributes($dataProvider, [
            'trOsnovs.tr_osnov_kab',
            'trMats.idParent.idMaterial.material_inv' => 'matparent',
        ]);

        return $dataProvider;
    }

    public function searchformolsmattraffic($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);

        $query->andWhere(['mattraffic.id_material' => $params['id']]);
        $query->andWhere(['in', 'mattraffic.mattraffic_tip', [1, 2]]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);

        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchforspisosnovakt($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);

        $query->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)');

        $this->baseRelations($query);

        $query->joinWith('idMaterial.idSchetuchet');

        $query->andWhere('(mattraffic_number > 0 and idMaterial.material_tip in (1,3))')
            ->andWhere(['in', 'mattraffic_tip', [1]])
            ->andWhere([
                'm2.mattraffic_date_m2' => NULL,
                'idMaterial.material_writeoff' => 0,
            ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $query->andFilterWhere(['LIKE', 'idSchetuchet.schetuchet_kod', $this->getAttribute('idMaterial.idSchetuchet.schetuchet_kod')]);

        $this->baseSort($dataProvider);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMaterial.idSchetuchet.schetuchet_kod',
        ]);

        return $dataProvider;
    }

    public function searchfornaklad($params)
    {
        $query = Mattraffic::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['mattraffic_date' => SORT_DESC, 'mattraffic_id' => SORT_DESC]],
        ]);


        $query->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)');

        $this->baseRelations($query);

        $query->andWhere(['idMaterial.material_writeoff' => 0]);
        $query->andWhere(['in', 'mattraffic_tip', [1]]);
        $query->andWhere(['m2.mattraffic_date_m2' => NULL]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);

        $this->baseSort($dataProvider);

        return $dataProvider;
    }

}
                                        