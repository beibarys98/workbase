<?php

use common\models\Admin;
use common\models\Work;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\WorkSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Работы');
?>
<div class="work-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if(Admin::findOne(['user_id' => Yii::$app->user->id])):?>
    <p>
        <?= Html::a(Yii::t('app', 'Добавить'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif;?>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $columns = [
        'id',
        [
            'attribute' => 'title',
            'label' => 'Заголовок',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a(Html::encode($model->title), Url::to(['view', 'id' => $model->id]));
            },
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
    ];

    if (Admin::findOne(['user_id' => Yii::$app->user->id])) {
        $columns[] = [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, Work $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            },
        ];
    }
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
