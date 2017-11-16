<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/13
 * Time: 16:34
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class address extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'address','tel','province','city','area'], 'required'],
            ['default','safe']
        ];
    }
}