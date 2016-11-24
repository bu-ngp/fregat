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
            'id_naklad' => $this->id_naklad,
            'id_mattraffic' => $this->id_mattraffic,
            'nakladmaterials_number' => $this->nakladmaterials_number,
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
}
