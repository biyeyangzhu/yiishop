<?php
/**
 * @var $this yii\web\view
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"username")->textInput();
echo $form->field($model,"email")->textInput();
echo $form->field($model,"password_hash")->passwordInput();
echo $form->field($model,'role',['inline'=>1])->checkboxList($roles);
echo "<input type='submit' class='btn btn-info'>";
\yii\bootstrap\ActiveForm::end();
