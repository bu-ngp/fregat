<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Importmaterial;
use app\func\Proc;

/**
 * ImportmaterialSearch represents the model behind the search form about `app\models\Fregat\Importmaterial`.
 */
class ImportmaterialSearch extends Importmaterial
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idmatvid.matvid_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['importmaterial_id', 'id_matvid'], 'integer'],
            [['importmaterial_combination', 'idmatvid.matvid_name'], 'safe'],
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
        $query = Importmaterial::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['importmaterial_combination' => SORT_ASC]],
        ]);

        $query->joinWith(['idmatvid']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'importmaterial_id' => $this->importmaterial_id,
            'id_matvid' => $this->id_matvid,
        ]);

        $query->andFilterWhere(['like', 'importmaterial_combination', $this->importmaterial_combination]);
        $query->andFilterWhere(['LIKE', 'idmatvid.matvid_name', $this->getAttribute('idmatvid.matvid_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idmatvid.matvid_name']);

        return $dataProvider;
    }

}
        