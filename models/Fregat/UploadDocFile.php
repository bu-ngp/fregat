<?php
namespace app\models\Fregat;

use app\func\OSHelper;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadDocFile extends Model
{
    /**
     * @var UploadedFile
     */
    public $docFile;

    public function rules()
    {
        Yii::$app->formatter->sizeFormatBase = 1000;
        return [
            [['docFile'], 'file', 'skipOnEmpty' => false, 'extensions' => ['png', 'jpg', 'jpeg', 'tiff', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'txt'], 'maxSize' => 30000000, 'tooBig' => 'Файл не может превышать ' . Yii::$app->formatter->asShortSize(30000000), 'checkExtensionByMimeType' => false],
       // [['docFile'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'docFile' => 'Загрузить файл',
        ];
    }

    public function upload()
    {
        $result = [
            'errors' => [],
            'success' => false,
            'savedfilename' => '',
            'savedhashfilename_utf8' => '',
            'savedhashfilename' => '',
            'fileextension' => '',
        ];

        if ($this->validate()) {
            $filebase = str_replace(" ", "_", $this->docFile->baseName);
            $fileroot = OSHelper::setFileNameByOS($filebase);
            $Unix = date('U');
            $result['savedhashfilename'] = $fileroot . '_' . $Unix . '.' . $this->docFile->extension;
            $result['savedhashfilename_utf8'] = $filebase . '_' . $Unix . '.' . $this->docFile->extension;
            $this->docFile->saveAs(Yii::$app->basePath . '/docs/' . $result['savedhashfilename']);
            $result['success'] = true;
            $result['savedfilename'] = $filebase . '.' . $this->docFile->extension;
            $result['fileextension'] = $this->docFile->extension;
        } else {
            $a='';
            $result['errors'] = $this->getErrors();
        }

        return $result;
    }
}