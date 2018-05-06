<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Articles;
use common\models\Categories;
use common\models\MyUser;
use common\models\Tag;
use yii\helpers\ArrayHelper;
use kartik\field\FieldRange;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Articles';
?>

<div class="articles-index">

    <?php Pjax::begin(['options' => ['class' => 'p-grid-all']]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label' => 'Изображение',
                    'value' => function($model, $key, $index, $column) {
                        if ($model->image != false) {
                            $img = Html::img("@uploads/" . "small_" . $model->image, ["class" => "grid-view-image", "hidden" => true]);
                            $html = '<div class="grid-img-wrapper"><a class="show-hide-image" href="#" data-pjax="0">Показать</a>' . $img . '</div>';
                            return $html;
                        }
                    },
                    'filter' => '<a href="#" class="show-all-images btn btn-primary" data-show="true" data-pjax="0">Показать все</a>',
                    'format' => 'raw',
                ],
                [

                    'attribute' => 'date_public',
                    'label' => 'Дата публикации',
                    'filter' => DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'from_date',
                                    'attribute2' => 'to_date',
                                    'type' => DatePicker::TYPE_RANGE,
                                    'separator' => '-',
                                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                                ]),
                    'format' => 'datetime',
                    'contentOptions' => [
                        'class' => 'grid-date',
                    ],
                ],
                [
                    'attribute' => 'title',
                    'label' => 'Заглавие',
                ],
                [
                    'attribute' => 'author',
                    'label' => 'Автор',
                    'filter' => MyUser::find()
                                    ->select(['username', 'id'])
                                    ->where(['IN', 'id', ArrayHelper::getColumn(Articles::find()->distinct()->select(['user_id'])->asArray()->all(), 'user_id')])
                                    ->indexBy('id')
                                    ->column(),
                    'value' => function($model, $key, $index, $column) {
                        return Html::a($model->user->username, ['users/user', 'id' => $model->user_id], ['class' => '', 'target' => '_blank', 'data-pjax' => 0]);
                    },
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'description',
                    'label' => 'Описание',
                    'format' => 'html',
                    'contentOptions' => [
                        'class' => 'grid-articles-text',
                        ]
                ],
                [
                    'attribute' => 'category_id',
                    'label' => 'Категория',
                    'filter' => Categories::find()->select(['name', 'id'])->indexBy('id')->column(),
                    'value' => 'category.name',
                ],
                [
                    'attribute' => 'comment_count',
                    'label' => '<span class="glyphicon glyphicon-comment sr-comment-icon" style="font-size: 16px;"></span>',
                    'encodeLabel' => false,
                    'filter' => false,
                    // 'value' => function($model, $key, $index, $column) {
                    //     debug($model->comment->id);
                    //},
                ],
                [
                    'attribute' => 'tags',
                    'label' => 'Теги (через ";")',
                    'value' => function($articles) {
                        return implode('; ', ArrayHelper::map($articles->tags, 'id', 'name'));
                    },
                ],
                [
                    'attribute' => 'published',
                    'label' => 'Статус',
                    'filter' => ['published' => 'Опубликовано', 'waiting' => 'Черновик',],
                    'value' => function($model, $key, $index, $column) {
                        if ($model->inwork) {
                            $html = '<p class="grid-published-info alert-danger">Черновик</p>';
                        } elseif ($model->date_public > date('Y-m-d H:i:s')) {
                            $html = '<p class="grid-published-info alert-info">Отложено до ' . $model->date_public . '</p>';
                            $html .= Html::a(
                                'Опубликовать', 
                                ['main/publish', 'id' => $model->id], 
                                ['class' => 'btn btn-primary btn-sm center-block', 'data-pjax' => 0]
                            );
                        } else {
                            $html = '<p class="grid-published-info alert-success">Опубликовано</p>';
                        }
                        return $html;
                    },
                    'format' => 'raw',
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view} {update} {moderation} {delete}',
                    'buttons' => [
                        'view' => function($url, $model, $key) {
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open', 'title' => 'Просмотреть статью']), 
                                    Yii::$app->frontendUrlManager->createUrl(['main/single', 'id' => $model->id]), 
                                    ['target' => '_blank', 'data-pjax' => 0]
                                );
                        },
                        'moderation' => function($url, $model, $key) {
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-comment', 'title' => 'Просмотреть комментарии']),
                                    ['moderation/mod', 'Moderation[article_id]' => $model->id],
                                    ['target' => '_blank', 'data-pjax' => 0]
                                );
                        } 
                    ],
                    'visibleButtons' => [
                        'update' => function($model, $key, $index) {
                            if (
                                    ($model->user_id == Yii::$app->user->identity['id']) || 
                                    (Yii::$app->user->identity['role'] == 'admin') || 
                                    (Yii::$app->user->identity['role'] == 'superadmin')
                                ) {
                                return true;
                            }
                        },
                        'delete' => function($model, $key, $index) {
                            if (
                                    ($model->user_id == Yii::$app->user->identity['id']) || 
                                    (Yii::$app->user->identity['role'] == 'admin') || 
                                    (Yii::$app->user->identity['role'] == 'superadmin')
                                ) {
                                return true;
                            }
                        }
                    ],   
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

<?= $this->render('../Common/_modal-image.php'); ?>