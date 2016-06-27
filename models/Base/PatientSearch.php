<?php

namespace app\models\Base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Base\Patient;
use app\func\Proc;

/**
 * PatientSearch represents the model behind the search form about `app\models\Base\Patient`.
 */
class PatientSearch extends Patient {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idFias.fias_city',
            'idFias.fias_street',
            'glaukuchets.glaukuchet_uchetbegin',
            'glaukuchets.glaukuchet_detect',
            'glaukuchets.glaukuchet_deregdate',
            'glaukuchets.glaukuchet_deregreason',
            'glaukuchets.glaukuchet_stage',
            'glaukuchets.glaukuchet_operdate',
            'glaukuchets.glaukuchet_invalid',
            'glaukuchets.glaukuchet_lastvisit',
            'glaukuchets.glaukuchet_lastmetabol',
            'glaukuchets.idEmployee.idperson.auth_user_fullname',
            'glaukuchets.idEmployee.iddolzh.dolzh_name',
            'glaukuchets.idEmployee.idpodraz.podraz_name',
            'glaukuchets.idEmployee.idbuild.build_name',
            'glaukuchets.idClassMkb.code',
            'glaukuchets.idClassMkb.name',
            'glaukuchets.glaukuchet_lastchange',
            'glaukuchets.glaukuchet_username',
            'glaukuchets.glpreps.glaukuchet_preparats',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['patient_id', 'patient_pol'], 'integer'],
            [['patient_fam', 'patient_im', 'patient_ot', 'patient_dr', 'id_fias', 'patient_dom', 'patient_korp', 'patient_kvartira', 'patient_username', 'patient_lastchange'], 'safe'],
            [['idFias.fias_city',
            'idFias.fias_street',
            'glaukuchets.glaukuchet_uchetbegin',
            'glaukuchets.glaukuchet_detect',
            'glaukuchets.glaukuchet_deregdate',
            'glaukuchets.glaukuchet_deregreason',
            'glaukuchets.glaukuchet_stage',
            'glaukuchets.glaukuchet_operdate',
            'glaukuchets.glaukuchet_invalid',
            'glaukuchets.glaukuchet_lastvisit',
            'glaukuchets.glaukuchet_lastmetabol',
            'glaukuchets.idEmployee.idperson.auth_user_fullname',
            'glaukuchets.idEmployee.iddolzh.dolzh_name',
            'glaukuchets.idEmployee.idpodraz.podraz_name',
            'glaukuchets.idEmployee.idbuild.build_name',
            'glaukuchets.idClassMkb.code',
            'glaukuchets.idClassMkb.name',
            'glaukuchets.glaukuchet_lastchange',
            'glaukuchets.glaukuchet_username',
            'glaukuchets.glpreps.glaukuchet_preparats'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function glaukRelations(&$query) {
        $query->joinWith([
            'glaukuchets' => function($query) {
                $query->from(['glaukuchets' => 'glaukuchet']);
                $query->joinWith([
                    'idClassMkb' => function($query) {
                        $query->from(['idClassMkb' => 'class_mkb']);
                    },
                            'idEmployee' => function($query) {
                        $query->from(['idEmployee' => 'employee']);
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
                            }, 'glpreps' => function($query) {
                                $query->select(new \yii\db\Expression("glpreps.glprep_id, glpreps.id_glaukuchet, IF ( glpreps.id_preparat IS NULL, '', GROUP_CONCAT(TRIM(CONCAT_WS(' ', idPreparat.preparat_name, IF (glpreps.glprep_rlocat IS NULL, '', IF (glpreps.glprep_rlocat = 1, '(Федеральная)', '(Региональная)'))))   SEPARATOR ', ')) AS glaukuchet_preparats"));
                                $query->from(['glpreps' => 'glprep']);
                                $query->joinWith([
                                    'idPreparat' => function($query) {
                                        $query->from(['idPreparat' => 'preparat']);
                                    }
                                        ]);
                                        $query->groupby(['glpreps.id_glaukuchet']);
                                    }]);
                                    },
                                            'idFias' => function($query) {
                                        $query->select(["idFias.AOGUID, IF (idFias.AOLEVEL < 7, CONCAT_WS(', ',  CONCAT_WS('. ',idFias2.SHORTNAME,idFias2.OFFNAME), CONCAT_WS('. ',idFias.SHORTNAME,idFias.OFFNAME)), CONCAT_WS('. ',idFias2.SHORTNAME,idFias2.OFFNAME)) AS fias_city", "IF (idFias.AOLEVEL < 7, '', CONCAT_WS('. ',idFias.SHORTNAME,idFias.OFFNAME)) AS fias_street"]);
                                        $query->from(['idFias' => 'fias']);
                                        $query->join('LEFT JOIN', 'fias AS idFias2', 'idFias.PARENTGUID = idFias2.AOGUID');
                                    }
                                        ]);
                                    }

                                    private function glaukFilter(&$query) {
                                        $query->andFilterWhere(['LIKE', 'patient_fam', $this->getAttribute('patient_fam')]);
                                        $query->andFilterWhere(['LIKE', 'patient_im', $this->getAttribute('patient_im')]);
                                        $query->andFilterWhere(['LIKE', 'patient_ot', $this->getAttribute('patient_ot')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'patient_dr', 'date'));
                                        $query->andFilterWhere(['LIKE', 'patient_pol', $this->getAttribute('patient_pol')]);
                                        if (!empty($this->getAttribute('idFias.fias_city')))
                                            $query->andFilterWhere(['or', ['and', ['LIKE', 'idFias.OFFNAME', $this->getAttribute('idFias.fias_city')], 'idFias.AOLEVEL < 7'], ['and', ['LIKE', 'idFias2.OFFNAME', $this->getAttribute('idFias.fias_city')], 'idFias.AOLEVEL >= 7']]);
                                        if (!empty($this->getAttribute('idFias.fias_street')))
                                            $query->andFilterWhere(['and', ['LIKE', 'idFias.OFFNAME', $this->getAttribute('idFias.fias_street')], 'idFias.AOLEVEL >= 7']);
                                        $query->andFilterWhere(['LIKE', 'patient_dom', $this->getAttribute('patient_dom')]);
                                        $query->andFilterWhere(['LIKE', 'patient_korp', $this->getAttribute('patient_korp')]);
                                        $query->andFilterWhere(['LIKE', 'patient_kvartira', $this->getAttribute('patient_kvartira')]);
                                        $query->andFilterWhere(['LIKE', 'patient_username', $this->getAttribute('patient_username')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'patient_lastchange', 'datetime'));

                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'glaukuchets.glaukuchet_uchetbegin', 'date'));
                                        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_detect', $this->getAttribute('glaukuchets.glaukuchet_detect')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'glaukuchets.glaukuchet_deregdate', 'date'));
                                        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_deregreason', $this->getAttribute('glaukuchets.glaukuchet_deregreason')]);
                                        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_stage', $this->getAttribute('glaukuchets.glaukuchet_stage')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'glaukuchets.glaukuchet_operdate', 'date'));
                                        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_invalid', $this->getAttribute('glaukuchets.glaukuchet_invalid')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'glaukuchets.glaukuchet_lastvisit', 'date'));
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'glaukuchets.glaukuchet_lastmetabol', 'date'));
                                        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('glaukuchets.idEmployee.idperson.auth_user_fullname')]);
                                        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('glaukuchets.idEmployee.iddolzh.dolzh_name')]);
                                        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('glaukuchets.idEmployee.idpodraz.podraz_name')]);
                                        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('glaukuchets.idEmployee.idbuild.build_name')]);
                                        $query->andFilterWhere(['LIKE', 'idClassMkb.code', $this->getAttribute('glaukuchets.idClassMkb.code')]);
                                        $query->andFilterWhere(['LIKE', 'idClassMkb.name', $this->getAttribute('glaukuchets.idClassMkb.name')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'patient_lastchange', 'datetime'));
                                        $query->andFilterWhere(['LIKE', 'patient_username', $this->getAttribute('patient_username')]);
                                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'glaukuchets.glaukuchet_lastchange', 'datetime'));
                                        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_username', $this->getAttribute('glaukuchets.glaukuchet_username')]);
                                    }

                                    private function glaukSort(&$dataProvider) {
                                        $dataProvider->sort->attributes['idFias.fias_city'] = [
                                            'asc' => ["IF (idFias.AOLEVEL < 7, idFias.OFFNAME, idFias2.OFFNAME)" => SORT_ASC],
                                            'desc' => ["IF (idFias.AOLEVEL < 7, idFias.OFFNAME, idFias2.OFFNAME)" => SORT_DESC],
                                        ];

                                        $dataProvider->sort->attributes['idFias.fias_street'] = [
                                            'asc' => ["IF (idFias.AOLEVEL < 7, '', idFias.OFFNAME)" => SORT_ASC],
                                            'desc' => ["IF (idFias.AOLEVEL < 7, '', idFias.OFFNAME)" => SORT_DESC],
                                        ];

                                        Proc::AssignRelatedAttributes($dataProvider, [
                                            'glaukuchets.glaukuchet_uchetbegin',
                                            'glaukuchets.glaukuchet_detect',
                                            'glaukuchets.glaukuchet_deregdate',
                                            'glaukuchets.glaukuchet_deregreason',
                                            'glaukuchets.glaukuchet_stage',
                                            'glaukuchets.glaukuchet_operdate',
                                            'glaukuchets.glaukuchet_invalid',
                                            'glaukuchets.glaukuchet_lastvisit',
                                            'glaukuchets.glaukuchet_lastmetabol',
                                            'glaukuchets.idEmployee.idperson.auth_user_fullname',
                                            'glaukuchets.idEmployee.iddolzh.dolzh_name',
                                            'glaukuchets.idEmployee.idpodraz.podraz_name',
                                            'glaukuchets.idEmployee.idbuild.build_name',
                                            'glaukuchets.idClassMkb.code',
                                            'glaukuchets.idClassMkb.name',
                                            'glaukuchets.glaukuchet_lastchange',
                                            'glaukuchets.glaukuchet_username',
                                        ]);
                                    }

                                    private function glaukDopFilter(&$query) {
                                        $filter = Proc::GetFilter($this->formName(), 'PatientFilter');

                                        if (!empty($filter)) {

                                            $attr = 'patient_fam';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_im';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_ot';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_dr';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_vozrast';
                                            $znak = 'patient_vozrast_znak';
                                            if (!empty($filter[$znak]) && !empty($filter[$attr]))
                                                $query->andWhere('TIMESTAMPDIFF(YEAR, patient_dr, CURDATE()) ' . $filter[$znak] . ' ' . $filter[$attr]);

                                            $attr = 'patient_pol';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere([$attr => $filter[$attr]]);

                                            $attr = 'fias_city';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['or', ['and', ['LIKE', 'idFias.AOGUID', $filter[$attr]], 'idFias.AOLEVEL < 7'], ['and', ['LIKE', 'idFias2.AOGUID', $filter[$attr]], 'idFias.AOLEVEL >= 7']]);

                                            $attr = 'fias_street';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['and', ['LIKE', 'idFias.AOGUID', $filter[$attr]], 'idFias.AOLEVEL >= 7']);

                                            $attr = 'patient_dom';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_korp';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_kvartira';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'is_glauk_mark';
                                            if ($filter[$attr] === '1')
                                                $query->andWhere(['not', ['glaukuchets.glaukuchet_id' => null]]);

                                            $attr = 'glaukuchet_uchetbegin';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }

                                            $attr = 'glaukuchet_detect';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', $attr, $filter[$attr]]);

                                            $attr = 'is_glaukuchet_mark';
                                            if ($filter[$attr] === '1')
                                                $query->andWhere(['glaukuchets.glaukuchet_deregreason' => null, 'glaukuchets.glaukuchet_deregdate' => null]);

                                            $attr = 'glaukuchet_deregreason';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', $attr, $filter[$attr]]);

                                            $attr = 'glaukuchet_deregdate';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }

                                            $attr = 'glaukuchet_stage';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', $attr, $filter[$attr]]);

                                            $attr = 'glaukuchet_operdate';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }

                                            $attr = 'glaukuchet_not_oper_mark';
                                            if ($filter[$attr] === '1')
                                                $query->andWhere(['glaukuchets.glaukuchet_operdate' => null]);

                                            $attr = 'glaukuchet_invalid';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', $attr, $filter[$attr]]);

                                            $attr = 'glaukuchet_not_invalid_mark';
                                            if ($filter[$attr] === '1')
                                                $query->andWhere(['glaukuchets.glaukuchet_invalid' => null]);

                                            $attr = 'glaukuchet_lastvisit';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }

                                            $attr = 'glaukuchet_lastmetabol';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }

                                            $attr = 'glaukuchet_not_lastmetabol_mark';
                                            if ($filter[$attr] === '1')
                                                $query->andWhere(['glaukuchets.glaukuchet_lastmetabol' => null]);

                                            $attr = 'glaukuchet_id_employee';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['glaukuchets.id_employee' => $filter[$attr]]);

                                            $attr = 'employee_id_dolzh';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', 'idEmployee.id_dolzh', $filter[$attr]]);

                                            $attr = 'employee_id_podraz';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', 'idEmployee.id_podraz', $filter[$attr]]);

                                            $attr = 'employee_id_build';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['IN', 'idEmployee.id_build', $filter[$attr]]);

                                            $attr = 'glprep_id_preparat';
                                            if (!empty($filter[$attr]))
                                                $query->andWhere('glaukuchets.glaukuchet_id in (select gl1.id_glaukuchet from glprep gl1 where gl1.id_preparat in (' . implode(',', $filter[$attr]) . '))');

                                            $attr = 'glprep_rlocat';
                                            if (!empty($filter[$attr]))
                                                $query->andWhere('glaukuchets.glaukuchet_id in (select gl1.id_glaukuchet from glprep gl1 where gl1.glprep_rlocat in (' . implode(',', $filter[$attr]) . '))');

                                            $attr = 'glprep_not_preparat_mark';
                                            if ($filter[$attr] === '1')
                                                $query->andWhere('glaukuchets.glaukuchet_id not in (select gl1.id_glaukuchet from glprep gl1 group by gl1.id_glaukuchet)');

                                            $attr = 'patient_username';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'patient_lastchange';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }

                                            $attr = 'glaukuchet_username';
                                            if (!empty($filter[$attr]))
                                                $query->andFilterWhere(['LIKE', $attr, $filter[$attr]]);

                                            $attr = 'glaukuchet_lastchange';
                                            if (!empty($filter[$attr . '_beg']) && !empty($filter[$attr . '_end']))
                                                $query->andFilterWhere(['between', $attr, $filter[$attr . '_beg'], $filter[$attr . '_end']]);
                                            elseif (!empty($filter[$attr . '_beg']) || !empty($filter[$attr . '_end'])) {
                                                $znak = !empty($filter[$attr . '_beg']) ? '>=' : '<=';
                                                $value = !empty($filter[$attr . '_beg']) ? $filter[$attr . '_beg'] : $filter[$attr . '_end'];
                                                $query->andFilterWhere([$znak, $attr, $value]);
                                            }
                                        }
                                    }

                                    /**
                                     * Creates data provider instance with search query applied
                                     *
                                     * @param array $params
                                     *
                                     * @return ActiveDataProvider
                                     */
                                    public function search($params) {
                                        $query = Patient::find();

                                        // add conditions that should always apply here

                                        $dataProvider = new ActiveDataProvider([
                                            'query' => $query,
                                            'sort' => ['defaultOrder' => ['glaukuchets.glaukuchet_lastchange' => SORT_DESC]],
                                        ]);

                                        $this->glaukRelations($query);

                                        $this->load($params);

                                        if (!$this->validate()) {
                                            // uncomment the following line if you do not want to return any records when validation fails
                                            // $query->where('0=1');
                                            return $dataProvider;
                                        }

                                        $this->glaukFilter($query);
                                        $this->glaukSort($dataProvider);

                                        $this->glaukDopfilter($query);

                                        return $dataProvider;
                                    }

                                }
                                