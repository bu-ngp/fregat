<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "rra_docfiles".
 *
 * @property string $rra_docfiles_id
 * @property string $id_docfiles
 * @property string $id_recoveryrecieveakt
 *
 * @property Docfiles $idDocfiles
 * @property Recoveryrecieveakt $idRecoveryrecieveakt
 */
class RraDocfiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rra_docfiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_docfiles', 'id_recoveryrecieveakt'], 'required'],
            [['id_docfiles', 'id_recoveryrecieveakt'], 'integer'],
            [['id_docfiles'], 'exist', 'skipOnError' => true, 'targetClass' => Docfiles::className(), 'targetAttribute' => ['id_docfiles' => 'docfiles_id']],
            [['id_recoveryrecieveakt'], 'exist', 'skipOnError' => true, 'targetClass' => Recoveryrecieveakt::className(), 'targetAttribute' => ['id_recoveryrecieveakt' => 'recoveryrecieveakt_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rra_docfiles_id' => 'Rra Docfiles ID',
            'id_docfiles' => 'Id Docfiles',
            'id_recoveryrecieveakt' => 'Id Recoveryrecieveakt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDocfiles()
    {
        return $this->hasOne(Docfiles::className(), ['docfiles_id' => 'id_docfiles'])->from(['idDocfiles' => Docfiles::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRecoveryrecieveakt()
    {
        return $this->hasOne(Recoveryrecieveakt::className(), ['recoveryrecieveakt_id' => 'id_recoveryrecieveakt'])->from(['idRecoveryrecieveakt' => Recoveryrecieveakt::tableName()]);
    }
}
