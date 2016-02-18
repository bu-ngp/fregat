<?php

namespace app\models\Config;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config\Authuser;

/**
 * AuthuserSearch represents the model behind the search form about `app\models\Config\Authuser`.
 */
class AuthuserSearch extends Authuser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_user_id'], 'integer'],
            [['auth_user_fullname', 'auth_user_login', 'auth_user_password'], 'safe'],
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
        $query = Authuser::find();

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
            'auth_user_id' => $this->auth_user_id,
        ]);

        $query->andFilterWhere(['like', 'auth_user_fullname', $this->auth_user_fullname])
            ->andFilterWhere(['like', 'auth_user_login', $this->auth_user_login])
            ->andFilterWhere(['like', 'auth_user_password', $this->auth_user_password]);

        return $dataProvider;
    }
}
