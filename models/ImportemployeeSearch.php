<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Importemployee;

/**
 * ImportemployeeSearch represents the model behind the search form about `app\models\Importemployee`.
 */
class ImportemployeeSearch extends Importemployee {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idbuild.build_name', 'idpodraz.podraz_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['importemployee_id', 'id_build', 'id_podraz'], 'integer'],
            [['importemployee_combination', 'idbuild.build_name', 'idpodraz.podraz_name'], 'safe'],
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
        $query = Importemployee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'idbuild' => function($query) {
                $query->from(['idbuild' => 'build']);
            },
                ]);

                $query->joinWith(['idpodraz' => function($query) {
                        $query->from(['idpodraz' => 'podraz']);
                    }]);

                        $this->load($params);

                        if (!$this->validate()) {
                            // uncomment the following line if you do not want to return any records when validation fails
                            // $query->where('0=1');
                            return $dataProvider;
                        }

                        $query->andFilterWhere([
                            'importemployee_id' => $this->importemployee_id,
                            'id_build' => $this->id_build,
                            'id_podraz' => $this->id_podraz,
                        ]);

                        $query->andFilterWhere(['like', 'importemployee_combination', $this->importemployee_combination]);
                        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
                        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);

                        $dataProvider->sort->attributes['idbuild.build_name'] = [
                            'asc' => ['idbuild.build_name' => SORT_ASC],
                            'desc' => ['idbuild.build_name' => SORT_DESC],
                        ];
                        $dataProvider->sort->attributes['idpodraz.podraz_name'] = [
                            'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                            'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                        ];

                        return $dataProvider;
                    }

                }
                