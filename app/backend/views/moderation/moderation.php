<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use common\models\Articles;
use common\models\MyUser;
use yii\helpers\ArrayHelper;
use kartik\field\FieldRange;
use kartik\date\DatePicker;
use common\models\Comments;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\Moderation*/
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Moderation';
?>

<div class="moderation-form pull-right">
    <form class="form-inline">
        <div class="form-group">
            <input type="text" class="form-control update-timer" placeholder="Время автообновления" list="browsers" value=""/>
            <datalist id="browsers">
              <option value="30 секунд">
              <option value="1 минута">
              <option value="2 минуты">
              <option value="5 минут">
              <option value="10 минут">
            </datalist> 
        </div>
        <button class="btn btn-primary">Обновить</button>
    </form>   
</div>

<div class="articles-index">

    <?php Pjax::begin(['options' => ['class' => 'm-grid-all', 'id' => 'moderation']]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                //['class' => 'yii\grid\SerialColumn'],
                //'id',
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
                        'class' => 'grid-date',
                    ],
                ],
                [
                    'attribute' => 'article_id',
                    'label' => 'Статья',
                    'value' => function($model, $key, $index, $column) {
                        return Html::a(
                                    $model->article->title,
                                    ['main/all', 'ArticlesSearch[title]' => $model->article->title], 
                                    ['target' => '_blank', 'data-pjax' => 0]
                                );
                    },
                    'format' => 'raw',
                    'contentOptions' => [
                        'class' => 'grid-moderation-article',
                    ],
                    'filter' => Articles::find()
                                    ->select(['title', 'id'])
                                    ->orderBy(['id' => SORT_DESC])
                                    ->limit(20)
                                    ->indexBy('id')
                                    ->column(),
                ],
                [
                    'label' => 'Изображения',
                    'value' => function($model, $key, $index, $column) {
                        if ($model->images != false) {
                            $html_images = '';
                            // foreach (json_decode($model->images) as $key => $image) {
                            //     $html_images .= Html::img(
                            //                         "@uploads/" . $image, 
                            //                         ["class" => "grid-view-image", "hidden" => false]
                            //                     );
                            // }
                            foreach (explode('###', $model->images) as $key => $image) {
                                $html_images .= Html::img(
                                                    "@uploads/" . $image, 
                                                    ["class" => "grid-view-image", "hidden" => false]
                                                );
                            }
                            $html = '<div class="grid-img-wrapper"><a class="show-hide-image" href="#" data-pjax="0">Скрыть</a>' . $html_images . '</div>';
                            return $html;
                        }
                    },
                    'filter' => '<a href="#" class="show-all-images btn btn-primary" data-show="true" data-pjax="0">Скрыть все</a>',
                    'format' => 'raw',
                    'contentOptions' => [
                        'class' => 'grid-moderation-images',
                    ],
                ],
                [
                    'attribute' => 'text',
                    'label' => 'Текст комментария',
                    'format' => 'html',
                    'contentOptions' => [
                        'class' => 'grid-moderation-text',
                    ],
                    'value' => function($model, $key, $index, $column) {
                        if ($model->deleted) {
                            return $model->text 
                                    . '<p class="del-comment">Комментарий был удален!</p>' 
                                    . '<div class="center-block">' .
                                    //. '<a class="btn btn-success btn-sm">Восстановить</a>'
                                    Html::a(
                                            'Восстановить', 
                                            ['moderation/restore', 'id' => $model->id], 
                                            ['class' => 'btn btn-success btn-sm', 'data-pjax' => 0]
                                        )
                                    . '</div>';
                        } else {
                            return $model->text;
                        }
                    }
                ],
                [
                    'attribute' => 'user_id',
                    'label' => 'Автор',
                    'filter' => MyUser::find()
                                    ->select(['username', 'id'])
                                    ->where(['IN', 'id', ArrayHelper::getColumn(Comments::find()->distinct()->select(['user_id'])->asArray()->all(), 'user_id')])
                                    ->indexBy('id')
                                    ->column(),
                    'value' => function($model, $key, $index, $column) {
                        // $html = Html::a($model->user->username, ['users/user', 'id' => $model->user_id], ['class' => 'grid-user center-block', 'target' => '_blank', 'data-pjax' => 0]);
                        $html = '<h5>' . $model->user->username . '</h5>';
                        if ($model->user->unlim_ban) {
                            $html .= '<p class="grid-ban alert-danger">Забанен навсегда</p>';
                            $html .= Html::a(
                                            'Разбанить', 
                                            ['moderation/rid', 'id' => $model->user_id], 
                                            ['class' => 'btn btn-primary btn-sm center-block', 'data-pjax' => 0]
                                        );
                        } else {
                            if (strtotime($model->user->ban_until) > time()) {
                                $html .= '<p class="grid-ban alert-warning">Забанен до: <br> ' . $model->user->ban_until . '</p>';
                                $html .= Html::a(
                                                'Разбанить', 
                                                ['moderation/rid', 'id' => $model->user_id], 
                                                ['class' => 'btn btn-primary btn-sm center-block', 'data-pjax' => 0]
                                            );
                            }
                        }
                        return $html;
                    },
                    'format' => 'raw',
                    'contentOptions' => [
                        'class' => 'grid-moderation-author',
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete} {ban} {rid}',
                    'buttons' => [
                        'delete' => function($url, $model, $key) {
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove', 'title' => 'Удалить']),
                                    ['moderation/del', 'id' => $model->id], 
                                    ['data-pjax' => 0]
                                );
                        },
                        'ban' => function($url, $model, $key) {
                            if ($model->user->unlim_ban) {
                                return false;
                            }
                            return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-ban-circle', 'title' => 'Забанить']),
                                    ['#'], 
                                    [
                                        'data-pjax' => 0, 
                                        'data-toggle' => 'modal', 
                                        'data-target' => '#banModal',
                                        'data-userid' => $model->user_id,
                                        'data-username' => $model->user->username
                                    ]
                                );
                        },
                    ],  
                ],
            ],
        ]); ?>
    <?php Pjax::end(); ?>
</div>

<?= $this->render('../Common/_modal-image.php'); ?>

<?= $this->render('../Common/_modal-ban.php'); ?>