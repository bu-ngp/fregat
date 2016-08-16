<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Material;
use app\func\Proc;

/**
 * MaterialSearch represents the model behind the search form about `app\models\Fregat\Material`.
 */
class MaterialSearch extends Material {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idMatv.matvid_name', 'idIzmer.izmer_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['material_id', 'material_tip', 'material_writeoff', 'id_matvid', 'id_izmer', 'material_importdo'], 'integer'],
            [['material_name', 'material_name1c', 'material_1c', 'material_inv', 'material_serial', 'material_release', 'material_username', 'material_lastchange', 'idMatv.matvid_name', 'idIzmer.izmer_name'], 'safe'],
            [['material_number', 'material_price'], 'safe'],
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
        $query = Material::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['material_name' => SORT_ASC]],
        ]);

        $query->joinWith(['idMatv' => function($query) {
                $query->from(['idMatv' => 'matvid']);
            }]);

                $query->joinWith(['idIzmer' => function($query) {
                        $query->from(['idIzmer' => 'izmer']);
                    }]);


                        $this->load($params);

                        if (!$this->validate()) {
                            // uncomment the following line if you do not want to return any records when validation fails
                            // $query->where('0=1');
                            return $dataProvider;
                        }

                        $query->andFilterWhere([
                            'material_id' => $this->material_id,
                            'material_tip' => $this->material_tip,
                            'material_writeoff' => $this->material_writeoff,
                            'id_matvid' => $this->id_matvid,
                            'id_izmer' => $this->id_izmer,
                            'material_importdo' => $this->material_importdo,
                        ]);

                        $query->andFilterWhere(['like', 'material_name', $this->material_name])
                                ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
                                ->andFilterWhere(['like', 'material_1c', $this->material_1c])
                                ->andFilterWhere(['like', 'material_inv', $this->material_inv])
                                ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_release', 'date'));
                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_number'));
                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_price'));
                        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMatv.matvid_name')]);
                        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idIzmer.izmer_name')]);
                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_username'));
                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_lastchange', 'datetime'));
                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_number'));

                        Proc::AssignRelatedAttributes($dataProvider, ['idMatv.matvid_name', 'idIzmer.izmer_name']);

                        return $dataProvider;
                    }

                    public function searchforinstallakt_mat($params) {
                        $query = Material::find();

                        $dataProvider = new ActiveDataProvider([
                            'query' => $query,
                            'sort' => ['defaultOrder' => ['material_name' => SORT_ASC]],
                        ]);

                        $query->joinWith(['idMatv' => function($query) {
                                $query->from(['idMatv' => 'matvid']);
                            },
                                    'idIzmer' => function($query) {
                                $query->from(['idIzmer' => 'izmer']);
                            }
                                ]);

                                $this->load($params);

                                if (!$this->validate()) {
                                    // uncomment the following line if you do not want to return any records when validation fails
                                    // $query->where('0=1');
                                    return $dataProvider;
                                }

                                $query->andFilterWhere([
                                    'material_id' => $this->material_id,
                                    'material_tip' => $this->material_tip,
                                    'material_writeoff' => $this->material_writeoff,
                                    'id_matvid' => $this->id_matvid,
                                    'id_izmer' => $this->id_izmer,
                                    'material_importdo' => $this->material_importdo,
                                ]);

                                $query->andFilterWhere(['like', 'material_name', $this->material_name])
                                        ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
                                        ->andFilterWhere(['like', 'material_1c', $this->material_1c])
                                        ->andFilterWhere(['like', 'material_inv', $this->material_inv])
                                        ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_release', 'date'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_number'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_price'));
                                $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMatv.matvid_name')]);
                                $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idIzmer.izmer_name')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_username'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_lastchange', 'datetime'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'material_number'));

                                Proc::AssignRelatedAttributes($dataProvider, ['idMatv.matvid_name', 'idIzmer.izmer_name']);

                                return $dataProvider;
                            }

                        }
                        