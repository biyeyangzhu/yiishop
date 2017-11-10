<?php
/**
 * Created by PhpStorm.
 * User: melo
 * Date: 2017/11/9
 * Time: 10:36
 */

namespace backend\controllers;


use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class AuthController extends Controller
{

    /**
     * 权限的添加
     */
    public function actionAddPermission()
    {
        //实例化authManager组件操作rbac
        $auth = \Yii::$app->authManager;
        //实例化表单模型显示表单
        $model = new PermissionForm();
        $request = \Yii::$app->request;
        //给这个方法添加场景
        $model->scenario =PermissionForm::SCENARIO_ADD;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //使用组件中的方法进行权限的创建 获取到的是一个权限对象
                $permission = $auth->createPermission($model->name);
                //给权限添加描述
                $permission->description = $model->description;
                //使用组件中的方法进行权限的添加  这里需要传入的是一个权限对象
                if ($auth->add($permission)) {
                    //添加成功进行跳转
                    \Yii::$app->session->setFlash('success', '添加成功');
                    $this->redirect('index-permission');
                }
            }
        }
        return $this->render('add-per', ['model' => $model]);
    }

    /**
     * 权限列表
     * @return string
     */
    public function actionIndexPermission()
    {
        //authManager组件负责管理(操作)rbac
        $auth = \Yii::$app->authManager;
        //使用组件获取所有的权限
        $permission = $auth->getPermissions();
        return $this->render('index-per', ['model' => $permission]);
    }

    /**
     * 权限的删除
     */

    public function actionDeletePermission($name)
    {
        //authManager组件负责管理(操作)rbac
        $auth = \Yii::$app->authManager;
        //找到这个权限的对象
        $permission = $auth->getPermission($name);
        //删除权限
        if ($auth->remove($permission)) {
            echo 1;
        } else {
            echo '删除失败';
        }
    }

    /**
     * 权限的修改
     */
    public function actionEditPermission($name)
    {
        //回显权限 查找权限
        //实例化authManager组件操作rbac
        $auth = \Yii::$app->authManager;
        //得到到权限对象
        $permission = $auth->getPermission($name);
        $model = new PermissionForm();
        $model->oldName = $name;
        //添加场景
        $model->scenario =PermissionForm::SCENARIO_EDIT;
        //给表单模型赋值 用于回显
        $model->name = $permission->name;
        $model->description = $permission->description;
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //将提交到模型的新的值赋值给权限
                $permission->name = $model->name;
                $permission->description = $model->description;
                //更新权限
                $auth->update($name, $permission);
                //添加成功进行跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                $this->redirect('index-permission');
            }
        }
        return $this->render('edit-per', ['model' => $model]);
    }

    /**
     * 角色的添加
     */
    public function actionAddRole()
    {
        //实例化authManager组件操作rbac
        $auth = \Yii::$app->authManager;
        //实例化表单模型显示表单
        $model = new RoleForm();
        //添加场景
        $model->scenario =RoleForm::SCENARIO_ADD;

        //显示所有的权限
        $permission = $auth->getPermissions();
        $permissions = ArrayHelper::map($permission, 'name', 'description');
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //验证角色名重复
                $count = $auth->getRole($model->name);
                if ($count) {
                    //添加成功进行跳转
                    \Yii::$app->session->setFlash('danger', '角色名重复,请重新输入');
                    $this->redirect('add-role');
                    return false;
                }
                //使用组件中的方法进行角色的创建 获取到的是一个角色对象
                $role = $auth->createRole($model->name);
                //给角色添加描述
                $role->description = $model->description;
                //使用组件中的方法进行角色的添加  这里需要传入的是一个角色对象
                $auth->add($role);
                //将接受到的权限进行逐个添加给角色  表单提交过来的是个数组遍历进行添加
                foreach ($model->permission as $pername) {
                    $permission = $auth->getPermission($pername);
                    $auth->addChild($role, $permission);
                }
                //添加成功进行跳转
                \Yii::$app->session->setFlash('success', '添加成功');
                $this->redirect('index-role');


            }
        }
        return $this->render('add-role', ['model' => $model, 'permission' => $permissions]);
    }

    /**
     * 角色的列表
     */
    public function actionIndexRole()
    {
        //authManager组件负责管理(操作)rbac
        $auth = \Yii::$app->authManager;
        //使用组件获取所有的角色
        $Roles = $auth->getRoles();
        return $this->render('index-role', ['model' => $Roles]);
    }

    /**
     * 角色的删除
     */

    public function actionDeleteRole($name)
    {
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($name);
        if ($auth->remove($role)) {
            echo 1;
        } else {
            echo '删除失败';
        }
    }

    /**
     * 角色的修改
     */
    public function actionEditRole($name)
    {
        $auth = \Yii::$app->authManager;
        //得到需要更新角色的对象
        $role = $auth->getRole($name);
        $model = new RoleForm();
        //添加场景
        $model->scenario =RoleForm::SCENARIO_EDIT;
        //赋值给原来值
        $model->oldName = $name;
        //将修改角色的值赋值给模型用于回显
        $model->name = $role->name;
        $model->description = $role->description;
        //将获取到的角色下的所有权限赋值给model
        $model->permission = array_keys($auth->getPermissionsByRole($name));
        //获取所有的权限
        $permission = $auth->getPermissions();
        $permission = ArrayHelper::map($permission, 'name', 'description');
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $model->load($request->post());
            if ($model->validate()) {
                //先删除角色和权限的所有关系在重新赋值
                $auth->removeChildren($role);
                //将表单提交的所有数据在赋值
                $role->name = $model->name;
                $role->description = $model->description;
                //修改角色的名称和描述
                $auth->update($name, $role);
                //重新分配权限给角色
                foreach ($model->permission as $pername) {
                    $permission = $auth->getPermission($pername);
                    $auth->addChild($role, $permission);
                }
                //添加成功进行跳转
                \Yii::$app->session->setFlash('success', '修改成功');
                $this->redirect('index-role');
            }
        }
        return $this->render('edit-role', ['model' => $model, 'permission' => $permission]);
    }
}