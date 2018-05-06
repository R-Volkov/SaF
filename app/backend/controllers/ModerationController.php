<?php

namespace backend\controllers;

use Yii;
use common\models\Comments;
use common\models\MyUser;
use backend\models\Moderation;


class ModerationController extends BackendController {

	public $defaultAction = 'mod';
	public $layout = 'template2';

	public function actionBan($id, $until = NULL) {
		$model = MyUser::findOne($id);
		if ($model->id == Yii::$app->user->identity['id']) {
			Yii::$app->session->setFlash('failure', "Вы пытаетесь забанить сами себя. Это тупо. Тупо, как лось." );
			return $this->redirect(Yii::$app->request->referrer);
		}
		if (($model->role == 'admin' || $model->role == 'superadmin' || $model->role == 'moderator') && (Yii::$app->user->identity['role'] == 'superadmin')) {
			Yii::$app->session->setFlash('failure', "Вы пытаетесь забанить пользователя с ролью {$model->role}. Пожалуйста, обратитьсь в раздел <a href=\"/admin/admins/all\">\"Админы\"</a>" );
			return $this->redirect(Yii::$app->request->referrer);
		}
		if (($model->role == 'admin' || $model->role == 'superadmin' || $model->role == 'moderator') && (Yii::$app->user->identity['role'] != 'superadmin')) {
			Yii::$app->session->setFlash('failure', "Вы пытаетесь забанить пользователя с ролью {$model->role}. Ваших прав недостаточно." );
			return $this->redirect(Yii::$app->request->referrer);
		}
		if ($until == false) {
			$model->unlim_ban = true;
			$model->status = 'STATUS_INACTIVE';
			$model->save();
			Yii::$app->session->setFlash('success', "Пользователь {$model->username} был забанен навсегда.");
			return $this->redirect(Yii::$app->request->referrer);
		} else {
			$model->ban_until = $until;
			$model->save();
			Yii::$app->session->setFlash('success', "Пользователь {$model->username} был забанен до {$until}." );
			return $this->redirect(Yii::$app->request->referrer);
		}	
	}

	public function actionRid($id) {
		$model = MyUser::findOne($id);
		if (($model->role == 'admin' || $model->role == 'superadmin' || $model->role == 'moderator') && (Yii::$app->user->identity['role'] == 'superadmin')) {
			Yii::$app->session->setFlash('failure', "Вы пытаетесь разбанить пользователя с ролью {$model->role}. Пожалуйста, обратитьсь в раздел <a href=\"/admin/admins/all\">\"Админы\"</a>" );
			return $this->redirect(Yii::$app->request->referrer);
		}
		if (($model->role == 'admin' || $model->role == 'superadmin' || $model->role == 'moderator') && (Yii::$app->user->identity['role'] != 'superadmin')) {
			Yii::$app->session->setFlash('failure', "Вы пытаетесь разбанить пользователя с ролью {$model->role}. Ваших прав недостаточно." );
			return $this->redirect(Yii::$app->request->referrer);
		}
		$model->unlim_ban = false;
		$model->ban_until = NULL;
		$model->status = 1;
		$model->save();
		Yii::$app->session->setFlash('success', "Пользователь {$model->username} был разбанен.");
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionDel($id) {
		$model = Comments::findOne($id);
		$model->deleted = 1;
		$model->save();
		Yii::$app->session->setFlash('success', "Комментарий был успешно удален." );
		return $this->redirect(Yii::$app->request->referrer);
	}

	public function actionMod()
	{
        $searchModel = new Moderation();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('moderation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
	}

	public function actionRestore($id)
	{
		$model = Comments::findOne($id);
		$model->deleted = 0;
		$model->save();
		Yii::$app->session->setFlash('success', "Комментарий был успешно восстановлен." );
		return $this->redirect(Yii::$app->request->referrer);
	}


}