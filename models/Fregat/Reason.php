<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "reason".
 *
 * @property integer $reason_id
 * @property string $reason_text
 *
 * @property Osmotrakt[] $osmotrakts
 */
class Reason extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'reason';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['reason_text'], 'required'],
            [['reason_text'], 'string', 'max' => 400],
            [['reason_text'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['reason_text'], 'filter', 'filter' => function($value) {
            return mb_strtoupper($value, 'UTF-8');
        }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'reason_id' => 'Reason ID',
            'reason_text' => 'Причина поломки',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts() {
        return $this->hasMany(Osmotrakt::className(), ['id_reason' => 'reason_id'])->from(['osmotrakts' => Osmotrakt::tableName()])->inverseOf('idReason');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotraktmats() {
        return $this->hasMany(Osmotraktmat::className(), ['id_reason' => 'reason_id'])->from(['osmotraktmats' => Osmotraktmat::tableName()]);
    }

}
