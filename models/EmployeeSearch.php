<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `app\models\Employee`.
 */
class EmployeeSearch extends Employee {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['employee_id', 'id_dolzh', 'id_podraz', 'id_build'], 'integer'],
            [['employee_fio', 'iddolzh.dolzh_name', 'idbuild.build_name', 'idpodraz.podraz_name'], 'safe'],
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

                                $query->andFilterWhere(['like', 'employee_fio', $this->employee_fio]);
                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);

                                if (empty($query->orderBy))
                                    $query->orderBy('employee_fio');

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

                                                                $query->where('(impemployees.id_importemployee <> :id_importemployee or impemployees.id_importemployee is null)', [
                                                                    'id_importemployee' => $params['id'],
                                                                ]);

                                                                $query->andFilterWhere(['like', 'employee_fio', $this->employee_fio]);
                                                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('iddolzh.dolzh_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idbuild.build_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idpodraz.podraz_name')]);

                                                                if (empty($query->orderBy))
                                                                    $query->orderBy('employee_fio');

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
                                                        