<?php

use common\models\FileType;
use common\models\Program;
use common\models\Subject;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\LinkPager;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Work $model */
/** @var $document*/
/** @var $document2*/

$this->title = Yii::t('app', 'Добавить работу');
?>
<div class="work-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?php $fileTypeItems = ArrayHelper::map(FileType::find()->andWhere(['!=', 'id', 1])->all(), 'id', 'title_ru');?>
    <?= $form->field($model, 'file_type_id')->dropDownList($fileTypeItems, ['prompt' => 'Вид'])->label(false); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Название'])->label(false) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true, 'placeholder' => 'Автор'])->label(false) ?>

    <?= $form->field($model, 'year')->textInput(['placeholder' => 'Год'])->label(false) ?>

    <?= $form->field($model, 'course')->textInput(['placeholder' => 'Курс'])->label(false) ?>

    <?php
    $programItems = ArrayHelper::map(Program::find()->all(), 'id', function($program) {
        return $program->code . ' - ' . $program->title_ru;
    });
    ?>
    <?= $form->field($model, 'program_id')->dropDownList($programItems, ['prompt' => 'Программа'])->label(false) ?>

    <?php $subjectItems = ArrayHelper::map(Subject::find()->all(), 'id', 'title_ru')?>
    <?= $form->field($model, 'subject_id')->dropDownList($subjectItems, ['prompt' => 'Предмет'])->label(false) ?>

    <?= $form->field($document, 'doc')->fileInput(['class' => 'form-control'])->label('Антиплагиат') ?>

    <?= $form->field($document2, 'pdf')->fileInput(['class' => 'form-control'])->label('Работа') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
