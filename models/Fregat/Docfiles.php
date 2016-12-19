<?php

namespace app\models\Fregat;

use app\func\Proc;
use kartik\icons\Icon;
use Yii;
use yii\bootstrap\Html;

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
 * @property MaterialDocfiles[] $materialDocfiles
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
            [['docfiles_name', 'docfiles_hash', 'docfiles_ext'], 'required'],
            [['docfiles_id'], 'integer'],
            [['docfiles_name', 'docfiles_hash'], 'string', 'max' => 255],
            //  [['docfiles_name'], 'unique', 'message' => '{attribute} = {value} уже существует'],
            [['docfiles_ext'], 'string', 'max' => 10],
            [['docfiles_ext'], 'filter', 'filter' => function ($value) {
                return mb_strtoupper($value, 'UTF-8');
            }],
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
            'docfiles_ext' => 'Тип',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRraDocfiles()
    {
        return $this->hasMany(RraDocfiles::className(), ['id_docfiles' => 'docfiles_id'])->from(['rraDocfiles' => RraDocfiles::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRramatDocfiles()
    {
        return $this->hasMany(RramatDocfiles::className(), ['id_docfiles' => 'docfiles_id'])->from(['rramatDocfiles' => RramatDocfiles::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialDocfiles()
    {
        return $this->hasMany(MaterialDocfiles::className(), ['id_docfiles' => 'docfiles_id'])->from(['materialDocfiles' => MaterialDocfiles::tableName()]);;
    }

    public function getdocfiles_name_html()
    {
        return Proc::file_exists_utf8(Yii::$app->basePath . '/docs/' . $this->docfiles_hash) ? Html::a(Html::encode($this->docfiles_name), ['Fregat/docfiles/download-file', 'id' => $this->docfiles_id], ['data-pjax' => '0']) : '<span style="text-decoration: line-through">' . Html::encode($this->docfiles_name) . '</span>';
    }

    public function getdocfiles_iconshow()
    {
        $excel = '<span style="font-size: 19px; color: green;">' . Icon::show('file-excel-o') . '</span>';
        $word = '<span style="font-size: 19px; color: blue;">' . Icon::show('file-word-o') . '</span>';
        $pdf = '<span style="font-size: 19px; color: red;">' . Icon::show('file-pdf-o') . '</span>';
        $image = '<span style="font-size: 19px; color: orange;">' . Icon::show('file-image-o') . '</span>';
        $text = '<span style="font-size: 19px; color: black;">' . Icon::show('file-text-o') . '</span>';

        if (in_array($this->docfiles_ext, ['XLS', 'XLSX']))
            return $excel;
        elseif (in_array($this->docfiles_ext, ['DOC', 'DOCX']))
            return $word;
        elseif (in_array($this->docfiles_ext, ['PDF']))
            return $pdf;
        elseif (in_array($this->docfiles_ext, ['PNG', 'JPG', 'JPEG', 'TIFF']))
            return $image;
        elseif (in_array($this->docfiles_ext, ['TXT']))
            return $text;

    }
}
