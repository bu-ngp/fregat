<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Cabinet;

/**
 * CabinetSearch represents the model behind the search form about `app\models\Fregat\Cabinet`.
 */
class CabinetSearch extends Cabinet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cabinet_id', 'id_build'], 'integer'],
            [['cabinet_name'], 'safe'],
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
        $query = Cabinet::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['cabinet_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_build' => $_GET['id'] ?: -1,
        ]);

        $query->andFilterWhere(['LIKE', 'cabinet_name', $this->getAttribute('cabinet_name')]);

        return $dataProvider;
    }
}
