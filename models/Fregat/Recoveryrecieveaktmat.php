<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "recoveryrecieveaktmat".
 *
 * @property string $recoveryrecieveaktmat_id
 * @property string $recoveryrecieveaktmat_result
 * @property integer $recoveryrecieveaktmat_repaired
 * @property string $recoveryrecieveaktmat_date
 * @property integer $id_recoverysendakt
 * @property string $id_tr_mat_osmotr
 *
 * @property Recoverysendakt $idRecoverysendakt
 * @property TrMatOsmotr $idTrMatOsmotr
 */
class Recoveryrecieveaktmat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recoveryrecieveaktmat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['recoveryrecieveaktmat_repaired', 'id_recoverysendakt', 'id_tr_mat_osmotr'], 'integer'],
            [['recoveryrecieveaktmat_date'], 'safe'],
            [['id_recoverysendakt', 'id_tr_mat_osmotr'], 'required'],
            [['recoveryrecieveaktmat_result'], 'string', 'max' => 255],
            [['id_recoverysendakt'], 'exist', 'skipOnError' => true, 'targetClass' => Recoverysendakt::className(), 'targetAttribute' => ['id_recoverysendakt' => 'recoverysendakt_id']],
            [['id_tr_mat_osmotr'], 'exist', 'skipOnError' => true, 'targetClass' => TrMatOsmotr::className(), 'targetAttribute' => ['id_tr_mat_osmotr' => 'tr_mat_osmotr_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recoveryrecieveaktmat_id' => 'Recoveryrecieveaktmat ID',
            'recoveryrecieveaktmat_result' => 'Результат восстановления',
            'recoveryrecieveaktmat_repaired' => 'Подлежит восстановлению',
            'recoveryrecieveaktmat_date' => 'Дата получения',
            'id_recoverysendakt' => 'Акт отправки на восстановление',
            'id_tr_mat_osmotr' => 'Акт осмотра материала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRecoverysendakt()
    {
        return $this->hasOne(Recoverysendakt::className(), ['recoverysendakt_id' => 'id_recoverysendakt']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrMatOsmotr()
    {
        return $this->hasOne(TrMatOsmotr::className(), ['tr_mat_osmotr_id' => 'id_tr_mat_osmotr']);
    }
}
