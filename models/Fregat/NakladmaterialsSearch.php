<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Nakladmaterials;

/**
 * NakladmaterialsSearch represents the model behind the search form about `app\models\Fregat\Nakladmaterials`.
 */
class NakladmaterialsSearch extends Nakladmaterials
{
    public $nakladmaterials_sum; // Для работы фильтра

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.idMaterial.idIzmer.izmer_name',
            'idMattraffic.idMaterial.idIzmer.izmer_kod_okei',
            'idMattraffic.idMaterial.material_price',
            'idNaklad.naklad_id',
            'idNaklad.naklad_date',
            'idNaklad.idMolRelease.idperson.auth_user_fullname',
            'idNaklad.idMolRelease.iddolzh.dolzh_name',
            'idNaklad.idMolRelease.idpodraz.podraz_name',
            'idNaklad.idMolRelease.idbuild.build_name',
            'idNaklad.idMolGot.idperson.auth_user_fullname',
            'idNaklad.idMolGot.iddolzh.dolzh_name',
            'idNaklad.idMolGot.idpodraz.podraz_name',
            'idNaklad.idMolGot.idbuild.build_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nakladmaterials_id', 'id_naklad', 'id_mattraffic'], 'integer'],
            [['nakladmaterials_number'], 'number'],
            [[
                'nakladmaterials_sum',
                'idMattraffic.idMaterial.material_name',
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.idMaterial.idIzmer.izmer_name',
                'idMattraffic.idMaterial.idIzmer.izmer_kod_okei',
                'idMattraffic.idMaterial.material_price',
                'idNaklad.naklad_id',
                'idNaklad.naklad_date',
                'idNaklad.idMolRelease.idperson.auth_user_fullname',
                'idNaklad.idMolRelease.iddolzh.dolzh_name',
                'idNaklad.idMolRelease.idpodraz.podraz_name',
                'idNaklad.idMolRelease.idbuild.build_name',
                'idNaklad.idMolGot.idperson.auth_user_fullname',
                'idNaklad.idMolGot.iddolzh.dolzh_name',
                'idNaklad.idMolGot.idpodraz.podraz_name',
                'idNaklad.idMolGot.idbuild.build_name',
            ], 'safe']
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
        $query = Nakladmaterials::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['nakladmaterials_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idMattraffic.idMaterial.idIzmer']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'nakladmaterials_id' => $this->nakladmaterials_id,
            'id_naklad' => $_GET['id'] ?: -1,
            'id_mattraffic' => $this->id_mattraffic,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'nakladmaterials_number'));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_name', $this->getAttribute('idMattraffic.idMaterial.idIzmer.izmer_name')]);
        $query->andFilterWhere(['LIKE', 'idIzmer.izmer_kod_okei', $this->getAttribute('idMattraffic.idMaterial.idIzmer.izmer_kod_okei')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_price', $this->getAttribute('idMattraffic.idMaterial.material_price')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'nakladmaterials_sum', 0, '`idMaterial`.`material_price` * `nakladmaterials_number`'));

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.idMaterial.idIzmer.izmer_name',
            'idMattraffic.idMaterial.idIzmer.izmer_kod_okei',
            'idMattraffic.idMaterial.material_price',
        ]);

        $dataProvider->sort->attributes['nakladmaterials_sum'] = [
            'asc' => ['`idMaterial`.`material_price` * `nakladmaterials_number`' => SORT_ASC],
            'desc' => ['`idMaterial`.`material_price` * `nakladmaterials_number`' => SORT_DESC],
        ];

        return $dataProvider;
    }

    public function searchformaterialnaklad($params)
    {
        $query = Nakladmaterials::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['nakladmaterials_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMattraffic',
            'idNaklad.idMolRelease.idperson idpersonrelease',
            'idNaklad.idMolRelease.iddolzh iddolzhrelease',
            'idNaklad.idMolRelease.idpodraz idpodrazrelease',
            'idNaklad.idMolRelease.idbuild idbuildrelease',
            'idNaklad.idMolGot.idperson idpersongot',
            'idNaklad.idMolGot.iddolzh iddolzhgot',
            'idNaklad.idMolGot.idpodraz idpodrazgot',
            'idNaklad.idMolGot.idbuild idbuildgot',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idMattraffic.id_material' => $_GET['id'] ?: -1,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'nakladmaterials_number'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idNaklad.naklad_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idNaklad.naklad_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idpersonrelease.auth_user_fullname', $this->getAttribute('idNaklad.idMolRelease.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzhrelease.dolzh_name', $this->getAttribute('idNaklad.idMolRelease.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodrazrelease.podraz_name', $this->getAttribute('idNaklad.idMolRelease.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuildrelease.build_name', $this->getAttribute('idNaklad.idMolRelease.idbuild.build_name')]);
        $query->andFilterWhere(['LIKE', 'idpersongot.auth_user_fullname', $this->getAttribute('idNaklad.idMolGot.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzhgot.dolzh_name', $this->getAttribute('idNaklad.idMolGot.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodrazgot.podraz_name', $this->getAttribute('idNaklad.idMolGot.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuildgot.build_name', $this->getAttribute('idNaklad.idMolGot.idbuild.build_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idNaklad.naklad_id',
            'idNaklad.naklad_date',
            'idNaklad.idMolRelease.idperson.auth_user_fullname' => 'idpersonrelease',
            'idNaklad.idMolRelease.iddolzh.dolzh_name' => 'iddolzhrelease',
            'idNaklad.idMolRelease.idpodraz.podraz_name' => 'idpodrazrelease',
            'idNaklad.idMolRelease.idbuild.build_name' => 'idbuildrelease',
            'idNaklad.idMolGot.idperson.auth_user_fullname' => 'idpersongot',
            'idNaklad.idMolGot.iddolzh.dolzh_name' => 'iddolzhgot',
            'idNaklad.idMolGot.idpodraz.podraz_name' => 'idpodrazgot',
            'idNaklad.idMolGot.idbuild.build_name' => 'idbuildgot',
        ]);

        return $dataProvider;
    }
}
