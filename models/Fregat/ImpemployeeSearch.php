<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Impemployee;

/**
 * ImpemployeeSearch represents the model behind the search form about `app\models\Fregat\Impemployee`.
 */
class ImpemployeeSearch extends Impemployee {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idemployee.employee_id',
            'idemployee.employee_fio',
            'idemployee.iddolzh.dolzh_name',
            'idemployee.idpodraz.podraz_name',
            'idemployee.idbuild.build_name',
            'idemployee.idperson.auth_user_fullname',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['impemployee_id', 'id_importemployee', 'id_employee'], 'integer'],
            [['idemployee.employee_id', 'idemployee.employee_fio', 'idemployee.iddolzh.dolzh_name', 'idemployee.idbuild.build_name', 'idemployee.idpodraz.podraz_name', 'idemployee.idperson.auth_user_fullname'], 'safe'],
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
        $query = Impemployee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'idemployee' => function($query) {
                $query->from(['idemployee' => 'employee']);
                $query->joinWith([
                    'iddolzh' => function($query) {
                        $query->from(['iddolzh' => 'dolzh']);
                    }]);
                        $query->joinWith([
                            'idpodraz' => function($query) {
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
                                            },
                                                ]);

                                                $this->load($params);
                                                $this->id_importemployee = $params['id'];

                                                if (!$this->validate()) {
                                                    // uncomment the following line if you do not want to return any records when validation fails
                                                    // $query->where('0=1');
                                                    return $dataProvider;
                                                }

                                                $query->andFilterWhere([
                                                    'impemployee_id' => $this->impemployee_id,
                                                    'id_importemployee' => $this->id_importemployee,
                                                    'id_employee' => $this->id_employee,
                                                ]);

                                                $query->andFilterWhere(['LIKE', 'idemployee.employee_id', $this->getAttribute('idemployee.employee_id')]);
                                                $query->andFilterWhere(['LIKE', 'idemployee.employee_fio', $this->getAttribute('idemployee.employee_fio')]);
                                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idemployee.iddolzh.dolzh_name')]);
                                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idemployee.idpodraz.podraz_name')]);
                                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idemployee.idbuild.build_name')]);
                                                $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idemployee.idperson.auth_user_fullname')]);


                                                $dataProvider->sort->attributes['idemployee.employee_id'] = [
                                                    'asc' => ['idemployee.employee_id' => SORT_ASC],
                                                    'desc' => ['idemployee.employee_id' => SORT_DESC],
                                                ];

                                                $dataProvider->sort->attributes['idemployee.employee_fio'] = [
                                                    'asc' => ['idemployee.employee_fio' => SORT_ASC],
                                                    'desc' => ['idemployee.employee_fio' => SORT_DESC],
                                                ];

                                                $dataProvider->sort->attributes['idemployee.iddolzh.dolzh_name'] = [
                                                    'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                                    'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                                ];

                                                $dataProvider->sort->attributes['idemployee.idbuild.build_name'] = [
                                                    'asc' => ['idbuild.build_name' => SORT_ASC],
                                                    'desc' => ['idbuild.build_name' => SORT_DESC],
                                                ];

                                                $dataProvider->sort->attributes['idemployee.idpodraz.podraz_name'] = [
                                                    'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                                                    'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                                                ];

                                                $dataProvider->sort->attributes['idemployee.idperson.auth_user_fullname'] = [
                                                    'asc' => ['idperson.auth_user_fullname' => SORT_ASC],
                                                    'desc' => ['idperson.auth_user_fullname' => SORT_DESC],
                                                ];

                                                return $dataProvider;
                                            }

                                            public function searchForimportemployee($params) {
                                                $query = Impemployee::find();

                                                $dataProvider = new ActiveDataProvider([
                                                    'query' => $query,
                                                ]);

                                                $query->joinWith([
                                                    'idemployee' => function($query) {
                                                        $query->from(['idemployee' => 'employee']);
                                                        $query->joinWith([
                                                            'iddolzh' => function($query) {
                                                                $query->from(['iddolzh' => 'dolzh']);
                                                            }]);
                                                                $query->joinWith([
                                                                    'idpodraz' => function($query) {
                                                                        $query->from(['idpodraz' => 'podraz']);
                                                                    }]);
                                                                        $query->joinWith([
                                                                            'idbuild' => function($query) {
                                                                                $query->from(['idbuild' => 'build']);
                                                                            }]);
                                                                            },
                                                                                ]);

                                                                                $this->load($params);
                                                                                $this->id_importemployee = $params['id'];

                                                                                if (!$this->validate()) {
                                                                                    // uncomment the following line if you do not want to return any records when validation fails
                                                                                    // $query->where('0=1');
                                                                                    return $dataProvider;
                                                                                }

                                                                                $query->andFilterWhere([
                                                                                    'impemployee_id' => $this->impemployee_id,
                                                                                    'id_importemployee' => $this->id_importemployee,
                                                                                    'id_employee' => $this->id_employee,
                                                                                ]);

                                                                                $query->andFilterWhere(['LIKE', 'idemployee.employee_id', $this->getAttribute('idemployee.employee_id')]);
                                                                                $query->andFilterWhere(['LIKE', 'idemployee.employee_fio', $this->getAttribute('idemployee.employee_fio')]);
                                                                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idemployee.iddolzh.dolzh_name')]);
                                                                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idemployee.idpodraz.podraz_name')]);
                                                                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idemployee.idbuild.build_name')]);

                                                                                if (empty($query->orderBy))
                                                                                    $query->orderBy('idemployee.employee_fio');

                                                                                $dataProvider->sort->attributes['idemployee.employee_id'] = [
                                                                                    'asc' => ['idemployee.employee_id' => SORT_ASC],
                                                                                    'desc' => ['idemployee.employee_id' => SORT_DESC],
                                                                                ];

                                                                                $dataProvider->sort->attributes['idemployee.employee_fio'] = [
                                                                                    'asc' => ['idemployee.employee_fio' => SORT_ASC],
                                                                                    'desc' => ['idemployee.employee_fio' => SORT_DESC],
                                                                                ];

                                                                                $dataProvider->sort->attributes['idemployee.iddolzh.dolzh_name'] = [
                                                                                    'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                                                                    'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                                                                ];

                                                                                $dataProvider->sort->attributes['idemployee.idbuild.build_name'] = [
                                                                                    'asc' => ['idbuild.build_name' => SORT_ASC],
                                                                                    'desc' => ['idbuild.build_name' => SORT_DESC],
                                                                                ];

                                                                                $dataProvider->sort->attributes['idemployee.idpodraz.podraz_name'] = [
                                                                                    'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                                                                                    'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                                                                                ];

                                                                                return $dataProvider;
                                                                            }

                                                                        }
                                                                        