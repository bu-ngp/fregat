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
    public $tr_osnov_kab;
    public $id_installer;

    public $primaryKey;

    public function rules()
    {
        return [
            [['id_mattraffic', 'tr_osnov_kab', 'mattraffic_number'], 'required'],
            [['id_mattraffic', 'id_installer', 'mattraffic_trosnov_id'], 'integer'],
            [['mattraffic_number'], 'double', 'min' => 0, 'max' => 10000000000],
            [['tr_osnov_kab'], 'string', 'max' => 255],
            [['mattraffic_number'], 'MaxNumberMove'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_mattraffic' => 'Инвентарный номер',
            'mattraffic_number' => 'Количество для перемещения',
            'tr_osnov_kab' => 'Кабинет',
            'id_installer' => 'Составитель акта',
        ];
    }

    public function MaxNumberMove($attribute)
    {
        if (!empty($this->id_mattraffic)) {
            $query = Mattraffic::find()
                ->andWhere(['mattraffic_id' => $this->id_mattraffic])
                ->one();

            if ($query->mattraffic_number == 0 && in_array($query->idMaterial->material_tip, [2, 3]))
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
                        $trOsnov->tr_osnov_kab = $this->tr_osnov_kab;
                        if ($trOsnov->save()) {
                            $this->mattraffic_trosnov_id = $trOsnov->primaryKey;
                            $this->primaryKey = $trOsnov->primaryKey;
                            $transaction->commit();
                            return true;
                        } else {
                            $this->addError('id_mattraffic', $trOsnov->getErrors('id_mattraffic')[0]);
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