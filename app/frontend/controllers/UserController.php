<?php

 namespace frontend\controllers;

 use Yii;
 use yii\web\Controller;
 use yii\web\BadRequestHttpException;
 use frontend\models\Signup;
 use common\models\Login;
 use yii\web\UploadedFile;
 use common\models\MyUser;
 use yii\web\ForbiddenHttpException;
 
 class UserController extends Controller
 {

    /**
    * Register new user
    *
    * @return redirecting on main page if success
    */
 	public function actionSignup()
    {
    	$model = new Signup();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signup', ['model' => $model]);
    }

    /**
    * Auth user
    *
    * @return redirecting on previous page if success
    */
    public function actionLogin()
    {
    	if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new Login();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
    * Logout
    *
    * @return redirecting on main page if success
    */
    public function actionLogout()
    {
    	Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
    * Provides access to information and settings for user which has permission for it  
    *
    * @return page with user profile if success
    */
    public function actionProfile($id)
    {
    	if ($id != Yii::$app->user->identity['id']) {
    		throw new ForbiddenHttpException('Вы не являетесь владельцем этого профиля!');
    	}
		if (($model = MyUser::findOne($id)) == false) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->raw_userpic = UploadedFile::getInstance($model, 'raw_userpic');
        	if ($model->validate() && $model->upload() && $model->save()) {
        		Yii::$app->session->setFlash('success', "Ваш профиль обновлен!");
        		return $this->refresh();
        	} else {
        		Yii::$app->session->setFlash('failure', "Ошибка обновления профиля!");
        	}
        }
    	return $this->render('profile', ['model' => $model]);
    }

 }