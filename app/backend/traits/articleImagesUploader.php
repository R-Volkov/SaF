<?php 
namespace backend\traits;

use Yii;
use yii\helpers\Url;
use yii\web\UploadedFile;
use backend\models\Upload;


trait articleImagesUploader {

	function uploadArticleImage() {
            $dir = Yii::getAlias('@root') . Yii::getAlias('@temp') . '/';
            $answer_link = Yii::getAlias('@temp') . '/';
            $model_upload = new Upload;
            $model_upload->file = UploadedFile::getInstanceByName('file');
            $model_upload->file->name = time() . '_' . Yii::$app->getSecurity()->generateRandomString(8) . '.' . $model_upload->file->extension;
            $model_upload->file->saveAs($dir . $model_upload->file->name);
            $result = ['filelink' => $answer_link . $model_upload->file->name, 'filename' => $model_upload->file->name]; 
            return $result;
        }

}