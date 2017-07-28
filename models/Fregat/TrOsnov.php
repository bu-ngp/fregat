<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_osnov".
 *
 * @property string $tr_osnov_id
 * @property string $tr_osnov_kab
 * @property string $id_installakt
 * @property string $id_mattraffic
 *
 * @property Installakt $idInstallakt
 * @property Mattraffic $idMattraffic
 */
class TrOsnov extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_osnov';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tr_osnov_kab', 'id_installakt', 'id_mattraffic'], 'required', 'except' => 'forosmotrakt'],
            [['id_installakt', 'id_mattraffic'], 'integer'],
            [['tr_osnov_kab'], 'string', 'max' => 255],
            [['id_installakt'], 'exist', 'skipOnError' => true, 'targetClass' => Installakt::className(), 'targetAttribute' => ['id_installakt' => 'installakt_id']],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            [['id_mattraffic'], 'CheckBuild'],
            [['tr_osnov_kab'], 'checkKabUnique'],
        ];
    }

    // Проверка на заполненность Здания у МОЛ
    public function CheckBuild($attribute)
    {
        if (empty($this->idMattraffic->idMol->id_build))
            $this->addError($attribute, 'У материально ответственного лица "' . $this->idMattraffic->idMol->idperson->auth_user_fullname . '" не заполнено "Здание", в которое устанавливается материальная ценность');
    }

    // Проверка на уже установленную мат ценность в кабинете
    public function checkKabUnique($attribute)
    {
        if ($this->isNewRecord) {
            $trOsnov = self::find()
                ->select(['tr_osnov_id', 'tr_osnov_kab', 'idMol.id_build', 'idInstallakt.installakt_date'])
                ->joinWith(['idMattraffic.idMol', 'idInstallakt'])
                ->andWhere(['idMattraffic.id_material' => $this->idMattraffic->id_material])
                ->orderBy(['idMattraffic.mattraffic_date' => SORT_DESC, 'idMattraffic.mattraffic_id' => SORT_DESC])
                ->limit(1)
                ->asArray()
                ->one();

            if ($trOsnov && $trOsnov['tr_osnov_kab'] == $this->tr_osnov_kab && $trOsnov['id_build'] == $this->idMattraffic->idMol->id_build) {
                $this->addError($attribute, 'Данная материальная ценность "' . $this->idMattraffic->idMaterial->material_name . '" уже установлена в кабинет "' . $this->tr_osnov_kab . '" в акте установки №' . $trOsnov['tr_osnov_id'] . ' от ' . Yii::$app->formatter->asDate($trOsnov['installakt_date']) . '.');
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_osnov_id' => 'Tr Osnov ID',
            'tr_osnov_kab' => 'Кабинет',
            'id_installakt' => 'Акт установки',
            'id_mattraffic' => 'Инвентарный номер',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstallakt()
    {
        return $this->hasOne(Installakt::className(), ['installakt_id' => 'id_installakt'])->from(['idInstallakt' => Installakt::tableName()])->inverseOf('trOsnovs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->from(['idMattraffic' => Mattraffic::tableName()])->inverseOf('trOsnovs');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOsmotrakts()
    {
        return $this->hasOne(Osmotrakt::className(), ['id_tr_osnov' => 'tr_osnov_id'])->from(['osmotrakts' => Osmotrakt::tableName()]);
    }

    public function selectinputforosmotrakt($params)
    {

        $method = isset($params['init']) ? 'one' : 'all';

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['tr_osnov_id AS id'], ['CONCAT(idMaterial.material_inv, ", каб. ", tr_osnov_kab, ", ", IF(idbuild.build_name IS NULL, "Здание отсутствует", idbuild.build_name), ", ", idMaterial.material_name) AS text']))
            ->joinWith([
                'idMattraffic.idMol.idperson',
                'idMattraffic.idMol.iddolzh',
                'idMattraffic.idMol.idpodraz',
                'idMattraffic.idMol.idbuild',
                'idInstallakt',
            ])
            ->join('LEFT JOIN', 'material idMaterial', 'id_material = idMaterial.material_id')
            ->join('LEFT JOIN', '(select id_material as id_material_m2, id_mol as id_mol_m2, mattraffic_date as mattraffic_date_m2, mattraffic_tip as mattraffic_tip_m2 from mattraffic) m2', 'idMattraffic.id_material = m2.id_material_m2 and idMattraffic.id_mol = m2.id_mol_m2 and idMattraffic.mattraffic_date < m2.mattraffic_date_m2 and m2.mattraffic_tip_m2 in (3)')
            // Последнее перемещение
            ->join('LEFT JOIN', '(select mt.id_material, inst.installakt_date from installakt inst RIGHT JOIN tr_osnov ts ON inst.installakt_id = ts.id_installakt LEFT JOIN mattraffic mt ON ts.id_mattraffic = mt.mattraffic_id) lastinst', 'lastinst.id_material = idMattraffic.id_material and idInstallakt.installakt_date < lastinst.installakt_date')
            ->where(['like', isset($params['init']) ? 'tr_osnov_id' : 'idMaterial.material_inv', $params['q'], isset($params['init']) ? false : null])
            ->andWhere('idMattraffic.mattraffic_number > 0')
            ->andWhere(['in', 'idMattraffic.mattraffic_tip', [3]])
            ->andWhere(isset($params['init']) ? [] : ['m2.mattraffic_date_m2' => NULL])
            ->andWhere(isset($params['init']) ? [] : ['lastinst.installakt_date' => NULL])// Последнее перемещение
            ->orderBy(['idMattraffic.mattraffic_date' => SORT_DESC, 'idMattraffic.mattraffic_id' => SORT_DESC])
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

}
                        