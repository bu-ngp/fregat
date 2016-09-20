<?php

namespace app\models\Base;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Base\Patient;
use app\func\Proc;
use yii\db\Expression;

/**
 * PatientSearch represents the model behind the search form about `app\models\Base\Patient`.
 */
class PatientSearch extends Patient
{

    public function attributes()
    {
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
    public function rules()
    {
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
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    private function glaukRelations(&$query)
    {
        $query->joinWith([
            'glaukuchets' => function ($query) {
                $query->from(['glaukuchets' => 'glaukuchet']);
                $query->joinWith([
                    'glpreps' => function ($query) {
                        $query->select(new \yii\db\Expression("glpreps.glprep_id, glpreps.id_glaukuchet, IF ( glpreps.id_preparat IS NULL, '', GROUP_CONCAT(TRIM(CONCAT_WS(' ', idPreparat.preparat_name, IF (glpreps.glprep_rlocat IS NULL, '', IF (glpreps.glprep_rlocat = 1, '(Федеральная)', '(Региональная)'))))   SEPARATOR ', ')) AS glaukuchet_preparats"));
                        $query->from(['glpreps' => 'glprep']);
                        $query->joinWith(['idPreparat']);
                        $query->addGroupBy(['glpreps.id_glaukuchet']);
                    }]);
                $query->GroupBy(['glaukuchets.glaukuchet_id']);
            },
            'idFias' => function ($query) {
                $query->select(["idFias.AOGUID, IF (idFias.AOLEVEL < 7, CONCAT_WS(', ',  CONCAT_WS('. ',idFias2.SHORTNAME,idFias2.OFFNAME), CONCAT_WS('. ',idFias.SHORTNAME,idFias.OFFNAME)), CONCAT_WS('. ',idFias2.SHORTNAME,idFias2.OFFNAME)) AS fias_city", "IF (idFias.AOLEVEL < 7, '', CONCAT_WS('. ',idFias.SHORTNAME,idFias.OFFNAME)) AS fias_street"]);
                $query->from(['idFias' => 'fias']);
                $query->join('LEFT JOIN', 'fias AS idFias2', 'idFias.PARENTGUID = idFias2.AOGUID');
            },
            'glaukuchets.idClassMkb',
            'glaukuchets.idEmployee.idperson',
            'glaukuchets.idEmployee.iddolzh',
            'glaukuchets.idEmployee.idpodraz',
            'glaukuchets.idEmployee.idbuild',
        ]);
        $query->addGroupBy(['patient_id']);
    }

    private function glaukFilter(&$query)
    {
        $query->andFilterWhere(['LIKE', 'patient_fam', $this->getAttribute('patient_fam')]);
        $query->andFilterWhere(['LIKE', 'patient_im', $this->getAttribute('patient_im')]);
        $query->andFilterWhere(['LIKE', 'patient_ot', $this->getAttribute('patient_ot')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'patient_dr', Proc::Date));
        $query->andFilterWhere(['LIKE', 'patient_pol', $this->getAttribute('patient_pol')]);
        $fias_city = $this->getAttribute('idFias.fias_city');
        if (!empty($fias_city))
            $query->andFilterWhere(['or', ['and', ['LIKE', 'idFias.OFFNAME', $this->getAttribute('idFias.fias_city')], 'idFias.AOLEVEL < 7'], ['and', ['LIKE', 'idFias2.OFFNAME', $this->getAttribute('idFias.fias_city')], 'idFias.AOLEVEL >= 7']]);
        $fias_street = $this->getAttribute('idFias.fias_street');
        if (!empty($fias_street))
            $query->andFilterWhere(['and', ['LIKE', 'idFias.OFFNAME', $this->getAttribute('idFias.fias_street')], 'idFias.AOLEVEL >= 7']);
        $query->andFilterWhere(['LIKE', 'patient_dom', $this->getAttribute('patient_dom')]);
        $query->andFilterWhere(['LIKE', 'patient_korp', $this->getAttribute('patient_korp')]);
        $query->andFilterWhere(['LIKE', 'patient_kvartira', $this->getAttribute('patient_kvartira')]);
        $query->andFilterWhere(['LIKE', 'patient_username', $this->getAttribute('patient_username')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'patient_lastchange', Proc::DateTime));

        $query->andFilterWhere(Proc::WhereConstruct($this, 'glaukuchets.glaukuchet_uchetbegin', Proc::Date));
        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_detect', $this->getAttribute('glaukuchets.glaukuchet_detect')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'glaukuchets.glaukuchet_deregdate', Proc::Date));
        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_deregreason', $this->getAttribute('glaukuchets.glaukuchet_deregreason')]);
        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_stage', $this->getAttribute('glaukuchets.glaukuchet_stage')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'glaukuchets.glaukuchet_operdate', Proc::Date));
        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_invalid', $this->getAttribute('glaukuchets.glaukuchet_invalid')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'glaukuchets.glaukuchet_lastvisit', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'glaukuchets.glaukuchet_lastmetabol', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('glaukuchets.idEmployee.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('glaukuchets.idEmployee.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('glaukuchets.idEmployee.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('glaukuchets.idEmployee.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idClassMkb.code', $this->getAttribute('glaukuchets.idClassMkb.code')]);
        $query->andFilterWhere(['LIKE', 'idClassMkb.name', $this->getAttribute('glaukuchets.idClassMkb.name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'patient_lastchange', Proc::DateTime));
        $query->andFilterWhere(['LIKE', 'patient_username', $this->getAttribute('patient_username')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'glaukuchets.glaukuchet_lastchange', Proc::DateTime));
        $query->andFilterWhere(['LIKE', 'glaukuchets.glaukuchet_username', $this->getAttribute('glaukuchets.glaukuchet_username')]);
    }

    private function glaukSort(&$dataProvider)
    {
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

    private function glaukDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'PatientFilter');

        if (!empty($filter)) {
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_fam']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_im']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_ot']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_dr']);
            Proc::Filter_Compare(Proc::Number, $query, $filter, [
                'Attribute' => 'patient_vozrast',
                'SQLAttribute' => 'TIMESTAMPDIFF(YEAR, patient_dr, CURDATE())',
            ]);
            Proc::Filter_Compare(Proc::Strict, $query, $filter, ['Attribute' => 'patient_pol']);
            Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                'Attribute' => 'fias_city',
                'WhereStatement' => ['or', ['and', ['LIKE', 'idFias.AOGUID', $filter['fias_city']], 'idFias.AOLEVEL < 7'], ['and', ['LIKE', 'idFias2.AOGUID', $filter['fias_city']], 'idFias.AOLEVEL >= 7']],
            ]);
            Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                'Attribute' => 'fias_street',
                'WhereStatement' => ['and', ['LIKE', 'idFias.AOGUID', $filter['fias_street']], 'idFias.AOLEVEL >= 7'],
            ]);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_dom']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_korp']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_kvartira']);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'is_glauk_mark',
                'WhereStatement' => ['not', ['glaukuchets.glaukuchet_id' => null]],
            ]);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'glaukuchet_uchetbegin']);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, ['Attribute' => 'glaukuchet_detect']);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'is_glaukuchet_mark',
                'WhereStatement' => ['glaukuchets.glaukuchet_deregreason' => null, 'glaukuchets.glaukuchet_deregdate' => null],
            ]);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, ['Attribute' => 'glaukuchet_deregreason']);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'glaukuchet_deregdate']);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, ['Attribute' => 'glaukuchet_stage']);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'glaukuchet_operdate']);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'glaukuchet_not_oper_mark',
                'WhereStatement' => ['glaukuchets.glaukuchet_operdate' => null],
            ]);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, ['Attribute' => 'glaukuchet_invalid']);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'glaukuchet_not_invalid_mark',
                'WhereStatement' => ['glaukuchets.glaukuchet_invalid' => null],
            ]);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'glaukuchet_lastvisit']);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'glaukuchet_lastmetabol']);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'glaukuchet_not_lastmetabol_mark',
                'WhereStatement' => ['glaukuchets.glaukuchet_lastmetabol' => null],
            ]);
            Proc::Filter_Compare(Proc::Strict, $query, $filter, [
                'Attribute' => 'glaukuchet_id_employee',
                'SQLAttribute' => 'glaukuchets.id_employee',
            ]);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, [
                'Attribute' => 'employee_id_person',
                'SQLAttribute' => 'idEmployee.id_person',
            ]);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, [
                'Attribute' => 'employee_id_dolzh',
                'SQLAttribute' => 'idEmployee.id_dolzh',
            ]);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, [
                'Attribute' => 'employee_id_podraz',
                'SQLAttribute' => 'idEmployee.id_podraz',
            ]);
            Proc::Filter_Compare(Proc::MultiChoice, $query, $filter, [
                'Attribute' => 'employee_id_build',
                'SQLAttribute' => 'idEmployee.id_build',
            ]);
            Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                'Attribute' => 'glprep_id_preparat',
                'WhereStatement' => 'glaukuchets.glaukuchet_id in (select gl1.id_glaukuchet from glprep gl1 where gl1.id_preparat ' . (empty($filter['glprep_id_preparat' . '_not']) ? 'IN' : 'NOT IN') . ' (' . implode(',', !is_array($filter['glprep_id_preparat']) ? [] : $filter['glprep_id_preparat']) . '))',
            ]);
            Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                'Attribute' => 'glprep_rlocat',
                'WhereStatement' => 'glaukuchets.glaukuchet_id in (select gl1.id_glaukuchet from glprep gl1 where gl1.glprep_rlocat ' . (empty($filter['glprep_rlocat' . '_not']) ? 'IN' : 'NOT IN') . ' (' . implode(',', !is_array($filter['glprep_rlocat']) ? [] : $filter['glprep_rlocat']) . '))',
            ]);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'glprep_not_preparat_mark',
                'WhereStatement' => 'glaukuchets.glaukuchet_id not in (select gl1.id_glaukuchet from glprep gl1 group by gl1.id_glaukuchet)',
            ]);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'glprep_preparat_mark',
                'WhereStatement' => 'glaukuchets.glaukuchet_id in (select gl1.id_glaukuchet from glprep gl1 group by gl1.id_glaukuchet)',
            ]);
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => 'glaukuchet_comment_mark',
                'WhereStatement' => "glaukuchets.glaukuchet_comment <> ''",
            ]);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'glaukuchet_comment']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'patient_username']);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'patient_lastchange']);
            Proc::Filter_Compare(Proc::Text, $query, $filter, ['Attribute' => 'glaukuchet_username']);
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, ['Attribute' => 'glaukuchet_lastchange']);
        }
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
                                