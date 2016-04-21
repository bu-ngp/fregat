<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_osnov".
 *
 * @property string $tr_osnov_id
 * @property string $tr_osnov_kab
 * @property string $id_installakt
 * @property string $id_mattraffic
 *
 * @property Installakt $idInstallakt
 * @property Mattraffic $idMattraffic
 */
class TrOsnov extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_osnov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_osnov_kab', 'id_installakt', 'id_mattraffic'], 'required'],
            [['id_installakt', 'id_mattraffic'], 'integer'],
            [['tr_osnov_kab'], 'string', 'max' => 255],
            [['id_installakt'], 'exist', 'skipOnError' => true, 'targetClass' => Installakt::className(), 'targetAttribute' => ['id_installakt' => 'installakt_id']],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_osnov_id' => 'Tr Osnov ID',
            'tr_osnov_kab' => 'Кабинет',
            'id_installakt' => 'Акт установки',
            'id_mattraffic' => 'Инвентарный номер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstallakt()
    {
        return $this->hasOne(Installakt::className(), ['installakt_id' => 'id_installakt'])->inverseOf('trOsnovs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->inverseOf('trOsnovs');
    }
}
