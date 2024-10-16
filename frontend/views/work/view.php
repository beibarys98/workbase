<?php

use common\models\Admin;
use common\models\Document;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Work $model */
/** @var $documents*/

$this->title = $model->title;
\yii\web\YiiAsset::register($this);
?>
<div class="work-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if(Admin::findOne(['user_id' => Yii::$app->user->id])):?>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Скрыть'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <?php endif;?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'title',
                'label' => 'Заголовок'
            ],
            [
                'attribute' => 'author',
                'label' => 'Автор'
            ],
            [
                'attribute' => 'year',
                'label' => 'Год'
            ],
            [
                'attribute' => 'course',
                'label' => 'Курс'
            ],
            [
                'label' => 'Программа',
                'attribute' => 'program.title_ru',
            ],
            [
                'label' => 'Предмет',
                'attribute' => 'subject.title_ru',
            ],
            [
                'label' => 'Вид',
                'attribute' => 'fileType.title_ru',
            ],
        ],
    ]) ?>

    <?= GridView::widget([
        'dataProvider' => $documents,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'showHeader' => false,
        'layout' => "{items}",
        'columns' => [
            [
                'attribute' => 'file_name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->file_name),
                        Url::to(['download', 'id' => $model->id]),
                        ['data-method' => 'post', 'target' => '_blank']);
                },
            ],
        ],
    ]); ?>

</div>
