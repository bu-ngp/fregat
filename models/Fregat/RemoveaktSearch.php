<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Removeakt;
use app\func\Proc;
use yii\db\Query;

/**
 * RemoveaktSearch represents the model behind the search form about `app\models\Fregat\Removeakt`.
 */
class RemoveaktSearch extends Removeakt
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idRemover.idperson.auth_user_fullname', 'idRemover.iddolzh.dolzh_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['removeakt_id', 'id_remover'], 'integer'],
            [['removeakt_date', 'idRemover.idperson.auth_user_fullname', 'idRemover.iddolzh.dolzh_name'], 'safe'],
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
        $query = Removeakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['removeakt_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idRemover.idperson', 'idRemover.iddolzh',]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'removeakt_id' => $this->removeakt_id,
            'id_remover' => $this->id_remover,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'removeakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idRemover.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idRemover.iddolzh.dolzh_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idRemover.idperson.auth_user_fullname', 'idRemover.iddolzh.dolzh_name']);

        $this->removeaktDopFilter($query);

        return $dataProvider;
    }

    private function removeaktDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'RemoveaktFilter');

        if (!empty($filter)) {

            $attr = 'mat_id_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattraffic.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trRmMat.id_removeakt')
                    ->from('tr_rm_mat trRmMat')
                    ->leftJoin('tr_mat trMat', 'trMat.tr_mat_id = trRmMat.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trMat.id_mattraffic')
                    ->andWhere('trRmMat.id_removeakt = removeakt.removeakt_id')
            ]);

            $attr = 'mol_id_person';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('trRmMat.id_removeakt')
                    ->from('tr_rm_mat trRmMat')
                    ->leftJoin('tr_mat trMat', 'trMat.tr_mat_id = trRmMat.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trMat.id_mattraffic')
                    ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                    ->andWhere('trRmMat.id_removeakt = removeakt.removeakt_id')
            ]);

            $attr = 'id_parent';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idParent.id_material',
                'ExistsSubQuery' => (new Query())
                    ->select('trRmMat.id_removeakt')
                    ->from('tr_rm_mat trRmMat')
                    ->leftJoin('tr_mat trMat', 'trMat.tr_mat_id = trRmMat.id_tr_mat')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trMat.id_mattraffic')
                    ->leftJoin('mattraffic idParent', 'idParent.mattraffic_id = trMat.id_parent')
                    ->andWhere('trRmMat.id_removeakt = removeakt.removeakt_id')
            ]);

        }
    }
}
