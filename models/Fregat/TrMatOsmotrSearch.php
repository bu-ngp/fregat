<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrMatOsmotr;
use app\func\Proc;

/**
 * TrMatOsmotrSearch represents the model behind the search form about `app\models\Fregat\TrMatOsmotr`.
 */
class TrMatOsmotrSearch extends TrMatOsmotr {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idParent.material_name',
            'idTrMat.idParent.material_inv',
            'idReason.reason_text',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tr_mat_osmotr_id', 'id_reason', 'id_tr_mat', 'id_osmotraktmat'], 'integer'],
            [['tr_mat_osmotr_comment',
            'tr_mat_osmotr_number',
            'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idParent.material_name',
            'idTrMat.idParent.material_inv',
            'idReason.reason_text',
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
        $query = TrMatOsmotr::find();

        $query->joinWith([
            'idTrMat' => function($query) {
                $query->from(['idTrMat' => 'tr_mat']);
                $query->joinWith([
                    'idMattraffic' => function($query) {
                        $query->from(['idMattraffic' => 'mattraffic']);
                        $query->joinWith([
                            'idMaterial' => function($query) {
                                $query->from(['idMaterial' => 'material']);
                                $query->joinWith([
                                    'idMatv' => function($query) {
                                        $query->from(['idMatv' => 'matvid']);
                                    },
                                        ]);
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
                                            },
                                                    'idParent' => function($query) {
                                                $query->from(['idParent' => 'material']);
                                            },
                                                ]);
                                            },
                                                    'idReason' => function($query) {
                                                $query->from(['idReason' => 'reason']);
                                            },
                                                ]);

                                                // add conditions that should always apply here

                                                $dataProvider = new ActiveDataProvider([
                                                    'query' => $query,
                                                ]);

                                                $this->load($params);

                                                if (!$this->validate()) {
                                                    // uncomment the following line if you do not want to return any records when validation fails
                                                    // $query->where('0=1');
                                                    return $dataProvider;
                                                }

                                                // grid filtering conditions
                                                $query->andFilterWhere([
                                                    'tr_mat_osmotr_id' => $this->tr_mat_osmotr_id,
                                                    'id_tr_mat' => $this->id_tr_mat,
                                                    'id_reason' => $this->id_reason,
                                                    'id_osmotraktmat' => $params['id'],
                                                ]);

                                                $query->andFilterWhere(['like', 'tr_mat_osmotr_comment', $this->tr_mat_osmotr_comment]);
                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'osmotraktmat_id'));
                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'osmotraktmat_date', 'date'));
                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'osmotraktmat_countmat'));
                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'tr_mat_osmotr_number'));
                                                $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.idMatv.matvid_name')]);
                                                $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_name')]);
                                                $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_inv')]);
                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idTrMat.idMattraffic.mattraffic_number'));
                                                $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
                                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);
                                                $query->andFilterWhere(['LIKE', 'idParent.material_name', $this->getAttribute('idTrMat.idParent.material_name')]);
                                                $query->andFilterWhere(['LIKE', 'idParent.material_inv', $this->getAttribute('idTrMat.idParent.material_inv')]);
                                                $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);


                                                Proc::AssignRelatedAttributes($dataProvider, [
                                                    'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
                                                    'idTrMat.idMattraffic.idMaterial.material_name',
                                                    'idTrMat.idMattraffic.idMaterial.material_inv',
                                                    'idTrMat.idMattraffic.mattraffic_number',
                                                    'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                                                    'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                                                    'idTrMat.idParent.material_name',
                                                    'idTrMat.idParent.material_inv',
                                                    'idReason.reason_text',
                                                ]);

                                                return $dataProvider;
                                            }

                                        }
                                        