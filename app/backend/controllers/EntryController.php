<?php 
namespace backend\controllers;

use Yii;
use common\models\Login;
use yii\filters\AccessControl;
use backend\controllers\BackendController;

class EntryController extends BackendController {

    public $layout = false;

    /**
    * Auth user
    *
    * @return redirecting on previous page if success
    */
    public function actionLogin() {
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
    * @return redirecting on Дщпшт page if success
    */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

}