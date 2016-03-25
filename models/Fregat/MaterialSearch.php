<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Material;

/**
 * MaterialSearch represents the model behind the search form about `app\models\Fregat\Material`.
 */
class MaterialSearch extends Material {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['material_id', 'material_tip', 'material_writeoff', 'id_matvid', 'id_izmer', 'material_importdo'], 'integer'],
            [['material_name', 'material_name1c', 'material_1c', 'material_inv', 'material_serial', 'material_release', 'material_username', 'material_lastchange'], 'safe'],
            [['material_number', 'material_price'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Material::find();

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
            'material_id' => $this->material_id,
            'material_release' => $this->material_release,
            'material_number' => $this->material_number,
            'material_price' => $this->material_price,
            'material_tip' => $this->material_tip,
            'material_writeoff' => $this->material_writeoff,
            'id_matvid' => $this->id_matvid,
            'id_izmer' => $this->id_izmer,
        ]);

        $query->andFilterWhere(['like', 'material_name', $this->material_name])
                ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
                ->andFilterWhere(['like', 'material_1c', $this->material_1c])
                ->andFilterWhere(['like', 'material_inv', $this->material_inv])
                ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

        return $dataProvider;
    }

}
