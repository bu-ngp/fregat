<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Osmotraktmat;
use app\func\Proc;

/**
 * OsmotraktmatSearch represents the model behind the search form about `app\models\Fregat\Osmotraktmat`.
 */
class OsmotraktmatSearch extends Osmotraktmat
{

    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
            'trMatOsmotrs.tr_mat_osmotr_id',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['osmotraktmat_id', 'id_master'], 'integer'],
            [[
                'osmotraktmat_date',
                'idMaster.idperson.auth_user_fullname',
                'idMaster.iddolzh.dolzh_name',
                'trMatOsmotrs.tr_mat_osmotr_id',
                'osmotraktmat_countmat',
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

    public function search($params)
    {
        $query = Osmotraktmat::find();
        $query->select(['osmotraktmat_id', 'osmotraktmat_date', 'id_master', 'count(trMatOsmotrs.tr_mat_osmotr_id) AS osmotraktmat_countmat']);
        $query->joinWith(['idMaster.idperson idmasterperson', 'idMaster.iddolzh idmasterdolzh', 'trMatOsmotrs']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_master' => $this->id_master,
        ]);


        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotraktmat_id'));
        $query->andFilterWhere(Proc::WhereConstruct($this, 'osmotraktmat_date', Proc::Date));

        $query->groupBy(['osmotraktmat_id']);
        if (!empty($this->osmotraktmat_countmat)) {
            $w = Proc::WhereConstruct($this, 'osmotraktmat_countmat');
            $query->having('count(osmotraktmat_id) ' . $w[0] . $w[2]);
        }

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMaster.idperson.auth_user_fullname',
            'idMaster.iddolzh.dolzh_name',
        ]);

        $dataProvider->sort->attributes['osmotraktmat_countmat'] = [
            'asc' => ['count(osmotraktmat_id)' => SORT_ASC],
            'desc' => ['count(osmotraktmat_id)' => SORT_DESC],
        ];

        return $dataProvider;
    }

}
                