<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 28.04.2020
 * Time: 15:34
 */

namespace app\controllers;
use yii\web\ForbiddenHttpException;


class AdminController extends CommonController
{
    public $layout = 'adminlayout';

    public function actionAdminprofile()
    {
        if($this->actionIsAdmin() && $this->logincheck())
        {
            return $this->render('adminprofile');
        }else{
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionExit()
    {
        $this->actionLogout();
    }
}