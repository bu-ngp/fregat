<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "recoveryrecieveakt".
 *
 * @property string $recoveryrecieveakt_id
 * @property string $id_osmotrakt
 * @property integer $id_recoverysendakt
 * @property string $recoveryrecieveakt_result
 * @property integer $recoveryrecieveakt_repaired
 * @property string $recoveryrecieveakt_date
 *
 * @property Osmotrakt $idOsmotrakt
 * @property Recoverysendakt $idRecoverysendakt
 */
class Recoveryrecieveakt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'recoveryrecieveakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_osmotrakt', 'id_recoverysendakt'], 'required'],
            [['id_osmotrakt', 'id_recoverysendakt', 'recoveryrecieveakt_repaired'], 'integer'],
            [['recoveryrecieveakt_date'], 'safe'],
            [['recoveryrecieveakt_result'], 'string', 'max' => 255],
            [['id_osmotrakt'], 'exist', 'skipOnError' => true, 'targetClass' => Osmotrakt::className(), 'targetAttribute' => ['id_osmotrakt' => 'osmotrakt_id']],
            [['id_recoverysendakt'], 'exist', 'skipOnError' => true, 'targetClass' => Recoverysendakt::className(), 'targetAttribute' => ['id_recoverysendakt' => 'recoverysendakt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'recoveryrecieveakt_id' => 'Recoveryrecieveakt ID',
            'id_osmotrakt' => 'Акт осмотра',
            'id_recoverysendakt' => 'Акт отправки на восстановление',
            'recoveryrecieveakt_result' => 'Результат восстановления',
            'recoveryrecieveakt_repaired' => 'Подлежит восстановлению',
            'recoveryrecieveakt_date' => 'Дата получения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOsmotrakt()
    {
        return $this->hasOne(Osmotrakt::className(), ['osmotrakt_id' => 'id_osmotrakt'])->inverseOf('recoveryrecieveakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRecoverysendakt()
    {
        return $this->hasOne(Recoverysendakt::className(), ['recoverysendakt_id' => 'id_recoverysendakt'])->inverseOf('recoveryrecieveakts');
    }
}