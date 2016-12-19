<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Employee;
use app\func\Proc;

/**
 * EmployeeSearch represents the model behind the search form about `app\models\Fregat\Employee`.
 */
class EmployeeSearch extends Employee
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name', 'idperson.auth_user_fullname']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dolzh', 'id_podraz', 'id_build', 'id_person'], 'integer'],
            [['employee_id', 'employee_lastchange', 'employee_dateinactive', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name', 'idperson.auth_user_fullname'], 'safe'],
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
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['employee_id' => SORT_ASC]],
        ]);

        $query->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_person' => $this->id_person,
            'id_dolzh' => $this->id_dolzh,
            'id_podraz' => $this->id_podraz,
            'id_build' => $this->id_build,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_id'));
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'employee_username', $this->getAttribute('employee_username')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idperson.auth_user_fullname')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_dateinactive', Proc::Date));

        Proc::AssignRelatedAttributes($dataProvider, ['idperson.auth_user_fullname', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name']);

        return $dataProvider;
    }

    public function searchforactiveemployee($params)
    {
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['employee_id' => SORT_ASC]],
        ]);

        $query->joinWith(['idperson', 'iddolzh', 'idpodraz', 'idbuild']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_person' => $this->id_person,
            'id_dolzh' => $this->id_dolzh,
            'id_podraz' => $this->id_podraz,
            'id_build' => $this->id_build,
        ]);

        $query->andWhere(['employee_dateinactive' => NULL]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_id'));
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'employee_username', $this->getAttribute('employee_username')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idperson.auth_user_fullname')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_dateinactive', Proc::Date));

        Proc::AssignRelatedAttributes($dataProvider, ['idperson.auth_user_fullname', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name']);

        return $dataProvider;
    }

    public function searchforimportemployee($params)
    {
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['employee_id' => SORT_ASC]],
        ]);

        $query->joinWith(['impemployees', 'idperson', 'iddolzh', 'idpodraz', 'idbuild']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'employee_id' => $this->employee_id,
            'id_dolzh' => $this->id_dolzh,
            'id_podraz' => $this->id_podraz,
            'id_build' => $this->id_build,
            'id_person' => $this->id_person,
        ]);

        $query->where('(impemployees.id_importemployee <> :id_importemployee or impemployees.id_importemployee is null)', [
            'id_importemployee' => $params['id'],
        ]);

        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'employee_username', $this->getAttribute('employee_username')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_dateinactive', Proc::Date));

        Proc::AssignRelatedAttributes($dataProvider, ['idperson.auth_user_fullname', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name']);

        return $dataProvider;
    }

    public function searchforauthuser($params)
    {
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['employee_id' => SORT_ASC]],
        ]);

        $query->joinWith(['iddolzh', 'idpodraz', 'idbuild']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'employee_id' => $this->employee_id,
            'id_dolzh' => $this->id_dolzh,
            'id_podraz' => $this->id_podraz,
            'id_build' => $this->id_build,
            'id_person' => $params['id'],
        ]);

        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'employee_username', $this->getAttribute('employee_username')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_dateinactive', Proc::Date));

        Proc::AssignRelatedAttributes($dataProvider, ['iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name']);

        return $dataProvider;
    }

    public function searchfornaklad($params)
    {
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['employee_id' => SORT_ASC]],
        ]);

        $query->joinWith(['iddolzh', 'idpodraz', 'idbuild', 'idperson']);
        $query->innerJoinWith('mattraffics');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'employee_id' => $this->employee_id,
            'id_dolzh' => $this->id_dolzh,
            'id_podraz' => $this->id_podraz,
            'id_build' => $this->id_build,
            'id_person' => $this->id_person,
        ]);

        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idperson.auth_user_fullname')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'employee_dateinactive', Proc::Date));

        $query->andWhere(['mattraffics.mattraffic_tip' => 1]);

        Proc::AssignRelatedAttributes($dataProvider, ['idperson.auth_user_fullname', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name']);

        $query->groupBy('employee_id');

        return $dataProvider;
    }

}
                                                                                