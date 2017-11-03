<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,"name")->textInput();
echo $form->field($model,"intro")->textarea();
echo $form->field($model,"sort")->textInput();
echo $form->field($model,"status",['inline'=>1])->radioList([1=>'正常',0=>'隐藏']);
echo $form->field($model,"imgfile")->fileInput();
echo "<input type='submit' class='btn btn-info'>";
\yii\bootstrap\ActiveForm::end();