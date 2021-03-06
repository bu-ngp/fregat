<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\TrMatOsmotr;
use app\func\Proc;

/**
 * TrMatOsmotrSearch represents the model behind the search form about `app\models\Fregat\TrMatOsmotr`.
 */
class TrMatOsmotrSearch extends TrMatOsmotr
{

    public function attributes()
    {
// add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idMattraffic.idMol.idbuild.build_name',
            'idTrMat.idParent.idMaterial.material_name',
            'idTrMat.idParent.idMaterial.material_inv',
            'idReason.reason_text',
            'idOsmotraktmat.osmotraktmat_id',
            'idOsmotraktmat.osmotraktmat_date',
            'idOsmotraktmat.idMaster.idperson.auth_user_fullname',
            'idOsmotraktmat.idMaster.iddolzh.dolzh_name',
            'idTrMat.idInstallakt.installakt_id',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_mat_osmotr_id', 'id_reason', 'id_tr_mat', 'id_osmotraktmat'], 'integer'],
            [['tr_mat_osmotr_comment',
                'tr_mat_osmotr_number',
                'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
                'idTrMat.idMattraffic.idMaterial.material_name',
                'idTrMat.idMattraffic.idMaterial.material_inv',
                'idTrMat.idMattraffic.mattraffic_number',
                'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
                'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
                'idTrMat.idMattraffic.idMol.idbuild.build_name',
                'idTrMat.idParent.idMaterial.material_name',
                'idTrMat.idParent.idMaterial.material_inv',
                'idReason.reason_text',
                'idOsmotraktmat.osmotraktmat_id',
                'idOsmotraktmat.osmotraktmat_date',
                'idOsmotraktmat.idMaster.idperson.auth_user_fullname',
                'idOsmotraktmat.idMaster.iddolzh.dolzh_name',
                'idTrMat.idInstallakt.installakt_id',
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
        $query = TrMatOsmotr::find();

        $query->joinWith([
            'idTrMat.idInstallakt',
            'idTrMat.idMattraffic.idMaterial.idMatv',
            'idTrMat.idMattraffic.idMol.idperson',
            'idTrMat.idMattraffic.idMol.iddolzh',
            'idTrMat.idParent.idMaterial matparent',
            'idReason',
            'idOsmotraktmat',
        ]);

// add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to return any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

// grid filtering conditions
        $query->andFilterWhere([
            'tr_mat_osmotr_id' => $this->tr_mat_osmotr_id,
            'id_tr_mat' => $this->id_tr_mat,
            'id_reason' => $this->id_reason,
            'id_osmotraktmat' => $params['id'],
        ]);

        $query->andFilterWhere(['like', 'tr_mat_osmotr_comment', $this->tr_mat_osmotr_comment]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotraktmat.osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotraktmat.osmotraktmat_date', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'tr_mat_osmotr_number'));
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMat.idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idParent.material_name', $this->getAttribute('idTrMat.idParent.material_name')]);
        $query->andFilterWhere(['LIKE', 'idParent.material_inv', $this->getAttribute('idTrMat.idParent.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idTrMat.idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idTrMat.idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrMat.idInstallakt.installakt_id'));

        Proc::AssignRelatedAttributes($dataProvider, [
            'idTrMat.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.mattraffic_number',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idParent.idMaterial.material_name' => 'matparent',
            'idTrMat.idParent.idMaterial.material_inv' => 'matparent',
            'idReason.reason_text',
            'idTrMat.idInstallakt.installakt_id',
        ]);

        return $dataProvider;
    }

    public function forrecoveryrecieveaktmat($params)
    {
        $query = TrMatOsmotr::find();

        $query->joinWith([
            'idTrMat.idMattraffic.idMaterial',
            'idTrMat.idMattraffic.idMol.idperson',
            'idTrMat.idMattraffic.idMol.iddolzh',
            'idTrMat.idMattraffic.idMol.idbuild',
            'idTrMat.idParent.idMaterial matparent',
            'idOsmotraktmat.idMaster.idperson idpersonmaster',
            'idOsmotraktmat.idMaster.iddolzh iddolzhmaster',
        ]);

// add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to return any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

// grid filtering conditions
        $query->andFilterWhere([
            'tr_mat_osmotr_id' => $this->tr_mat_osmotr_id,
            'id_tr_mat' => $this->id_tr_mat,
            'id_reason' => $this->id_reason,
            'id_osmotraktmat' => $this->id_osmotraktmat,
        ]);

        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMat.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'tr_mat_osmotr_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idTrMat.idMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idTrMat.idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idTrMat.idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotraktmat.osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotraktmat.osmotraktmat_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idpersonmaster.auth_user_fullname', $this->getAttribute('idOsmotraktmat.idMaster.idpersonmaster.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzhmaster.dolzh_name', $this->getAttribute('idOsmotraktmat.idMaster.iddolzhmaster.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'tr_mat_osmotr_comment', $this->getAttribute('tr_mat_osmotr_comment')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idTrMat.idMattraffic.idMaterial.material_name',
            'idTrMat.idMattraffic.idMaterial.material_inv',
            'idTrMat.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrMat.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrMat.idMattraffic.idMol.idbuild.build_name',
            'idTrMat.idParent.idMaterial.material_name' => 'matparent',
            'idTrMat.idParent.idMaterial.material_inv' => 'matparent',
            'idOsmotraktmat.osmotraktmat_id',
            'idOsmotraktmat.osmotraktmat_date',
            'idOsmotraktmat.idMaster.idperson.auth_user_fullname' => 'idpersonmaster',
            'idOsmotraktmat.idMaster.iddolzh.dolzh_name' => 'iddolzhmaster',
            'idReason.reason_text',
        ]);

        return $dataProvider;
    }

    public function searchformaterialkarta($params)
    {
        $query = TrMatOsmotr::find();

        $query->joinWith([
            'idTrMat.idMattraffic',
            'idOsmotraktmat.idMaster.idperson',
            'idOsmotraktmat.idMaster.iddolzh',
            'idReason',
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idOsmotraktmat.osmotraktmat_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
// uncomment the following line if you do not want to return any records when validation fails
// $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['idMattraffic.id_material' => $params['id']]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotraktmat.osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idOsmotraktmat.osmotraktmat_date', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'tr_mat_osmotr_number'));
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'tr_mat_osmotr_comment', $this->getAttribute('tr_mat_osmotr_comment')]);
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idOsmotraktmat.idMaster.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idOsmotraktmat.idMaster.iddolzh.dolzh_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idOsmotraktmat.osmotraktmat_id',
            'idOsmotraktmat.osmotraktmat_date',
            'idReason.reason_text',
            'idOsmotraktmat.idMaster.idperson.auth_user_fullname',
            'idOsmotraktmat.idMaster.iddolzh.dolzh_name',
        ]);

        return $dataProvider;
    }

}
                                                                                                                        