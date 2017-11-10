<?php
header("Content-Type:text/html;charset=utf-8");
$from = \yii\bootstrap\ActiveForm::begin();
echo $from ->field($model,'name')->textInput();
echo $from ->field($model,'parent_id')->dropDownList($parent_id);
echo $from ->field($model,'url')->dropDownList($url);
echo $from ->field($model,'sort')->textInput();
echo "<input type='submit' value='添加' class='btn btn-info'>";
\yii\bootstrap\ActiveForm::end();