<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Organ;

/**
 * OrganSearch represents the model behind the search form about `app\models\Fregat\Organ`.
 */
class OrganSearch extends Organ
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['organ_id'], 'integer'],
            [['organ_name', 'organ_phones', 'organ_email'], 'safe'],
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
        $query = Organ::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['organ_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'organ_id' => $this->organ_id,
        ]);

        $query->andFilterWhere(['like', 'organ_name', $this->organ_name]);
        $query->andFilterWhere(['like', 'organ_email', $this->organ_email]);
        $query->andFilterWhere(['like', 'organ_phones', $this->organ_phones]);

        return $dataProvider;
    }

}
