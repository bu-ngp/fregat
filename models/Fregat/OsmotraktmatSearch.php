<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Osmotraktmat;

/**
 * OsmotraktmatSearch represents the model behind the search form about `app\models\Fregat\Osmotraktmat`.
 */
class OsmotraktmatSearch extends Osmotraktmat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['osmotraktmat_id', 'id_reason', 'id_tr_mat', 'id_master'], 'integer'],
            [['osmotraktmat_comment', 'osmotraktmat_date'], 'safe'],
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
        $query = Osmotraktmat::find();

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
            'osmotraktmat_id' => $this->osmotraktmat_id,
            'osmotraktmat_date' => $this->osmotraktmat_date,
            'id_reason' => $this->id_reason,
            'id_tr_mat' => $this->id_tr_mat,
            'id_master' => $this->id_master,
        ]);

        $query->andFilterWhere(['like', 'osmotraktmat_comment', $this->osmotraktmat_comment]);

        return $dataProvider;
    }
}
