<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Mattraffic;

/**
 * MattrafficSearch represents the model behind the search form about `app\models\Fregat\Mattraffic`.
 */
class MattrafficSearch extends Mattraffic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mattraffic_id', 'id_material', 'id_mol'], 'integer'],
            [['mattraffic_date'], 'safe'],
            [['mattraffic_number'], 'number'],
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
        $query = Mattraffic::find();

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
            'mattraffic_id' => $this->mattraffic_id,
            'mattraffic_date' => $this->mattraffic_date,
            'mattraffic_number' => $this->mattraffic_number,
            'id_material' => $this->id_material,
            'id_mol' => $this->id_mol,
        ]);

        return $dataProvider;
    }
}
