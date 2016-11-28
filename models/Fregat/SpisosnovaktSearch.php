<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Spisosnovakt;
use yii\db\Query;

/**
 * SpisosnovaktSearch represents the model behind the search form about `app\models\Fregat\Spisosnovakt`.
 */
class SpisosnovaktSearch extends Spisosnovakt
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
            'idEmployee.idperson.auth_user_fullname',
            'idEmployee.iddolzh.dolzh_name',
            'idSchetuchet.schetuchet_kod',
            'idSchetuchet.schetuchet_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spisosnovakt_id', 'id_schetuchet', 'id_mol', 'id_employee'], 'integer'],
            [['spisosnovakt_date'], 'safe'],
            [[
                'idMol.idperson.auth_user_fullname',
                'idMol.iddolzh.dolzh_name',
                'idMol.idpodraz.podraz_name',
                'idEmployee.idperson.auth_user_fullname',
                'idEmployee.iddolzh.dolzh_name',
                'idSchetuchet.schetuchet_kod',
                'idSchetuchet.schetuchet_name',
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
        $query = Spisosnovakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith([
            'idMol.idperson idpersonmol',
            'idMol.iddolzh iddolzhmol',
            'idMol.idpodraz idpodrazmol',
            'idEmployee.idperson',
            'idEmployee.iddolzh',
            'idSchetuchet',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_schetuchet' => $this->id_schetuchet,
            'id_mol' => $this->id_mol,
            'id_employee' => $this->id_employee,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'spisosnovakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'spisosnovakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idpersonmol.auth_user_fullname', $this->getAttribute('idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzhmol.dolzh_name', $this->getAttribute('idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodrazmol.podraz_name', $this->getAttribute('idMol.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idEmployee.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idEmployee.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idSchetuchet.schetuchet_kod', $this->getAttribute('idSchetuchet.schetuchet_kod')]);
        $query->andFilterWhere(['LIKE', 'idSchetuchet.schetuchet_name', $this->getAttribute('idSchetuchet.schetuchet_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMol.idperson.auth_user_fullname' => 'idpersonmol',
            'idMol.iddolzh.dolzh_name' => 'iddolzhmol',
            'idMol.idpodraz.podraz_name' => 'idpodrazmol',
            'idEmployee.idperson.auth_user_fullname',
            'idEmployee.iddolzh.dolzh_name',
            'idSchetuchet.schetuchet_kod',
            'idSchetuchet.schetuchet_name',
        ]);

        $this->spisosnovaktDopFilter($query);

        return $dataProvider;
    }

    private function spisosnovaktDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'SpisosnovaktFilter');

        if (!empty($filter)) {

            $attr = 'mat_id_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('spisosnovmaterials.id_spisosnovakt')
                    ->from('spisosnovmaterials spisosnovmaterials')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = spisosnovmaterials.id_mattraffic')
                    ->andWhere('spisosnovmaterials.id_spisosnovakt = spisosnovakt.spisosnovakt_id')
            ]);

            $attr = 'mol_id_person';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('spisosnovmaterials.id_spisosnovakt')
                    ->from('spisosnovmaterials spisosnovmaterials')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = spisosnovmaterials.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('spisosnovmaterials.id_spisosnovakt = spisosnovakt.spisosnovakt_id')
            ]);

        }
    }
}
