<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\RramatDocfiles;

/**
 * RramatDocfilesSearch represents the model behind the search form about `app\models\Fregat\RramatDocfiles`.
 */
class RramatDocfilesSearch extends RramatDocfiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rramat_docfiles_id', 'id_docfiles', 'id_recoveryrecieveaktmat'], 'integer'],
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
        $query = RramatDocfiles::find();

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
            'rramat_docfiles_id' => $this->rramat_docfiles_id,
            'id_docfiles' => $this->id_docfiles,
            'id_recoveryrecieveaktmat' => $this->id_recoveryrecieveaktmat,
        ]);

        return $dataProvider;
    }
}
