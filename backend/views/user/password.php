<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'oldpassword')->passwordInput();
echo $form->field($model,'newpassword')->passwordInput();
echo $form->field($model,'repassword')->passwordInput();
echo \yii\helpers\Html::submitButton('确认修改',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();