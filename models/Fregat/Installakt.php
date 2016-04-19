<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "installakt".
 *
 * @property string $installakt_id
 * @property string $installakt_date
 * @property integer $id_installer
 *
 * @property Employee $idInstaller
 * @property TrMat[] $trMats
 * @property TrOsnov[] $trOsnovs
 */
class Installakt extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'installakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['installakt_date', 'id_installer'], 'required'],
            [['installakt_date'], 'safe'],
            [['id_installer'], 'integer'],
            [['id_installer'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_installer' => 'employee_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'installakt_id' => '№ акта установки',
            'installakt_date' => 'Дата установки',
            'id_installer' => 'Установщик',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstaller()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_installer'])->inverseOf('installakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMats()
    {
        return $this->hasMany(TrMat::className(), ['id_installakt' => 'installakt_id'])->inverseOf('idInstallakt');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs()
    {
        return $this->hasMany(TrOsnov::className(), ['id_installakt' => 'installakt_id'])->inverseOf('idInstallakt');
    }
}
