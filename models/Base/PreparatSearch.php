<?php

namespace app\models\Base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Base\Preparat;

/**
 * PreparatSearch represents the model behind the search form about `app\models\Base\Preparat`.
 */
class PreparatSearch extends Preparat {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['preparat_id'], 'integer'],
            [['preparat_name'], 'safe'],
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
        $query = Preparat::find();

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
            'preparat_id' => $this->preparat_id,
        ]);

        $query->andFilterWhere(['like', 'preparat_name', $this->preparat_name]);

        return $dataProvider;
    }

    public function searchforglaukuchet($params) {
        $query = Preparat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['glpreps' => function($query) {
                $query->from(['glpreps' => 'glprep']);
            }]);

                $this->load($params);

                if (!$this->validate()) {
                    // uncomment the following line if you do not want to return any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                $query->where('preparat_id not in ( select glprep.id_preparat from glprep inner join glaukuchet on glprep.id_glaukuchet = glaukuchet.glaukuchet_id where glaukuchet.id_patient = :patient_id)', [
                    'patient_id' => $params['id'],
                ]);

                // grid filtering conditions
                $query->andFilterWhere([
                    'preparat_id' => $this->preparat_id,
                ]);

                $query->andFilterWhere(['like', 'preparat_name', $this->preparat_name]);

                return $dataProvider;
            }

        }
        