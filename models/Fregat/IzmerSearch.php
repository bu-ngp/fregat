<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Izmer;

/**
 * IzmerSearch represents the model behind the search form about `app\models\Fregat\Izmer`.
 */
class IzmerSearch extends Izmer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['izmer_id'], 'integer'],
            [['izmer_name'], 'safe'],
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
        $query = Izmer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['izmer_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'izmer_id' => $this->izmer_id,
        ]);

        $query->andFilterWhere(['like', 'izmer_name', $this->izmer_name]);

        return $dataProvider;
    }
}
