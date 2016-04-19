<?php

namespace app\models\Fregat;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Fregat\Installakt;
use app\func\Proc;

/**
 * InstallaktSearch represents the model behind the search form about `app\models\Fregat\Installakt`.
 */
class InstallaktSearch extends Installakt {

    public function attributes() {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), ['idInstaller.idperson.auth_user_fullname', 'idInstaller.iddolzh.dolzh_name']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['installakt_id', 'id_installer'], 'integer'],
            [['installakt_date', 'idInstaller.idperson.auth_user_fullname', 'idInstaller.iddolzh.dolzh_name'], 'safe'],
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
        $query = Installakt::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['idInstaller' => function($query) {
                $query->from(['idInstaller' => 'employee']);
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
                            'installakt_id' => $this->installakt_id,
                            'id_installer' => $this->id_installer,
                        ]);

                        $query->andFilterWhere(Proc::WhereCunstruct($this, 'installakt_date', 'date'));
                        $query->andFilterWhere(['LIKE', 'idperson.auth_user_fullname', $this->getAttribute('idInstaller.idperson.auth_user_fullname')]);
                        $query->andFilterWhere(['LIKE', 'iddolzh.dolzh_name', $this->getAttribute('idInstaller.iddolzh.dolzh_name')]);

                        $dataProvider->sort->attributes['idInstaller.idperson.auth_user_fullname'] = [
                            'asc' => ['idperson.auth_user_fullname' => SORT_ASC],
                            'desc' => ['idperson.auth_user_fullname' => SORT_DESC],
                        ];

                        $dataProvider->sort->attributes['idInstaller.iddolzh.dolzh_name'] = [
                            'asc' => ['iddolzh.dolzh_name' => SORT_ASC],
                            'desc' => ['iddolzh.dolzh_name' => SORT_DESC],
                        ];

                        if (empty($params['sort']))
                            $query->orderBy('installakt_id desc');

                        return $dataProvider;
                    }

                }
                