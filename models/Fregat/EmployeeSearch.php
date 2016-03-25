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
class EmployeeSearch extends Employee {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name', 'idperson.auth_user_fullname']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['employee_id', 'id_dolzh', 'id_podraz', 'id_build', 'id_person'], 'integer'],
            [['employee_username', 'employee_lastchange', 'employee_dateinactive', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name', 'idperson.auth_user_fullname'], 'safe'],
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
        $query = Employee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['iddolzh' => function($query) {
                $query->from(['iddolzh' => 'dolzh']);
            }]);

                $query->joinWith(['idpodraz' => function($query) {
                        $query->from(['idpodraz' => 'podraz']);
                    }]);

                        $query->joinWith([
                            'idbuild' => function($query) {
                                $query->from(['idbuild' => 'build']);
                            },
                                ]);

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
                                ]);

                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
                                $query->andFilterWhere(['LIKE', 'employee_username', $this->getAttribute('employee_username')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'employee_lastchange', 'datetime'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'employee_dateinactive', 'date'));

                                if (empty($query->orderBy))
                                    $query->orderBy('employee_id');

                                $dataProvider->sort->attributes['iddolzh.dolzh_name'] = [
                                    'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                    'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                ];
                                $dataProvider->sort->attributes['idbuild.build_name'] = [
                                    'asc' => ['idbuild.build_name' => SORT_ASC],
                                    'desc' => ['idbuild.build_name' => SORT_DESC],
                                ];
                                $dataProvider->sort->attributes['idpodraz.podraz_name'] = [
                                    'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                                    'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                                ];

                                return $dataProvider;
                            }

                            public function searchforimportemployee($params) {
                                $query = Employee::find();

                                $dataProvider = new ActiveDataProvider([
                                    'query' => $query,
                                ]);




                                $query->joinWith(['impemployees' => function($query) {
                                        $query->from(['impemployees' => 'impemployee']);
                                    }]);

                                        $query->joinWith(['iddolzh' => function($query) {
                                                $query->from(['iddolzh' => 'dolzh']);
                                            }]);

                                                $query->joinWith(['idpodraz' => function($query) {
                                                        $query->from(['idpodraz' => 'podraz']);
                                                    }]);

                                                        $query->joinWith([
                                                            'idbuild' => function($query) {
                                                                $query->from(['idbuild' => 'build']);
                                                            }]);

                                                                $query->joinWith([
                                                                    'idperson' => function($query) {
                                                                        $query->from(['idperson' => 'auth_user']);
                                                                    }]);

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
                                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'employee_lastchange', 'datetime'));
                                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'employee_dateinactive', 'date'));

                                                                        if (empty($query->orderBy))
                                                                            $query->orderBy('employee_id');

                                                                        $dataProvider->sort->attributes['idperson.auth_user_fullname'] = [
                                                                            'asc' => ['idperson.auth_user_fullname' => SORT_ASC],
                                                                            'desc' => ['idperson.auth_user_fullname' => SORT_DESC],
                                                                        ];
                                                                        $dataProvider->sort->attributes['iddolzh.dolzh_name'] = [
                                                                            'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                                                            'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                                                        ];
                                                                        $dataProvider->sort->attributes['idbuild.build_name'] = [
                                                                            'asc' => ['idbuild.build_name' => SORT_ASC],
                                                                            'desc' => ['idbuild.build_name' => SORT_DESC],
                                                                        ];
                                                                        $dataProvider->sort->attributes['idpodraz.podraz_name'] = [
                                                                            'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                                                                            'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                                                                        ];

                                                                        return $dataProvider;
                                                                    }

                                                                    public function searchforauthuser($params) {
                                                                        $query = Employee::find();

                                                                        $dataProvider = new ActiveDataProvider([
                                                                            'query' => $query,
                                                                        ]);




                                                                        /*   $query->joinWith(['idperson' => function($query) {
                                                                          $query->from(['idperson' => 'person']);
                                                                          }]); */

                                                                        $query->joinWith(['iddolzh' => function($query) {
                                                                                $query->from(['iddolzh' => 'dolzh']);
                                                                            }]);

                                                                                $query->joinWith(['idpodraz' => function($query) {
                                                                                        $query->from(['idpodraz' => 'podraz']);
                                                                                    }]);

                                                                                        $query->joinWith([
                                                                                            'idbuild' => function($query) {
                                                                                                $query->from(['idbuild' => 'build']);
                                                                                            },
                                                                                                ]);

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

                                                                                                /*      $query->where('(idperson.id_importemployee <> :id_importemployee or impemployees.id_importemployee is null)', [
                                                                                                  'id_importemployee' => $params['id'],
                                                                                                  ]); */

                                                                                                //  $query->andFilterWhere(['like', 'employee_fio', $this->employee_fio]);
                                                                                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
                                                                                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
                                                                                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);
                                                                                                $query->andFilterWhere(['LIKE', 'employee_username', $this->getAttribute('employee_username')]);
                                                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'employee_lastchange', 'datetime'));
                                                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'employee_dateinactive', 'date'));

                                                                                                if (empty($query->orderBy))
                                                                                                    $query->orderBy('employee_id');

                                                                                                $dataProvider->sort->attributes['iddolzh.dolzh_name'] = [
                                                                                                    'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                                                                                    'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                                                                                ];
                                                                                                $dataProvider->sort->attributes['idbuild.build_name'] = [
                                                                                                    'asc' => ['idbuild.build_name' => SORT_ASC],
                                                                                                    'desc' => ['idbuild.build_name' => SORT_DESC],
                                                                                                ];
                                                                                                $dataProvider->sort->attributes['idpodraz.podraz_name'] = [
                                                                                                    'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                                                                                                    'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                                                                                                ];

                                                                                                return $dataProvider;
                                                                                            }

                                                                                        }
                                                                                        