<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Impemployee;

/**
 * ImpemployeeSearch represents the model behind the search form about `app\models\Impemployee`.
 */
class ImpemployeeSearch extends Impemployee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['impemployee_id', 'id_importemployee', 'id_employee'], 'integer'],
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
        $query = Impemployee::find();

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
            'impemployee_id' => $this->impemployee_id,
            'id_importemployee' => $this->id_importemployee,
            'id_employee' => $this->id_employee,
        ]);

        return $dataProvider;
    }
}
