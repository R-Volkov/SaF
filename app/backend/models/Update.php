<?php 
namespace backend\models;

use Yii;
use backend\models\CU;
use yii\base\Event;


class Update extends CU {

	//public $previous_title;
	//public $new_title;
	//public $previous_image;

	// public function createImageFolder() {
 //        $this->path_image_folder = Yii::getAlias("@absolute_uploads/$this->previous_title");
 //        if (!file_exists($this->path_image_folder)) {
 //           	mkdir("$this->path_image_folder");
 //           	if (!file_exists($this->path_image_folder . '/comment_images//')) {
 //           		mkdir("$this->path_image_folder" . '/comment_images//');
 //        	}
 //        	if (!file_exists($this->path_image_folder . '/article_images//')) {
 //           		mkdir("$this->path_image_folder" . '/article_images//');
 //        	}
 //        }
 //    }

	// public function updateImageFolder() {
	// 	$old_image_path = Yii::getAlias("@absolute_uploads/$this->previous_title");
	// 	$new_image_path = Yii::getAlias("@absolute_uploads/$this->new_title");
	// 	@rename($old_image_path, $new_image_path);
	// }

	// public function updateMainImage() {
	// 	$new_path_image_folder = Yii::getAlias("@absolute_uploads/$this->new_title");
	// 	if ($this->previous_image != false){
	// 		$small_previous_image = "small_" . $this->previous_image;
	// 		$full_image_path = "$new_path_image_folder" . "/" . "$this->previous_image";
	// 		$full_small_image_path = "$new_path_image_folder" . "/" . "$small_previous_image";
	// 		if (file_exists($full_image_path)) {
	// 			unlink($full_image_path);
	// 		}
	// 		if (file_exists($full_small_image_path)) {
	// 			unlink($full_small_image_path);
	// 		}
	// 	}	
	// }

	// public function afterFind() {
	// 	//$this->previous_title = $this->getAlias($this->title);
	// 	//$this->previous_image = $this->image;
	// 	$this->tags_array = $this->tags;
	// }

	// public function afterSave($insert, $changedAttributes) {
	// 	parent::afterSave($insert, $changedAttributes);
	// 	//$this->new_title = $this->getAlias($this->title);
	// 	if ($this->previous_title != $this->new_title) {
	// 		$this->updateImageFolder();
	// 	}
	// 	if ($this->image_file != false) {
	// 		$this->updateMainImage();
	// 	}
	// }

}