<?php

namespace app\models\Config;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Config\Authuser;
use yii\db\Query;

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

        $this->authuserDopFilter($query);

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

        $this->authuserDopFilter($query);

        return $dataProvider;
    }

    private function authuserDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'AuthuserFilter');

        if (!empty($filter)) {

            $attr = 'id_dolzh';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'employees.id_dolzh',
                'ExistsSubQuery' => (new Query())
                    ->select('employees.id_person')
                    ->from('employee employees')
                    ->leftJoin('auth_user idperson', 'idperson.auth_user_id = employees.id_person')
                    ->andWhere('employees.id_person = auth_user.auth_user_id')
            ]);

            $attr = 'id_podraz';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'employees.id_podraz',
                'ExistsSubQuery' => (new Query())
                    ->select('employees.id_person')
                    ->from('employee employees')
                    ->leftJoin('auth_user idperson', 'idperson.auth_user_id = employees.id_person')
                    ->andWhere('employees.id_person = auth_user.auth_user_id')
            ]);

            $attr = 'id_build';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'employees.id_build',
                'ExistsSubQuery' => (new Query())
                    ->select('employees.id_person')
                    ->from('employee employees')
                    ->leftJoin('auth_user idperson', 'idperson.auth_user_id = employees.id_person')
                    ->andWhere('employees.id_person = auth_user.auth_user_id')
            ]);

            $attr = 'authuser_active_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('employees.id_person')
                    ->from('employee employees')
                    ->innerJoin('auth_user idperson', 'idperson.auth_user_id = employees.id_person')
                    ->andWhere(['employees.employee_dateinactive' => null])
                    ->andWhere('employees.id_person = auth_user.auth_user_id'),
                ],
            ]);

            $attr = 'authuser_inactive_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['not exists', (new Query())
                    ->select('employees.id_person')
                    ->from('employee employees')
                    ->rightJoin('auth_user idperson', 'idperson.auth_user_id = employees.id_person')
                    ->andWhere(['or', ['employees.employee_dateinactive' => null], ['employees.employee_id' => null]])
                    ->andWhere('idperson.auth_user_id = auth_user.auth_user_id'),
                ],
            ]);

            $attr = 'employee_null_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('employees.id_person')
                    ->from('employee employees')
                    ->rightJoin('auth_user idperson', 'idperson.auth_user_id = employees.id_person')
                    ->andWhere(['employees.employee_id' => null])
                    ->andWhere('idperson.auth_user_id = auth_user.auth_user_id'),
                ],
            ]);

        }
    }

}
