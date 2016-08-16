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
class TrRmMatSearch extends TrRmMat {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrMat.idParent.material_name',
            'idTrMat.idParent.material_inv',
            'idTrMat.idParent.material_serial',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tr_rm_mat_id', 'id_removeakt', 'id_tr_mat'], 'integer'],
            [['idTrMat.idParent.material_name',
            'idTrMat.idParent.material_inv',
            'idTrMat.idParent.material_serial',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = TrRmMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_rm_mat_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idTrMat' => function($query) {
                $query->from(['idTrMat' => 'tr_mat']);
                $query->joinWith([
                    'idMattraffic' => function($query) {
                        $query->from(['idMattraffic' => 'mattraffic']);
                        $query->joinWith([
                            'idMaterial' => function($query) {
                                $query->from(['idMaterial' => 'material']);
                            },
                                    'idMol' => function($query) {
                                $query->from(['idMol' => 'employee']);
                                $query->joinWith([
                                    'idperson' => function($query) {
                                        $query->from(['idperson' => 'auth_user']);
                                    },
                                            'iddolzh' => function($query) {
                                        $query->from(['iddolzh' => 'dolzh']);
                                    },
                                        ]);
                                    },
                                        ]);
                                    }
                                        ]);
                                    },
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
                                            'id_removeakt' => (string) filter_input(INPUT_GET, 'id'),
                                            'id_tr_mat' => $this->id_tr_mat,
                                        ]);

                                        $query->andFilterWhere(['LIKE', 'idParent.material_name', $this->getAttribute('idTrMat.idParent.material_name')]);
                                        $query->andFilterWhere(['LIKE', 'idParent.material_inv', $this->getAttribute('idTrMat.idParent.material_inv')]);
                                        $query->andFilterWhere(['LIKE', 'idParent.material_serial', $this->getAttribute('idTrMat.idParent.material_serial')]);
                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_name')]);
                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_inv')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idTrMat.idMattraffic.mattraffic_number'));
                                        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
                                        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);

                                        Proc::AssignRelatedAttributes($dataProvider, [
                                            'idTrMat.idParent.material_name',
                                            'idTrMat.idParent.material_inv',
                                            'idTrMat.idParent.material_serial',
                                            'idTrMat.idMattraffic.idMaterial.material_name',
                                            'idTrMat.idMattraffic.idMaterial.material_inv',
                                            'idTrMat.idMattraffic.mattraffic_number',
                                            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                                            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                                        ]);

                                        return $dataProvider;
                                    }

                                }
                                