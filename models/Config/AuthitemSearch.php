<?php

namespace app\models\Config;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config\Authitem;
use app\func\Proc;

/**
 * AuthitemSearch represents the model behind the search form about `app\models\Config\Authitem`.
 */
class AuthitemSearch extends Authitem {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'description', 'rule_name', 'data'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
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
        $query = Authitem::find();

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
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'rule_name', $this->rule_name])
                ->andFilterWhere(['like', 'data', $this->data]);

        $filter = Proc::GetFilter('AuthitemSearch', 'AuthitemFilter');

        if (!empty($filter)) {
            if ($filter['onlyrootauthitems_mark'] === '1') {
                $query->joinWith('authitemchildrenparent')
                        ->where('(not parent in (select b.child from auth_item_child b) or (parent Is Null))')
                        ->andFilterWhere(['type' => 1])
                        ->groupBy(['name', 'type', 'description']);
            }
        }

        return $dataProvider;
    }

    public function searchforauthitemchild($params) {
        $query = Authitem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['authitemchildrenchild' => function($query) {
                $query->from(['authitemchildrenchild' => 'auth_item_child']);
            }]);

                $this->load($params);

                if (!$this->validate()) {
                    // uncomment the following line if you do not want to return any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                $query->where('(name <> :parent) and (parent <> :parent or parent is null)', [
                    'parent' => $params['id'],
                ]);

                $query->andFilterWhere([
                    'type' => $this->type,
                    'created_at' => $this->created_at,
                    'updated_at' => $this->updated_at,
                ]);

                $query->andFilterWhere(['like', 'name', $this->name])
                        ->andFilterWhere(['like', 'description', $this->description])
                        ->andFilterWhere(['like', 'rule_name', $this->rule_name])
                        ->andFilterWhere(['like', 'data', $this->data]);

                if (empty($query->orderBy))
                    $query->orderBy('description');


                return $dataProvider;
            }

            public function searchforauthassignment($params) {
                $query = Authitem::find();

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                ]);

                $query->joinWith(['authassignments' => function($query) {
                        $query->from(['authassignments' => 'auth_assignment']);
                    }]);

                        $query->joinWith(['authitemchildrenchild' => function($query) {
                                $query->from(['authitemchildrenchild' => 'auth_item_child']);
                            }]);

                                $this->load($params);

                                if (!$this->validate()) {
                                    // uncomment the following line if you do not want to return any records when validation fails
                                    // $query->where('0=1');
                                    return $dataProvider;
                                }

                                $query->andFilterWhere([
                                    'type' => $this->type,
                                    'created_at' => $this->created_at,
                                    'updated_at' => $this->updated_at,
                                ]);

                                //      $query->where('auth_item.type = 1 and (authassignments.user_id <> :user_id or authassignments.user_id is null) and  (not auth_item.name in (select b.child from auth_item_child b))', [

                                $query->where('auth_item.type = 1 and (authassignments.user_id <> :user_id or authassignments.user_id is null) and (not authitemchildrenchild.parent in (select a.item_name from auth_assignment a where a.user_id = :user_id) or authitemchildrenchild.parent is null)', [
                                    'user_id' => $params['id'],
                                ]);

                                $query->andFilterWhere(['like', 'name', $this->name])
                                        ->andFilterWhere(['like', 'description', $this->description])
                                        ->andFilterWhere(['like', 'rule_name', $this->rule_name])
                                        ->andFilterWhere(['like', 'data', $this->data]);

                                if (empty($query->orderBy))
                                    $query->orderBy('description');


                                return $dataProvider;
                            }

                        }
                        