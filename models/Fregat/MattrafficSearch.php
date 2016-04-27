<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Mattraffic;
use app\func\Proc;

/**
 * MattrafficSearch represents the model behind the search form about `app\models\Fregat\Mattraffic`.
 */
class MattrafficSearch extends Mattraffic {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMaterial.material_tip',
            'idMaterial.idMatv.matvid_name',
            'idMaterial.material_name',
            'idMaterial.material_inv',
            'idMaterial.material_serial',
            'idMaterial.material_release',
            'idMaterial.material_number',
            'idMaterial.idIzmer.izmer_name',
            'idMaterial.material_price',
            'idMol.employee_id',
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
            'idMol.idbuild.build_name',
            'idMol.employee_dateinactive',
            'idMaterial.material_writeoff',
            'idMaterial.material_username',
            'idMaterial.material_lastchange',
            'idMaterial.material_importdo',
            'idMol.employee_username',
            'idMol.employee_lastchange',
            'idMol.employee_importdo',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['mattraffic_id', 'id_material', 'id_mol', 'mattraffic_tip', 'idMaterial.material_tip', 'idMol.employee_id', 'idMaterial.material_writeoff', 'idMaterial.material_importdo'], 'integer'],
            [['mattraffic_date', 'mattraffic_username', 'mattraffic_lastchange',
            'idMaterial.idMatv.matvid_name',
            'idMaterial.material_name',
            'idMaterial.material_inv',
            'idMaterial.material_serial',
            'idMaterial.material_release',
            'idMaterial.idIzmer.izmer_name',
            'idMol.idperson.auth_user_fullname',
            'idMol.iddolzh.dolzh_name',
            'idMol.idpodraz.podraz_name',
            'idMol.idbuild.build_name',
            'idMol.employee_dateinactive',
            'idMaterial.material_username',
            'idMaterial.material_lastchange',
            'idMol.employee_username',
            'idMol.employee_lastchange',
            'idMol.employee_importdo',
            'mattraffic_username',
            'mattraffic_lastchange'], 'safe'],
            [['mattraffic_number', 'idMaterial.material_number', 'idMaterial.material_price'], 'number'],
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
            'idMaterial' => function($query) {
                $query->from(['idMaterial' => 'material']);
                $query->joinWith([
                    'idMatv' => function($query) {
                        $query->from(['idMatv' => 'matvid']);
                    },
                            'idIzmer' => function($query) {
                        $query->from(['idIzmer' => 'izmer']);
                    },
                        ]);
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
                                    'idpodraz' => function($query) {
                                $query->from(['idpodraz' => 'podraz']);
                            },
                                    'idbuild' => function($query) {
                                $query->from(['idbuild' => 'build']);
                            },
                                ]);
                            }]);
                            }

                            private function baseFilter(&$query) {
                                $query->andFilterWhere([
                                    'mattraffic_id' => $this->mattraffic_id,
                                    'mattraffic_date' => $this->mattraffic_date,
                                    'mattraffic_number' => $this->mattraffic_number,
                                    'mattraffic_tip' => $this->mattraffic_tip,
                                    'id_material' => $this->id_material,
                                    'id_mol' => $this->id_mol,
                                ]);

                                $query->andFilterWhere(['LIKE', 'idMaterial.material_tip', $this->getAttribute('idMaterial.material_tip')]);
                                $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMaterial.idMatv.matvid_name')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMaterial.material_name')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMaterial.material_inv')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idMaterial.material_serial')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMaterial.material_name')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMaterial.material_release', 'date'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMaterial.material_number'));
                                $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idMaterial.idIzmer.izmer_name')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMaterial.material_price'));
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMol.employee_id'));
                                $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idMol.idperson.auth_user_fullname')]);
                                $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idMol.iddolzh.dolzh_name')]);
                                $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idMol.idpodraz.podraz_name')]);
                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMol.idbuild.build_name')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMol.employee_dateinactive', 'date'));
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_writeoff', $this->getAttribute('idMaterial.material_writeoff')]);
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_username', $this->getAttribute('idMaterial.material_username')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMaterial.material_lastchange', 'datetime'));
                                $query->andFilterWhere(['LIKE', 'idMaterial.material_importdo', $this->getAttribute('idMaterial.material_importdo')]);
                                $query->andFilterWhere(['LIKE', 'idMol.employee_username', $this->getAttribute('idMol.employee_username')]);
                                $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idMol.idbuild.build_name')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'idMol.employee_lastchange', 'datetime'));
                                $query->andFilterWhere(['LIKE', 'idMol.employee_importdo', $this->getAttribute('idMol.employee_importdo')]);
                                $query->andFilterWhere(['LIKE', 'mattraffic_username', $this->getAttribute('mattraffic_username')]);
                                $query->andFilterWhere(Proc::WhereCunstruct($this, 'mattraffic_lastchange', 'datetime'));
                            }

                            private function baseSort(&$dataProvider) {
                                $dataProvider->sort->attributes['idMaterial.material_tip'] = [
                                    'asc' => ['idMaterial.material_tip' => SORT_ASC],
                                    'desc' => ['idMaterial.material_tip' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.idMatv.matvid_name'] = [
                                    'asc' => ['idMatv.matvid_name' => SORT_ASC],
                                    'desc' => ['idMatv.matvid_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_name'] = [
                                    'asc' => ['idMaterial.material_name' => SORT_ASC],
                                    'desc' => ['idMaterial.material_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_inv'] = [
                                    'asc' => ['idMaterial.material_inv' => SORT_ASC],
                                    'desc' => ['idMaterial.material_inv' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_serial'] = [
                                    'asc' => ['idMaterial.material_serial' => SORT_ASC],
                                    'desc' => ['idMaterial.material_serial' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_release'] = [
                                    'asc' => ['idMaterial.material_release' => SORT_ASC],
                                    'desc' => ['idMaterial.material_release' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_number'] = [
                                    'asc' => ['idMaterial.material_number' => SORT_ASC],
                                    'desc' => ['idMaterial.material_number' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.idIzmer.izmer_name'] = [
                                    'asc' => ['idIzmer.izmer_name' => SORT_ASC],
                                    'desc' => ['idIzmer.izmer_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_price'] = [
                                    'asc' => ['idMaterial.material_price' => SORT_ASC],
                                    'desc' => ['idMaterial.material_price' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.employee_id'] = [
                                    'asc' => ['idMol.employee_id' => SORT_ASC],
                                    'desc' => ['idMol.employee_id' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.idperson.auth_user_fullname'] = [
                                    'asc' => ['idperson.auth_user_fullname' => SORT_ASC],
                                    'desc' => ['idperson.auth_user_fullname' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.iddolzh.dolzh_name'] = [
                                    'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                                    'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.idpodraz.podraz_name'] = [
                                    'asc' => ['idpodraz.podraz_name' => SORT_ASC],
                                    'desc' => ['idpodraz.podraz_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.idbuild.build_name'] = [
                                    'asc' => ['idbuild.build_name' => SORT_ASC],
                                    'desc' => ['idbuild.build_name' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.employee_dateinactive'] = [
                                    'asc' => ['idMol.employee_dateinactive' => SORT_ASC],
                                    'desc' => ['idMol.employee_dateinactive' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_writeoff'] = [
                                    'asc' => ['idMaterial.material_writeoff' => SORT_ASC],
                                    'desc' => ['idMaterial.material_writeoff' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_username'] = [
                                    'asc' => ['idMaterial.material_username' => SORT_ASC],
                                    'desc' => ['idMaterial.material_username' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_lastchange'] = [
                                    'asc' => ['idMaterial.material_lastchange' => SORT_ASC],
                                    'desc' => ['idMaterial.material_lastchange' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMaterial.material_importdo'] = [
                                    'asc' => ['idMaterial.material_importdo' => SORT_ASC],
                                    'desc' => ['idMaterial.material_importdo' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.employee_username'] = [
                                    'asc' => ['idMol.employee_username' => SORT_ASC],
                                    'desc' => ['idMol.employee_username' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.employee_lastchange'] = [
                                    'asc' => ['idMol.employee_lastchange' => SORT_ASC],
                                    'desc' => ['idMol.employee_lastchange' => SORT_DESC],
                                ];

                                $dataProvider->sort->attributes['idMol.employee_importdo'] = [
                                    'asc' => ['idMol.employee_importdo' => SORT_ASC],
                                    'desc' => ['idMol.employee_importdo' => SORT_DESC],
                                ];
                            }

                            public function search($params) {
                                $query = Mattraffic::find();

                                $dataProvider = new ActiveDataProvider([
                                    'query' => $query,
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

                                if (empty($params['sort']))
                                    $query->orderBy('mattraffic_date desc, mattraffic_id desc');

                                return $dataProvider;
                            }

                            public function searchforinstallakt($params) {
                                $query = Mattraffic::find();

                                $dataProvider = new ActiveDataProvider([
                                    'query' => $query,
                                ]);

                                $query->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffic.id_material = m2.id_material_m2 and mattraffic.id_mol = m2.id_mol_m2 and mattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                                        ->join('LEFT JOIN', 'tr_osnov', 'material_tip = 1 and tr_osnov.id_mattraffic in (select mattraffic_id from mattraffic mt where mt.id_mol = mattraffic.id_mol and mt.id_material = mattraffic.id_material)');

                                $this->baseRelations($query);

                                $query->andWhere('mattraffic_number > 0')
                                        ->andWhere(['in', 'mattraffic_tip', [1, 2]])
                                        ->andWhere(['m2.mattraffic_date_m2' => NULL])
                                        ->andWhere(['tr_osnov.id_mattraffic' => NULL]);

                                $this->load($params);

                                if (!$this->validate()) {
                                    // uncomment the following line if you do not want to return any records when validation fails
                                    // $query->where('0=1');
                                    return $dataProvider;
                                }

                                $this->baseFilter($query);
                                $this->baseSort($dataProvider);

                                if (empty($params['sort']))
                                    $query->orderBy('mattraffic_date desc, mattraffic_id desc');

                                return $dataProvider;
                            }

                        }
                        