<?php
namespace backend\models;

use yii\base\Model;
use Yii;

class Search extends Model {

	private $raw_category_query = "SELECT * FROM articles WHERE (category_id IS NULL";
	private $raw_like_query = " AND (id LIKE 'abc'";
    private $raw_tags_query = " AND (JSON_CONTAINS (tags, '[\"------\"]')";
    
    public $category_all = NULL;

    public $category_id;

	public $text = NULL;
    public $title = NULL;
    public $description = NULL;
    public $body = NULL;
    public $tags = NULL;
    public $like_all = NULL;

    public function rules() {
    	return [
    		[['category_all', 'title', 'description', 'body', 'like_all'], 'boolean'],
    		[['text', 'tags'], 'trim'],
            [['category_id'], 'safe'],
    	];
    }

    private function prepereCategoryQuery() {
        //SELECT * FROM articles WHERE (ctegory IS NULL OR category = 'somecategory_1' ... OR category = 'somecategory_N')
        if ($this->category_all == false) {
            $condition_category_query = '';
            if ($this->category_id != false) {
                foreach ($this->category_id as $category) {
                $condition_category_query .= " OR category_id = {$category}";
                }
            }
            $condition_category_query .= ")";
        } else {
          //SELECT * FROM articles WHERE (ctegory_id IS NULL OR category_id IS NOT NULL)
          $condition_category_query = " OR category_id IS NOT NULL)";
        }
        $category_query = $this->raw_category_query . $condition_category_query;
        return $category_query;
    }

    private function prepareLikeQuery() {
        //SELECT * FROM articles WHERE (!PREVIOUS CONDITION!) AND (id LIKE 'abc' OR somefield_1 LIKE somevariable_1 OR somefield_N LIKE somevariable_N)
        if (($this->text != false) && ($this->like_all == false)){
            $this->description = $this->description ? " OR description LIKE '%{$this->text}%'" : " ";
            $this->title = $this->title ? " OR title LIKE '%{$this->text}%'" : " ";
            $this->body = $this->body ? " OR body LIKE '%{$this->text}%'" : " ";
            $condition_like_query = $this->title . $this->description . $this->body . ")";
            return $this->raw_like_query . $condition_like_query;
        }
        if (($this->text != false) && ($this->like_all != false)) {
            $condition_like_query = " OR description LIKE '%{$this->text}%' OR title LIKE '%{$this->text}%' 
                                    OR body LIKE '%{$this->text}%')";
            return $this->raw_like_query . $condition_like_query;
        }
        //SELECT * FROM articles WHERE (!PREVIOUS CONDITION!)
        if ($this->text == false){
            return " ";
        }
    }

    private function prepareTagsQuery() {
        //SELECT * FROM articles WHERE (!PREVIOUS CONDITION!) AND (JSON_CONTAINS (tags, '[\"------\"]') OR JSON_CONTAINS (tags, 'sometag_1') OR JSON_CONTAINS (tags, 'sometag_N'));
        if ($this->tags == false) {
            return " ";
        }
        $pattern = '/( |\r|\n|\t)+/m';
        $replacement = " ";
        $raw_tags = preg_replace($pattern, $replacement, $this->tags);
        $tags_array = explode(" ", $raw_tags);
        $tags_array = array_unique($tags_array);
        $condition_tags_query = '';
        foreach ($tags_array as $tag) {
           $condition_tags_query .= " OR JSON_CONTAINS (tags, '[\"{$tag}\"]')"; 
        }
        $condition_tags_query .= ')';
        $tags_query = $this->raw_tags_query . $condition_tags_query;
        return $tags_query;
    }

    private function prepareQuery() {
        $final_query = $this->prepereCategoryQuery() . $this->prepareLikeQuery() . $this->prepareTagsQuery();
    	return $final_query;
    }

    public function getArticles() {
    	$query = $this->prepareQuery();
    	$model = Yii::$app->db->createCommand($query)->queryAll();
        return $model;
    }

   
}