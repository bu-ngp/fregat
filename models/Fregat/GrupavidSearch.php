<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Grupavid;
use app\func\Proc;

/**
 * GrupavidSearch represents the model behind the search form about `app\models\Fregat\Grupavid`.
 */
class GrupavidSearch extends Grupavid
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idmatvid.matvid_name',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grupavid_id', 'grupavid_main', 'id_grupa', 'id_matvid'], 'integer'],
            [['idmatvid.matvid_name'], 'safe'],
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
        $query = Grupavid::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['idmatvid']);

        $this->load($params);

        $this->id_grupa = $params['id'];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'grupavid_id' => $this->grupavid_id,
            'grupavid_main' => $this->grupavid_main,
            'id_grupa' => $this->id_grupa,
            'id_matvid' => $this->id_matvid,
        ]);

        $query->andFilterWhere(['LIKE', 'idmatvid.matvid_name', $this->getAttribute('idmatvid.matvid_name')]);

        Proc::AssignRelatedAttributes($dataProvider, ['idmatvid.matvid_name']);

        return $dataProvider;
    }

}
        