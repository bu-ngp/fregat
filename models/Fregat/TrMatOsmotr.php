<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_mat_osmotr".
 *
 * @property string $tr_mat_osmotr_id
 * @property string $id_tr_mat
 * @property string $id_osmotraktmat
 *
 * @property Recoveryrecieveaktmat[] $recoveryrecieveaktmats
 * @property Osmotraktmat $idOsmotraktmat
 * @property TrMat $idTrMat
 */
class TrMatOsmotr extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_mat_osmotr';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_tr_mat', 'id_osmotraktmat', 'tr_mat_osmotr_number'], 'required'],
            [['id_tr_mat', 'id_osmotraktmat', 'id_reason'], 'integer'],
            [['tr_mat_osmotr_comment'], 'string', 'max' => 400],
            [['tr_mat_osmotr_number'], 'default', 'value' => 1],
            [['tr_mat_osmotr_number'], 'double', 'min' => 0.001, 'max' => 10000000000],
            [['id_osmotraktmat'], 'exist', 'skipOnError' => true, 'targetClass' => Osmotraktmat::className(), 'targetAttribute' => ['id_osmotraktmat' => 'osmotraktmat_id']],
            [['id_tr_mat'], 'exist', 'skipOnError' => true, 'targetClass' => TrMat::className(), 'targetAttribute' => ['id_tr_mat' => 'tr_mat_id']],
            [['id_reason'], 'exist', 'skipOnError' => true, 'targetClass' => Reason::className(), 'targetAttribute' => ['id_reason' => 'reason_id']],
            [['tr_mat_osmotr_number'], 'MaxNumberMaterial'],
            [['id_reason', 'tr_mat_osmotr_comment'], 'required', 'when' => function ($model) {
                return empty($model->id_reason) && empty($model->tr_mat_osmotr_comment);
            }, 'message' => 'Необходимо заполнить одно из полей.', 'enableClientValidation' => false],
        ];
    }

    // Проверяет количество осмотреного материала, которое не должно превышать количество перемещеного материала в рамках одного МОЛа
    public function MaxNumberMaterial($attribute)
    {
        $currentnumber = self::find()->andWhere([
            'id_tr_mat' => $this->id_tr_mat,
            'id_osmotraktmat' => $this->id_osmotraktmat,
        ])->count();

        $thisnumber = $this->isNewRecord ? 0 : self::FindOne($this->tr_mat_osmotr_id)->tr_mat_osmotr_number;

        if ($this->tr_mat_osmotr_number > $this->idTrMat->idMattraffic->mattraffic_number - $currentnumber + $thisnumber)
            $this->addError($attribute, 'Количество осмотренного материала не может превышать количество перемещенного материала в рамках текущего акта. Максимально допустимое количество осмотренного материала = ' . ($this->idTrMat->idMattraffic->mattraffic_number - $currentnumber + $thisnumber) . ($currentnumber > 0 && $this->isNewRecord ? '. Этот материал уже присутствует в этом акте осмотра' : ''));
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_mat_osmotr_id' => 'Tr Mat Osmotr ID',
            'id_tr_mat' => 'Материал',
            'tr_mat_osmotr_comment' => 'Описание причины неисправности',
            'id_reason' => 'Причина неисправности',
            'id_osmotraktmat' => 'Акт осмотра материала',
            'tr_mat_osmotr_number' => 'Количество осмотренного материала',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveaktmats()
    {
        return $this->hasMany(Recoveryrecieveaktmat::className(), ['id_tr_mat_osmotr' => 'tr_mat_osmotr_id'])->from(['recoveryrecieveaktmats' => Recoveryrecieveaktmat::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdOsmotraktmat()
    {
        return $this->hasOne(Osmotraktmat::className(), ['osmotraktmat_id' => 'id_osmotraktmat'])->from(['idOsmotraktmat' => Osmotraktmat::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrMat()
    {
        return $this->hasOne(TrMat::className(), ['tr_mat_id' => 'id_tr_mat'])->from(['idTrMat' => TrMat::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReason()
    {
        return $this->hasOne(Reason::className(), ['reason_id' => 'id_reason'])->from(['idReason' => Reason::tableName()]);
    }

    public static function getMolsByTrMatOsmotr($Osmotraktmat_id)
    {
        return self::find()
            ->select(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
            ->joinWith([
                'idTrMat.idMattraffic.idMol.idperson',
                'idTrMat.idMattraffic.idMol.iddolzh',])
            ->andWhere(['id_osmotraktmat' => $Osmotraktmat_id])
            ->andWhere(['idMattraffic.mattraffic_tip' => 4])
            ->groupBy(['idMol.id_person', 'idMol.id_dolzh'])
            ->asArray()
            ->all();
    }

    public static function getBuildandKabByTrMatOsmotr($Tr_mat_osmotr_id)
    {
        if (!empty($Tr_mat_osmotr_id)) {
            $query = self::find()
                ->select(['idbuild.build_name', 'trOsnovs.tr_osnov_kab'])
                ->joinWith([
                    'idTrMat.idParent.idMol.idbuild',
                    'idTrMat.idParent.trOsnovs',
                ])
                //   ->leftJoin('mattraffic mt', 'mattraffics.id_material = mt.id_material and  mattraffics.mattraffic_date < mt.mattraffic_date')
                ->andWhere(['tr_mat_osmotr_id' => $Tr_mat_osmotr_id])
                ->andWhere(['idParent.mattraffic_tip' => 3])
                //   ->andWhere('`mt`.`mattraffic_date` IS NULL')
                ->asArray()
                ->one();

            if (!empty($query))
                return $query['build_name'] . ', ' . $query['tr_osnov_kab'];
        }
    }

}
                                                                                