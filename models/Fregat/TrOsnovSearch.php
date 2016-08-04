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
class TrOsnovSearch extends TrOsnov {

    public function attributes() {
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
    public function rules() {
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
    public function scenarios() {
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
    public function search($params) {
        $query = TrOsnov::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tr_osnov_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idMattraffic' => function($query) {
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
                                ]);
                            },
                                ]);
                            }]);

                                $this->load($params);

                                if (!$this->validate()) {
                                    // uncomment the following line if you do not want to return any records when validation fails
                                    // $query->where('0=1');
                                    return $dataProvider;
                                }

                                // grid filtering conditions
                                $query->andFilterWhere([
                                    'tr_osnov_id' => $this->tr_osnov_id,
                                    'id_installakt' => (string) filter_input(INPUT_GET, 'id'),
                                    'id_mattraffic' => $this->id_mattraffic,
                                ]);

                                $query->andFilterWhere(['like', 'tr_osnov_kab', $this->tr_osnov_kab]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMattraffic.mattraffic_number'));
                                $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);

                                Proc::AssignRelatedAttributes($dataProvider, [
                                    'idMattraffic.idMaterial.material_name',
                                    'idMattraffic.idMaterial.material_inv',
                                    'idMattraffic.mattraffic_number',
                                    'idMattraffic.idMol.idperson.auth_user_fullname',
                                    'idMattraffic.idMol.iddolzh.dolzh_name',
                                ]);

                                return $dataProvider;
                            }

                            public function searchforosmotrakt($params) {
                                $query = TrOsnov::find();

                                // add conditions that should always apply here

                                $dataProvider = new ActiveDataProvider([
                                    'query' => $query,
                                    'sort' => ['defaultOrder' => ['idMattraffic.mattraffic_date' => SORT_DESC]],
                                ]);

                                $query->joinWith([
                                            'idMattraffic' => function($query) {
                                                $query->from(['idMattraffic' => 'mattraffic']);
                                                $query->joinWith([
                                                    'idMol' => function($query) {
                                                        $query->from(['idMol' => 'employee']);
                                                        $query->joinWith([
                                                            'idperson' => function($query) {
                                                                $query->from(['idperson' => 'auth_user']);
                                                            },
                                                                    'iddolzh' => function($query) {
                                                                $query->from(['iddolzh' => 'dolzh']);
                                                            },
                                                                    'idpodraz' => function($query) {
                                                                $query->from(['idpodraz' => 'podraz']);
                                                            },
                                                                    'idbuild' => function($query) {
                                                                $query->from(['idbuild' => 'build']);
                                                            },
                                                                ]);
                                                            },
                                                                ]);
                                                            },
                                                                    'idInstallakt' => function($query) {
                                                                $query->from(['idInstallakt' => 'installakt']);
                                                            },
                                                                ])
                                                                ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
                                                                ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'idMattraffic.id_material = m2.id_material_m2 and idMattraffic.id_mol = m2.id_mol_m2 and idMattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)');

                                                        $query->andWhere('idMattraffic.mattraffic_number > 0');
                                                        $query->andWhere(['in', 'idMattraffic.mattraffic_tip', [3]]);
                                                        $query->andWhere(['m2.mattraffic_date_m2' => NULL]);

                                                        $query->andFilterWhere(['like', 'tr_osnov_kab', $this->tr_osnov_kab]);
                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idInstallakt.installakt_id'));
                                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'idInstallakt.installakt_date', 'date'));
                                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
                                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
                                                        $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idMattraffic.idMaterial.material_serial')]);
                                                        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMattraffic.idMol.idbuild.build_name')]);
                                                        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMattraffic.idMol.idperson.auth_user_fullname')]);
                                                        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMattraffic.idMol.iddolzh.dolzh_name')]);

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

                                                        return $dataProvider;
                                                    }

                                                }
                                                