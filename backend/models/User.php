<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/7
 * Time: 15:27
 */

namespace backend\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public $role;
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'email'=>'邮箱',
            'password_hash'=>'密码',
            'role'=>'角色'
        ];

    }

    public function rules()
    {
        return [
            [['email','password_hash','username','role'], 'required','message'=>'不能为空'],
            ['username', 'unique', 'targetClass' => '\backend\models\User', 'message' => '这个用户名已经被采取'],
            ['email','email','message'=>'请正确填写邮箱规则'],
        ];
    }

    /**
     * 菜单的生成
     * @return array
     */
    public function getMenus(){
        $menuItems =[];
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $items =[];
            foreach ($menu->children as $child){
                if(\Yii::$app->user->can($child->url)){
                    $items[]=['label'=>$child->name,'url'=>[$child->url]];
                }
            }
            $menuItem=['label'=>$menu->name,'items'=>$items];
            if($items){
                $menuItems[]=$menuItem;
            }
        }
        return $menuItems;
    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
        // TODO: Implement getAuthKey() method.
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key===$authKey;
        // TODO: Implement validateAuthKey() method.
    }

    /**
     * 自动更新时间
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}