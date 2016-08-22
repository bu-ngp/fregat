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
class RecoveryrecieveaktmatSearch extends Recoveryrecieveaktmat {

    public function attributes() {
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
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
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
                ], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function baseRelations(&$query) {
        $query->joinWith([
            'idTrMatOsmotr' => function($query) {
                $query->from(['idTrMatOsmotr' => 'tr_mat_osmotr']);
                $query->joinWith([
                    'idOsmotraktmat' => function($query) {
                        $query->from(['idOsmotraktmat' => 'osmotraktmat']);
                    },
                    'idTrMat' => function($query) {
                        $query->from(['idTrMat' => 'tr_mat']);
                        $query->joinWith([
                            'idMattraffic' => function($query) {
                                $query->from(['idMattraffic' => 'mattraffic']);
                                $query->joinWith([
                                    'idMaterial' => function($query) {
                                        $query->from(['idMaterial' => 'material']);
                                    },
                                    'idMol' => function($query) {
                                        $query->from(['idMol' => 'employee']);
                                        $query->joinWith([
                                            'idperson' => function($query) {
                                                $query->from(['idperson' => 'auth_user']);
                                            },
                                            'iddolzh' => function($query) {
                                                $query->from(['iddolzh' => 'dolzh']);
                                            },
                                            'idbuild' => function($query) {
                                                $query->from(['idbuild' => 'build']);
                                            },
                                        ]);
                                    },
                                ]);
                            },
                        ]);
                    },
                    'idReason' => function($query) {
                        $query->from(['idReason' => 'reason']);
                    },
                ]);
            },
        ]);
    }
    
    private function baseFilter(&$query) {
        $query->andFilterWhere([
            'recoveryrecieveaktmat_id' => $this->recoveryrecieveaktmat_id,
            'recoveryrecieveaktmat_repaired' => $this->recoveryrecieveaktmat_repaired,
            'id_recoverysendakt' => $this->id_recoverysendakt,
            'id_tr_mat_osmotr' => $this->id_tr_mat_osmotr,
        ]);

        $query->andFilterWhere(Proc::WhereCunstruct($this, 'recoveryrecieveaktmat_date', 'date'));
        $query->andFilterWhere(['LIKE', 'recoveryrecieveaktmat_result', $this->getAttribute('recoveryrecieveaktmat_result')]);
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idTrMatOsmotr.idOsmotraktmat.osmotraktmat_date', 'date'));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idTrMatOsmotr.tr_mat_osmotr_number'));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idTrMatOsmotr.idTrMat.idMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idTrMatOsmotr.idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'idTrMatOsmotr.tr_mat_osmotr_comment', $this->getAttribute('idTrMatOsmotr.tr_mat_osmotr_comment')]);
    }

    private function baseSort(&$dataProvider) {
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

    public function search($params) {
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
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

}
