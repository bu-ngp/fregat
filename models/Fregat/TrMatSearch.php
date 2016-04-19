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
class TrMatSearch extends TrMat {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idParent.material_name',
            'idParent.material_inv',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['tr_mat_id', 'id_installakt', 'id_mattraffic', 'id_parent'], 'integer'],
            [['idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idParent.material_name',
            'idParent.material_inv'], 'safe'],
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
        $query = TrMat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $query->joinWith(['idMattraffic' => function($query) {
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
                            },
                                    'idParent' => function($query) {
                                $query->from(['idParent' => 'material']);
                            }
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
                                ]);

                                $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
                                $query->andFilterWhere(['LIKE', 'idParent.material_name', $this->getAttribute('idParent.material_name')]);
                                $query->andFilterWhere(['LIKE', 'idParent.material_inv', $this->getAttribute('idParent.material_inv')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMattraffic.mattraffic_number'));
                                $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);

                                $dataProvider->sort->attributes['idMattraffic.idMaterial.material_name'] = [
                                    'asc' => ['idMaterial.material_name' => SORT_ASC],
                                    'desc' => ['idMaterial.material_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMattraffic.idMaterial.material_inv'] = [
                                    'asc' => ['idMaterial.material_inv' => SORT_ASC],
                                    'desc' => ['idMaterial.material_inv' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idParent.material_name'] = [
                                    'asc' => ['idParent.material_name' => SORT_ASC],
                                    'desc' => ['idParent.material_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idParent.material_inv'] = [
                                    'asc' => ['idParent.material_inv' => SORT_ASC],
                                    'desc' => ['idParent.material_inv' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMattraffic.mattraffic_number'] = [
                                    'asc' => ['idMattraffic.mattraffic_number' => SORT_ASC],
                                    'desc' => ['idMattraffic.mattraffic_number' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMattraffic.idMol.idperson.auth_user_fullname'] = [
                                    'asc' => ['idperson.auth_user_fullname' => SORT_ASC],
                                    'desc' => ['idperson.auth_user_fullname' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMattraffic.idMol.iddolzh.dolzh_name'] = [
                                    'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                    'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                ];

                                if (empty($params['sort']))
                                    $query->orderBy('tr_mat_id desc');

                                return $dataProvider;
                            }

                        }
                        