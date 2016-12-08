<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrMat;
use app\func\Proc;

/**
 * TrMatSearch represents the model behind the search form about `app\models\Fregat\TrMat`.
 */
class TrMatSearch extends TrMat
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idInstallakt.installakt_date',
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idMattraffic.idMol.idbuild.build_name',
            'idParent.idMaterial.material_name',
            'idParent.idMaterial.material_inv',
            'idParent.idMol.idbuild.build_name',
            'idParent.trOsnovs.tr_osnov_kab',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_mat_id', 'id_installakt', 'id_mattraffic', 'id_parent'], 'integer'],
            [['idInstallakt.installakt_date',
                'idMattraffic.idMaterial.material_name',
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.mattraffic_number',
                'idMattraffic.idMol.idperson.auth_user_fullname',
                'idMattraffic.idMol.iddolzh.dolzh_name',
                'idMattraffic.idMol.idbuild.build_name',
                'idParent.idMaterial.material_name',
                'idParent.idMaterial.material_inv',
                'idParent.idMol.idbuild.build_name',
                'idParent.trOsnovs.tr_osnov_kab',
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TrMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_mat_id' => SORT_DESC]],
        ]);


        $query->joinWith([
            'idMattraffic.idMaterial',
            'idMattraffic.idMol.idperson',
            'idMattraffic.idMol.iddolzh',
            'idParent.idMaterial matparent',
            'idParent.idMol molparent',
            'idParent.idMol.idbuild',
            'idParent.trOsnovs',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tr_mat_id' => $this->tr_mat_id,
            'id_installakt' => $this->id_installakt,
            'id_mattraffic' => $this->id_mattraffic,
            'id_parent' => $this->id_parent,
            'tr_mat.id_installakt' => (string)filter_input(INPUT_GET, 'id'),
        ]);

        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idParent.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'trOsnovs.tr_osnov_kab', $this->getAttribute('idParent.trOsnovs.tr_osnov_kab')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idParent.idMaterial.material_name' => 'matparent',
            'idParent.idMaterial.material_inv' => 'matparent',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idParent.idMol.idbuild.build_name',
            'idParent.trOsnovs.tr_osnov_kab',
        ]);

        return $dataProvider;
    }

    public function searchfortrrmmat($params)
    {
        $query = TrMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_mat_id' => SORT_DESC]],
        ]);


        $query->joinWith([
            'idMattraffic.idMaterial',
            'idMattraffic.idMol.idperson',
            'idMattraffic.idMol.iddolzh',
            'idParent.idMaterial matparent',
            'trRmMats',
            'idParent.idMol molparent',
            'idParent.idMol.idbuild',
            'idParent.trOsnovs',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->where(['trRmMats.id_removeakt' => NULL]);

        // grid filtering conditions
        $query->andFilterWhere([
            'tr_mat_id' => $this->tr_mat_id,
            'id_installakt' => $this->id_installakt,
            'id_mattraffic' => $this->id_mattraffic,
            'id_parent' => $this->id_parent,
        ]);

        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idParent.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'trOsnovs.tr_osnov_kab', $this->getAttribute('idParent.trOsnovs.tr_osnov_kab')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idParent.idMaterial.material_name' => 'matparent',
            'idParent.idMaterial.material_inv' => 'matparent',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idParent.idMol.idbuild.build_name',
            'idParent.trOsnovs.tr_osnov_kab',
        ]);

        return $dataProvider;
    }

    public function searchfortrmatosmotr($params)
    {
        $query = TrMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_mat_id' => SORT_DESC]],
        ]);


        $query->joinWith([
            'idMattraffic.idMaterial',
            'idMattraffic.idMol.idperson',
            'idMattraffic.idMol.iddolzh',
            'idParent.idMaterial matparent',
            'idParent.idMol molparent',
            'idParent.idMol.idbuild',
            'idParent.trOsnovs',
            'trRmMats',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //      $query->andWhere('tr_mat_id not in (select tmo.id_tr_mat from tr_mat_osmotr tmo where tmo.id_osmotraktmat = ' . $params['idosmotraktmat'] . ')');
        // grid filtering conditions
        $query->andFilterWhere([
            'tr_mat_id' => $this->tr_mat_id,
            'id_installakt' => $this->id_installakt,
            'id_mattraffic' => $this->id_mattraffic,
            'id_parent' => $this->id_parent,
        ]);

        $query->andWhere(['trRmMats.id_tr_mat' => NULL]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idParent.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'trOsnovs.tr_osnov_kab', $this->getAttribute('idParent.trOsnovs.tr_osnov_kab')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idParent.idMaterial.material_name' => 'matparent',
            'idParent.idMaterial.material_inv' => 'matparent',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idMattraffic.idMaterial.material_name',
            'idParent.idMol.idbuild.build_name',
            'idParent.trOsnovs.tr_osnov_kab',
        ]);

        return $dataProvider;
    }

    public function searchformaterialcontain($params)
    {
        $query = TrMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_mat_id' => SORT_DESC]],
        ]);


        $query->joinWith([
            'idInstallakt',
            'idMattraffic.idMaterial',
            'idMattraffic.idMol.idperson',
            'idMattraffic.idMol.iddolzh',
            'idParent',
            'trRmMats',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //      $query->andWhere('tr_mat_id not in (select tmo.id_tr_mat from tr_mat_osmotr tmo where tmo.id_osmotraktmat = ' . $params['idosmotraktmat'] . ')');
        // grid filtering conditions
        $query->andFilterWhere([
            'tr_mat_id' => $this->tr_mat_id,
            'id_mattraffic' => $this->id_mattraffic,
            'id_parent' => $this->id_parent,
        ]);

        $query->andWhere(['trRmMats.id_tr_mat' => NULL]);
        $query->andWhere(['idParent.id_material' => $params['id']]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'id_installakt'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idInstallakt.installakt_date'), 'date');
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMattraffic.idMol.idbuild.build_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idInstallakt.installakt_date',
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idMattraffic.idMol.idbuild.build_name',
        ]);

        return $dataProvider;
    }

}
                                                                        