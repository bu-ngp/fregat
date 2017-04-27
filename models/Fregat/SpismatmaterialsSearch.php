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
            'idMattraffic.trMats.id_installakt',
            'idMattraffic.trMats.idInstallakt.installakt_date',
            'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
            'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
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
                'idMattraffic.trMats.id_installakt',
                'idMattraffic.trMats.idInstallakt.installakt_date',
                'idMattraffic.trMats.idInstallakt.idInstaller.idperson.auth_user_fullname',
                'idMattraffic.trMats.idInstallakt.idInstaller.iddolzh.dolzh_name',
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
}
