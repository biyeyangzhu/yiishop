<?php
header("Content-Type:text/html;charset=utf-8");
$from = \yii\bootstrap\ActiveForm::begin();
echo $from ->field($model,'name')->textInput();
echo $from ->field($model,'intro')->textarea();
echo $from ->field($model,'sort')->textInput();
echo $from ->field($model,'status',['inline'=>1])->radioList([1=>'正常','0'=>'隐藏']);
echo "<input type='submit' value='添加' class='btn btn-info'>";
\yii\bootstrap\ActiveForm::end();