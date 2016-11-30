<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Osmotraktmat;
use app\func\Proc;
use yii\db\Query;

/**
 * OsmotraktmatSearch represents the model behind the search form about `app\models\Fregat\Osmotraktmat`.
 */
class OsmotraktmatSearch extends Osmotraktmat
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
            'trMatOsmotrs.tr_mat_osmotr_id',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['osmotraktmat_id', 'id_master'], 'integer'],
            [[
                'osmotraktmat_date',
                'idMaster.idperson.auth_user_fullname',
                'idMaster.iddolzh.dolzh_name',
                'trMatOsmotrs.tr_mat_osmotr_id',
                'osmotraktmat_countmat',
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

    public function search($params)
    {
        $query = Osmotraktmat::find();
        $query->select(['osmotraktmat_id', 'osmotraktmat_date', 'id_master', 'count(trMatOsmotrs.tr_mat_osmotr_id) AS osmotraktmat_countmat']);
        $query->joinWith(['idMaster.idperson idmasterperson', 'idMaster.iddolzh idmasterdolzh', 'trMatOsmotrs']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['osmotraktmat_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_master' => $this->id_master,
        ]);


        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotraktmat_date', Proc::Date));

        $query->groupBy(['osmotraktmat_id']);
        if (!empty($this->osmotraktmat_countmat)) {
            $w = Proc::WhereConstruct($this, 'osmotraktmat_countmat');
            $query->having('count(osmotraktmat_id) ' . $w[0] . $w[2]);
        }

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
        ]);

        $dataProvider->sort->attributes['osmotraktmat_countmat'] = [
            'asc' => ['count(osmotraktmat_id)' => SORT_ASC],
            'desc' => ['count(osmotraktmat_id)' => SORT_DESC],
        ];

        $this->osmotraktmatDopFilter($query);

        return $dataProvider;
    }


    private function osmotraktmatDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'OsmotraktmatFilter');

        if (!empty($filter)) {

            $attr = 'mat_id_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trMatOsmotr.id_osmotraktmat')
                    ->from('tr_mat_osmotr trMatOsmotr')
                    ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = trMatOsmotr.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrMat.id_mattraffic')
                    ->andWhere('trMatOsmotr.id_osmotraktmat = osmotraktmat.osmotraktmat_id')
            ]);

            $attr = 'mol_id_person';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('trMatOsmotr.id_osmotraktmat')
                    ->from('tr_mat_osmotr trMatOsmotr')
                    ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = trMatOsmotr.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrMat.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('trMatOsmotr.id_osmotraktmat = osmotraktmat.osmotraktmat_id')
            ]);


            $attr = 'reason_text';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'trMatOsmotr.id_reason',
                'ExistsSubQuery' => (new Query())
                    ->select('trMatOsmotr.id_osmotraktmat')
                    ->from('tr_mat_osmotr trMatOsmotr')
                    ->andWhere('trMatOsmotr.id_osmotraktmat = osmotraktmat.osmotraktmat_id')
            ]);

            $attr = 'tr_mat_osmotr_comment';
            Proc::Filter_Compare(Proc::Text, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'trMatOsmotr.tr_mat_osmotr_comment',
                'ExistsSubQuery' => (new Query())
                    ->select('trMatOsmotr.id_osmotraktmat')
                    ->from('tr_mat_osmotr trMatOsmotr')
                    ->andWhere('trMatOsmotr.id_osmotraktmat = osmotraktmat.osmotraktmat_id')
            ]);

            $attr = 'id_parent';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idParent.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trMatOsmotr.id_osmotraktmat')
                    ->from('tr_mat_osmotr trMatOsmotr')
                    ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = trMatOsmotr.id_tr_mat')
                    ->leftJoin('mattraffic idParent', 'idParent.mattraffic_id = idTrMat.id_parent')
            ]);

            $attr = 'installakt_id';
            Proc::Filter_Compare(Proc::Number, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idInstallakt.installakt_id',
                'ExistsSubQuery' => (new Query())
                    ->select('trMatOsmotr.id_osmotraktmat')
                    ->from('tr_mat_osmotr trMatOsmotr')
                    ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = trMatOsmotr.id_tr_mat')
                    ->leftJoin('installakt idInstallakt', 'idInstallakt.installakt_id = idTrMat.id_installakt')
            ]);

        }
    }
}
                