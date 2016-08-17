<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Recoveryrecieveaktmat;

/**
 * RecoveryrecieveaktmatSearch represents the model behind the search form about `app\models\Fregat\Recoveryrecieveaktmat`.
 */
class RecoveryrecieveaktmatSearch extends Recoveryrecieveaktmat
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recoveryrecieveaktmat_id', 'recoveryrecieveaktmat_repaired', 'id_recoverysendakt', 'id_tr_mat_osmotr'], 'integer'],
            [['recoveryrecieveaktmat_result', 'recoveryrecieveaktmat_date'], 'safe'],
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
        $query = Recoveryrecieveaktmat::find();

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
            'recoveryrecieveaktmat_id' => $this->recoveryrecieveaktmat_id,
            'recoveryrecieveaktmat_repaired' => $this->recoveryrecieveaktmat_repaired,
            'recoveryrecieveaktmat_date' => $this->recoveryrecieveaktmat_date,
            'id_recoverysendakt' => $this->id_recoverysendakt,
            'id_tr_mat_osmotr' => $this->id_tr_mat_osmotr,
        ]);

        $query->andFilterWhere(['like', 'recoveryrecieveaktmat_result', $this->recoveryrecieveaktmat_result]);

        return $dataProvider;
    }
}
