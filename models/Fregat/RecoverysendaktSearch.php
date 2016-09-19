<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Recoverysendakt;
use app\func\Proc;

/**
 * RecoverysendaktSearch represents the model behind the search form about `app\models\Fregat\Recoverysendakt`.
 */
class RecoverysendaktSearch extends Recoverysendakt
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idOrgan.organ_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recoverysendakt_id', 'id_organ'], 'integer'],
            [['recoverysendakt_date', 'idOrgan.organ_name'], 'safe'],
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
        $query = Recoverysendakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['recoverysendakt_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idOrgan']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_organ' => $this->id_organ,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoverysendakt_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'recoverysendakt_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idOrgan.organ_name', $this->getAttribute('idOrgan.organ_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idOrgan.organ_name']);

        return $dataProvider;
    }

}
        