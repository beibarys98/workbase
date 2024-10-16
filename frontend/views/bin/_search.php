<?php

use app\models\DocumentType;
use app\models\Program;
use app\models\Subject;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WorkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="work-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?php // echo  $form->field($model, 'title') ?>

    <?php // echo  $form->field($model, 'author') ?>

    <?php // echo  $form->field($model, 'year') ?>

    <?= $form->field($model, 'program_id')->widget(Select2::class, [
        'data' => Program::getForSelect(),
        'options' => ['placeholder' => Yii::t('app', 'Education Program') . '...'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]) ?>

    <?php //$form->field($model, 'subject_id')->widget(Select2::class, [
//        'data' => Subject::getForSelect(),
//        'options' => ['placeholder' => Yii::t('app', 'Subject') . '...'],
//        'pluginOptions' => [
//            'allowClear' => true
//        ]
//    ]) ?>

    <?= $form->field($model, 'doc_type_id')->widget(Select2::class, [
        'data' => DocumentType::getForSelect(),
        'options' => ['placeholder' => Yii::t('app', 'Document Type') . '...'],
        'pluginOptions' => [
            'allowClear' => true,
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
