<?php

namespace app\models\Base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Base\Classmkb;

/**
 * ClassmkbSearch represents the model behind the search form about `app\models\Base\Classmkb`.
 */
class ClassmkbSearch extends Classmkb {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'parent_id', 'node_count'], 'integer'],
            [['name', 'code', 'parent_code', 'additional_info'], 'safe'],
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
        $query = Classmkb::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['code' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['node_count' => 0]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'node_count' => $this->node_count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'parent_code', $this->parent_code])
                ->andFilterWhere(['like', 'additional_info', $this->additional_info]);

        return $dataProvider;
    }

    // список диагнозов для регистра глаукомных пациентов
    public function searchglauk($params) {
        $query = Classmkb::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['code' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['node_count' => 0]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'node_count' => $this->node_count,
        ]);

        $query->andFilterWhere(['or', ['like', 'code', 'H40%', false], ['like', 'code', 'Q15.0', false]]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'parent_code', $this->parent_code])
                ->andFilterWhere(['like', 'additional_info', $this->additional_info]);

        return $dataProvider;
    }

}
