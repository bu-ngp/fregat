<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "{{%cabinet}}".
 *
 * @property integer $cabinet_id
 * @property integer $id_build
 * @property string $cabinet_name
 *
 * @property Build $idbuild
 * @property TrOsnov[] $trOsnovs
 */
class Cabinet extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cabinet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_build'], 'integer'],
            [['cabinet_name'], 'string', 'max' => 255],
            [['id_build', 'cabinet_name'], 'unique', 'targetAttribute' => ['id_build', 'cabinet_name'], 'message' => 'В здании уже имеется этот кабинет.'],
            [['id_build'], 'exist', 'skipOnError' => true, 'targetClass' => Build::className(), 'targetAttribute' => ['id_build' => 'build_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cabinet_id' => 'Cabinet ID',
            'id_build' => 'Здание',
            'cabinet_name' => 'Кабинет',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdbuild()
    {
        return $this->hasOne(Build::className(), ['build_id' => 'id_build'])->from(['idbuild' => Build::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs()
    {
        return $this->hasMany(TrOsnov::className(), ['id_cabinet' => 'cabinet_id']);
    }

    public static function getCabinetByID($ID)
    {
        $query = self::findOne($ID);
        return $query->idbuild->build_name . ', каб. ' . $query->cabinet_name;
    }

    public function selectinput($params)
    {
        $method = isset($params['init']) ? 'one' : 'all';
        $id_mattraffic = (string)filter_input(INPUT_GET, 'id_mattraffic');
        $id_build = null;

        if ($id_mattraffic) {
            $mattraffic = Mattraffic::findOne($id_mattraffic);
            $id_build = $mattraffic->idMol->id_build;
        }

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['CONCAT_WS(", ", idbuild.build_name, CONCAT_WS(" ", "каб.", cabinet_name)) AS text']))
            ->joinWith(['idbuild'])
            ->where($method === 'one' ? ['cabinet_id' => $params['q']] : ['like', 'cabinet_name', $params['q'] . '%', false])
            ->andFilterWhere($id_build ? ['id_build' => $id_build] : [])
            ->orderBy(['idbuild.build_name' => SORT_ASC, 'cabinet_name' => SORT_ASC])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }
}
