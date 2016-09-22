<?php

namespace app\models\Config;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config\Authuser;

/**
 * AuthuserSearch represents the model behind the search form about `app\models\Config\Authuser`.
 */
class AuthuserSearch extends Authuser
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'profile.profile_id',
            'profile.profile_inn',
            'profile.profile_dr',
            'profile.profile_pol',
            'profile.profile_address',
            'profile.profile_snils',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_user_id'], 'integer'],
            [['auth_user_fullname', 'auth_user_login', 'auth_user_password'], 'safe'],
            [[
                'profile.profile_id',
                'profile.profile_inn',
                'profile.profile_dr',
                'profile.profile_pol',
                'profile.profile_address',
                'profile.profile_snils',
            ], 'safe'],
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

        if (Yii::$app->user->can('Administrator'))
            $query->joinWith(['profile']);

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

        if (Yii::$app->user->can('Administrator')) {
            $query->andFilterWhere(['like', 'profile.profile_inn', $this->getAttribute('profile.profile_inn')]);
            $query->andFilterWhere(Proc::WhereConstruct($this, 'profile.profile_dr', Proc::Date));
            $query->andFilterWhere(['like', 'profile.profile_address', $this->getAttribute('profile.profile_address')]);
            $query->andFilterWhere(['like', 'profile.profile_snils', $this->getAttribute('profile.profile_snils')]);
            $query->andFilterWhere([
                'profile.profile_pol' => $this->getAttribute('profile.profile_pol'),
            ]);

            $query->groupBy(['auth_user_id']); // Костыль для gridview, не отображает положенные 10 записей при сортировке или фильтрации.
            // Причина возможно в NULL значениях первичного ключа profile_id связанной модели Profile

            Proc::AssignRelatedAttributes($dataProvider, [
                'profile.profile_inn',
                'profile.profile_dr',
                'profile.profile_pol',
                'profile.profile_address',
                'profile.profile_snils',
            ]);
        }

        return $dataProvider;
    }

    public function searchemployee($params)
    {
        $query = Authuser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['auth_user_fullname' => SORT_ASC]],
        ]);

        $query->joinWith('employees', true, 'INNER JOIN');
        if (Yii::$app->user->can('Administrator'))
            $query->joinWith(['profile']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'auth_user_id' => $this->auth_user_id,
        ]);

        $query->andFilterWhere(['like', 'auth_user_fullname', $this->auth_user_fullname]);

        if (Yii::$app->user->can('Administrator')) {
            $query->andFilterWhere(['like', 'profile.profile_inn', $this->getAttribute('profile.profile_inn')]);
            $query->andFilterWhere(Proc::WhereConstruct($this, 'profile.profile_dr', Proc::Date));
            $query->andFilterWhere(['like', 'profile.profile_address', $this->getAttribute('profile.profile_address')]);
            $query->andFilterWhere(['like', 'profile.profile_snils', $this->getAttribute('profile.profile_snils')]);
            $query->andFilterWhere([
                'profile.profile_pol' => $this->getAttribute('profile.profile_pol'),
            ]);

            $query->groupBy(['auth_user_id']); // Костыль для gridview, не отображает положенные 10 записей при сортировке или фильтрации.
            // Причина возможно в NULL значениях первичного ключа profile_id связанной модели Profile

            Proc::AssignRelatedAttributes($dataProvider, [
                'profile.profile_inn',
                'profile.profile_dr',
                'profile.profile_pol',
                'profile.profile_address',
                'profile.profile_snils',
            ]);
        }

        return $dataProvider;
    }

}
