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
            [['recoveryrecieveaktmat_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['recoveryrecieveaktmat_date'], 'compare', 'compareValue' => $this->idRecoverysendakt->recoverysendakt_date, 'operator' => '>=', 'message' => 'Значение {attribute} должно быть больше или равно даты акта отправки материала сторонней организации «' . Yii::$app->formatter->asDate($this->idRecoverysendakt->recoverysendakt_date) . '».'],
            [['id_recoverysendakt', 'id_tr_mat_osmotr'], 'required'],
            [['recoveryrecieveaktmat_result'], 'string', 'max' => 255],
            [['id_recoverysendakt'], 'exist', 'skipOnError' => true, 'targetClass' => Recoverysendakt::className(), 'targetAttribute' => ['id_recoverysendakt' => 'recoverysendakt_id']],
            [['id_tr_mat_osmotr'], 'exist', 'skipOnError' => true, 'targetClass' => TrMatOsmotr::className(), 'targetAttribute' => ['id_tr_mat_osmotr' => 'tr_mat_osmotr_id']],
            [['id_tr_mat_osmotr'], 'UniqueRecoveryrecieveaktmat'],
            [['recoveryrecieveaktmat_repaired', 'recoveryrecieveaktmat_date'], 'ResultRecoveryrecieveaktmat'],
        ];
    }

    // Проверяет на уникальность записи в таблице recoveryrecieveaktmat по полям id_osmotraktmat, id_recoverysendakt, recoveryrecieveaktmat_date, где recoveryrecieveaktmat_date может быть NULL
    public function UniqueRecoveryrecieveaktmat($attribute, $params)
    {
        if ($this->isNewRecord) {
            $query = self::find()
                ->andWhere([
                    'id_tr_mat_osmotr' => $this->id_tr_mat_osmotr,
                    'id_recoverysendakt' => $this->id_recoverysendakt,
                    'recoveryrecieveaktmat_date' => empty($this->recoveryrecieveaktmat_date) ? NULL : $this->recoveryrecieveaktmat_date,
                ])
                ->all();

            if (count($query) > 0)
                $this->addError('recoveryrecieveaktmat_date', 'Нарушена уникальность записи. Запись уже существует.');
        }
    }

    // Проверяет чтобы при сохранении результата восстановления были заполнены поля recoveryrecieveakt_repaired и recoveryrecieveakt_date
    public function ResultRecoveryrecieveaktmat($attribute, $params)
    {
        if (empty($this->recoveryrecieveaktmat_repaired) xor empty($this->recoveryrecieveaktmat_date))
            $this->addError('recoveryrecieveaktmat_repaired', 'Для сохранения результата восстановления необходимо заполнить поля "Подлежит восстановлению" и "Дата получения"');
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
        return $this->hasOne(Recoverysendakt::className(), ['recoverysendakt_id' => 'id_recoverysendakt'])->from(['idRecoverysendakt' => Recoverysendakt::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrMatOsmotr()
    {
        return $this->hasOne(TrMatOsmotr::className(), ['tr_mat_osmotr_id' => 'id_tr_mat_osmotr'])->from(['idTrMatOsmotr' => TrMatOsmotr::tableName()]);
    }

    public static function getMolsByRecoverysendakt($Recoverysendakt_id)
    {
        return self::find()
            ->select(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
            ->joinWith([
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.idperson',
                'idTrMatOsmotr.idTrMat.idMattraffic.idMol.iddolzh',
            ])
         //   ->leftJoin('mattraffic mt', 'idMattraffic.id_material = mt.id_material and  idMattraffic.mattraffic_date < mt.mattraffic_date')
            ->andWhere(['id_recoverysendakt' => $Recoverysendakt_id])
            ->andWhere(['idMattraffic.mattraffic_tip' => 4])
          //  ->andWhere('`mt`.`mattraffic_date` IS NULL')
            ->groupBy(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
            ->asArray()
            ->all();
    }

    public static function VariablesValues($attribute)
    {
        $values = [
            'recoveryrecieveaktmat_repaired' => [1 => 'Восстановлению не подлежит', 2 => 'Восстановлено'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
                                        