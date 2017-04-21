<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Spismatmaterials;

/**
 * SpismatmaterialsSearch represents the model behind the search form about `app\models\Fregat\Spismatmaterials`.
 */
class SpismatmaterialsSearch extends Spismatmaterials
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spismatmaterials_id', 'id_spismat', 'id_mattraffic'], 'integer'],
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
        $query = Spismatmaterials::find();

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
            'spismatmaterials_id' => $this->spismatmaterials_id,
            'id_spismat' => $this->id_spismat,
            'id_mattraffic' => $this->id_mattraffic,
        ]);

        return $dataProvider;
    }
}
