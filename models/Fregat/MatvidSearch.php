<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Matvid;
use app\func\Proc;

/**
 * MatvidSearch represents the model behind the search form about `app\models\Fregat\Matvid`.
 */
class MatvidSearch extends Matvid {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['grupavids.grupavid_main']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['matvid_id'], 'integer'],
            [['matvid_name', 'grupavids.grupavid_main'], 'safe'],
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
        $query = Matvid::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['matvid_name' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'matvid_id' => $this->matvid_id,
        ]);

        $query->andFilterWhere(['like', 'matvid_name', $this->matvid_name]);

        return $dataProvider;
    }

    public function searchforgrupavid($params) {
        $query = Matvid::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['matvid_name' => SORT_ASC]],
        ]);

        $query->joinWith(['grupavids' => function($query) {
                $query->from(['grupavids' => 'grupavid']);
            }]);

                $this->load($params);

                if (!$this->validate()) {
                    // uncomment the following line if you do not want to return any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                $query->andFilterWhere([
                    'matvid_id' => $this->matvid_id,
                ]);

                $query->andFilterWhere(['like', 'matvid_name', $this->matvid_name]);

                $query->andFilterWhere(['like', 'grupavids.grupa_main', $this->getAttribute('grupavids.grupa_main')]);

                Proc::AssignRelatedAttributes($dataProvider, ['grupavids.grupa_main']);

                return $dataProvider;
            }

        }
        