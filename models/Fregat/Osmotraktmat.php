<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "osmotraktmat".
 *
 * @property string $osmotraktmat_id
 * @property string $osmotraktmat_comment
 * @property string $osmotraktmat_date
 * @property integer $id_reason
 * @property string $id_tr_mat
 * @property integer $id_master
 *
 * @property Employee $idMaster
 * @property Reason $idReason
 * @property TrMat $idTrMat
 * @property TrMatOsmotr[] $trMatOsmotrs
 */
class Osmotraktmat extends \yii\db\ActiveRecord {

    public $osmotraktmat_countmat;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'osmotraktmat';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['osmotraktmat_date', 'id_master'], 'required'],
            [['osmotraktmat_date'], 'safe'],
            [['id_master'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_master' => 'employee_id']],
            [['osmotraktmat_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['osmotraktmat_date'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение {attribute} должно быть меньше или равно значения «' . Yii::$app->formatter->asDate(date('Y-m-d')) . '».'],
            [['osmotraktmat_countmat'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'osmotraktmat_id' => 'Номер акта осмотра материала',
            'osmotraktmat_date' => 'Дата осмотра материала',
            'id_master' => 'Составитель акта',
            'osmotraktmat_countmat' => 'Количество материалов',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaster() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_master']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMatOsmotrs() {
        return $this->hasMany(TrMatOsmotr::className(), ['id_osmotraktmat' => 'osmotraktmat_id']);
    }

}
