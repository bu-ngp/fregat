<?php

namespace app\models\Base;

use app\models\Glauk\Glaukuchet;
use Yii;
use app\func\Proc;

/**
 * This is the model class for table "class_mkb".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $parent_id
 * @property string $parent_code
 * @property integer $node_count
 * @property string $additional_info
 *
 * @property Classmkb $parent
 * @property Classmkb[] $classmkbs
 * @property Glaukuchet[] $glaukuchets
 */
class Classmkb extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'class_mkb';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'code'], 'required'],
            [['parent_id', 'node_count'], 'integer'],
            [['additional_info'], 'string'],
            [['name'], 'string', 'max' => 512],
            [['code', 'parent_code'], 'string', 'max' => 20],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Classmkb::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'PK',
            'name' => 'Наименование диагноза',
            'code' => 'Код МКБ10',
            'parent_id' => 'Вышестоящий объект',
            'parent_code' => 'Код вышестоящего объекта',
            'node_count' => 'Количество вложенных в текущую ветку',
            'additional_info' => 'Дополнительные данные по диагнозу',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent() {
        return $this->hasOne(Classmkb::className(), ['id' => 'parent_id'])->from(['parent' => Classmkb::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClassmkbs() {
        return $this->hasMany(Classmkb::className(), ['parent_id' => 'id'])->from(['classmkbs' => Classmkb::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGlaukuchets() {
        return $this->hasMany(Glaukuchet::className(), ['id_class_mkb' => 'id'])->from(['glaukuchets' => Glaukuchet::tableName()]);
    }

    public function selectinput($params) {
        $method = isset($params['init']) ? 'one' : 'all';

        // Меняем раскладку на английскую при вводе МКБ10
        if ($method === 'all') {
            preg_match('/^([а-яА-Я]\d)/ui', $params['q'], $match);
            if (!empty($match[1]))
                $params['q'] = Proc::switcher($params['q']);
        }

        $query = self::find()
                ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['CONCAT_WS(" - ", code, name) AS text']))
                ->where(['node_count' => 0])
                ->andwhere(['or', ['like', isset($params['init']) ? 'id' : 'code', $params['q'], isset($params['init']) ? false : null], $method === 'all' ? ['like', 'name', $params['q']] : '1<>1'])
                ->andwhere(['or', ['like', 'code', 'H40%', false], ['like', 'code', 'Q15.0', false]])
                ->limit(10)
                ->asArray()
                ->$method();

        return $query;
    }

}
