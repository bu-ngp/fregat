<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Cabinet;

/**
 * CabinetSearch represents the model behind the search form about `app\models\Fregat\Cabinet`.
 */
class CabinetSearch extends Cabinet
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idbuild.build_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cabinet_id', 'id_build'], 'integer'],
            [['cabinet_name', 'idbuild.build_name'], 'safe'],
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

    public function searchforinstallakt($params)
    {
        $query = Cabinet::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idbuild.build_name' => SORT_ASC, 'cabinet_name' => SORT_ASC]],
        ]);

        $query->joinWith(['idbuild']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (empty($params['id_mattraffic']) || !($mattraffic = Mattraffic::findOne($params['id_mattraffic'])) || empty($mattraffic->idMol->id_build)) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_build' => $mattraffic->idMol->id_build,
        ]);

        $query->andFilterWhere(['LIKE', 'cabinet_name', $this->getAttribute('cabinet_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idbuild.build_name']);

        return $dataProvider;
    }
}
