<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\ActionColumn;
use yii\web\YiiAsset;
use kartik\icons\FontAwesomeAsset;
use kartik\icons\Icon;

/* @var $this yii\web\View */
/* @var $model app\models\Work */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Works'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
FontAwesomeAsset::register($this);

?>
<div class="work-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::a('Показать', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-warning',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'author',
            'year',
            'course',
            'program.title',
            'subject.title',
            'documentType.title',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

    <h3><?= Yii::t('app', 'Uploaded Files') . Icon::show('document', ['framework' => Icon::JUI]) ?></h3>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'pager' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'fileType.title',
                'enableSorting' => false
            ],
            [
                'attribute' => 'extension',
                'enableSorting' => false
            ],
            [
                'attribute' => 'size',
                'label' => Yii::t('app', 'File Size(Mb)'),
                'value' => function($model){return $model->getSizeOnMb();},
                'enableSorting' => false
            ],
//            [
//                'attribute' => 'uploaded_at',
//                'enableSorting' => false
//            ],
            [
                'class' => ActionColumn::class,
                'template' => '{document}',
                'buttons' => [
                    'document' => function ($url, $model) {
                        return Html::a(
                            Icon::show('file', ['framework' => Icon::FAS, 'class' => 'fa-lg']),
                            Yii::$app->storage->getUrl('documents', $model->path),
                            ['title' => Yii::t('app', 'Watch file'), 'target' => '_blank']
                        );
                    }
                ]
            ],
        ]
    ]) ?>

</div>
