<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Recoveryrecieveakt;
use app\func\Proc;

/**
 * RecoveryrecieveaktSearch represents the model behind the search form about `app\models\Fregat\Recoveryrecieveakt`.
 */
class RecoveryrecieveaktSearch extends Recoveryrecieveakt
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idOsmotrakt.osmotrakt_id',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idOsmotrakt.idTrosnov.idCabinet.cabinet_name',
            'idOsmotrakt.idReason.reason_text',
            'idOsmotrakt.osmotrakt_comment',
            'idOsmotrakt.idMaster.idperson.auth_user_fullname',
            'idOsmotrakt.idMaster.iddolzh.dolzh_name',
            'idOsmotrakt.osmotrakt_date',
            'idRecoverysendakt.recoverysendakt_date',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recoveryrecieveakt_id', 'id_osmotrakt', 'id_recoverysendakt', 'recoveryrecieveakt_repaired'], 'integer'],
            [['recoveryrecieveakt_result', 'recoveryrecieveakt_date'], 'safe'],
            [[
                'idOsmotrakt.osmotrakt_id',
                'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
                'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
                'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
                'idOsmotrakt.idTrosnov.idCabinet.cabinet_name',
                'idOsmotrakt.idReason.reason_text',
                'idOsmotrakt.osmotrakt_comment',
                'idOsmotrakt.idMaster.idperson.auth_user_fullname',
                'idOsmotrakt.idMaster.iddolzh.dolzh_name',
                'idOsmotrakt.osmotrakt_date',
                'idRecoverysendakt.recoverysendakt_date',
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
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial',
            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild',
            'idOsmotrakt.idReason',
            'idOsmotrakt.idMaster.idperson',
            'idOsmotrakt.idMaster.iddolzh',
        ]);
    }

    private function baseFilter(&$query)
    {
        $query->andFilterWhere([
            'recoveryrecieveakt_id' => $this->recoveryrecieveakt_id,
            'id_osmotrakt' => $this->id_osmotrakt,
            'id_recoverysendakt' => $this->id_recoverysendakt,
            'recoveryrecieveakt_repaired' => $this->recoveryrecieveakt_repaired,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotrakt.osmotrakt_id'));
        $query->andFilterWhere(['like', 'recoveryrecieveakt_result', $this->recoveryrecieveakt_result]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoveryrecieveakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idCabinet.cabinet_name', $this->getAttribute('idOsmotrakt.idTrosnov.idCabinet.cabinet_name')]);
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idOsmotrakt.idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'idOsmotrakt.osmotrakt_comment', $this->getAttribute('idOsmotrakt.osmotrakt_comment')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idOsmotrakt.idMaster.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idOsmotrakt.idMaster.iddolzh.dolzh_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotrakt.osmotrakt_date', Proc::Date));
    }

    private function baseSort(&$dataProvider)
    {
        Proc::AssignRelatedAttributes($dataProvider, [
            'idOsmotrakt.osmotrakt_id',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_inv',
            'idOsmotrakt.idTrosnov.idMattraffic.idMaterial.material_name',
            'idOsmotrakt.idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idOsmotrakt.idTrosnov.idCabinet.cabinet_name',
            'idOsmotrakt.idReason.reason_text',
            'idOsmotrakt.osmotrakt_comment',
            'idOsmotrakt.idMaster.idperson.auth_user_fullname',
            'idOsmotrakt.idMaster.iddolzh.dolzh_name',
            'idOsmotrakt.osmotrakt_date',
        ]);
    }

    public function searchbase($params)
    {
        $query = Recoveryrecieveakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'recoveryrecieveakt_id' => $params['id'],
        ]);

        return $dataProvider;
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
        $query = Recoveryrecieveakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['recoveryrecieveakt_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id_recoverysendakt' => (string)filter_input(INPUT_GET, 'id'),
        ]);

        $this->baseFilter($query);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchformaterialkarta($params)
    {
        $query = Recoveryrecieveakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idRecoverysendakt.recoverysendakt_date' => SORT_DESC, 'recoveryrecieveakt_date' => SORT_DESC]],
        ]);

        $query->joinWith(['idRecoverysendakt', 'idOsmotrakt.idTrosnov.idMattraffic',]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'recoveryrecieveakt_repaired' => $this->recoveryrecieveakt_repaired,
            'idMattraffic.id_material' => $params['id'],
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'id_recoverysendakt'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idRecoverysendakt.recoverysendakt_date'), 'date');
        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoveryrecieveakt_date'), 'date');
        $query->andFilterWhere(['LIKE', 'recoveryrecieveakt_result', $this->getAttribute('recoveryrecieveakt_result')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'id_osmotrakt'));

        Proc::AssignRelatedAttributes($dataProvider, [
            'idRecoverysendakt.recoverysendakt_date',
        ]);

        return $dataProvider;
    }

}
                                                                        