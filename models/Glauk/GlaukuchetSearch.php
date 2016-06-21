<?php

namespace app\models\Glauk;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Glauk\Glaukuchet;

/**
 * GlaukuchetSearch represents the model behind the search form about `app\models\Glauk\Glaukuchet`.
 */
class GlaukuchetSearch extends Glaukuchet {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['glaukuchet_id', 'glaukuchet_detect', 'glaukuchet_deregreason', 'glaukuchet_stage', 'glaukuchet_invalid', 'id_patient', 'id_employee', 'id_class_mkb'], 'integer'],
            [['glaukuchet_uchetbegin', 'glaukuchet_deregdate', 'glaukuchet_operdate', 'glaukuchet_lastvisit', 'glaukuchet_lastmetabol', 'glaukuchet_comment', 'glaukuchet_username', 'glaukuchet_lastchange'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Glaukuchet::find();

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
            'glaukuchet_id' => $this->glaukuchet_id,
            'glaukuchet_uchetbegin' => $this->glaukuchet_uchetbegin,
            'glaukuchet_detect' => $this->glaukuchet_detect,
            'glaukuchet_deregdate' => $this->glaukuchet_deregdate,
            'glaukuchet_deregreason' => $this->glaukuchet_deregreason,
            'glaukuchet_stage' => $this->glaukuchet_stage,
            'glaukuchet_operdate' => $this->glaukuchet_operdate,            
            'glaukuchet_invalid' => $this->glaukuchet_invalid,
            'glaukuchet_lastvisit' => $this->glaukuchet_lastvisit,
            'glaukuchet_lastmetabol' => $this->glaukuchet_lastmetabol,
            'id_patient' => $this->id_patient,
            'id_employee' => $this->id_employee,
            'id_class_mkb' => $this->id_class_mkb,
        ]);

        $query->andFilterWhere(['like', 'glaukuchet_comment', $this->glaukuchet_comment]);

        return $dataProvider;
    }

}
