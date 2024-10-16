<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Program $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="program-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->label('Код') ?>

    <?= $form->field($model, 'title_kk')->textInput(['maxlength' => true])->label('Атауы') ?>

    <?= $form->field($model, 'title_ru')->textInput(['maxlength' => true])->label('Заголовок') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
