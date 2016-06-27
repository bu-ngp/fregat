<?php

namespace app\models\Fregat\Import;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Import\Logreport;
use app\func\Proc;

/**
 * LogreportSearch represents the model behind the search form about `app\models\Fregat\Import\Logreport`.
 */
class LogreportSearch extends Logreport {
    /*  public function attributes() {
      // add related fields to searchable attributes
      return array_merge(parent::attributes(), ['maxfilelastdate']);
      } */

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['logreport_id', 'logreport_errors', 'logreport_updates', 'logreport_additions', 'logreport_amount', 'logreport_missed', 'logreport_executetime', 'logreport_memoryused'], 'safe'],
            [['logreport_date', 'maxfilelastdate'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = Logreport::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['logreport_id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_id'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_executetime', 'time'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_date', 'date'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_errors'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_updates'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_additions'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_amount'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_missed'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_memoryused'));

        return $dataProvider;
    }

}
