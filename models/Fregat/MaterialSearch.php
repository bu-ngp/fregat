<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Material;
use app\func\Proc;
use yii\db\Expression;
use yii\db\Query;

/**
 * MaterialSearch represents the model behind the search form about `app\models\Fregat\Material`.
 */
class MaterialSearch extends Material
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMatv.matvid_name',
            'idIzmer.izmer_name',
            'mattraffics.mattraffic_lastchange',
            'mattraffics.mattraffic_username',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['material_id', 'material_tip', 'material_writeoff', 'id_matvid', 'id_izmer', 'material_importdo'], 'integer'],
            [['material_name', 'material_name1c', 'material_1c', 'material_inv', 'material_serial', 'material_release', 'material_username', 'material_lastchange', 'idMatv.matvid_name', 'idIzmer.izmer_name'], 'safe'],
            [['material_number', 'material_price'], 'safe'],
            [[
                'mattraffics.mattraffic_lastchange',
                'mattraffics.mattraffic_username',
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
        $query = Material::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['material_name' => SORT_ASC]],
        ]);

        $query->joinWith(['idMatv', 'idIzmer']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'material_id' => $this->material_id,
            'material_tip' => $this->material_tip,
            'material_writeoff' => $this->material_writeoff,
            'id_matvid' => $this->id_matvid,
            'id_izmer' => $this->id_izmer,
            'material_importdo' => $this->material_importdo,
        ]);

        $query->andFilterWhere(['like', 'material_name', $this->material_name])
            ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
            ->andFilterWhere(['like', 'material_1c', $this->material_1c])
            ->andFilterWhere(['like', 'material_inv', $this->material_inv])
            ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_release', 'date'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_price'));
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idIzmer.izmer_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_username'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_lastchange', 'datetime'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMatv.matvid_name',
            'idIzmer.izmer_name',
        ]);

        $this->materialDopfilter($query);

        return $dataProvider;
    }

    private function materialDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'MaterialFilter');

        if (!empty($filter)) {

            $attr = 'mol_fullname_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('employee idMol', 'idMol.employee_id = mattraffics.id_mol')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere(['in', 'mattraffics.mattraffic_tip', [1, 2]])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            Proc::Filter_Compare(Proc::Strict, $query, $filter, ['Attribute' => 'material_writeoff']);

            $attr = 'mol_id_build';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_build',
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('employee idMol', 'idMol.employee_id = mattraffics.id_mol')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (3)')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere(['in', 'mattraffics.mattraffic_tip', [3]])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'tr_osnov_kab';
            Proc::Filter_Compare(Proc::Text, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'trOsnovs.' . $attr,
                'LikeManual' => true,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (3)')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere(['in', 'mattraffics.mattraffic_tip', [3]])
                    ->andWhere('mattraffics.id_material = material.material_id')

            ]);

            $attr = 'mattraffic_username';
            Proc::Filter_Compare(Proc::Text, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'mattraffics.' . $attr,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'mattraffic_lastchange';
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'mattraffics.' . $attr,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);
        }
    }

    public function searchforinstallakt_mat($params)
    {
        $query = Material::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['material_name' => SORT_ASC]],
        ]);

        $query->joinWith(['idMatv', 'idIzmer']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'material_id' => $this->material_id,
            'material_tip' => $this->material_tip,
            'material_writeoff' => $this->material_writeoff,
            'id_matvid' => $this->id_matvid,
            'id_izmer' => $this->id_izmer,
            'material_importdo' => $this->material_importdo,
        ]);

        $query->andFilterWhere(['like', 'material_name', $this->material_name])
            ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
            ->andFilterWhere(['like', 'material_1c', $this->material_1c])
            ->andFilterWhere(['like', 'material_inv', $this->material_inv])
            ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_release', 'date'));
        //    $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));
        $query->andWhere(['material_number' => 1]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_price'));
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idIzmer.izmer_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_username'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_lastchange', 'datetime'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));

        Proc::AssignRelatedAttributes($dataProvider, ['idMatv.matvid_name', 'idIzmer.izmer_name']);

        return $dataProvider;
    }

}
                        