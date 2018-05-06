<?php 
namespace backend\models;

use yii\base\Model;

class Upload extends Model {

	public $all_images = []; 
	public $file;

	public function rules() {
		return [
			[['file'], 'file', 'extensions' => 'png, jpg, jpeg'],
		];
	}	


}