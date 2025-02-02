<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Work */
/* @var $files array */

$this->title = Yii::t('app', 'Update: {name}', [
    'name' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Works'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="work-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'files' => $files,
    ]) ?>

</div>
