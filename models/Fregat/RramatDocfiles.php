<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "rramat_docfiles".
 *
 * @property string $rramat_docfiles_id
 * @property string $id_docfiles
 * @property string $id_recoveryrecieveaktmat
 *
 * @property Docfiles $idDocfiles
 * @property Recoveryrecieveaktmat $idRecoveryrecieveaktmat
 */
class RramatDocfiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rramat_docfiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_docfiles', 'id_recoveryrecieveaktmat'], 'required'],
            [['id_docfiles', 'id_recoveryrecieveaktmat'], 'integer'],
            [['id_docfiles'], 'exist', 'skipOnError' => true, 'targetClass' => Docfiles::className(), 'targetAttribute' => ['id_docfiles' => 'docfiles_id']],
            [['id_recoveryrecieveaktmat'], 'exist', 'skipOnError' => true, 'targetClass' => Recoveryrecieveaktmat::className(), 'targetAttribute' => ['id_recoveryrecieveaktmat' => 'recoveryrecieveaktmat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rramat_docfiles_id' => 'Rramat Docfiles ID',
            'id_docfiles' => 'Id Docfiles',
            'id_recoveryrecieveaktmat' => 'Id Recoveryrecieveaktmat',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDocfiles()
    {
        return $this->hasOne(Docfiles::className(), ['docfiles_id' => 'id_docfiles']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRecoveryrecieveaktmat()
    {
        return $this->hasOne(Recoveryrecieveaktmat::className(), ['recoveryrecieveaktmat_id' => 'id_recoveryrecieveaktmat']);
    }
}
