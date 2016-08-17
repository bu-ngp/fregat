<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrMatOsmotr;

/**
 * TrMatOsmotrSearch represents the model behind the search form about `app\models\Fregat\TrMatOsmotr`.
 */
class TrMatOsmotrSearch extends TrMatOsmotr
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_mat_osmotr_id', 'id_tr_mat', 'id_osmotraktmat'], 'integer'],
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
        $query = TrMatOsmotr::find();

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
            'tr_mat_osmotr_id' => $this->tr_mat_osmotr_id,
            'id_tr_mat' => $this->id_tr_mat,
            'id_osmotraktmat' => $this->id_osmotraktmat,
        ]);

        return $dataProvider;
    }
}
