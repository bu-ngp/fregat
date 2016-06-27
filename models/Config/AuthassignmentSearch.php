<?php

namespace app\models\Config;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config\Authassignment;
use app\func\Proc;

/**
 * AuthassignmentSearch represents the model behind the search form about `app\models\Config\Authassignment`.
 */
class AuthassignmentSearch extends Authassignment {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'itemname.description',
            'itemname.type',
            'itemname.name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['item_name', 'itemname.description', 'itemname.type', 'itemname.name'], 'safe'],
            [['user_id', 'created_at'], 'integer'],
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
        $query = Authassignment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'itemname' => function($query) {
                $query->from(['itemname' => 'auth_item']);
            },
                ]);


                $this->load($params);
                $this->user_id = $params['id'];

                if (!$this->validate()) {
                    // uncomment the following line if you do not want to return any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                $query->andFilterWhere([
                    'user_id' => $this->user_id,
                    'created_at' => $this->created_at,
                ]);

                $query->andFilterWhere(['like', 'item_name', $this->item_name]);

                $query->andFilterWhere(['LIKE', 'itemname.description', $this->getAttribute('itemname.description')]);
                $query->andFilterWhere(['LIKE', 'itemname.type', $this->getAttribute('itemname.type')]);
                $query->andFilterWhere(['LIKE', 'itemname.name', $this->getAttribute('itemname.name')]);

                Proc::AssignRelatedAttributes($dataProvider, ['itemname.description', 'itemname.type', 'itemname.name']);

                return $dataProvider;
            }

        }
        