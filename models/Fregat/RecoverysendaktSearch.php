<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Recoverysendakt;
use app\func\Proc;
use yii\db\Query;

/**
 * RecoverysendaktSearch represents the model behind the search form about `app\models\Fregat\Recoverysendakt`.
 */
class RecoverysendaktSearch extends Recoverysendakt
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idOrgan.organ_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recoverysendakt_id', 'id_organ'], 'integer'],
            [['recoverysendakt_date', 'idOrgan.organ_name'], 'safe'],
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
        $query = Recoverysendakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['recoverysendakt_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idOrgan']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_organ' => $this->id_organ,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoverysendakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoverysendakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idOrgan.organ_name', $this->getAttribute('idOrgan.organ_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idOrgan.organ_name']);

        $this->recoverysendaktDopFilter($query);

        return $dataProvider;
    }

    private function recoverysendaktDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'RecoverysendaktFilter');

        if (!empty($filter)) {

            $attr = 'mat_id_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('recoveryrecieveakts.id_recoverysendakt')
                    ->from('recoveryrecieveakt recoveryrecieveakts')
                    ->leftJoin('osmotrakt idOsmotrakt', 'idOsmotrakt.osmotrakt_id = recoveryrecieveakts.id_osmotrakt')
                    ->leftJoin('tr_osnov idTrosnov', 'idOsmotrakt.id_tr_osnov = idTrosnov.tr_osnov_id')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrosnov.id_mattraffic')
                    ->andWhere('recoveryrecieveakts.id_recoverysendakt = recoverysendakt.recoverysendakt_id')
            ]);

            $attr = 'recoverysendakt_closed_mark';
            if ($filter[$attr] === '1')
                Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                    'Attribute' => $attr,
                    'WhereStatement' => ['not exists', (new Query())
                        ->select('rsa.recoverysendakt_id')
                        ->from('recoverysendakt rsa')
                        ->leftJoin('recoveryrecieveakt rra', 'rsa.recoverysendakt_id = rra.id_recoverysendakt')
                        ->leftJoin('recoveryrecieveaktmat rramat', 'rsa.recoverysendakt_id = rramat.id_recoverysendakt')
                        ->andWhere(['rra.recoveryrecieveakt_repaired' => NULL])
                        ->andWhere(['rramat.recoveryrecieveaktmat_repaired' => NULL])
                        ->andWhere('rsa.recoverysendakt_id = recoverysendakt.recoverysendakt_id')],
                ]);

            $attr = 'recoverysendakt_opened_mark';
            if ($filter[$attr] === '1')
                Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                    'Attribute' => $attr,
                    'WhereStatement' => ['exists', (new Query())
                        ->select('rsa.recoverysendakt_id')
                        ->from('recoverysendakt rsa')
                        ->leftJoin('recoveryrecieveakt rra', 'rsa.recoverysendakt_id = rra.id_recoverysendakt')
                        ->leftJoin('recoveryrecieveaktmat rramat', 'rsa.recoverysendakt_id = rramat.id_recoverysendakt')
                        ->andWhere(['rra.recoveryrecieveakt_repaired' => NULL])
                        ->andWhere(['rramat.recoveryrecieveaktmat_repaired' => NULL])
                        ->andWhere('rsa.recoverysendakt_id = recoverysendakt.recoverysendakt_id')],
                ]);

            $attr = 'recoveryrecieveakt_repaired';
            Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('rsa.recoverysendakt_id')
                    ->from('recoverysendakt rsa')
                    ->leftJoin('recoveryrecieveakt rra', 'rsa.recoverysendakt_id = rra.id_recoverysendakt')
                    ->leftJoin('recoveryrecieveaktmat rramat', 'rsa.recoverysendakt_id = rramat.id_recoverysendakt')
                    ->andWhere(['or', ['rra.recoveryrecieveakt_repaired' => $filter[$attr]], ['rramat.recoveryrecieveaktmat_repaired' => $filter[$attr]]])
                    ->andWhere('rsa.recoverysendakt_id = recoverysendakt.recoverysendakt_id')],
            ]);

            $attr = 'mol_id_person';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('recoveryrecieveakts.id_recoverysendakt')
                    ->from('recoveryrecieveakt recoveryrecieveakts')
                    ->leftJoin('osmotrakt idOsmotrakt', 'idOsmotrakt.osmotrakt_id = recoveryrecieveakts.id_osmotrakt')
                    ->leftJoin('tr_osnov idTrosnov', 'idOsmotrakt.id_tr_osnov = idTrosnov.tr_osnov_id')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrosnov.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('recoveryrecieveakts.id_recoverysendakt = recoverysendakt.recoverysendakt_id')
            ]);

            $attr = 'mat_id_material_mat';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('recoveryrecieveaktmats.id_recoverysendakt')
                    ->from('recoveryrecieveaktmat recoveryrecieveaktmats')
                    ->leftJoin('tr_mat_osmotr idTrMatOsmotr', 'idTrMatOsmotr.tr_mat_osmotr_id = recoveryrecieveaktmats.id_tr_mat_osmotr')
                    ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = idTrMatOsmotr.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrMat.id_mattraffic')
                    ->andWhere('recoveryrecieveaktmats.id_recoverysendakt = recoverysendakt.recoverysendakt_id')
            ]);

            $attr = 'mol_id_person_mat';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('recoveryrecieveaktmats.id_recoverysendakt')
                    ->from('recoveryrecieveaktmat recoveryrecieveaktmats')
                    ->leftJoin('tr_mat_osmotr idTrMatOsmotr', 'idTrMatOsmotr.tr_mat_osmotr_id = recoveryrecieveaktmats.id_tr_mat_osmotr')
                    ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = idTrMatOsmotr.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrMat.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('recoveryrecieveaktmats.id_recoverysendakt = recoverysendakt.recoverysendakt_id')
            ]);
        }
    }

}
        