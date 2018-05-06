<?php 
namespace backend\models;

use Yii;
use common\models\Articles;


class Delete extends Articles {

	public function myRmdir($dir) 
	{
        if ($objs = glob($dir."/*")) {
            foreach($objs as $obj) {
            is_dir($obj) ? $this->myRmdir($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    public function afterDelete() 
    {
        if (file_exists($this->absolute_uploads_path)) {
            $this->myRmdir($this->absolute_uploads_path);
        }
    }

        

}