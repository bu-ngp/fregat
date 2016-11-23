<?php

namespace app\models\Fregat;

use app\func\Proc;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NakladSearch represents the model behind the search form about `app\models\Fregat\Naklad`.
 */
class NakladSearch extends Naklad
{
    public function attributes()
    {
        // add related fields to searchable attributes
        return array_merge(parent::attributes(), [
            'idMolGot.idperson.auth_user_fullname',
            'idMolGot.iddolzh.dolzh_name',
            'idMolGot.idpodraz.podraz_name',
            'idMolRelease.idperson.auth_user_fullname',
            'idMolRelease.iddolzh.dolzh_name',
            'idMolRelease.idpodraz.podraz_name',
        ]);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['naklad_id', 'id_mol_release', 'id_mol_got'], 'integer'],
            [['naklad_date'], 'safe'],
            [[
                'idMolGot.idperson.auth_user_fullname',
                'idMolGot.iddolzh.dolzh_name',
                'idMolGot.idpodraz.podraz_name',
                'idMolRelease.idperson.auth_user_fullname',
                'idMolRelease.iddolzh.dolzh_name',
                'idMolRelease.idpodraz.podraz_name',
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Naklad::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['naklad_id' => SORT_DESC]],
        ]);

        $query->joinWith([
            'idMolGot.idperson idmolgotperson',
            'idMolGot.iddolzh idmolgotdolzh',
            'idMolGot.idpodraz idmolgotpodraz',
            'idMolRelease.idperson idmolreleaseperson',
            'idMolRelease.iddolzh idmolreleasedolzh',
            'idMolRelease.idpodraz idmolreleasepodraz',
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'naklad_id' => $this->naklad_id,
            'id_mol_release' => $this->id_mol_release,
            'id_mol_got' => $this->id_mol_got,
        ]);

        $query->andFilterWhere(Proc::WhereConstruct($this, 'naklad_date', Proc::Date));
        $query->andFilterWhere(['LIKE', 'idmolgotperson.auth_user_fullname', $this->getAttribute('idMolGot.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'idmolgotdolzh.dolzh_name', $this->getAttribute('idMolGot.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idmolgotpodraz.dolzh_name', $this->getAttribute('idMolGot.idpodraz.podraz_name')]);
        $query->andFilterWhere(['LIKE', 'idmolreleaseperson.auth_user_fullname', $this->getAttribute('idMolRelease.idperson.auth_user_fullname')]);
        $query->andFilterWhere(['LIKE', 'idmolreleasedolzh.dolzh_name', $this->getAttribute('idMolRelease.iddolzh.dolzh_name')]);
        $query->andFilterWhere(['LIKE', 'idmolreleasepodraz.dolzh_name', $this->getAttribute('idMolRelease.idpodraz.podraz_name')]);

        Proc::AssignRelatedAttributes($dataProvider, [
            'idMolGot.idperson.auth_user_fullname' => 'idmolgotperson',
            'idMolGot.iddolzh.dolzh_name' => 'idmolgotdolzh',
            'idMolGot.idpodraz.podraz_name' => 'idmolgotpodraz',
            'idMolRelease.idperson.auth_user_fullname' => 'idmolreleaseperson',
            'idMolRelease.iddolzh.dolzh_name' => 'idmolreleasedolzh',
            'idMolRelease.idpodraz.podraz_name' => 'idmolreleasepodraz',
        ]);

        return $dataProvider;
    }
}
