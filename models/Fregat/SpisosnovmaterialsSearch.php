<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Spisosnovmaterials;

/**
 * SpisosnovmaterialsSearch represents the model behind the search form about `app\models\Fregat\Spisosnovmaterials`.
 */
class SpisosnovmaterialsSearch extends Spisosnovmaterials
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.idMaterial.material_serial',
            'idMattraffic.idMaterial.material_release',
            'idMattraffic.idMaterial.material_price',
            'idSpisosnovakt.spisosnovakt_id',
            'idSpisosnovakt.spisosnovakt_date',
            'idSpisosnovakt.idMol.idperson.auth_user_fullname',
            'idSpisosnovakt.idMol.iddolzh.dolzh_name',
            'idSpisosnovakt.idMol.idpodraz.podraz_name',
            'idSpisosnovakt.idMol.idbuild.build_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spisosnovmaterials_id', 'id_mattraffic', 'id_spisosnovakt'], 'integer'],
            [['spisosnovmaterials_number'], 'number'],
            [[
                'idMattraffic.idMaterial.material_name',
                'idMattraffic.idMaterial.material_inv',
                'idMattraffic.idMaterial.material_serial',
                'idMattraffic.idMaterial.material_release',
                'idMattraffic.idMaterial.material_price',
                'idSpisosnovakt.spisosnovakt_id',
                'idSpisosnovakt.spisosnovakt_date',
                'idSpisosnovakt.idMol.idperson.auth_user_fullname',
                'idSpisosnovakt.idMol.iddolzh.dolzh_name',
                'idSpisosnovakt.idMol.idpodraz.podraz_name',
                'idSpisosnovakt.idMol.idbuild.build_name',
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
        $query = Spisosnovmaterials::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['spisosnovmaterials_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMattraffic.idMaterial',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'spisosnovmaterials_id' => $this->spisosnovmaterials_id,
            'id_mattraffic' => $this->id_mattraffic,
            'id_spisosnovakt' => $_GET['id'] ?: -1,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'spisosnovmaterials_number'));
        $query->andFilterWhere(['LIKE', 'idMaterial.material_name', $this->getAttribute('idMattraffic.idMaterial.material_name')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_inv', $this->getAttribute('idMattraffic.idMaterial.material_inv')]);
        $query->andFilterWhere(['LIKE', 'idMaterial.material_serial', $this->getAttribute('idMattraffic.idMaterial.material_serial')]);
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.idMaterial.material_release', Proc::Date));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idMattraffic.idMaterial.material_price'));

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMattraffic.idMaterial.material_name',
            'idMattraffic.idMaterial.material_inv',
            'idMattraffic.idMaterial.material_serial',
            'idMattraffic.idMaterial.material_release',
            'idMattraffic.idMaterial.material_price',
        ]);

        return $dataProvider;
    }

    public function searchformaterialspisosnovakt($params)
    {
        $query = Spisosnovmaterials::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['spisosnovmaterials_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMattraffic',
            'idSpisosnovakt.idMol.idperson',
            'idSpisosnovakt.idMol.iddolzh',
            'idSpisosnovakt.idMol.idpodraz',
            'idSpisosnovakt.idMol.idbuild',
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

        $query->andFilterWhere(Proc::WhereConstruct($this, 'spisosnovmaterials_number'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idSpisosnovakt.spisosnovakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'idSpisosnovakt.spisosnovakt_date', Proc::Date));

        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idSpisosnovakt.idMol.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idSpisosnovakt.idMol.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idpodraz.podraz_name', $this->getAttribute('idSpisosnovakt.idMol.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idbuild.build_name', $this->getAttribute('idSpisosnovakt.idMol.idbuild.build_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idSpisosnovakt.spisosnovakt_id',
            'idSpisosnovakt.spisosnovakt_date',
            'idSpisosnovakt.idMol.idperson.auth_user_fullname',
            'idSpisosnovakt.idMol.iddolzh.dolzh_name',
            'idSpisosnovakt.idMol.idpodraz.podraz_name',
            'idSpisosnovakt.idMol.idbuild.build_name',
        ]);

        return $dataProvider;
    }
}
