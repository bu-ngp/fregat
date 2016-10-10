<?php

namespace app\models\Fregat;

use Yii;

/**
 * This is the model class for table "docfiles".
 *
 * @property string $docfiles_id
 * @property string $docfiles_name
 * @property string $docfiles_hash
 * @property string $docfiles_ext
 *
 * @property RraDocfiles[] $rraDocfiles
 * @property RramatDocfiles[] $rramatDocfiles
 */
class Docfiles extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'docfiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['docfiles_id', 'docfiles_name', 'docfiles_hash', 'docfiles_ext'], 'required'],
            [['docfiles_id'], 'integer'],
            [['docfiles_name', 'docfiles_hash'], 'string', 'max' => 255],
            [['docfiles_ext'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'docfiles_id' => 'Docfiles ID',
            'docfiles_name' => 'Имя файла',
            'docfiles_hash' => 'Имя файла в файловой системе',
            'docfiles_ext' => 'Расширение файла',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRraDocfiles()
    {
        return $this->hasMany(RraDocfiles::className(), ['id_docfiles' => 'docfiles_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRramatDocfiles()
    {
        return $this->hasMany(RramatDocfiles::className(), ['id_docfiles' => 'docfiles_id']);
    }
}
