<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "tr_mat".
 *
 * @property string $tr_mat_id
 * @property string $id_installakt
 * @property string $id_mattraffic
 * @property string $id_parent
 *
 * @property Installakt $idInstallakt
 * @property Material $idParent
 * @property Mattraffic $idMattraffic
 */
class TrMat extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tr_mat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_installakt', 'id_mattraffic', 'id_parent'], 'required'],
            [['id_installakt', 'id_mattraffic', 'id_parent'], 'integer'],
            [['id_installakt'], 'exist', 'skipOnError' => true, 'targetClass' => Installakt::className(), 'targetAttribute' => ['id_installakt' => 'installakt_id']],
            [['id_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_parent' => 'mattraffic_id']],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
            //   [['id_parent'], 'IsMaterialInstalled'],
        ];
    }

    // Проверка, что материальная ценность имеет акт установки
    public function IsMaterialInstalled($attribute)
    {
        $query = TrOsnov::find()
            ->joinWith(['idMattraffic'])
            ->andWhere(['idMattraffic.id_material' => $this->id_parent])
            ->one();

        if (empty($query))
            $this->addError($attribute, 'Материальная ценность не установлена в кабинет. Необходимо добавить ее в акт перемещения материальной ценности в таблицу перемещения');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_mat_id' => 'Tr Mat ID',
            'id_installakt' => 'Акт установки',
            'id_mattraffic' => 'Перемещаемая материальная ценность',
            'id_parent' => 'Комплектуемая материальная ценность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstallakt()
    {
        return $this->hasOne(Installakt::className(), ['installakt_id' => 'id_installakt'])->from(['idInstallakt' => Installakt::tableName()])->inverseOf('trMats');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdParent()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_parent'])->from(['idParent' => Mattraffic::tableName()])->inverseOf('trMats');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->from(['idMattraffic' => Mattraffic::tableName()])->inverseOf('trMats');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrMatOsmotrs()
    {
        return $this->hasMany(TrMatOsmotr::className(), ['id_tr_mat' => 'tr_mat_id'])->from(['trMatOsmotrs' => TrMatOsmotr::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTrRmMats()
    {
        return $this->hasMany(TrRmMat::className(), ['id_tr_mat' => 'tr_mat_id'])->from(['trRmMats' => TrRmMat::tableName()]);
    }

    public function selectinputfortrmatosmotr($params)
    {

        $method = isset($params['init']) ? 'one' : 'all';

        $where = isset($params['init']) ? ['like', 'tr_mat_id', $params['q'], false] : ['or', ['like', 'material_inv', $params['q']], ['like', 'material_name', $params['q']]];

        $query = self::find()
            ->select(array_merge(isset($params['init']) ? [] : ['tr_mat_id AS id'], ['CONCAT_WS(", ", idMaterial.material_name, idperson.auth_user_fullname, iddolzh.dolzh_name, idMattraffic.mattraffic_number) AS text']))
            ->joinWith([
                'idMattraffic.idMaterial',
                'idMattraffic.idMol.idperson',
                'idMattraffic.idMol.iddolzh',
                'trRmMats',
            ])
            ->where($where)
            ->andWhere(['trRmMats.id_tr_mat' => NULL])
            //   ->andWhere('tr_mat_id not in (select tmo.id_tr_mat from tr_mat_osmotr tmo where tmo.id_osmotraktmat = ' . $params['idosmotraktmat'] . ')')
            ->limit(20)
            ->asArray()
            ->$method();

        return $query;
    }

    public static function getCountMaterials($id_mol, $period_beg, $period_end, $spisinclude = false)
    {
        $query = self::find()
            ->joinWith(['idInstallakt', 'idMattraffic.idMaterial', 'idMattraffic.idMol'])
            ->andWhere(['in', 'idMaterial.material_tip', [Material::MATERIAL, Material::MATERIAL_R]])
            ->andWhere(['idMol.id_person' => Employee::findOne($id_mol)->id_person])
            ->andWhere(['between', 'idInstallakt.installakt_date', $period_beg, $period_end]);

        if (!$spisinclude)
            $query->andWhere(['idMaterial.material_writeoff' => 0]);

        $count = $query->count();

        return $query === false ? 0 : $count;
    }

    public static function getMaterialsSpismat($id_mol, $period_beg, $period_end, $spisinclude = false)
    {
        $query = self::find()
            ->joinWith(['idInstallakt', 'idMattraffic.idMaterial', 'idMattraffic.idMol'])
            ->andWhere(['in', 'idMaterial.material_tip', [Material::MATERIAL, Material::MATERIAL_R]])
            ->andWhere(['idMol.id_person' => Employee::findOne($id_mol)->id_person])
            ->andWhere(['between', 'idInstallakt.installakt_date', $period_beg, $period_end]);

        if (!$spisinclude)
            $query->andWhere(['idMaterial.material_writeoff' => 0]);

        $rows = $query->asArray()->all();

        return $rows;
    }

}
                        