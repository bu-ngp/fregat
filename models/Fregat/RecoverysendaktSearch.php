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
                /*    'WhereStatement' => ['not exists', (new Query())
                        ->select('recoveryrecieveakts.id_recoverysendakt')
                        ->from('recoveryrecieveakt recoveryrecieveakts')
                        ->andWhere(['recoveryrecieveakt_repaired' => NULL])
                        ->andWhere('recoveryrecieveakts.id_recoverysendakt = recoverysendakt.recoverysendakt_id')],*/
                    'WhereStatement' => ['not exists', (new Query())
                        ->select('recoveryrecieveakts.id_recoverysendakt')
                        ->from('recoveryrecieveakt recoveryrecieveakts')
                        ->andWhere(['recoveryrecieveakt_repaired' => NULL])
                        ->andWhere('recoveryrecieveakts.id_recoverysendakt = recoverysendakt.recoverysendakt_id')],
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
        }
    }

}
        