<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Osmotrakt;

/**
 * OsmotraktSearch represents the model behind the search form about `app\models\Fregat\Osmotrakt`.
 */
class OsmotraktSearch extends Osmotrakt
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['osmotrakt_id', 'id_reason', 'id_user', 'id_master', 'id_mattraffic'], 'integer'],
            [['osmotrakt_comment'], 'safe'],
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
        $query = Osmotrakt::find();

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
            'osmotrakt_id' => $this->osmotrakt_id,
            'id_reason' => $this->id_reason,
            'id_user' => $this->id_user,
            'id_master' => $this->id_master,
            'id_mattraffic' => $this->id_mattraffic,
        ]);

        $query->andFilterWhere(['like', 'osmotrakt_comment', $this->osmotrakt_comment]);

        return $dataProvider;
    }
}
