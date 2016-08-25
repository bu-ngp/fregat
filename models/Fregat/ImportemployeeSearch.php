<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Importemployee;
use app\func\Proc;

/**
 * ImportemployeeSearch represents the model behind the search form about `app\models\Fregat\Importemployee`.
 */
class ImportemployeeSearch extends Importemployee
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idbuild.build_name', 'idpodraz.podraz_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['importemployee_id', 'id_build', 'id_podraz'], 'integer'],
            [['importemployee_combination', 'idbuild.build_name', 'idpodraz.podraz_name'], 'safe'],
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
        $query = Importemployee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['importemployee_combination' => SORT_ASC]],
        ]);

        $query->joinWith(['idpodraz', 'idbuild']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'importemployee_id' => $this->importemployee_id,
            'id_build' => $this->id_build,
            'id_podraz' => $this->id_podraz,
        ]);

        $query->andFilterWhere(['like', 'importemployee_combination', $this->importemployee_combination]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idbuild.build_name', 'idpodraz.podraz_name']);

        return $dataProvider;
    }

}
                