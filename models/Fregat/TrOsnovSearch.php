<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrOsnov;
use app\func\Proc;

/**
 * TrOsnovSearch represents the model behind the search form about `app\models\Fregat\TrOsnov`.
 */
class TrOsnovSearch extends TrOsnov
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMattraffic.mattraffic_date',
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.idMaterial.material_serial',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idMattraffic.idMol.idbuild.build_name',
            'idInstallakt.installakt_id',
            'idInstallakt.installakt_date',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_osnov_id', 'id_installakt', 'id_mattraffic'], 'integer'],
            [['tr_osnov_kab', 'idMattraffic.idMaterial.material_name',
                'idMattraffic.mattraffic_date',
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.mattraffic_number',
                'idMattraffic.idMol.idperson.auth_user_fullname',
                'idMattraffic.idMol.iddolzh.dolzh_name',
                'idMattraffic.idMaterial.material_serial',
                'idMattraffic.idMol.idbuild.build_name',
                'idInstallakt.installakt_id',
                'idInstallakt.installakt_date',
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

    private function baseRelations(&$query)
    {
        $query->joinWith([
            'idMattraffic.idMol.idperson',
            'idMattraffic.idMol.iddolzh',
            'idMattraffic.idMol.idpodraz',
            'idMattraffic.idMol.idbuild',
            'idInstallakt',
        ])
            ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'idMattraffic.id_material = m2.id_material_m2 and idMattraffic.id_mol = m2.id_mol_m2 and idMattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (3)');
    }

    private function baseFilter(&$query)
    {
        $query->andWhere('idMattraffic.mattraffic_number > 0');
        $query->andWhere(['in', 'idMattraffic.mattraffic_tip', [3]]);
        $query->andWhere(['m2.mattraffic_date_m2' => NULL]);

        $query->andFilterWhere(['like', 'tr_osnov_kab', $this->tr_osnov_kab]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idInstallakt.installakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idInstallakt.installakt_date', 'date'));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idMattraffic.idMaterial.material_serial')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);

        // Последнее перемещение
        $query->andWhere('idInstallakt.installakt_date IN (SELECT max(inst.installakt_date) FROM installakt inst RIGHT join tr_osnov ts ON inst.installakt_id = ts.id_installakt LEFT JOIN mattraffic mt ON ts.id_mattraffic = mt.mattraffic_id GROUP BY mt.id_material)');
    }

    private function baseSort(&$dataProvider)
    {
        Proc::AssignRelatedAttributes($dataProvider, [
            'idInstallakt.installakt_id',
            'idInstallakt.installakt_date',
            'idMattraffic.mattraffic_date',
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.idMaterial.material_serial',
            'idMattraffic.idMol.idbuild.build_name',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
        ]);
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
        $query = TrOsnov::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_osnov_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMattraffic.idMaterial',
            'idMattraffic.idMol.idperson',
            'idMattraffic.idMol.iddolzh',
            'idMattraffic.idMol.idbuild',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'tr_osnov_id' => $this->tr_osnov_id,
            'id_installakt' => (string)filter_input(INPUT_GET, 'id'),
            'id_mattraffic' => $this->id_mattraffic,
        ]);

        $query->andFilterWhere(['like', 'tr_osnov_kab', $this->tr_osnov_kab]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMattraffic.idMol.idbuild.build_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.idMol.idperson.auth_user_fullname',
            'idMattraffic.idMol.iddolzh.dolzh_name',
            'idMattraffic.idMol.idbuild.build_name',
        ]);

        return $dataProvider;
    }

    public function searchforosmotrakt($params)
    {
        $query = TrOsnov::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idMattraffic.mattraffic_date' => SORT_DESC]],
        ]);

        $this->baseRelations($query);
        // Последнее перемещение
        $query->join('LEFT JOIN', '(select mt.id_material, inst.installakt_date from installakt inst RIGHT JOIN tr_osnov ts ON inst.installakt_id = ts.id_installakt LEFT JOIN mattraffic mt ON ts.id_mattraffic = mt.mattraffic_id) lastinst', 'lastinst.id_material = idMattraffic.id_material and idInstallakt.installakt_date < lastinst.installakt_date');
        $query->andWhere(['lastinst.installakt_date' => NULL]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

}
                                                