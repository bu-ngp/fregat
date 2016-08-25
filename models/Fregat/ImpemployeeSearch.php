<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Impemployee;
use app\func\Proc;

/**
 * ImpemployeeSearch represents the model behind the search form about `app\models\Fregat\Impemployee`.
 */
class ImpemployeeSearch extends Impemployee
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idemployee.employee_id',
            'idemployee.iddolzh.dolzh_name',
            'idemployee.idpodraz.podraz_name',
            'idemployee.idbuild.build_name',
            'idemployee.idperson.auth_user_fullname',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['impemployee_id', 'id_importemployee', 'id_employee'], 'integer'],
            [['idemployee.employee_id', 'idemployee.iddolzh.dolzh_name', 'idemployee.idbuild.build_name', 'idemployee.idpodraz.podraz_name', 'idemployee.idperson.auth_user_fullname'], 'safe'],
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
        $query = Impemployee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'idemployee.idperson',
            'idemployee.iddolzh',
            'idemployee.idpodraz',
            'idemployee.idbuild',
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
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idemployee.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idemployee.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idemployee.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idemployee.idperson.auth_user_fullname')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idemployee.employee_id',
            'idemployee.iddolzh.dolzh_name',
            'idemployee.idbuild.build_name',
            'idemployee.idpodraz.podraz_name',
            'idemployee.idperson.auth_user_fullname',
        ]);

        return $dataProvider;
    }

    public function searchForimportemployee($params)
    {
        $query = Impemployee::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idemployee.idperson.auth_user_fullname' => SORT_ASC]],
        ]);

        $query->joinWith([
            'idemployee.iddolzh',
            'idemployee.idpodraz',
            'idemployee.idbuild',
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
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idemployee.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idemployee.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idemployee.idbuild.build_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idemployee.employee_id',
            'idemployee.iddolzh.dolzh_name',
            'idemployee.idbuild.build_name',
            'idemployee.idpodraz.podraz_name',
        ]);

        return $dataProvider;
    }

}
                                                                        