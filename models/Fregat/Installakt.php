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
class Installakt extends \yii\db\ActiveRecord {

    public $auth_user_fullname_tmp; // Для формирования акта установки в Excel
    public $dolzh_name_tmp; // Для формирования акта установки в Excel

    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'installakt';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['installakt_date', 'id_installer'], 'required'],
            [['id_installer'], 'integer'],
            [['id_installer'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_installer' => 'employee_id']],
            [['installakt_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['installakt_date'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение {attribute} должно быть меньше или равно значения «' . Yii::$app->formatter->asDate(date('Y-m-d')) . '».'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'installakt_id' => '№ акта установки',
            'installakt_date' => 'Дата установки',
            'id_installer' => 'Установщик',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstaller() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_installer'])->inverseOf('installakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMats() {
        return $this->hasMany(TrMat::className(), ['id_installakt' => 'installakt_id'])->inverseOf('idInstallakt');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrOsnovs() {
        return $this->hasMany(TrOsnov::className(), ['id_installakt' => 'installakt_id'])->inverseOf('idInstallakt');
    }

    public static function getMolsByInstallakt($installakt_id) {
        return self::find()
                        ->select(['idperson.auth_user_fullname auth_user_fullname_tmp', 'iddolzh.dolzh_name dolzh_name_tmp'])
                        ->leftJoin('tr_osnov trOsnovs', 'installakt.installakt_id = trOsnovs.id_installakt')
                        ->leftJoin('tr_mat trMats', 'installakt.installakt_id = trMats.id_installakt')
                        ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = trOsnovs.id_mattraffic or idMattraffic.mattraffic_id = trMats.id_mattraffic')
                        ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                        ->leftJoin('auth_user idperson', 'idMol.id_person = idperson.auth_user_id')
                        ->leftJoin('dolzh iddolzh', 'iddolzh.dolzh_id = idMol.id_dolzh')
                        ->andWhere(['installakt_id' => $installakt_id])
                        ->groupBy(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
                        ->all();
    }

}
