<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Removeakt;
use app\func\Proc;

/**
 * RemoveaktSearch represents the model behind the search form about `app\models\Fregat\Removeakt`.
 */
class RemoveaktSearch extends Removeakt {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idRemover.idperson.auth_user_fullname', 'idRemover.iddolzh.dolzh_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['removeakt_id', 'id_remover'], 'integer'],
            [['removeakt_date', 'idRemover.idperson.auth_user_fullname', 'idRemover.iddolzh.dolzh_name'], 'safe'],
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
        $query = Removeakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['removeakt_id' => SORT_DESC]],
        ]);

        $query->joinWith(['idRemover' => function($query) {
                $query->from(['idRemover' => 'employee']);
                $query->joinWith([
                    'idperson' => function($query) {
                        $query->from(['idperson' => 'auth_user']);
                    },
                            'iddolzh' => function($query) {
                        $query->from(['iddolzh' => 'dolzh']);
                    },
                        ]);
                    }]);

                        $this->load($params);

                        if (!$this->validate()) {
                            // uncomment the following line if you do not want to return any records when validation fails
                            // $query->where('0=1');
                            return $dataProvider;
                        }

                        // grid filtering conditions
                        $query->andFilterWhere([
                            'removeakt_id' => $this->removeakt_id,
                            'id_remover' => $this->id_remover,
                        ]);

                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'removeakt_date', 'date'));
                        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idRemover.idperson.auth_user_fullname')]);
                        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idRemover.iddolzh.dolzh_name')]);

                        Proc::AssignRelatedAttributes($dataProvider, ['idRemover.idperson.auth_user_fullname', 'idRemover.iddolzh.dolzh_name']);

                        return $dataProvider;
                    }

}
