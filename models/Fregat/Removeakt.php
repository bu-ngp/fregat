<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "removeakt".
 *
 * @property string $removeakt_id
 * @property string $removeakt_date
 * @property integer $id_remover
 *
 * @property Employee $idRemover
 * @property TrRmMat[] $trRmMats
 */
class Removeakt extends \yii\db\ActiveRecord {

    public $auth_user_fullname_tmp; // Для формирования акта установки в Excel
    public $dolzh_name_tmp; // Для формирования акта установки в Excel

    /**
     * @inheritdoc
     */

    public static function tableName() {
        return 'removeakt';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['removeakt_date', 'id_remover'], 'required'],
            [['removeakt_date'], 'safe'],
            [['id_remover'], 'integer'],
            [['id_remover'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_remover' => 'employee_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'removeakt_id' => '№ акта снятия',
            'removeakt_date' => 'Дата снятия материала',
            'id_remover' => 'Демонтировщик',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRemover() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_remover'])->from(['idRemover' => Employee::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrRmMats() {
        return $this->hasMany(TrRmMat::className(), ['id_removeakt' => 'removeakt_id'])->from(['trRmMats' => TrRmMat::tableName()]);
    }

    public static function getMolsByRemoveakt($removeakt_id) {
        return self::find()
                        ->select(['idperson.auth_user_fullname auth_user_fullname_tmp', 'iddolzh.dolzh_name dolzh_name_tmp'])
                        ->leftJoin('tr_rm_mat trRmMats', 'removeakt.removeakt_id = trRmMats.id_removeakt')
                        ->leftJoin('tr_mat idTrMat', 'idTrMat.tr_mat_id = trRmMats.id_tr_mat')
                        ->leftJoin('mattraffic idMattraffic', 'idMattraffic.mattraffic_id = idTrMat.id_mattraffic')
                        ->leftJoin('employee idMol', 'idMattraffic.id_mol = idMol.employee_id')
                        ->leftJoin('auth_user idperson', 'idMol.id_person = idperson.auth_user_id')
                        ->leftJoin('dolzh iddolzh', 'iddolzh.dolzh_id = idMol.id_dolzh')
                        ->andWhere(['removeakt_id' => $removeakt_id])
                        ->groupBy(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
                        ->all();
    }

}
