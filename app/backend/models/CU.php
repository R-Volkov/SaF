<?php 
namespace backend\models;

use Yii;
use common\models\Articles;
use yii\base\Event;
use yii\helpers\Url;
use common\MyHelpers\ImageBuilder;


class CU extends Articles {

    
    public function imageBuild(){
        if ($this->image_file != false){ 
            $image_base_name = $this->image_file->getBaseName();
            $image_extension = $this->image_file->getExtension();
            $image_name = time() . '_' . Yii::$app->getSecurity()->generateRandomString(8) . $image_base_name . "." . $image_extension; 
            $image_small_name = "small_" . $image_name;
            
            $this->image_file->saveAs(Yii::getAlias("@absolute_uploads/") . $image_name);
            ImageBuilder::createMiniature($image_name, $image_small_name, $image_extension, Yii::getAlias("@absolute_uploads/"), 300); 
            
            return ($image_name);
        }  elseif (($this->image_file == false) && ($this->image != false)) {
            return $this->image;
        }  else {
            return NULL;
        }
    }

    protected function loadArticleImages($text) {
        $this->absolute_uploads_path = Yii::getAlias("@absolute_uploads/");
        $pattern_image_name = '/(?<=\/web\/temp\/)[\w\_-]+.(jpg|jpeg|png|gif)(?=")/im';
        $image_name_matches = [];
        $all_new_images_in_text = [];
        if (preg_match_all($pattern_image_name, $text, $image_name_matches) == true) {
            foreach ($image_name_matches[0] as $image_name) {
                $all_new_images_in_text[] = $image_name;
                if (!file_exists($this->absolute_uploads_path . $image_name)) {
                    if (file_exists(Yii::getAlias("@absolute_temp") . '/' . $image_name)) {
                        copy(Yii::getAlias("@absolute_temp") . '/' . $image_name, $this->absolute_uploads_path . $image_name);
                        unlink(Yii::getAlias("@absolute_temp") . '/' . $image_name);  
                    }  
                }
                if (file_exists(Yii::getAlias("@absolute_temp") . '/' . $image_name)) {
                    unlink(Yii::getAlias("@absolute_temp") . '/' . $image_name); 
                }
            }
        }
        // //Должно удалять все картинки в папке хрпнения, кроме тех, что есть в массиве $all_images_in_text и тексте статьи с адресом во фронтенде.
        // //Для этого считать все картинки в папке хранения в массив и найти расхождение с полученным массивом
        // //Результат удалить
        // $images_in_folder = scandir($this->path_image_folder . '/article_images/');
        // $directory_marker = ['.', '..'];
        // $all_images_in_folder = array_diff($images_in_folder, $directory_marker);
        // $all_old_images_in_text = [];
        // //preg_match_all('/(?<=\/article_images\/)\w+.(jpg|jpeg|png|gif)(?=")/im', $text, $all_old_images_in_text);
        // preg_match_all('/(?<=\/article_images\/\/)[\w\_-]+.(jpg|jpeg|png|gif)/im', $text, $all_old_images_in_text);
        // $all_images_in_text = array_merge($all_new_images_in_text, $all_old_images_in_text[0]);
        // $useless_images = array_diff($all_images_in_folder, $all_images_in_text);
        // foreach ($useless_images as $useless_image) {
        //     unlink($this->path_image_folder . '/article_images/' . $useless_image);
        // }
    }

    protected function correctImagesPath($text) {
        $new_text = str_replace(Yii::getAlias('@temp'), Yii::getAlias('@uploads'), $text);
        return $new_text;
    }
   
    public function saveArticle(){
        $this->image = $this->imageBuild();
        $this->loadArticleImages($this->body);
        $this->body = $this->correctImagesPath($this->body);
        $this->user_id = Yii::$app->user->id;
        $this->save(false); 
        Yii::$app->cache->delete('main_articles');
        Yii::$app->cache->delete('last_articles');
    }
 
}	
