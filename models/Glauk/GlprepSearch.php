<?php

namespace app\models\Glauk;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Glauk\Glprep;

/**
 * GlprepSearch represents the model behind the search form about `app\models\Glauk\Glprep`.
 */
class GlprepSearch extends Glprep {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['glprep_id', 'id_glaukuchet', 'id_preparat', 'glprep_rlocat'], 'integer'],
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
        $query = Glprep::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'idPreparat' => function($query) {
                $query->from(['idPreparat' => 'preparat']);
            }]);

                $this->load($params);

                if (isset($params['id'])) {
                    $Glaukuchet = Glaukuchet::findOne(['id_patient' => $params['id']]);
                    $query->andFilterWhere(['id_glaukuchet' => empty($Glaukuchet) ? -1 : $Glaukuchet->primaryKey]);
                } else
                    $query->andFilterWhere(['id_glaukuchet' => -1]);

                if (!$this->validate()) {
                    // uncomment the following line if you do not want to return any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                // grid filtering conditions
                $query->andFilterWhere([
                    'glprep_id' => $this->glprep_id,
                    'id_glaukuchet' => $this->id_glaukuchet,
                    'id_preparat' => $this->id_preparat,
                    'glprep_rlocat' => $this->glprep_rlocat,
                ]);

                $query->andFilterWhere(['LIKE', 'idPreparat.preparat_name', $this->getAttribute('idPreparat.preparat_name')]);

                $dataProvider->sort->attributes['idPreparat.preparat_name'] = [
                    'asc' => ['idPreparat.preparat_name' => SORT_ASC],
                    'desc' => ['idPreparat.preparat_name' => SORT_DESC],
                ];

                return $dataProvider;
            }

        }
        