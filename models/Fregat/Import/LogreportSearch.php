<?php

namespace app\models\Fregat\Import;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Import\Logreport;

/**
 * LogreportSearch represents the model behind the search form about `app\models\Fregat\Import\Logreport`.
 */
class LogreportSearch extends Logreport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logreport_id', 'logreport_errors', 'logreport_updates', 'logreport_additions', 'logreport_amount', 'logreport_missed'], 'integer'],
            [['logreport_date'], 'safe'],
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
        $query = Logreport::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'logreport_id' => $this->logreport_id,
            'logreport_date' => $this->logreport_date,
            'logreport_errors' => $this->logreport_errors,
            'logreport_updates' => $this->logreport_updates,
            'logreport_additions' => $this->logreport_additions,
            'logreport_amount' => $this->logreport_amount,
            'logreport_missed' => $this->logreport_missed,
        ]);

        return $dataProvider;
    }
}
