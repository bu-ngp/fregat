<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Spismat;
use yii\db\Query;

/**
 * SpismatSearch represents the model behind the search form about `app\models\Fregat\Spismat`.
 */
class SpismatSearch extends Spismat
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spismat_id', 'id_mol'], 'integer'],
            [['spismat_date'], 'safe'],
            [[
                'idMol.idperson.auth_user_fullname',
                'idMol.iddolzh.dolzh_name',
                'idMol.idpodraz.podraz_name',
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
        $query = Spismat::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['spismat_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMol.idperson',
            'idMol.iddolzh',
            'idMol.idpodraz',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_mol' => $this->id_mol,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'spismat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'spismat_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idMol.idpodraz.podraz_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
        ]);

        $this->spismatDopFilter($query);

        return $dataProvider;
    }

    private function spismatDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'SpismatFilter');

        if (!empty($filter)) {

            $attr = 'mat_id_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('spismatmaterials.id_spismat')
                    ->from('spismatmaterials spismatmaterials')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = spismatmaterials.id_mattraffic')
                    ->andWhere('spismatmaterials.id_spismat = spismat.spismat_id')
            ]);

            $attr = 'mol_id_person';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('spismatmaterials.id_spismat')
                    ->from('spismatmaterials spismatmaterials')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = spismatmaterials.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('spismatmaterials.id_spismat = spismat.spismat_id')
            ]);

        }
    }
}
