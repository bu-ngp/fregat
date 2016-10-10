<?php
namespace app\models\Fregat;

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
        return [
            [['docFile'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->docFile->saveAs(Yii::$app->basePath . '/docs/' . $this->docFile->baseName . date .'.' . $this->docFile->extension);
            return true;
        } else {
            return false;
        }
    }
}