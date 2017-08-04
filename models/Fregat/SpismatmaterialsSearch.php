<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Spismatmaterials;

/**
 * SpismatmaterialsSearch represents the model behind the search form about `app\models\Fregat\Spismatmaterials`.
 */
class SpismatmaterialsSearch extends Spismatmaterials
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_name1c',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.trMats.idParent.idMaterial.material_name',
            'idMattraffic.trMats.idParent.idMaterial.material_inv',
            'idMattraffic.trMats.idParent.idMaterial.material_serial',
            'idMattraffic.trMats.idParent.idMaterial.material_writeoff',
            'idMattraffic.trMats.idParent.idMol.idperson.auth_user_fullname',
            'idMattraffic.trMats.idParent.idMol.idbuild.build_name',
            'idMattraffic.trMats.idParent.trOsnovs.tr_osnov_kab',
            'idMattraffic.trMats.id_installakt',
            'idMattraffic.trMats.idInstallakt.installakt_date',
            'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
            'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
            'idMattraffic.trMats.idInstallakt.idInstaller.idpodraz.podraz_name',
            'idSpismat.spismat_id',
            'idSpismat.spismat_date',
            'idSpismat.idMol.idperson.auth_user_fullname',
            'idSpismat.idMol.iddolzh.dolzh_name',
            'idSpismat.idMol.idpodraz.podraz_name',
            'idSpismat.idMol.idbuild.build_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spismatmaterials_id', 'id_spismat', 'id_mattraffic'], 'integer'],
            [[
                'idMattraffic.idMaterial.material_name',
                'idMattraffic.idMaterial.material_name1c',
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.mattraffic_number',
                'idMattraffic.trMats.idParent.idMaterial.material_name',
                'idMattraffic.trMats.idParent.idMaterial.material_inv',
                'idMattraffic.trMats.idParent.idMaterial.material_serial',
                'idMattraffic.trMats.idParent.idMaterial.material_writeoff',
                'idMattraffic.trMats.idParent.idMol.idperson.auth_user_fullname',
                'idMattraffic.trMats.idParent.idMol.idbuild.build_name',
                'idMattraffic.trMats.idParent.trOsnovs.tr_osnov_kab',
                'idMattraffic.trMats.id_installakt',
                'idMattraffic.trMats.idInstallakt.installakt_date',
                'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
                'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
                'idMattraffic.trMats.idInstallakt.idInstaller.idpodraz.podraz_name',
                'idSpismat.spismat_id',
                'idSpismat.spismat_date',
                'idSpismat.idMol.idperson.auth_user_fullname',
                'idSpismat.idMol.iddolzh.dolzh_name',
                'idSpismat.idMol.idpodraz.podraz_name',
                'idSpismat.idMol.idbuild.build_name',
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
        $query = Spismatmaterials::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['spismatmaterials_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMattraffic.idMaterial',
            'idMattraffic.trMats.idParent.idMaterial matparent',
            'idMattraffic.trMats.idInstallakt.idInstaller.idperson personmaster',
            'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh dolzhmaster',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'spismatmaterials_id' => $this->spismatmaterials_id,
            'id_spismat' => $_GET['id'] ?: -1,
            'id_mattraffic' => $this->id_mattraffic,
        ]);

        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name1c', $this->getAttribute('idMattraffic.idMaterial.material_name1c')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idMattraffic.trMats.idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idMattraffic.trMats.idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.trMats.id_installakt'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.trMats.idInstallakt.installakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'personmaster.auth_user_fullname', $this->getAttribute('idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'dolzhmaster.dolzh_name', $this->getAttribute('idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_name1c',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.mattraffic_number',
            'idMattraffic.trMats.idParent.idMaterial.material_name' => 'matparent',
            'idMattraffic.trMats.idParent.idMaterial.material_inv' => 'matparent',
            'idMattraffic.trMats.id_installakt',
            'idMattraffic.trMats.idInstallakt.installakt_date',
            'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname' => 'personmaster',
            'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name' => 'dolzhmaster',
        ]);

        return $dataProvider;
    }

    public function searchformaterialspismatakt($params)
    {
        $query = Spismatmaterials::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['idSpismat.spismat_date' => SORT_DESC, 'idSpismat.spismat_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMattraffic.idMaterial',
            'idMattraffic.trMats.idParent.idMaterial matparent',
            'idMattraffic.trMats.idParent.idMol.idperson personmolparent',
            'idMattraffic.trMats.idParent.idMol.idbuild buildmolparent',
            'idMattraffic.trMats.idParent.trOsnovs osnparent',
            'idMattraffic.trMats.idInstallakt.idInstaller.idperson personmaster',
            'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh dolzhmaster',
            'idMattraffic.trMats.idInstallakt.idInstaller.idpodraz podrazmaster',
            'idSpismat.idMol spismatMol',
            'idSpismat.idMol.idperson personmol',
            'idSpismat.idMol.iddolzh dolzhmol',
            'idSpismat.idMol.idpodraz podrazmol',
            'idSpismat.idMol.idbuild buildmol',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idMattraffic.id_material' => $_GET['id'],
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'idSpismat.spismat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idSpismat.spismat_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'personmol.auth_user_fullname', $this->getAttribute('idSpismat.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'dolzhmol.dolzh_name', $this->getAttribute('idSpismat.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'podrazmol.podraz_name', $this->getAttribute('idSpismat.idMol.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'buildmol.build_name', $this->getAttribute('idSpismat.idMol.idbuild.build_name')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.mattraffic_number'));
        $query->andFilterWhere(['LIKE', 'personmaster.auth_user_fullname', $this->getAttribute('idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'dolzhmaster.dolzh_name', $this->getAttribute('idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'podrazmaster.podraz_name', $this->getAttribute('idMattraffic.trMats.idInstallakt.idInstaller.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'personmolparent.auth_user_fullname', $this->getAttribute('idMattraffic.trMats.idParent.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'buildmolparent.build_name', $this->getAttribute('idMattraffic.trMats.idParent.idMol.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'osnparent.tr_osnov_kab', $this->getAttribute('idMattraffic.trMats.idParent.trOsnovs.tr_osnov_kab')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_name', $this->getAttribute('idMattraffic.trMats.idParent.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_inv', $this->getAttribute('idMattraffic.trMats.idParent.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'matparent.material_serial', $this->getAttribute('idMattraffic.trMats.idParent.idMaterial.material_serial')]);
        $query->andFilterWhere(['matparent.material_writeoff' => $this->getAttribute('idMattraffic.trMats.idParent.idMaterial.material_writeoff')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idSpismat.spismat_id',
            'idSpismat.spismat_date',
            'idSpismat.idMol.idperson.auth_user_fullname' => 'personmol',
            'idSpismat.idMol.iddolzh.dolzh_name' => 'dolzhmol',
            'idSpismat.idMol.idpodraz.podraz_name' => 'podrazmol',
            'idSpismat.idMol.idbuild.build_name' => 'buildmol',
            'idMattraffic.mattraffic_number',
            'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname' => 'personmaster',
            'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name' => 'dolzhmaster',
            'idMattraffic.trMats.idInstallakt.idInstaller.idpodraz.podraz_name' => 'podrazmaster',
            'idMattraffic.trMats.idParent.idMol.idperson.auth_user_fullname' => 'personmolparent',
            'idMattraffic.trMats.idParent.idMol.idbuild.build_name' => 'buildmolparent',
            'idMattraffic.trMats.idParent.trOsnovs.tr_osnov_kab' => 'osnparent',
            'idMattraffic.trMats.idParent.idMaterial.material_name' => 'matparent',
            'idMattraffic.trMats.idParent.idMaterial.material_inv' => 'matparent',
            'idMattraffic.trMats.idParent.idMaterial.material_serial' => 'matparent',
            'idMattraffic.trMats.idParent.idMaterial.material_writeoff' => 'matparent',
        ]);

        return $dataProvider;
    }
}
