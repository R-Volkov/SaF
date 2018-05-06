<?php

namespace backend\controllers;

use Yii;
use backend\models\Create;
use backend\models\Update;
use backend\models\Delete;
use backend\models\Search;
use yii\web\UploadedFile;
use yii\helpers\Url;
use backend\traits\articleImagesUploader;
use common\models\Articles;
use backend\models\ArticlesSearch;
 
class MainController extends BackendController
{
 
    use articleImagesUploader;

    public $defaultAction = 'index'; 
     
    public function actionIndex()
    {
        Yii::$app->session->setFlash('default', "Добро пожаловать в админн-панель сайта SaF! Ваша роль - " . Yii::$app->user->identity['role']);
        return $this->render('index');  
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionAll()
    {
        $this->layout = 'template2';
        
        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        if (!Yii::$app->request->isAjax) {
            $model = new Create();
            if ($model->load(Yii::$app->request->post()) && $model->validate() && (Yii::$app->request->post('CreateArticle'))){
                $model->image_file = UploadedFile::getInstance($model, 'image_file');
               $model->saveArticle();
               Yii::$app->session->setFlash('sucsess', "Статья успешно создана и сохранена!");
               return $this->refresh();
            } else {
               return $this->render('create', ['model' => $model]);
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->uploadArticleImage();
        }
    }
    

    public function actionUpdate($id = NULL)
    {
        if (!Yii::$app->request->isAjax) {
            $model = Update::findOne($id);
            if (empty($model)) {
                Yii::$app->session->setFlash('failure', "Статья не найдена!");
                return $this->redirect(['search']);
            };
        
            if ($model->load(Yii::$app->request->post()) && $model->validate() && (Yii::$app->request->post('CreateArticle'))){
                $model->image_file = UploadedFile::getInstance($model, 'image_file');
                $model->saveArticle();
                Yii::$app->session->setFlash('sucsess', "Статья была успешно обновлена!");
                return $this->redirect(Yii::$app->request->referrer);
            } else if ($model->load(Yii::$app->request->post()) && (Yii::$app->request->post('Delete'))) {
                return $this->redirect(['delete', 'id' => $model->id]);
            } else {
                return $this->render('update', ['model' => $model]);
            }
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return $this->uploadArticleImage();
        }
    }

    public function actionDelete($id) 
    {
        $model = Delete::findOne($id);
        if (empty($model)) {
            Yii::$app->session->setFlash('failure', "Статья не найдена!");
            return $this->redirect(Yii::$app->request->referrer);
        }
        $model->delete();
        Yii::$app->session->setFlash('success', "Статья была удалена из базы данных");
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPublish($id)
    {
        $model = Articles::findOne($id);
        $model->date_public = date('Y-m-d H:i:s');
        $model->save();
        Yii::$app->session->setFlash('success', "Статья была успешно опубликована!");
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    
}
