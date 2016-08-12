<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Removeakt;

/**
 * RemoveaktSearch represents the model behind the search form about `app\models\Fregat\Removeakt`.
 */
class RemoveaktSearch extends Removeakt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['removeakt_id', 'id_remover'], 'integer'],
            [['removeakt_date'], 'safe'],
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
        $query = Removeakt::find();

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
            'removeakt_id' => $this->removeakt_id,
            'removeakt_date' => $this->removeakt_date,
            'id_remover' => $this->id_remover,
        ]);

        return $dataProvider;
    }
}
