<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "dolzh".
 *
 * @property integer $dolzh_id
 * @property string $dolzh_name
 *
 * @property Employee[] $employees
 */
class Dolzh extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'dolzh';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['dolzh_name'], 'required'],
            [['dolzh_name'], 'string', 'max' => 100],
            [['dolzh_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['dolzh_name'], 'match', 'pattern' => '/^null$/iu', 'not' => true, 'message' => '{attribute} не может быть равен "NULL"'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'dolzh_id' => 'Dolzh ID',
            'dolzh_name' => 'Должность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees() {
        return $this->hasMany(Employee::className(), ['id_dolzh' => 'dolzh_id']);
    }

    public static function getDolzhByID($ID) {
        return $query = self::findOne($ID)->dolzh_name;
    }

    public function selectinput($params) {
        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
                ->select(array_merge(isset($params['init']) ? [] : [self::primaryKey()[0] . ' AS id'], ['dolzh_name AS text']))
                ->where(['like', isset($params['init']) ? 'dolzh_id' : 'dolzh_name', $params['q'], isset($params['init']) ? false : null])
                ->limit(20)
                ->asArray()
                ->$method();

        return $query;
    }

}
