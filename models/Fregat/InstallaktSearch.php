<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Installakt;
use app\func\Proc;
use yii\db\Query;

/**
 * InstallaktSearch represents the model behind the search form about `app\models\Fregat\Installakt`.
 */
class InstallaktSearch extends Installakt
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idInstaller.idperson.auth_user_fullname', 'idInstaller.iddolzh.dolzh_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['installakt_id', 'id_installer'], 'integer'],
            [['installakt_date', 'idInstaller.idperson.auth_user_fullname', 'idInstaller.iddolzh.dolzh_name'], 'safe'],
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
        $query = Installakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['installakt_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idInstaller.idperson', 'idInstaller.iddolzh']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'installakt_id' => $this->installakt_id,
            'id_installer' => $this->id_installer,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'installakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idInstaller.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idInstaller.iddolzh.dolzh_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idInstaller.idperson.auth_user_fullname', 'idInstaller.iddolzh.dolzh_name']);

        $this->installaktDopFilter($query);

        return $dataProvider;
    }

    private function installaktDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'InstallaktFilter');

        if (!empty($filter)) {

            $attr = 'mat_id_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trOsnov.id_installakt')
                    ->from('tr_osnov trOsnov')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trOsnov.id_mattraffic')
                    ->andWhere('trOsnov.id_installakt = installakt.installakt_id')
            ]);

            $attr = 'mol_id_person';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('trOsnov.id_installakt')
                    ->from('tr_osnov trOsnov')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trOsnov.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('trOsnov.id_installakt = installakt.installakt_id')
            ]);

            $attr = 'tr_osnov_mol_id_build';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_build',
                'ExistsSubQuery' => (new Query())
                    ->select('trOsnov.id_installakt')
                    ->from('tr_osnov trOsnov')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trOsnov.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('trOsnov.id_installakt = installakt.installakt_id')
            ]);

            $attr = 'tr_osnov_kab';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'trOsnov.tr_osnov_kab',
                'ExistsSubQuery' => (new Query())
                    ->select('trOsnov.id_installakt')
                    ->from('tr_osnov trOsnov')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trOsnov.id_mattraffic')
                    ->andWhere('trOsnov.id_installakt = installakt.installakt_id')
            ]);

            $attr = 'mat_id_material_trmat';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trMat.id_installakt')
                    ->from('tr_mat trMat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trMat.id_mattraffic')
                    ->andWhere('trMat.id_installakt = installakt.installakt_id')
            ]);

            $attr = 'mol_id_person_trmat';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('trMat.id_installakt')
                    ->from('tr_mat trMat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trMat.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('trMat.id_installakt = installakt.installakt_id')
            ]);

            $attr = 'id_parent';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idParent.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trMat.id_installakt')
                    ->from('tr_mat trMat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trMat.id_mattraffic')
                    ->leftJoin('mattraffic idParent', 'idParent.mattraffic_id = trMat.id_parent')
                    ->andWhere('trMat.id_installakt = installakt.installakt_id')
            ]);
        }
    }
}
                