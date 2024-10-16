<?php

use app\models\Document;
use app\models\DocumentType;
use app\models\Program;
use app\models\Subject;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Work */
/* @var $form yii\widgets\ActiveForm */
/* @var $files array */
?>

<div class="work-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'course')->textInput(['type' => 'number']) ?>

    <?= $form->field($model, 'program_id')->widget(Select2::class, [
        'data' => Program::getForSelect(),
        'options' => ['placeholder' => Yii::t('app', 'Education Program') . '...'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]) ?>

    <?= $form->field($model, 'subject_id')->widget(Select2::class, [
        'data' => Subject::getForSelect(),
        'options' => ['placeholder' => Yii::t('app', 'Subject') . '...'],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]) ?>

    <?= $form->field($model, 'doc_type_id')->widget(Select2::class, [
        'data' => DocumentType::getForSelect(),
        'options' => ['placeholder' => Yii::t('app', 'Document Type') . '...'],
        'pluginOptions' => [
            'allowClear' => true,
            'disabled' => true,
        ]
    ]) ?>

    <?php
    /* @var $file app\models\File */
    foreach ($files as $k => $file) {
        $document = $file->getDocument();
        if ($document !== null) {
            echo '<div class="form-group">' . Html::label($file->getFileType()->title)
                . ': ' .
                Html::a(
                    Yii::t('app', 'Watch File'),
                    Yii::$app->storage->getUrl(Document::BUCKET, $document->path),
                    ['target' => '_blank']
                ) . ' | ' . Html::a(
                    Yii::t('app', 'Delete File'),
                    Url::toRoute(['work/document-delete', 'id' => $model->id, 'doc_id' => $document->id])
                ) . '</div>';
            continue;
        }

        echo $form->field($file, "[{$k}]_doc_type_id")->hiddenInput(['value' => $file->getDocType()->id])->label(false);
        echo $form->field($file, "[{$k}]_file_type_id")->hiddenInput(['value' => $file->getFileType()->id])->label(false);
        echo Html::hiddenInput("", $file->isRequired(),['id' =>  $file->getRequiredId()]);

        echo $form->field($file, "[{$k}]file",  [
                'labelOptions' => ['data-browse' => Yii::t('app', 'Add')]
        ])->fileInput([
            'custom' => true,
            'accept' => $file->getFileType()->getExtAsString(),
            'size' => $file->getFileType()->getExtAsString(),
            'required' => false
        ])->label($file->getFileType()->title);

    }
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>