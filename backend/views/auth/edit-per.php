<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();