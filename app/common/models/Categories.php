<?php 
namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use common\models\Articles;


class Categories extends ActiveRecord {

	public static function tableName(){
    	return '{{categories}}';
    }

    public function getArticles(){
    	return $this->hasMany(Articles::className(), ['category_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent' => 'id']);
    }

    public function getParent0()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent']);
    }

    public static function categoriesTree()
    {
    	$categories = self::find()->asArray()->indexBy('id')->all();
    	$tree = [];
		foreach ($categories as $id => &$node) {
			if (!$node['parent']) {
				$tree[$id] = &$node;
			} else {
				$categories[$node['parent']]['childs'][$node['id']] = &$node;
			}
		}

		$new_tree = [];
		foreach ($tree as $id => $branch) {
			if (array_key_exists('childs', $branch)) {
				$new_tree[$branch['name']] = [];
				foreach ($branch['childs'] as $id => $child) {
					$new_tree[$branch['name']][$id] = $child['name'];
				}
			} else {
				$new_tree['Без родительской категории'][$branch['id']] = $branch['name'];
			}
		}
		
		return $new_tree;
    }


}