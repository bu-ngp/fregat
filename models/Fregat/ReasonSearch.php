<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Reason;

/**
 * ReasonSearch represents the model behind the search form about `app\models\Fregat\Reason`.
 */
class ReasonSearch extends Reason {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['reason_id'], 'integer'],
            [['reason_text'], 'safe'],
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
        $query = Reason::find();

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
            'reason_id' => $this->reason_id,
        ]);

        $query->andFilterWhere(['like', 'reason_text', $this->reason_text]);
        if (empty($params['sort']))
            $query->orderBy('reason_text');

        return $dataProvider;
    }

}
