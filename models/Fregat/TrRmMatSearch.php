<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrRmMat;
use app\func\Proc;

/**
 * TrRmMatSearch represents the model behind the search form about `app\models\Fregat\TrRmMat`.
 */
class TrRmMatSearch extends TrRmMat
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrMat.idParent.idMaterial.material_name',
            'idTrMat.idParent.idMaterial.material_inv',
            'idTrMat.idParent.idMaterial.material_serial',
            'idTrMat.idParent.idMol.idbuild.build_name',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idParent.trOsnovs.tr_osnov_kab',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_rm_mat_id', 'id_removeakt', 'id_tr_mat'], 'integer'],
            [['idTrMat.idParent.idMaterial.material_name',
                'idTrMat.idParent.idMaterial.material_inv',
                'idTrMat.idParent.idMaterial.material_serial',
                'idTrMat.idParent.idMol.idbuild.build_name',
                'idTrMat.idMattraffic.idMaterial.material_name',
                'idTrMat.idMattraffic.idMaterial.material_inv',
                'idTrMat.idMattraffic.mattraffic_number',
                'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                'idTrMat.idParent.trOsnovs.tr_osnov_kab',
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
        $query = TrRmMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_rm_mat_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idTrMat.idMattraffic.idMaterial',
            'idTrMat.idMattraffic.idMol.idperson',
            'idTrMat.idMattraffic.idMol.iddolzh',
            'idTrMat.idParent.idMaterial matparent',
            'idTrMat.idParent.idMol molparent',
            'idTrMat.idParent.idMol.idbuild',
            'idTrMat.idParent.trOsnovs',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tr_rm_mat_id' => $this->tr_rm_mat_id,
            'id_removeakt' => (string)filter_input(INPUT_GET, 'id'),
            'id_tr_mat' => $this->id_tr_mat,
        ]);

        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idTrMat.idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idTrMat.idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_serial', $this->getAttribute('idTrMat.idParent.idMaterial.material_serial')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idTrMat.idParent.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMat.idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'trOsnovs.tr_osnov_kab', $this->getAttribute('idTrMat.idParent.trOsnovs.tr_osnov_kab')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idTrMat.idParent.idMaterial.material_name' => 'matparent',
            'idTrMat.idParent.idMaterial.material_inv' => 'matparent',
            'idTrMat.idParent.idMaterial.material_serial' => 'matparent',
            'idTrMat.idParent.idMol.idbuild.build_name',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idParent.trOsnovs.tr_osnov_kab',
        ]);

        return $dataProvider;
    }

}
                                