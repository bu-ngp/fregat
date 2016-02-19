<?php

namespace app\models\Config;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config\Authitemchild;

/**
 * AuthitemchildSearch represents the model behind the search form about `app\models\Config\Authitemchild`.
 */
class AuthitemchildSearch extends Authitemchild {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'children.description',
            'children.type',
            'children.name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parent', 'child', 'children.description', 'children.type', 'children.name'], 'safe'],
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
        $query = Authitemchild::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'children' => function($query) {
                $query->from(['children' => 'auth_item']);
            },
                ]);

                $this->load($params);
                $this->parent = $params['id'];

                if (!$this->validate()) {
                    // uncomment the following line if you do not want to return any records when validation fails
                    // $query->where('0=1');
                    return $dataProvider;
                }

                $query->andFilterWhere(['like', 'parent', $this->parent])
                        ->andFilterWhere(['like', 'child', $this->child]);

                $query->andFilterWhere(['LIKE', 'children.description', $this->getAttribute('children.description')]);
                $query->andFilterWhere(['LIKE', 'children.type', $this->getAttribute('children.type')]);
                $query->andFilterWhere(['LIKE', 'children.name', $this->getAttribute('children.name')]);

                $dataProvider->sort->attributes['children.description'] = [
                    'asc' => ['children.description' => SORT_ASC],
                    'desc' => ['children.description' => SORT_DESC],
                ];
                $dataProvider->sort->attributes['children.type'] = [
                    'asc' => ['children.type' => SORT_ASC],
                    'desc' => ['children.type' => SORT_DESC],
                ];
                $dataProvider->sort->attributes['children.name'] = [
                    'asc' => ['children.name' => SORT_ASC],
                    'desc' => ['children.name' => SORT_DESC],
                ];


                return $dataProvider;
            }

        }
        