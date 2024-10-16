<?php

use common\models\Document;
use common\models\FileType;
use common\models\Program;
use common\models\Subject;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\Work $model */
/** @var $document*/
/** @var $document2*/
/** @var $documents*/

$this->title = Yii::t('app', '{name}', [
    'name' => $model->title,
]);
?>
<div class="work-update">

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

    <?php if ($document->work_id == null): ?>
        <?= $form->field($document, 'doc')->fileInput(['class' => 'form-control'])->label('Антиплагиат') ?>
    <?php endif; ?>

    <?php if($document2->work_id == null):?>
    <?= $form->field($document2, 'pdf')->fileInput(['class' => 'form-control'])->label('Работа') ?>
    <?php endif;?>

    <div class="form-group mt-3">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <br>

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
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, Document $model, $key, $index, $column) {
                    return Url::toRoute(['document/delete', 'id' => $model->id]);
                },
            ]
        ],
    ]); ?>

</div>
