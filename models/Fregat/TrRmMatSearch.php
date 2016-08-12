<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrRmMat;

/**
 * TrRmMatSearch represents the model behind the search form about `app\models\Fregat\TrRmMat`.
 */
class TrRmMatSearch extends TrRmMat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_rm_mat_id', 'id_removeakt', 'id_mattraffic'], 'integer'],
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
        $query = TrRmMat::find();

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
            'tr_rm_mat_id' => $this->tr_rm_mat_id,
            'id_removeakt' => $this->id_removeakt,
            'id_mattraffic' => $this->id_mattraffic,
        ]);

        return $dataProvider;
    }
}
