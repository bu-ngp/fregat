<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Osmotrakt;
use app\func\Proc;
use yii\db\Query;

/**
 * OsmotraktSearch represents the model behind the search form about `app\models\Fregat\Osmotrakt`.
 */
class OsmotraktSearch extends Osmotrakt
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrosnov.idMattraffic.idMaterial.material_name',
            'idTrosnov.idMattraffic.idMaterial.material_inv',
            'idTrosnov.idMattraffic.idMaterial.material_serial',
            'idTrosnov.idMattraffic.idMaterial.material_writeoff',
            'idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idTrosnov.tr_osnov_kab',
            'idUser.idperson.auth_user_fullname',
            'idUser.iddolzh.dolzh_name',
            'idUser.idbuild.build_name',
            'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
            'idReason.reason_text',
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
            'idTrosnov.idInstallakt.installakt_id',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['osmotrakt_id', 'id_reason', 'id_user', 'id_master', 'id_tr_osnov'], 'integer'],
            [['osmotrakt_comment', 'osmotrakt_date'], 'safe'],
            [['idTrosnov.idMattraffic.idMaterial.material_inv'], 'string'],
            [[
                'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
                'idTrosnov.idMattraffic.idMaterial.material_name',
                'idTrosnov.idMattraffic.idMaterial.material_serial',
                'idTrosnov.idMattraffic.idMaterial.material_writeoff',
                'idTrosnov.idMattraffic.idMol.idbuild.build_name',
                'idTrosnov.tr_osnov_kab',
                'idUser.idperson.auth_user_fullname',
                'idUser.iddolzh.dolzh_name',
                'idUser.idbuild.build_name',
                'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
                'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
                'idReason.reason_text',
                'idMaster.idperson.auth_user_fullname',
                'idMaster.iddolzh.dolzh_name',
                'idTrosnov.idInstallakt.installakt_id',
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
        $query->joinWith(['idTrosnov.idInstallakt']);
        $query->joinWith(['idTrosnov.idMattraffic.idMaterial.idMatv']);
        $query->joinWith(['idTrosnov.idMattraffic.idMol.idperson']);
        $query->joinWith(['idTrosnov.idMattraffic.idMol.iddolzh']);
        $query->joinWith(['idTrosnov.idMattraffic.idMol.idbuild']);
        $query->joinWith(['idUser.idperson iduserperson']);
        $query->joinWith(['idUser.iddolzh iduserdolzh']);
        $query->joinWith(['idReason']);
        $query->joinWith(['idMaster.idperson idmasterperson']);
        $query->joinWith(['idMaster.iddolzh idmasterdolzh']);
    }

    private function baseFilter(&$query)
    {
        $query->andFilterWhere([
            'id_reason' => $this->id_reason,
            'id_user' => $this->id_user,
            'id_master' => $this->id_master,
            'id_tr_osnov' => $this->id_tr_osnov,
        ]);

        $query->andFilterWhere(['like', 'osmotrakt_comment', $this->osmotrakt_comment]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotrakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotrakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idMatv.matvid_name', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idTrosnov.idMattraffic.idMaterial.material_serial')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrosnov.idMattraffic.idMaterial.material_writeoff'));
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idTrosnov.idMattraffic.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idTrosnov.tr_osnov_kab', $this->getAttribute('idTrosnov.tr_osnov_kab')]);
        $query->andFilterWhere(['LIKE', 'iduserperson.auth_user_fullname', $this->getAttribute('idUser.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iduserdolzh.dolzh_name', $this->getAttribute('idUser.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idmolperson.auth_user_fullname', $this->getAttribute('idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'idmoldolzh.dolzh_name', $this->getAttribute('idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'idmasterperson.auth_user_fullname', $this->getAttribute('idMaster.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'idmasterdolzh.dolzh_name', $this->getAttribute('idMaster.iddolzh.dolzh_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idTrosnov.idInstallakt.installakt_id'));
    }

    private function baseSort(&$dataProvider)
    {
        Proc::AssignRelatedAttributes($dataProvider, [
            'idTrosnov.idMattraffic.idMaterial.idMatv.matvid_name',
            'idTrosnov.idMattraffic.idMaterial.material_name',
            'idTrosnov.idMattraffic.idMaterial.material_inv',
            'idTrosnov.idMattraffic.idMaterial.material_serial',
            'idTrosnov.idMattraffic.idMaterial.material_writeoff',
            'idTrosnov.idMattraffic.idMol.idbuild.build_name',
            'idTrosnov.idMattraffic.idMol.idperson.auth_user_fullname',
            'idTrosnov.idMattraffic.idMol.iddolzh.dolzh_name',
            'idTrosnov.tr_osnov_kab',
            'idReason.reason_text',
            'idUser.idperson.auth_user_fullname' => 'iduserperson',
            'idUser.iddolzh.dolzh_name' => 'iduserdolzh',
            'idMaster.idperson.auth_user_fullname' => 'idmasterperson',
            'idMaster.iddolzh.dolzh_name' => 'idmasterdolzh',
            'idTrosnov.idInstallakt.installakt_id',
        ]);
    }

    public function search($params)
    {
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

        $this->osmotraktDopFilter($query);

        return $dataProvider;
    }

    public function searchmat($params)
    {
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

    public function searchforrecoveryrecieveakt($params)
    {
        $query = Osmotrakt::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['osmotrakt_id' => SORT_DESC]],
        ]);

        $this->baseRelations($query);

        $query->joinWith(['recoveryrecieveakts']);
        $query->join('LEFT JOIN', '(select mt.id_material, IF (rra.recoveryrecieveakt_date IS NULL, \'9999-12-31\', rra.recoveryrecieveakt_date) AS recoveryrecieveakt_date from recoveryrecieveakt rra LEFT JOIN osmotrakt oa ON oa.osmotrakt_id=rra.id_osmotrakt LEFT JOIN tr_osnov ts ON oa.id_tr_osnov = ts.tr_osnov_id LEFT JOIN mattraffic mt ON ts.id_mattraffic = mt.mattraffic_id) lastrra', 'lastrra.id_material = idMattraffic.id_material and recoveryrecieveakts.recoveryrecieveakt_date < lastrra.recoveryrecieveakt_date');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->baseFilter($query);
        //$query->andWhere('(lastrra.recoveryrecieveakt_date IS NULL and recoveryrecieveakts.recoveryrecieveakt_repaired = 2 or recoveryrecieveakts.recoveryrecieveakt_id IS NULL)');
        $query->andWhere('(lastrra.recoveryrecieveakt_date IS NULL and recoveryrecieveakts.recoveryrecieveakt_repaired IS NULL and recoveryrecieveakts.recoveryrecieveakt_id IS NULL)');
        $this->baseSort($dataProvider);

        return $dataProvider;
    }

    public function searchformaterialkarta($params)
    {
        $query = Osmotrakt::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['osmotrakt_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idTrosnov.idMattraffic',
            'idUser.idperson idpersonuser',
            'idUser.iddolzh iddolzhuser',
            'idUser.idbuild idbuilduser',
            'idMaster.idperson idpersonmaster',
            'idMaster.iddolzh iddolzhmaster',
            'idReason',
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andWhere(['idMattraffic.id_material' => $params['id']]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotrakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotrakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idReason.reason_text', $this->getAttribute('idReason.reason_text')]);
        $query->andFilterWhere(['LIKE', 'osmotrakt_comment', $this->getAttribute('osmotrakt_comment')]);
        $query->andFilterWhere(['LIKE', 'idpersonuser.auth_user_fullname', $this->getAttribute('idUser.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzhuser.dolzh_name', $this->getAttribute('idUser.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idbuilduser.build_name', $this->getAttribute('idUser.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpersonmaster.auth_user_fullname', $this->getAttribute('idMaster.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzhmaster.dolzh_name', $this->getAttribute('idMaster.iddolzh.dolzh_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idReason.reason_text',
            'idUser.idperson.auth_user_fullname' => 'idpersonuser',
            'idUser.iddolzh.dolzh_name' => 'iddolzhuser',
            'idUser.idbuild.build_name' => 'idbuilduser',
            'idMaster.idperson.auth_user_fullname' => 'idpersonmaster',
            'idMaster.iddolzh.dolzh_name' => 'iddolzhmaster',
        ]);

        return $dataProvider;
    }

    private function osmotraktDopFilter(&$query)
    {
        $filter = Proc::GetFilter($this->formName(), 'OsmotraktFilter');

        if (!empty($filter)) {

            $attr = 'mattraffic_date_writeoff';
            Proc::Filter_Compare(Proc::DateRange, $query, $filter, [
                'Attribute' => $attr,
                'SQLAttribute' => 'idMattrafficMat.mattraffic_date',
                'ExistsSubQuery' => (new Query())
                    ->select('idOsmotrakt.osmotrakt_id')
                    ->from('osmotrakt idOsmotrakt')
                    ->leftJoin('tr_osnov idTrosnov', 'idTrosnov.tr_osnov_id = idOsmotrakt.id_tr_osnov')
                    ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrosnov.id_mattraffic')
                    ->leftJoin('mattraffic idMattrafficMat', 'idMattraffic.id_material = idMattrafficMat.id_material and idMattraffic.id_mol = idMattrafficMat.id_mol and idMattrafficMat.mattraffic_tip = 2')
                    ->leftJoin('material idMaterial', 'idMaterial.material_id = idMattraffic.id_material')
                    ->andWhere(['idMaterial.material_writeoff' => 1])
                    ->andWhere('idOsmotrakt.osmotrakt_id = osmotrakt.osmotrakt_id')
            ]);

            $attr = 'osmotrakt_recoverysendakt_exists_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('recoveryrecieveakts.id_osmotrakt')
                    ->from('recoveryrecieveakt recoveryrecieveakts')
                    ->andWhere('recoveryrecieveakts.id_osmotrakt = osmotrakt.osmotrakt_id')
                ],
            ]);

            $attr = 'osmotrakt_recoverysendakt_not_exists_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['not exists', (new Query())
                    ->select('recoveryrecieveakts.id_osmotrakt')
                    ->from('recoveryrecieveakt recoveryrecieveakts')
                    ->andWhere('recoveryrecieveakts.id_osmotrakt = osmotrakt.osmotrakt_id')
                ],
            ]);

            $attr = 'osmotrakt_recoveryrecieveakt_repaired';
            Proc::Filter_Compare(Proc::WhereStatement, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('recoveryrecieveakts.id_osmotrakt')
                    ->from('recoveryrecieveakt recoveryrecieveakts')
                    ->andWhere(['recoveryrecieveakts.recoveryrecieveakt_repaired' => $filter[$attr]])
                    ->andWhere('recoveryrecieveakts.id_osmotrakt = osmotrakt.osmotrakt_id')],
            ]);

            $attr = 'osmotrakt_recoverysendakt_not_recieved_mark';
            Proc::Filter_Compare(Proc::Mark, $query, $filter, [
                'Attribute' => $attr,
                'WhereStatement' => ['exists', (new Query())
                    ->select('recoveryrecieveakts.id_osmotrakt')
                    ->from('recoveryrecieveakt recoveryrecieveakts')
                    ->andWhere(['recoveryrecieveakts.recoveryrecieveakt_repaired' => null])
                    ->andWhere('recoveryrecieveakts.id_osmotrakt = osmotrakt.osmotrakt_id')
                ],
            ]);

        }
    }

}
                                                                                                