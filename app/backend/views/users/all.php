<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\MyUser;
use yii\helpers\ArrayHelper;
use kartik\field\FieldRange;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
?>

<div class="articles-index">

    <?php Pjax::begin(['options' => ['class' => 'u-grid-all', 'id' => 'users']]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
                [
                    'label' => 'Изображение',
                    'value' => function($model, $key, $index, $column) {
                        if ($model->userpic != false) {
                            $img = Html::img("@uploads/USERS/" . $model->userpic, ["class" => "grid-view-image", "hidden" => true]);
                            $html = '<div class="grid-img-wrapper"><a class="show-hide-image" href="#" data-pjax="0">Показать</a>' . $img . '</div>';
                            return $html;
                        }
                    },
                    'filter' => '<a href="#" class="show-all-images btn btn-primary" data-show="true" data-pjax="0">Показать все</a>',
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата добавления',
                    'filter' => DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'from_date',
                                    'attribute2' => 'to_date',
                                    'type' => DatePicker::TYPE_RANGE,
                                    'separator' => '-',
                                    'pluginOptions' => ['format' => 'yyyy-mm-dd'],
                                ]),
                    'format' => 'datetime',
                    'contentOptions' => [
                        'class' => 'grid-moderation-date',
                    ],
                ],
                [
                    'attribute' => 'username',
                    'label' => 'Имя пользователя',
                ],
                [
                    'attribute' => 'email',
                    'label' => 'Почта',
                ],
                [
                    'attribute' => 'comment_count',
                    'label' => 'Комментарии',
                    'filter' => false,
                ],
                [
                    'attribute' => 'ban',
                    'label' => 'Баны',
                    'filter' => [
                            'ban_until' => 'Временный бан',
                            'unlim_ban' => 'Вечный бан',
                        ],
                    'value' => function($model, $key, $index, $column) {
                        if ($model->unlim_ban) {
                            $html = '<p class="grid-ban alert-danger">Забанен навсегда</p>';
                            $html .= Html::a(
                                            'Разбанить', 
                                            ['moderation/rid', 'id' => $model->id], 
                                            ['class' => 'btn btn-primary btn-sm center-block', 'data-pjax' => 0]
                                        );
                        } else {
                            if (strtotime($model->ban_until) > time()) {
                                $html = '<p class="grid-ban alert-warning">Забанен до: <br> ' . $model->ban_until . '</p>';
                                $html .= Html::a(
                                                'Разбанить', 
                                                ['moderation/rid', 'id' => $model->id], 
                                                ['class' => 'btn btn-primary btn-sm center-block', 'data-pjax' => 0]
                                            );
                            } else {
                                $html = '<p class="grid-ban alert-success">Не забанен</p>';
                            }
                        }
                        return $html;
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{ban} {moderation} {changeRole}',
                    'buttons' => [
                        'ban' => function($url, $model, $key) {
                            if ($model->unlim_ban) {
                                return false;
                            }
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-ban-circle', 'title' => 'Забанить']),
                                    ['#'], 
                                    [
                                        'data-pjax' => 0, 
                                        'data-toggle' => 'modal', 
                                        'data-target' => '#banModal',
                                        'data-userid' => $model->id,
                                        'data-username' => $model->username
                                    ]
                                );
                        },
                        'moderation' => function($url, $model, $key) {
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-comment', 'title' => 'Просмотреть комментарии']),
                                    ['moderation/mod', 'Moderation[user_id]' => $model->id],
                                    ['target' => '_blank', 'data-pjax' => 0]
                                );
                        },
                        'changeRole' => function($url, $model, $key) {
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-tower', 'title' => 'Сменить роль пользователя']),
                                    ['users/role', 'id' => $model->id],
                                    ['target' => '_blank', 'data-pjax' => 0]
                                );
                        }
                    ],  
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

<?= $this->render('../Common/_modal-image.php'); ?>

<?= $this->render('../Common/_modal-ban.php'); ?>