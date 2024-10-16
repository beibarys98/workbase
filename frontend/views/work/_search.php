<?php

use common\models\FileType;
use common\models\Program;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\WorkSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="work-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?php
    $programList = ArrayHelper::map(Program::find()->all(), 'id', 'title_ru');
    $fileTypeList = ArrayHelper::map(FileType::find()->where(['<>', 'id', 1])->all(), 'id', 'title_ru');
    ?>

    <?= $form->field($model, 'program_id')->dropDownList($programList, ['prompt' => 'Выберите программу'])->label(false) ?>
    <br>
    <?= $form->field($model, 'file_type_id')->dropDownList($fileTypeList, ['prompt' => 'Выберите вид'])->label(false) ?>

    <div class="form-group mt-3 mb-3">
        <?= Html::submitButton(Yii::t('app', 'Искать'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
