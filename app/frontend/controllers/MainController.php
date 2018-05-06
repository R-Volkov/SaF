<?php

 namespace frontend\controllers;

 use Yii;
 use yii\web\Controller;
 use yii\web\BadRequestHttpException;
 use frontend\models\Signup;
 use common\models\Login;
 use common\models\Articles;
 use common\models\Comments;
 use yii\widgets\Pjax;
 use yii\web\UploadedFile;
 use common\models\Tag;
 use yii\helpers\ArrayHelper;
 use yii\data\Pagination;
 use yii\db\Query;
 
 class MainController extends Controller
 {
   
    public $defaultAction = 'index';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
    * Function for build base query which applying for most of actions
    *
    * @return query object
    */
    protected function baseSearch()
    {
        $sub_query = (new Query())->select('COUNT(*)')
            ->from('comments')
            ->where('comments.article_id = {{articles}}.id');

        $model = Articles::find()
            ->select(['articles.*', 'categories.name AS category_name', 'comment_count' => $sub_query, 'users.username AS author_name'])
            ->joinWith(['category', 'tags', 'user', 'comment'])
            ->groupBy('articles.id')
            ->andWhere(['<=', 'date_public', date('o-m-d H:i:s')])
            ->andWhere(['inwork' => 0])
            ->orderBy(['date_public' => SORT_DESC]);
        return $model;
    }

    /**
    * Function, which adding pagination to base query
    *
    * @return standard pagination elements - $model_per_page and $pages
    */
    protected function paginator($model, $page_size)
    {
        $model_clone = clone $model;
        $pages = new Pagination([
            'totalCount' => $model_clone->count(), 
            'pageSize' => $page_size, 
            'forcePageParam' => false, 
            'pageSizeParam' => false,
            ]);
        $model_per_page = $model->offset($pages->offset)
            ->limit($pages->limit);
        return [$model_per_page, $pages];
    }

    /**
    * Function performs render 'search-result' view with params depending from action which call this method
    *
    * @param object Query $model result of execution baseSearch method
    * @param string $search_header contains string for header of 'search-result' view
    *
    * @return render result
    */
    protected function toRender($model, $search_header = NULL)
    {
        list($model_per_page, $pages) = $this->paginator($model, 15);
        $model = $model_per_page->asArray()->all();

        return $this->render('search-result', ['model' => $model, 'pages' => $pages, 'search_header' => $search_header]);
    } 

    /**
    * This action performs search all articles by specified category
    *
    * @param string $name contains category name
    *
    * @return result of execution 'toRender' method 
    */
    public function actionCategory($name)
    {
        $model = $this->baseSearch()
            ->andHaving(['category_name' => $name]);

        $search_header = 'Результаты поиска по категории: <span>' . $name . '</span>';

        return $this->toRender($model, $search_header);
    }

    /**
    * This action performs search all articles by specified author name
    *
    * @param string $name contains author name
    *
    * @return result of execution 'toRender' method 
    */
    public function actionAuthor($name)
    {
        $model = $this->baseSearch()
            ->andHaving(['author_name' => $name]);

        $search_header = 'Результаты поиска по автору: <span>' . $name . '</span>';

        return $this->toRender($model, $search_header);
    }

    /**
    * This action performs search all articles by specified word
    *
    * @param string $search contains searching word
    *
    * @return result of execution 'toRender' method 
    */
    public function actionSearch($search)
    {
        if (!$search) {
           return $this->redirect(Yii::$app->request->referrer);
        }

        $tags_name = ArrayHelper::getColumn(
                Tag::find()->select(['id'])->where(['like', 'name', $search])->asArray()->all(), 
                'id'
            );
        $model = $this->baseSearch()
            ->where(['like', 'title', $search])
            ->orWhere("MATCH(description) AGAINST('{$search}')")
            ->orWhere(['IN', '{{articles_tags}}.tag_id', $tags_name]);

        $search_header = 'Результаты поиска по запросу: <span>' . $search . '</span>';

        return $this->toRender($model, $search_header);
    }

    /**
    * This action performs search all articles by specified tag
    *
    * @param string $name contains tag name
    *
    * @return result of execution 'toRender' method 
    */
    public function actionTag($name)
    {
        $tags_name = Tag::find()->select(['id'])->where(['name' => $name])->asArray()->one();
        $model = $this->baseSearch()
            ->where(['{{articles_tags}}.tag_id' => $tags_name]);

        $search_header = 'Результаты поиска по тегу: <span>' . $name . '</span>';

        return $this->toRender($model, $search_header);
    }

    /**
    * This action provides data for main page and renders corresponding view
    *
    * @return result of rendering 'index' view
    */
    public function actionIndex()
    {
        $cache = Yii::$app->cache;
        
        $cached_main = $cache->getOrSet('main_articles', function () {
            $main = Articles::find()
                ->where(['important' => 1])
                ->andWhere(['inwork' => 0])
                ->andWhere(['<=', 'date_public', date('o-m-d H:i:s')])
                ->orderBy(['date_public' => SORT_DESC])
                ->limit(4)
                ->asArray()
                ->all();
            return $main;
        });

        $cached_last = $cache->getOrSet('last_articles', function () {
            $last = $this->baseSearch()
                ->limit(15)
                ->asArray()
                ->all();
            return $last;
        });

        return $this->render('index', ['main' => $cached_main, 'last' => $cached_last]);
    }

    /**
    * This action provides data for single page and renders corresponding view
    *
    * @param string $id contains article id
    *
    * @return result of rendering 'single' view 
    */
    public function actionSingle($id)
    {
        $model = Articles::find()->where(['id' => $id])->with(['tags', 'user', 'category'])->one();
        $newComment = new Comments();
        if ($newComment->load(Yii::$app->request->post()) && $newComment->validate() && (Yii::$app->request->post('addComment'))) {
            $newComment->ifiles = UploadedFile::getInstances($newComment, 'ifiles');
            $newComment->save();
            unset($newComment);
            $newComment = new Comments();
            if (!Yii::$app->request->isAjax) {
                return $this->refresh();
            }
            return $this->render('single', ['model' => $model, 'newComment' => $newComment]);
        }
        return $this->render('single', ['model' => $model, 'newComment' => $newComment]);
    }

    /**
    * This action provides all articles sorted by date
    *
    * @return result of execution 'toRender' method 
    */
    public function actionMore()
    {
        $model = $this->baseSearch();

        return $this->toRender($model);
    }

    public function actionNewComments($count)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_HTML;

            $new_count = Comments::find()->where(['>', 'id', 0])->count();
            if ($new_count == $count) {
                return '';
            }
            $new_comments = Comments::find()
                ->select(['comments.*', 'users.username AS username', 'articles.title AS article_title', 'users.userpic AS userpic'])
                ->where(['deleted' => 0])
                ->joinWith(['user', 'article'])
                ->orderBy(['created_at' => SORT_DESC])
                ->asArray()
                ->limit($new_count-$count)
                ->all();

            return $this->renderAjax('comments', ['comments' => $new_comments, 'count' => $new_count]);
        }
    }

    
 }