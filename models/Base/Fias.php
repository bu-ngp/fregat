<?php

namespace app\models\Base;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "fias".
 *
 * @property string $AOGUID
 * @property string $OFFNAME
 * @property string $SHORTNAME
 * @property string $IFNSFL
 * @property integer $AOLEVEL
 * @property string $PARENTGUID
 *
 * @property Patient[] $patients
 */
class Fias extends \yii\db\ActiveRecord {

    public $fias_city;
    public $fias_street;
    public $CountStreets;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'fias';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['OFFNAME', 'SHORTNAME', 'IFNSFL', 'AOLEVEL', 'PARENTGUID'], 'required'],
            [['AOGUID'], 'required', 'except' => 'citychoose'],
            [['AOLEVEL'], 'integer'],
            [['AOGUID', 'PARENTGUID'], 'string', 'max' => 36],
            [['OFFNAME'], 'string', 'max' => 120],
            [['SHORTNAME'], 'string', 'max' => 10],
            [['IFNSFL'], 'string', 'max' => 4],
            [['fias_city', 'fias_street'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'AOGUID' => 'Aoguid',
            'OFFNAME' => 'Offname',
            'SHORTNAME' => 'Shortname',
            'IFNSFL' => 'Ifnsfl',
            'AOLEVEL' => 'Aolevel',
            'PARENTGUID' => 'Parentguid',
            'fias_city' => 'Населенный пункт',
            'fias_street' => 'Улица',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPatients() {
        return $this->hasOne(Patient::className(), ['id_fias' => 'AOGUID']);
    }

    public function selectinputforcity($params) {

        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
                ->select(array_merge(isset($params['init']) ? [] : ['t2.AOGUID AS id'], ["IF ( t1.OFFNAME is null, CONCAT_WS('. ',t2.SHORTNAME,t2.OFFNAME), CONCAT_WS(', ',  CONCAT_WS('. ',t1.SHORTNAME,t1.OFFNAME), CONCAT_WS('. ',t2.SHORTNAME,t2.OFFNAME)) ) AS text"]))
                ->from(['t1' => 'fias'])
                ->join('RIGHT JOIN', 'fias AS t2', 't2.PARENTGUID = t1.AOGUID')
                ->where(['like', isset($params['init']) ? 't2.AOGUID' : 't2.OFFNAME', $params['q'], isset($params['init']) ? false : null])
                ->andWhere(['between', 't2.AOLEVEL', 4, 6])
                ->groupBy(['t2.AOGUID', 't2.AOLEVEL'])
                ->orderBy('t1.OFFNAME, t2.OFFNAME')
                ->limit(10)
                ->asArray()
                ->$method();

        return $query;
    }

    public function selectinputforstreet($params) {

        $method = isset($params['init']) ? 'one' : 'all';
        if (isset($params['fias_city']) || $method === 'one') {
            $query = self::find()
                    ->select(array_merge(isset($params['init']) ? [] : ['AOGUID AS id'], ["CONCAT_WS('. ',SHORTNAME,OFFNAME) AS text"]))
                    ->where(['like', isset($params['init']) ? 'AOGUID' : 'OFFNAME', $params['q'], isset($params['init']) ? false : null])
                    ->andWhere($method === 'one' ? '1=1' : ['like', 'PARENTGUID', $params['fias_city']])
                    ->andWhere(['AOLEVEL' => 7])
                    ->orderBy('OFFNAME')
                    ->limit(10)
                    ->asArray()
                    ->$method();

            return $query;
        }
    }

    public static function Checkstreets($AOGUID) {
        $result = 0;
        if (!empty($AOGUID)) {
            $query = self::find()
                    ->select(new Expression('count(t2.AOGUID) AS CountStreets'))
                    ->from(['t1' => 'fias'])
                    ->join('LEFT JOIN', 'fias AS t2', 't2.PARENTGUID = t1.AOGUID')
                    ->where(['like', 't1.AOGUID', $AOGUID, false])
                    ->one();

            if (!empty($query))
                $result = $query->CountStreets;
        }
        return $result;
    }

}
