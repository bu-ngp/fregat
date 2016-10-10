<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Schetuchet;

/**
 * SchetuchetSearch represents the model behind the search form about `app\models\Fregat\Schetuchet`.
 */
class SchetuchetSearch extends Schetuchet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['schetuchet_id'], 'integer'],
            [['schetuchet_kod', 'schetuchet_name'], 'safe'],
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
        $query = Schetuchet::find();

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
            'schetuchet_id' => $this->schetuchet_id,
        ]);

        $query->andFilterWhere(['like', 'schetuchet_kod', $this->schetuchet_kod])
            ->andFilterWhere(['like', 'schetuchet_name', $this->schetuchet_name]);

        return $dataProvider;
    }
}
