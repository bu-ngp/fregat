<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Grupa;

/**
 * GrupaSearch represents the model behind the search form about `app\models\Fregat\Grupa`.
 */
class GrupaSearch extends Grupa
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grupa_id'], 'integer'],
            [['grupa_name'], 'safe'],
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
        $query = Grupa::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'grupa_id' => $this->grupa_id,
        ]);

        $query->andFilterWhere(['like', 'grupa_name', $this->grupa_name]);

        return $dataProvider;
    }
}
