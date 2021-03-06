<?php
/**
 * Created by PhpStorm.
 * User: sysadmin
 * Date: 02.09.2016
 * Time: 14:16
 */

namespace app\models\Fregat;


use Exception;
use Yii;
use yii\base\Model;

class InstallTrOsnov extends Model
{

    public $mattraffic_trosnov_id;
    public $id_mattraffic;
    public $mattraffic_number;
    public $id_cabinet;
    public $id_installer;

    public $primaryKey;

    public function rules()
    {
        return [
            [['id_mattraffic', 'id_cabinet', 'mattraffic_number'], 'required'],
            [['id_mattraffic', 'id_installer', 'mattraffic_trosnov_id', 'id_cabinet'], 'integer'],
            [['mattraffic_number'], 'double', 'min' => 0, 'max' => 10000000000],
            [['mattraffic_number'], 'MaxNumberMove'],
            [['id_mattraffic'], 'OsmotrExists'],
            [['id_mattraffic'], 'isNotSpisan'],
            [['id_mattraffic'], 'canOsmotr'],
        ];
    }

    public function isNotSpisan($attribute)
    {
        if (!empty($this->$attribute) && Mattraffic::findOne($this->$attribute)->idMaterial->material_writeoff) {
            $this->addError($attribute, 'Нельзя составлять акты осмотра для списанных материальных ценностей');
        }
    }

    public function canOsmotr($attribute)
    {
        if (!empty($this->$attribute)) {
            $currentMaterialID = Mattraffic::findOne($this->$attribute)->id_material;

            $osmotrakt = Osmotrakt::find()->joinWith([
                'idTrosnov.idMattraffic',
                'recoveryrecieveakts',
            ])->andWhere([
                'idMattraffic.id_material' => $currentMaterialID,
                'recoveryrecieveakts.recoveryrecieveakt_repaired' => 1,
            ])->one();

            if ($osmotrakt) {
                $this->addError($attribute, 'Данная материальная ценность восстановлению не подлежит согласно составленному акту восстановления №' . $osmotrakt->recoveryrecieveakts[0]->id_recoverysendakt . ' от ' . Yii::$app->formatter->asDate($osmotrakt->recoveryrecieveakts[0]->idRecoverysendakt->recoverysendakt_date));
            } else {
                $osmotrakt = Osmotrakt::find()->joinWith([
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
        if (!empty($this->$attribute)) {
            $currentMaterialID = Mattraffic::findOne($this->$attribute)->id_material;
            $otherOsmotrakt = Osmotrakt::find()->joinWith([
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

    public function attributeLabels()
    {
        return [
            'id_mattraffic' => 'Инвентарный номер',
            'mattraffic_number' => 'Количество для перемещения',
            'id_cabinet' => 'Кабинет',
            'id_installer' => 'Составитель акта',
        ];
    }

    public function MaxNumberMove($attribute)
    {
        if (!empty($this->id_mattraffic)) {
            $query = Mattraffic::find()
                ->andWhere(['mattraffic_id' => $this->id_mattraffic])
                ->one();

            if ($query->mattraffic_number == 0 && in_array($query->idMaterial->material_tip, [Material::MATERIAL, Material::MATERIAL_R, Material::GROUP_UCHET]))
                $query->mattraffic_number++;

            if (!empty($query) && $this->mattraffic_number > $query->mattraffic_number)
                $this->addError($attribute, 'Количество не может превышать ' . $query->mattraffic_number);
        }
    }

    public function getIdMattraffic()
    {
        $a = new TrOsnov;
        return $a->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->from(['idMattraffic' => Mattraffic::tableName()]);
    }

    public function save($IDinstaller)
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $Installakt = new Installakt;
                $Installakt->installakt_date = date('Y-m-d');
                $Installakt->id_installer = $IDinstaller;
                if ($Installakt->save()) {
                    $Mattraffic = new Mattraffic;
                    $Mattraffic_choose = Mattraffic::findOne($this->id_mattraffic);
                    $Mattraffic->attributes = $Mattraffic_choose->attributes;
                    $Mattraffic->mattraffic_date = date('Y-m-d');
                    $Mattraffic->mattraffic_number = empty($this->mattraffic_number) ? 1 : $this->mattraffic_number;
                    $Mattraffic->mattraffic_tip = 3;
                    if ($Mattraffic->save()) {
                        $trOsnov = new TrOsnov;
                        $trOsnov->id_installakt = $Installakt->primaryKey;
                        $trOsnov->id_mattraffic = $Mattraffic->primaryKey;
                        $trOsnov->id_cabinet = $this->id_cabinet;
                        if ($trOsnov->save()) {
                            $this->mattraffic_trosnov_id = $trOsnov->primaryKey;
                            $this->primaryKey = $trOsnov->primaryKey;
                            $transaction->commit();
                            return true;
                        } else {
                            if ($errors = $trOsnov->getErrors('id_mattraffic')) {
                                $this->addError('id_mattraffic', $trOsnov->getErrors('id_mattraffic')[0]);
                            }

                            if ($errors = $trOsnov->getErrors('id_cabinet')) {
                                $this->addError('id_cabinet', $trOsnov->getErrors('id_cabinet')[0]);
                            }

                            $transaction->rollBack();
                            return false;
                        }
                    } else {
                        $this->addError('mattraffic_number', $Mattraffic->getErrors('mattraffic_number')[0]);
                        $transaction->rollBack();
                        return false;
                    }
                } else {
                    $transaction->rollBack();
                    return false;
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new Exception($e->getMessage());
            }
        } else
            return false;
    }

    public function formName()
    {
        return 'InstallTrOsnov';
    }
}