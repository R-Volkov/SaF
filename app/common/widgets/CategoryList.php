<?php 
namespace common\widgets;

use yii\base\Widget;
use common\models\Categories;
use Yii;

class CategoryList extends Widget {

	public $template;
	public $categories;
	public $tree;
	public $final_html = NULL;
	public $base_ul;
	public $field_value;

	public function init() {
		parent::init(); 
		if ($this->template == 'horizontal') {
			$this->base_ul = '<ul class="nav navbar-nav">';
			return $this->template .= '.php';
		}
		if ($this->template == 'vertical') {
			$this->base_ul = '<ul class="vertical">';
			return $this->template .= '.php';
		}
		if ($this->template == 'select') {
			return $this->template .= '.php';
		}
		if ($this->template == 'select2') {
			return $this->tree;
		}
		if ($this->template == 'checkBoxList') {
			$this->base_ul = '<ul class="vertical">';
			return $this->template .= '.php';
		}
		$this->template = 'horizontal.php';
		$this->base_ul = '<ul class="nav navbar-nav">';
	}

	public function run() {
		$cache_name = 'menu-' . $this->template;
		$cache_menu = Yii::$app->cache->getOrSet($cache_name, function(){
			$this->categories = Categories::find()->asArray()->indexBy('id')->all();
			$this->tree = $this->getTree();
			$this->final_html = $this->getMenuHtml($this->tree);
			if ($this->template == 'select.php') {
				return false;
			}
			return $this->final_html;
		}, 60);
		if ($cache_menu == false) {
			$cache_menu = $this->final_html;
		};
		return ($this->base_ul . $cache_menu . '</ul>');
	}

	protected function getTree() {
		$tree = [];
		foreach ($this->categories as $id => &$node) {
			if (!$node['parent']) {
				$tree[$id] = &$node;
			} else {
				$this->categories[$node['parent']]['childs'][$node['id']] = &$node;
			}
		}
		$new_tree = [];
		foreach ($tree as $branch) {
			$new_tree[$branch['priority']] = $branch;
		}
		ksort($new_tree);
		return $new_tree;
	}

	protected function getMenuHtml($tree, $indent = '') {
		$str = '';
		foreach ($tree as $category) {
			$str .= $this->catToTemplate($category, $indent);
		}
		return $str;
	}

	protected function catToTemplate($category, $indent) {
		ob_start();
		include __DIR__. '/category_list/' . $this->template;
		return ob_get_clean();
	}

}