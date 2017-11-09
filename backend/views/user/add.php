<?php
/**
 * @var $this yii\web\view
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"username")->textInput();
echo $form->field($model,"email")->textInput();
echo $form->field($model,"password_hash")->passwordInput();
echo "<input type='submit' class='btn btn-info'>";
\yii\bootstrap\ActiveForm::end();
