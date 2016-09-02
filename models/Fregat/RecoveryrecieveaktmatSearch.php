<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Recoveryrecieveaktmat;
use app\func\Proc;

/**
 * RecoveryrecieveaktmatSearch represents the model behind the search form about `app\models\Fregat\Recoveryrecieveaktmat`.
 */
class RecoveryrecieveaktmatSearch extends Recoveryrecieveaktmat
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id',
            'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_date',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMatOsmotr.tr_mat_osmotr_number',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild.build_name',
            'idTrMatOsmotr.idReason.reason_text',
            'idTrMatOsmotr.tr_mat_osmotr_comment',
            'idRecoverysendakt.recoverysendakt_date',
            'idTrMatOsmotr.id_osmotraktmat',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recoveryrecieveaktmat_id', 'recoveryrecieveaktmat_repaired', 'id_recoverysendakt', 'id_tr_mat_osmotr'], 'integer'],
            [['recoveryrecieveaktmat_result', 'recoveryrecieveaktmat_date',
                'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id',
                'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_date',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_inv',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_name',
                'idTrMatOsmotr.tr_mat_osmotr_number',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild.build_name',
                'idTrMatOsmotr.idReason.reason_text',
                'idTrMatOsmotr.tr_mat_osmotr_comment',
                'idRecoverysendakt.recoverysendakt_date',
                'idTrMatOsmotr.id_osmotraktmat',
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
            'idTrMatOsmotr.idOsmotraktmat',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild',
            'idTrMatOsmotr.idReason',
        ]);
    }

    private function baseFilter(&$query)
    {
        $query->andFilterWhere([
            'recoveryrecieveaktmat_id' => $this->recoveryrecieveaktmat_id,
            'recoveryrecieveaktmat_repaired' => $this->recoveryrecieveaktmat_repaired,
            'id_recoverysendakt' => $this->id_recoverysendakt,
            'id_tr_mat_osmotr' => $this->id_tr_mat_osmotr,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoveryrecieveaktmat_date', 'date'));
        $query->andFilterWhere(['LIKE', 'recoveryrecieveaktmat_result', $this->getAttribute('recoveryrecieveaktmat_result')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_date', 'date'));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMatOsmotr.tr_mat_osmotr_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idTrMatOsmotr.idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'idTrMatOsmotr.tr_mat_osmotr_comment', $this->getAttribute('idTrMatOsmotr.tr_mat_osmotr_comment')]);
    }

    private function baseSort(&$dataProvider)
    {
        Proc::AssignRelatedAttributes($dataProvider, [
            'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id',
            'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_date',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMatOsmotr.tr_mat_osmotr_number',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild.build_name',
            'idTrMatOsmotr.idReason.reason_text',
            'idTrMatOsmotr.tr_mat_osmotr_comment',
        ]);
    }

    public function search($params)
    {
        $query = Recoveryrecieveaktmat::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['recoveryrecieveaktmat_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        $query->andFilterWhere([
            'id_recoverysendakt' => $params['id'],
        ]);
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchformaterialkarta($params)
    {
        $query = Recoveryrecieveaktmat::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idRecoverysendakt.recoverysendakt_date' => SORT_DESC, 'recoveryrecieveaktmat_date' => SORT_DESC]],

        ]);

        $query->joinWith([
            'idRecoverysendakt',
            'idTrMatOsmotr.idTrMat.idMattraffic',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'recoveryrecieveaktmat_repaired' => $this->recoveryrecieveaktmat_repaired,
            'idMattraffic.id_material' => $params['id'],
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'id_recoverysendakt'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idRecoverysendakt.recoverysendakt_date'), 'date');
        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoveryrecieveaktmat_date'), 'date');
        $query->andFilterWhere(['LIKE', 'recoveryrecieveakt_result', $this->getAttribute('recoveryrecieveaktmat_result')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMatOsmotr.id_osmotraktmat'));

        Proc::AssignRelatedAttributes($dataProvider, [
            'idRecoverysendakt.recoverysendakt_date',
            'idTrMatOsmotr.id_osmotraktmat',
        ]);

        return $dataProvider;
    }

}
                                                                