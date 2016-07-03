<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Osmotrakt;
use app\func\Proc;

/**
 * OsmotraktSearch represents the model behind the search form about `app\models\Fregat\Osmotrakt`.
 */
class OsmotraktSearch extends Osmotrakt {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrosnov.idMattraffic.idMaterial.material_name',
            'idTrosnov.idMattraffic.idMaterial.material_inv',
            'idTrosnov.idMattraffic.idMaterial.material_serial',
            'idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idTrosnov.tr_osnov_kab',
            'idUser.idperson.auth_user_fullname',
            'idUser.iddolzh.dolzh_name',
            'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
            'idReason.reason_text',
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['osmotrakt_id', 'id_reason', 'id_user', 'id_master', 'id_tr_osnov'], 'integer'],
            [['osmotrakt_comment', 'osmotrakt_date'], 'safe'],
            [[
            'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrosnov.idMattraffic.idMaterial.material_name',
            'idTrosnov.idMattraffic.idMaterial.material_inv',
            'idTrosnov.idMattraffic.idMaterial.material_serial',
            'idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idTrosnov.tr_osnov_kab',
            'idUser.idperson.auth_user_fullname',
            'idUser.iddolzh.dolzh_name',
            'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
            'idReason.reason_text',
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
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
            'idTrosnov' => function($query) {
                $query->from(['idTrosnov' => 'tr_osnov']);
                $query->joinWith([
                    'idMattraffic' => function($query) {
                        $query->from(['idMattraffic' => 'mattraffic']);
                        $query->joinWith([
                            'idMaterial' => function($query) {
                                $query->from(['idMaterial' => 'material']);
                                $query->joinWith([
                                    'idMatv' => function($query) {
                                        $query->from(['idMatv' => 'matvid']);
                                    },
                                        ]);
                                    },
                                            'idMol' => function($query) {
                                        $query->from(['idMol' => 'employee']);
                                        $query->joinWith([
                                            'idbuild' => function($query) {
                                                $query->from(['idbuild' => 'build']);
                                            },
                                                    'idperson' => function($query) {
                                                $query->from(['idmolperson' => 'auth_user']);
                                            },
                                                    'iddolzh' => function($query) {
                                                $query->from(['idmoldolzh' => 'dolzh']);
                                            },
                                                ]);
                                            },
                                                ]);
                                            },
                                                ]);
                                            },
                                                    'idUser' => function($query) {
                                                $query->from(['idUser' => 'employee']);
                                                $query->joinWith([
                                                    'idperson' => function($query) {
                                                        $query->from(['iduserperson' => 'auth_user']);
                                                    },
                                                            'iddolzh' => function($query) {
                                                        $query->from(['iduserdolzh' => 'dolzh']);
                                                    },
                                                        ]);
                                                    },
                                                            'idReason' => function($query) {
                                                        $query->from(['idReason' => 'reason']);
                                                    },
                                                            'idMaster' => function($query) {
                                                        $query->from(['idMaster' => 'employee']);
                                                        $query->joinWith([
                                                            'idperson' => function($query) {
                                                                $query->from(['idmasterperson' => 'auth_user']);
                                                            },
                                                                    'iddolzh' => function($query) {
                                                                $query->from(['idmasterdolzh' => 'dolzh']);
                                                            },
                                                                ]);
                                                            },
                                                                ]);
                                                            }

                                                            private function baseFilter(&$query) {
                                                                $query->andFilterWhere([
                                                                    'id_reason' => $this->id_reason,
                                                                    'id_user' => $this->id_user,
                                                                    'id_master' => $this->id_master,
                                                                    'id_tr_osnov' => $this->id_tr_osnov,
                                                                ]);

                                                                $query->andFilterWhere(['like', 'osmotrakt_comment', $this->osmotrakt_comment]);
                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'osmotrakt_id'));
                                                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'osmotrakt_date', 'date'));
                                                                $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.material_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.material_inv')]);
                                                                $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.material_serial')]);
                                                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idTrosnov.idMattraffic.idMol.idbuild.build_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idTrosnov.tr_osnov_kab', $this->getAttribute('idTrosnov.tr_osnov_kab')]);
                                                                $query->andFilterWhere(['LIKE', 'iduserperson.auth_user_fullname', $this->getAttribute('idUser.idperson.auth_user_fullname')]);
                                                                $query->andFilterWhere(['LIKE', 'iduserdolzh.dolzh_name', $this->getAttribute('idUser.iddolzh.dolzh_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idmolperson.auth_user_fullname', $this->getAttribute('idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname')]);
                                                                $query->andFilterWhere(['LIKE', 'idmoldolzh.dolzh_name', $this->getAttribute('idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name')]);
                                                                $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);
                                                                $query->andFilterWhere(['LIKE', 'idmasterperson.auth_user_fullname', $this->getAttribute('idMaster.idperson.auth_user_fullname')]);
                                                                $query->andFilterWhere(['LIKE', 'idmasterdolzh.dolzh_name', $this->getAttribute('idMaster.iddolzh.dolzh_name')]);
                                                            }

                                                            private function baseSort(&$dataProvider) {
                                                                Proc::AssignRelatedAttributes($dataProvider, [
                                                                    'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
                                                                    'idTrosnov.idMattraffic.idMaterial.material_name',
                                                                    'idTrosnov.idMattraffic.idMaterial.material_inv',
                                                                    'idTrosnov.idMattraffic.idMaterial.material_serial',
                                                                    'idTrosnov.idMattraffic.idMol.idbuild.build_name',
                                                                    'idTrosnov.tr_osnov_kab',
                                                                    'idReason.reason_text',
                                                                ]);

                                                                $dataProvider->sort->attributes['idUser.idperson.auth_user_fullname'] = [
                                                                    'asc' => ["iduserperson.auth_user_fullname" => SORT_ASC],
                                                                    'desc' => ["iduserperson.auth_user_fullname" => SORT_DESC],
                                                                ];
                                                                $dataProvider->sort->attributes['idUser.iddolzh.dolzh_name'] = [
                                                                    'asc' => ["iduserdolzh.dolzh_name" => SORT_ASC],
                                                                    'desc' => ["iduserdolzh.dolzh_name" => SORT_DESC],
                                                                ];
                                                                $dataProvider->sort->attributes['idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname'] = [
                                                                    'asc' => ["idmolperson.auth_user_fullname" => SORT_ASC],
                                                                    'desc' => ["idmolperson.auth_user_fullname" => SORT_DESC],
                                                                ];
                                                                $dataProvider->sort->attributes['idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name'] = [
                                                                    'asc' => ["idmoldolzh.dolzh_name" => SORT_ASC],
                                                                    'desc' => ["idmoldolzh.dolzh_name" => SORT_DESC],
                                                                ];
                                                                $dataProvider->sort->attributes['idMaster.idperson.auth_user_fullname'] = [
                                                                    'asc' => ["idmasterperson.auth_user_fullname" => SORT_ASC],
                                                                    'desc' => ["idmasterperson.auth_user_fullname" => SORT_DESC],
                                                                ];
                                                                $dataProvider->sort->attributes['idMaster.iddolzh.dolzh_name'] = [
                                                                    'asc' => ["idmasterdolzh.dolzh_name" => SORT_ASC],
                                                                    'desc' => ["idmasterdolzh.dolzh_name" => SORT_DESC],
                                                                ];
                                                            }

                                                            /**
                                                             * Creates data provider instance with search query applied
                                                             *
                                                             * @param array $params
                                                             *
                                                             * @return ActiveDataProvider
                                                             */
                                                            public function search($params) {
                                                                $query = Osmotrakt::find();

                                                                $dataProvider = new ActiveDataProvider([
                                                                    'query' => $query,
                                                                    'sort' => ['defaultOrder' => ['osmotrakt_id' => SORT_DESC]],
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
                                                        