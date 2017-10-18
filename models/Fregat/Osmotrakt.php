<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "osmotrakt".
 *
 * @property string $osmotrakt_id
 * @property string $osmotrakt_comment
 * @property integer $id_reason
 * @property integer $id_user
 * @property integer $id_master
 * @property string $id_mattraffic
 *
 * @property Employee $idUser
 * @property Employee $idMaster
 * @property Mattraffic $idMattraffic
 * @property Reason $idReason
 * @property Recoveryrecieveakt[] $recoveryrecieveakts
 */
class Osmotrakt extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'osmotrakt';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_reason', 'id_user', 'id_master', 'id_tr_osnov'], 'integer'],
            [['id_tr_osnov'], 'required', 'except' => 'forosmotrakt'],
            [['id_user', 'id_master', 'osmotrakt_date'], 'required'],
            [['osmotrakt_comment'], 'string', 'max' => 400],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_user' => 'employee_id']],
            [['id_master'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['id_master' => 'employee_id']],
            [['id_tr_osnov'], 'exist', 'skipOnError' => true, 'targetClass' => TrOsnov::className(), 'targetAttribute' => ['id_tr_osnov' => 'tr_osnov_id']],
            [['id_reason'], 'exist', 'skipOnError' => true, 'targetClass' => Reason::className(), 'targetAttribute' => ['id_reason' => 'reason_id']],
            [['osmotrakt_date'], 'date', 'format' => 'yyyy-MM-dd'],
            [['osmotrakt_date'], 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '<=', 'message' => 'Значение {attribute} должно быть меньше или равно значения «' . Yii::$app->formatter->asDate(date('Y-m-d')) . '».'],
            [['id_reason', 'osmotrakt_comment'], 'required', 'when' => function ($model) {
                return empty($model->id_reason) && empty($model->osmotrakt_comment);
            }, 'message' => 'Необходимо заполнить одно из полей.', 'enableClientValidation' => false],
            [['id_tr_osnov'], 'OsmotrExists'],
            [['id_tr_osnov'], 'isNotSpisan'],
            [['id_tr_osnov'], 'canOsmotr'],
        ];
    }

    public function isNotSpisan($attribute)
    {
        if (!empty($this->id_tr_osnov) && self::find()->joinWith(['idTrosnov.idMattraffic.idMaterial'])->andWhere([
                'id_tr_osnov' => $this->id_tr_osnov,
                'idMaterial.material_writeoff' => 1,
            ])->one()
        ) {
            $this->addError($attribute, 'Нельзя составлять акты осмотра для списанных материальных ценностей');
        }
    }

    public function canOsmotr($attribute)
    {
        if (!empty($this->id_tr_osnov)) {
            $currentMaterialID = TrOsnov::findOne($this->id_tr_osnov)->idMattraffic->id_material;

            $osmotrakt = self::find()->joinWith([
                'idTrosnov.idMattraffic',
                'recoveryrecieveakts',
            ])->andWhere([
                'idMattraffic.id_material' => $currentMaterialID,
                'recoveryrecieveakts.recoveryrecieveakt_repaired' => 1,
            ])->one();

            if ($osmotrakt) {
                $this->addError($attribute, 'Данная материальная ценность восстановлению не подлежит согласно составленному акту восстановления №' . $osmotrakt->recoveryrecieveakts[0]->id_recoverysendakt . ' от ' . Yii::$app->formatter->asDate($osmotrakt->recoveryrecieveakts[0]->idRecoverysendakt->recoverysendakt_date));
            } else {
                $osmotrakt = self::find()->joinWith([
                    'idTrosnov.idMattraffic',
                    'recoveryrecieveakts',
                ])->andWhere([
                    'idMattraffic.id_material' => $currentMaterialID,
                    'recoveryrecieveakts.recoveryrecieveakt_repaired' => null,
                    'recoveryrecieveakts.recoveryrecieveakt_date' => null,
                ])->one();

                if ($osmotrakt) {
                    $this->addError($attribute, 'Данная материальная ценность находится на восстановлении. Акт восстановления №' . $osmotrakt->recoveryrecieveakts[0]->id_recoverysendakt . ' от ' . Yii::$app->formatter->asDate($osmotrakt->recoveryrecieveakts[0]->idRecoverysendakt->recoverysendakt_date));
                }
            }
        }
    }

    public function OsmotrExists($attribute)
    {
        if (!empty($this->id_tr_osnov)) {
            $currentMaterialID = TrOsnov::findOne($this->id_tr_osnov)->idMattraffic->id_material;
            $otherOsmotrakt = self::find()->joinWith([
                'idTrosnov.idMattraffic',
                'recoveryrecieveakts',
            ])->andWhere([
                'idMattraffic.id_material' => $currentMaterialID,
                'recoveryrecieveakts.recoveryrecieveakt_id' => null,
            ])->one();

            if ($otherOsmotrakt) {
                $this->addError($attribute, 'На текащую материальную ценность уже существует акт осмотра без акта восставноления. Акт осмотра №' . $otherOsmotrakt->osmotrakt_id . ' от ' . Yii::$app->formatter->asDate($otherOsmotrakt->osmotrakt_date));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'osmotrakt_id' => 'Номер акта осмотра',
            'osmotrakt_comment' => 'Описание причины неисправности',
            'id_reason' => 'Причина неисправности',
            'id_user' => 'Пользователь материальной ценностью',
            'id_master' => 'Составитель акта',
            'id_tr_osnov' => 'Материальная ценность',
            'osmotrakt_date' => 'Дата осмотра материальной ценности',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_user'])->from(['idUser' => Employee::tableName()])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMaster()
    {
        return $this->hasOne(Employee::className(), ['employee_id' => 'id_master'])->from(['idMaster' => Employee::tableName()])->inverseOf('osmotrakts0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdTrosnov()
    {
        return $this->hasOne(TrOsnov::className(), ['tr_osnov_id' => 'id_tr_osnov'])->from(['idTrosnov' => TrOsnov::tableName()])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdReason()
    {
        return $this->hasOne(Reason::className(), ['reason_id' => 'id_reason'])->from(['idReason' => Reason::tableName()])->inverseOf('osmotrakts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRecoveryrecieveakts()
    {
        return $this->hasMany(Recoveryrecieveakt::className(), ['id_osmotrakt' => 'osmotrakt_id'])->from(['recoveryrecieveakts' => Recoveryrecieveakt::tableName()])->inverseOf('idOsmotrakt');
    }

    public function selectinputforrecoverysendakt($params)
    {

        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['osmotrakt_id AS id'], ['CONCAT_WS(", ", CONCAT("Акт №", osmotrakt_id), idMaterial.material_inv, idMaterial.material_name, idbuild.build_name, idTrosnov.tr_osnov_kab) AS text']))
            ->joinWith([
                'idTrosnov.idMattraffic.idMol.idperson',
                'idTrosnov.idMattraffic.idMol.iddolzh',
                'idTrosnov.idMattraffic.idMol.idpodraz',
                'idTrosnov.idMattraffic.idMol.idbuild',
                'idTrosnov.idMattraffic.idMaterial',
                'recoveryrecieveakts',
            ])
            ->join('LEFT JOIN', '(select mt.id_material, IF (rra.recoveryrecieveakt_date IS NULL, \'9999-12-31\', rra.recoveryrecieveakt_date) AS recoveryrecieveakt_date from recoveryrecieveakt rra LEFT JOIN osmotrakt oa ON oa.osmotrakt_id=rra.id_osmotrakt LEFT JOIN tr_osnov ts ON oa.id_tr_osnov = ts.tr_osnov_id LEFT JOIN mattraffic mt ON ts.id_mattraffic = mt.mattraffic_id) lastrra', 'lastrra.id_material = idMattraffic.id_material and recoveryrecieveakts.recoveryrecieveakt_date < lastrra.recoveryrecieveakt_date')
            ->where(['like', isset($params['init']) ? 'mattraffic_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            //->andWhere('(lastrra.recoveryrecieveakt_date IS NULL and recoveryrecieveakts.recoveryrecieveakt_repaired = 2 or recoveryrecieveakts.recoveryrecieveakt_id IS NULL)')
            ->andWhere('(lastrra.recoveryrecieveakt_date IS NULL and recoveryrecieveakts.recoveryrecieveakt_repaired IS NULL and recoveryrecieveakts.recoveryrecieveakt_id IS NULL)')
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

}
                                