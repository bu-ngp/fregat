<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Impemployee;

/**
 * ImpemployeeSearch represents the model behind the search form about `app\models\Impemployee`.
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
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['impemployee_id', 'id_importemployee', 'id_employee'], 'integer'],
            [['idemployee.employee_id', 'idemployee.employee_fio', 'idemployee.iddolzh.dolzh_name', 'idemployee.idbuild.build_name', 'idemployee.idpodraz.podraz_name'], 'safe'],
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

                                        return $dataProvider;
                                    }

                                }
                                