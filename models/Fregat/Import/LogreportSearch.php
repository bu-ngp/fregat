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
            [['logreport_id', 'logreport_errors', 'logreport_updates', 'logreport_additions', 'logreport_amount', 'logreport_missed','logreport_executetime'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchreport($params) {
        $query = Logreport::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        

        $query->select(['logreport_id', 'logreport_executetime', 'logreport_date', 'logreport_errors', 'logreport_updates', 'logreport_additions', 'logreport_amount', 'logreport_missed', 'maxfilelastdate']);

        $query->from(['(select logreport_id, logreport_executetime, logreport_date, logreport_errors, logreport_updates, logreport_additions, logreport_amount, logreport_missed, CASE WHEN (MAX(matlog_filelastdate) > MAX(employeelog_filelastdate)) or employeelog_filelastdate is null THEN MAX(matlog_filelastdate) ELSE MAX(employeelog_filelastdate) END as maxfilelastdate
from logreport left join matlog on logreport.logreport_id = matlog.id_logreport left join employeelog on logreport.logreport_id = employeelog.id_logreport
group by logreport_id) logreport']);


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_id'));        
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_executetime', 'time'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_date', 'date'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'maxfilelastdate', 'datetime'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_errors'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_updates'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_additions'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_amount'));
        $query->andFilterWhere(Proc::WhereCunstruct($this, 'logreport_missed'));

        $dataProvider->sort->attributes['maxfilelastdate'] = [
            'asc' => ['maxfilelastdate' => SORT_ASC],
            'desc' => ['maxfilelastdate' => SORT_DESC],
        ];

        if (empty($_GET['sort']))
            $query->orderBy('logreport_id desc');

        return $dataProvider;
    }

}
