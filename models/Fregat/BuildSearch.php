<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Build;

/**
 * BuildSearch represents the model behind the search form about `app\models\Fregat\Build`.
 */
class BuildSearch extends Build {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['build_id'], 'integer'],
            [['build_name'], 'safe'],
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
        $query = Build::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['build_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'build_id' => $this->build_id,
        ]);

        $query->andFilterWhere(['like', 'build_name', $this->build_name]);

        return $dataProvider;
    }

}
