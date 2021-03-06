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
 * @property RraDocfiles[] $rraDocfiles
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
            [['recoveryrecieveakt_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['recoveryrecieveakt_date'], 'compare', 'compareValue' => $this->idRecoverysendakt->recoverysendakt_date, 'operator' => '>=', 'message' => 'Значение {attribute} должно быть больше или равно даты акта отправки материальной ценности сторонней организации «' . Yii::$app->formatter->asDate($this->idRecoverysendakt->recoverysendakt_date) . '».'],
            [['recoveryrecieveakt_date'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение {attribute} должно быть меньше или равно текущей даты «' . Yii::$app->formatter->asDate(date('Y-m-d')) . '».'],
            [['recoveryrecieveakt_result'], 'string', 'max' => 255],
            [['id_osmotrakt'], 'exist', 'skipOnError' => true, 'targetClass' => Osmotrakt::className(), 'targetAttribute' => ['id_osmotrakt' => 'osmotrakt_id']],
            [['id_recoverysendakt'], 'exist', 'skipOnError' => true, 'targetClass' => Recoverysendakt::className(), 'targetAttribute' => ['id_recoverysendakt' => 'recoverysendakt_id']],
            ['id_osmotrakt', 'UniqueRecoveryrecieveakt'],
            [['recoveryrecieveakt_repaired', 'recoveryrecieveakt_date'], 'ResultRecoveryrecieveakt'],
        ];
    }

    // Проверяет на уникальность записи в таблице recoveryrecieveakt по полям id_osmotrakt, id_recoverysendakt, recoveryrecieveakt_date, где recoveryrecieveakt_date может быть NULL
    public function UniqueRecoveryrecieveakt($attribute, $params)
    {
        $query = self::find()
            ->andWhere([
                'id_osmotrakt' => $this->id_osmotrakt,
                'id_recoverysendakt' => $this->id_recoverysendakt,
                'recoveryrecieveakt_date' => empty($this->recoveryrecieveakt_date) ? NULL : $this->recoveryrecieveakt_date,
            ])
            ->all();

        if (count($query) > 1)
            $this->addError('recoveryrecieveakt_date', 'Нарушена уникальность записи. Запись уже существует.');
    }

    // Проверяет чтобы при сохранении результата восстановления были заполнены поля recoveryrecieveakt_repaired и recoveryrecieveakt_date
    public function ResultRecoveryrecieveakt($attribute, $params)
    {
        if (empty($this->recoveryrecieveakt_repaired) xor empty($this->recoveryrecieveakt_date))
            $this->addError('recoveryrecieveakt_repaired', 'Для сохранения результата восстановления необходимо заполнить поля "Подлежит восстановлению" и "Дата получения"');
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
        return $this->hasOne(Osmotrakt::className(), ['osmotrakt_id' => 'id_osmotrakt'])->from(['idOsmotrakt' => Osmotrakt::tableName()])->inverseOf('recoveryrecieveakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdRecoverysendakt()
    {
        return $this->hasOne(Recoverysendakt::className(), ['recoverysendakt_id' => 'id_recoverysendakt'])->from(['idRecoverysendakt' => Recoverysendakt::tableName()])->inverseOf('recoveryrecieveakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRraDocfiles()
    {
        return $this->hasMany(RraDocfiles::className(), ['id_recoveryrecieveakt' => 'recoveryrecieveakt_id'])->from(['RraDocfiles' => RraDocfiles::tableName()]);
    }

    public static function getMolsByRecoverysendakt($Recoverysendakt_id)
    {
        if (is_integer($Recoverysendakt_id)) {
            return self::find()
                ->select(['idperson.auth_user_fullname', 'iddolzh.dolzh_name'])
                ->joinWith([
                    'idOsmotrakt.idTrosnov.idMattraffic.idMol.idperson',
                    'idOsmotrakt.idTrosnov.idMattraffic.idMol.iddolzh',
                ])
                ->andWhere(['id_recoverysendakt' => $Recoverysendakt_id])
                ->groupBy(['idMol.id_person', 'idMol.id_dolzh'])
                ->asArray()
                ->all();
        }
    }

    public static function VariablesValues($attribute)
    {
        $values = [
            'recoveryrecieveakt_repaired' => [1 => 'Восстановлению не подлежит', 2 => 'Восстановлено'],
        ];

        return isset($values[$attribute]) ? $values[$attribute] : NULL;
    }

}
                                        