<?php 
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class BackendController extends Controller {

    public $role;

	public function behaviors() {

        $this->role = Yii::$app->user->identity['role'];

        return [
            'access' => [
                'class' => AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    if (!Yii::$app->user->identity) {
                    	Yii::$app->user->loginRequired();
                    } elseif (Yii::$app->user->identity['unlim_ban'] == true || strtotime(Yii::$app->user->identity['ban_until']) > time()) {
                        Yii::$app->user->logout();
                        Yii::$app->session->setFlash('failure', 'Вы забанены. Досвидания.');
                        Yii::$app->user->loginRequired();
                    } elseif ($this->role == 'user') {
                    	Yii::$app->user->logout();
                    	Yii::$app->session->setFlash('failure', 'Недостаточно полномочий. Доступ в систему запрещен.');
						Yii::$app->user->loginRequired();
                    } else {
                    	Yii::$app->session->setFlash('failure', 'Недостаточно прав доступа!');
                    	return $this->goHome();
                    };  
                },
                'rules' => [
                    [
                        'allow' => false,
                        'matchCallback' => function ($rule, $action) {
                            return (Yii::$app->user->identity['unlim_ban'] == true || strtotime(Yii::$app->user->identity['ban_until']) > time());
                        }
                    ],
                	[
                        'allow' => true,
                        'controllers' => ['entry'],
                        'actions' => ['login', 'logout'],
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['main'],
                        'matchCallback' => function ($rule, $action) {
                            return (($this->role == 'admin') || ($this->role == 'superadmin'));
                       }
                    ],
                    [
                    	'allow' => true,
                    	'controllers' => ['main'],
                    	'actions' => ['index', 'all'],
                    	'matchCallback' => function ($rule, $action) {
                            return (($this->role != 'user') && (!Yii::$app->user->isGuest));
                       }
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['moderation'],
                        'matchCallback' => function ($rule, $action) {
                            return (($this->role != 'user') && (!Yii::$app->user->isGuest));
                       }
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['users'],
                        'matchCallback' => function ($rule, $action) {
                            return ($this->role == 'superadmin');
                       }
                    ],
                    [
                        'allow' => true,
                        'controllers' => ['users'],
                        'actions' => ['users'],
                        'matchCallback' => function ($rule, $action) {
                            return (($this->role != 'user') && (!Yii::$app->user->isGuest));
                       }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

}