<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Dolzh;

/**
 * DolzhSearch represents the model behind the search form about `app\models\Fregat\Dolzh`.
 */
class DolzhSearch extends Dolzh {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dolzh_id'], 'integer'],
            [['dolzh_name'], 'safe'],
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
        $query = Dolzh::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['dolzh_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'dolzh_id' => $this->dolzh_id,
        ]);

        $query->andFilterWhere(['like', 'dolzh_name', $this->dolzh_name]);

        return $dataProvider;
    }

}
