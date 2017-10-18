<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Material;
use app\func\Proc;
use yii\db\Expression;
use yii\db\Query;

/**
 * MaterialSearch represents the model behind the search form about `app\models\Fregat\Material`.
 */
class MaterialSearch extends Material
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMatv.matvid_name',
            'idIzmer.izmer_name',
            'mattraffics.mattraffic_lastchange',
            'mattraffics.mattraffic_username',
            'currentMattraffic.idMol.idperson.auth_user_fullname',
            'currentMattraffic.idMol.iddolzh.dolzh_name',
            'currentMattraffic.idMol.idbuild.build_name',
            'currentMattraffic.mattraffic_date',
            'idSchetuchet.schetuchet_kod',
            'idSchetuchet.schetuchet_name',
            'lastMattraffic.mattraffic_tip',
            'lastMattraffic.mattraffic_date',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['material_id', 'material_tip', 'material_writeoff', 'id_matvid', 'id_izmer', 'material_importdo'], 'integer'],
            [['material_name', 'material_name1c', 'material_1c', 'material_inv', 'material_serial', 'material_release', 'material_username', 'material_lastchange', 'idMatv.matvid_name', 'idIzmer.izmer_name', 'material_comment'], 'safe'],
            [['material_number', 'material_price'], 'safe'],
            [[
                'mattraffics.mattraffic_lastchange',
                'mattraffics.mattraffic_username',
                'currentMattraffic.idMol.idperson.auth_user_fullname',
                'currentMattraffic.idMol.iddolzh.dolzh_name',
                'currentMattraffic.idMol.idbuild.build_name',
                'currentMattraffic.mattraffic_date',
                'idSchetuchet.schetuchet_kod',
                'idSchetuchet.schetuchet_name',
                'lastMattraffic.mattraffic_tip',
                'lastMattraffic.mattraffic_date',
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
        $query = Material::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['material_name' => SORT_ASC]],
        ]);

        $query->joinWith(['idMatv', 'idIzmer', 'idSchetuchet']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'material_id' => $this->material_id,
            'material_tip' => $this->material_tip,
            'material_writeoff' => $this->material_writeoff,
            'id_matvid' => $this->id_matvid,
            'id_izmer' => $this->id_izmer,
            'material_importdo' => $this->material_importdo,
        ]);

        $query->andFilterWhere(['like', 'material_name', $this->material_name])
            ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
            ->andFilterWhere(['like', 'material_1c', $this->material_1c])
            ->andFilterWhere(['like', 'material_inv', $this->material_inv])
            ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_release', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_price'));
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idIzmer.izmer_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_username'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));
        $query->andFilterWhere(['LIKE', 'idSchetuchet.schetuchet_kod', $this->getAttribute('idSchetuchet.schetuchet_kod')]);
        $query->andFilterWhere(['LIKE', 'idSchetuchet.schetuchet_name', $this->getAttribute('idSchetuchet.schetuchet_name')]);
        $query->andFilterWhere(['LIKE', 'material_comment', $this->getAttribute('material_comment')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMatv.matvid_name',
            'idIzmer.izmer_name',
            'idSchetuchet.schetuchet_kod',
            'idSchetuchet.schetuchet_name',
        ]);

        $query->joinWith([
            'currentMattraffic.idMol.idperson currentidperson',
            'currentMattraffic.idMol.iddolzh currentiddolzh',
            'currentMattraffic.idMol.idbuild currentidbuild',
        ]);

        $query->with(['lastMattraffic']);

        $query->andFilterWhere(['LIKE', 'currentidperson.auth_user_fullname', $this->getAttribute('currentMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'currentiddolzh.dolzh_name', $this->getAttribute('currentMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'currentidbuild.build_name', $this->getAttribute('currentMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'currentMattraffic.mattraffic_date', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'lastMattraffic.mattraffic_tip'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'lastMattraffic.mattraffic_date', Proc::Date));

        Proc::AssignRelatedAttributes($dataProvider, [
            'currentMattraffic.idMol.idperson.auth_user_fullname' => 'currentidperson',
            'currentMattraffic.idMol.iddolzh.dolzh_name' => 'currentiddolzh',
            'currentMattraffic.idMol.idbuild.build_name' => 'currentidbuild',
            'currentMattraffic.mattraffic_date',
            'lastMattraffic.mattraffic_tip',
            'lastMattraffic.mattraffic_date',
        ]);

        $this->materialDopFilter($query);

        return $dataProvider;
    }

    private function materialDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'MaterialFilter');

        if (!empty($filter)) {

            $attr = 'mol_fullname_material';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_person',
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('employee idMol', 'idMol.employee_id = mattraffics.id_mol')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (1,2)')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere(['in', 'mattraffics.mattraffic_tip', [1, 2]])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            Proc::Filter_Compare(Proc::Strict, $query, $filter, ['Attribute' => 'material_writeoff']);

            $attr = 'mat_id_grupa';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'grupavids.id_grupa',
                'ExistsSubQuery' => (new Query())
                    ->select('material_id')
                    ->from('material materials')
                    ->leftJoin('matvid idMatv', 'idMatv.matvid_id = materials.id_matvid')
                    ->leftJoin('grupavid grupavids', 'idMatv.matvid_id = grupavids.id_matvid')
                    ->andWhere('materials.material_id = material.material_id')
            ]);

            $attr = 'material_attachfiles_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('materialDocfiles.id_material')
                    ->from('material_docfiles materialDocfiles')
                    ->andWhere('materialDocfiles.id_material = material.material_id')
                ],
            ]);

            $attr = 'not_material_attachfiles_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['not exists', (new Query())
                    ->select('materialDocfiles.id_material')
                    ->from('material_docfiles materialDocfiles')
                    ->andWhere('materialDocfiles.id_material = material.material_id')
                ],
            ]);

            $attr = 'material_attachphoto_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('materialDocfiles.id_material')
                    ->from('material_docfiles materialDocfiles')
                    ->leftJoin('docfiles idDocfiles', 'idDocfiles.docfiles_id = materialDocfiles.id_docfiles')
                    ->andWhere(['in', 'idDocfiles.docfiles_ext', ['PNG', 'JPG', 'JPEG', 'TIFF']])
                    ->andWhere('materialDocfiles.id_material = material.material_id')
                ],
            ]);

            $attr = 'material_attachdoc_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('materialDocfiles.id_material')
                    ->from('material_docfiles materialDocfiles')
                    ->leftJoin('docfiles idDocfiles', 'idDocfiles.docfiles_id = materialDocfiles.id_docfiles')
                    ->andWhere(['in', 'idDocfiles.docfiles_ext', ['XLS', 'XLSX', 'DOC', 'DOCX', 'PDF', 'TXT']])
                    ->andWhere('materialDocfiles.id_material = material.material_id')
                ],
            ]);

            $attr = 'material_comment_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => "material.material_comment <> ''",
            ]);

            $attr = 'material_contain_vkomplekte_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('tr_mat trMats', 'trMats.id_mattraffic = mattraffics.mattraffic_id')
                    ->leftJoin('mattraffic mtparent', 'trMats.id_parent = mtparent.mattraffic_id')
                    ->leftJoin('mattraffic mtchild', 'trMats.id_mattraffic = mtchild.mattraffic_id')
                    ->leftJoin('material matchild', 'mtchild.id_material = matchild.material_id')
                    ->andWhere(['matchild.material_tip' => Material::V_KOMPLEKTE])
                    ->andWhere('mtparent.id_material = material.material_id')
                ],
            ]);

            $attr = 'mol_id_build';
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMol.id_build',
                'LikeManual' => true,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('mattraffic m2', 'mattraffics.id_material = m2.id_material and mattraffics.mattraffic_date < m2.mattraffic_date and mattraffics.mattraffic_id < m2.mattraffic_id')
                    ->leftJoin('employee idMol', 'idMol.employee_id = mattraffics.id_mol')
                    ->leftJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere(['in', 'mattraffics.mattraffic_tip', [3]])
                    ->andWhere(['m2.mattraffic_date' => null])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'tr_osnov_kab_current';
            Proc::Filter_Compare(Proc::Text, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'trOsnovs.tr_osnov_kab',
                'LikeManual' => true,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('mattraffic m2', 'mattraffics.id_material = m2.id_material and mattraffics.mattraffic_date < m2.mattraffic_date and mattraffics.mattraffic_id < m2.mattraffic_id')
                    ->leftJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere(['in', 'mattraffics.mattraffic_tip', [3]])
                    ->andWhere(['m2.mattraffic_date' => null])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'tr_osnov_kab_always';
            Proc::Filter_Compare(Proc::Text, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'trOsnovs.tr_osnov_kab',
                'LikeManual' => true,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'tr_osnov_install_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->rightJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere('mattraffics.id_material = material.material_id')
                ],
            ]);

            $attr = 'tr_osnov_uninstall_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['not exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->rightJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere('mattraffics.id_material = material.material_id')
                ],
            ]);

            $attr = 'tr_mat_install_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->rightJoin('tr_mat trMats', 'trMats.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere('mattraffics.id_material = material.material_id')
                ],
            ]);

            $attr = 'tr_mat_uninstall_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['not exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->rightJoin('tr_mat trMats', 'trMats.id_mattraffic = mattraffics.mattraffic_id')
                    ->andWhere('mattraffics.id_material = material.material_id')
                ],
            ]);

            $attr = 'mattraffic_username';
            Proc::Filter_Compare(Proc::Text, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'mattraffics.' . $attr,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'mattraffic_lastchange';
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'mattraffics.' . $attr,
                'ExistsSubQuery' => (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftjoin('(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'mattraffics.id_material = m2.id_material_m2 and mattraffics.id_mol = m2.id_mol_m2 and mattraffics.mattraffic_date < m2.mattraffic_date_m2')
                    ->andWhere(['m2.mattraffic_date_m2' => NULL])
                    ->andWhere('mattraffics.id_material = material.material_id')
            ]);

            $attr = 'material_working_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['not exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->leftJoin('osmotrakt osmotrakts', 'osmotrakts.id_tr_osnov = trOsnovs.tr_osnov_id')
                    ->leftJoin('recoveryrecieveakt recoveryrecieveakts', 'recoveryrecieveakts.id_osmotrakt = osmotrakts.osmotrakt_id')
                    ->andWhere(['recoveryrecieveakts.recoveryrecieveakt_repaired' => 1])
                    ->andWhere('mattraffics.id_material = material.material_id')
                ],
            ]);

            $attr = 'material_recovery_attachfiles_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['or', ['exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('tr_osnov trOsnovs', 'trOsnovs.id_mattraffic = mattraffics.mattraffic_id')
                    ->leftJoin('osmotrakt osmotrakts', 'osmotrakts.id_tr_osnov = trOsnovs.tr_osnov_id')
                    ->leftJoin('recoveryrecieveakt recoveryrecieveakts', 'recoveryrecieveakts.id_osmotrakt = osmotrakts.osmotrakt_id')
                    ->leftJoin('rra_docfiles rraDocfiles', 'rraDocfiles.id_recoveryrecieveakt = recoveryrecieveakts.recoveryrecieveakt_id')
                    ->andWhere(['not', ['rraDocfiles.rra_docfiles_id' => NULL]])
                    ->andWhere('mattraffics.id_material = material.material_id')
                ], ['exists', (new Query())
                    ->select('mattraffics.id_material')
                    ->from('mattraffic mattraffics')
                    ->leftJoin('tr_mat trMats', 'trMats.id_mattraffic = mattraffics.mattraffic_id')
                    ->leftJoin('tr_mat_osmotr trMatOsmotrs', 'trMatOsmotrs.id_tr_mat = trMats.tr_mat_id')
                    ->leftJoin('recoveryrecieveaktmat recoveryrecieveaktmats', 'recoveryrecieveaktmats.id_tr_mat_osmotr = trMatOsmotrs.tr_mat_osmotr_id')
                    ->leftJoin('rramat_docfiles rramatDocfiles', 'rramatDocfiles.id_recoveryrecieveaktmat = recoveryrecieveaktmats.recoveryrecieveaktmat_id')
                    ->andWhere(['not', ['rramatDocfiles.rramat_docfiles_id' => NULL]])
                    ->andWhere('mattraffics.id_material = material.material_id')
                ]],
            ]);
        }
    }

    public function searchforinstallakt_mat($params)
    {
        $query = Material::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['material_name' => SORT_ASC]],
        ]);

        $query->joinWith(['idMatv', 'idIzmer']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'material_id' => $this->material_id,
            'material_tip' => $this->material_tip,
            'material_writeoff' => $this->material_writeoff,
            'id_matvid' => $this->id_matvid,
            'id_izmer' => $this->id_izmer,
            'material_importdo' => $this->material_importdo,
        ]);

        $query->andFilterWhere(['like', 'material_name', $this->material_name])
            ->andFilterWhere(['like', 'material_name1c', $this->material_name1c])
            ->andFilterWhere(['like', 'material_1c', $this->material_1c])
            ->andFilterWhere(['like', 'material_inv', $this->material_inv])
            ->andFilterWhere(['like', 'material_serial', $this->material_serial]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_release', Proc::Date));
        //    $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));
        $query->andWhere(['material_number' => 1]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_price'));
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idIzmer.izmer_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_username'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_lastchange', Proc::DateTime));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'material_number'));

        Proc::AssignRelatedAttributes($dataProvider, ['idMatv.matvid_name', 'idIzmer.izmer_name']);

        return $dataProvider;
    }

}
                        