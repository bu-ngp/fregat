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
            [['id_parent'], 'exist', 'skipOnError' => true, 'targetClass' => Material::className(), 'targetAttribute' => ['id_parent' => 'material_id']],
            [['id_mattraffic'], 'exist', 'skipOnError' => true, 'targetClass' => Mattraffic::className(), 'targetAttribute' => ['id_mattraffic' => 'mattraffic_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tr_mat_id' => 'Tr Mat ID',
            'id_installakt' => 'Акт установки',
            'id_mattraffic' => 'Id Mattraffic',
            'id_parent' => 'Родительская материальная ценность',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdInstallakt()
    {
        return $this->hasOne(Installakt::className(), ['installakt_id' => 'id_installakt'])->inverseOf('trMats');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdParent()
    {
        return $this->hasOne(Material::className(), ['material_id' => 'id_parent'])->inverseOf('trMats');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMattraffic()
    {
        return $this->hasOne(Mattraffic::className(), ['mattraffic_id' => 'id_mattraffic'])->inverseOf('trMats');
    }
}
