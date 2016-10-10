<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Docfiles;

/**
 * DocfilesSearch represents the model behind the search form about `app\models\Fregat\Docfiles`.
 */
class DocfilesSearch extends Docfiles
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['docfiles_id'], 'integer'],
            [['docfiles_name', 'docfiles_hash', 'docfiles_ext'], 'safe'],
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
        $query = Docfiles::find();

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
            'docfiles_id' => $this->docfiles_id,
        ]);

        $query->andFilterWhere(['like', 'docfiles_name', $this->docfiles_name])
            ->andFilterWhere(['like', 'docfiles_hash', $this->docfiles_hash])
            ->andFilterWhere(['like', 'docfiles_ext', $this->docfiles_ext]);

        return $dataProvider;
    }
}
