<?php

namespace backend\controllers;

use Yii;
use common\models\MyUser;
use backend\models\UserSearch;


class UsersController extends BackendController
{
	public $defaultAction = 'users'; 
	public $layout = 'template';

	public function actionUsers() {
        $searchModel = new UserSearch(['user']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);	
	}

	public function actionAdmins() {
        $searchModel = new UserSearch(['admin', 'moderator']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);	
	}

    public function actionRole($id)
    {
        $model = MyUser::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->save()) {
            Yii::$app->session->setFlash('success', "Роль пользователя {$model->username} была успешно изменена!" );
            return $this->redirect(Yii::$app->request->referrer);
        }
        return $this->render('role', ['model' => $model]);
    }


}