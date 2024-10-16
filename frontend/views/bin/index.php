<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\Work;
use yii\bootstrap5\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel common\models\WorkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $year
 */

$this->title = 'Корзина';
?>
<div class="work-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="dropdown">
        <button class="btn btn-secondary" style="width: 160px; height: 40px;" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <?php
            if ($year != null) {
                echo $year;
            }
            ?>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <?php
            foreach (Work::find()->distinct('year')->select('year')->all() as $y) {
                echo '<li><a class="dropdown-item" href="' . Url::to(['/bin/index', 'year' => $y->year]) . '">' . Html::encode($y->year) . '</a></li>';
            }
            ?>
        </ul>
        <?= Html::a('Скрыть', ['/bin/hide', 'year' => $year], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Показать', ['/bin/show', 'year' => $year], ['class' => 'btn btn-success']) ?>
    </div>



    <br>
    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => [
            'id',
            [
                'attribute' => 'title',
                'label' => 'Заголовок',
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
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, Work $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'visible' => !Yii::$app->user->isGuest
            ],
        ],
    ]); ?>


</div>
