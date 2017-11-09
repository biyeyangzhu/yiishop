<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'description')->textInput();
echo $form->field($model,'permission',['inline'=>1])->checkboxList($permission);
echo \yii\bootstrap\Html::submitButton('确认添加',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();