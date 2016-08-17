<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "recoverysendakt".
 *
 * @property integer $recoverysendakt_id
 * @property string $recoverysendakt_date
 * @property integer $id_organ
 *
 * @property Recoveryrecieveakt[] $recoveryrecieveakts
 * @property Organ $idOrgan
 */
class Recoverysendakt extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'recoverysendakt';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['recoverysendakt_date', 'id_organ'], 'required'],
            [['recoverysendakt_date'], 'safe'],
            [['id_organ'], 'integer'],
            [['id_organ'], 'exist', 'skipOnError' => true, 'targetClass' => Organ::className(), 'targetAttribute' => ['id_organ' => 'organ_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'recoverysendakt_id' => 'Номер акта восстановления мат. ценности',
            'recoverysendakt_date' => 'Дата отправки',
            'id_organ' => 'Организация',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveakts() {
        return $this->hasMany(Recoveryrecieveakt::className(), ['id_recoverysendakt' => 'recoverysendakt_id'])->inverseOf('idRecoverysendakt');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveaktmats() {
        return $this->hasMany(Recoveryrecieveaktmat::className(), ['id_recoverysendakt' => 'recoverysendakt_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOrgan() {
        return $this->hasOne(Organ::className(), ['organ_id' => 'id_organ'])->inverseOf('recoverysendakts');
    }

}
