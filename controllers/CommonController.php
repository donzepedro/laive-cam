<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 08.04.2020
 * Time: 17:22
 */

namespace app\controllers;


use Firebase\JWT\JWT;
use yii\web\Controller;
use app\models\User;


class CommonController extends Controller
{
    public function actionIsAdmin($token = null){
        $jwt = new JWT;
        require JWTCONF;
        if($token == null)$token = $this->actionGettrustkey();
        if ($token != 'trustisnotset')
        {
            $userinfo = $jwt->decode($token, $key, array('HS256'));
            $user = User::find()->where(['id' => $userinfo->data->id])->all();
//            debug( $user);
//            die;
            if ($user[0]['isAdmin'] == '1') {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function actionIsActivated($token = null){
        $jwt = new JWT;
        require JWTCONF;
        if($token == null)$token = $this->actionGettrustkey();
        if ($token != 'trustisnotset')
        {
            $userinfo = $jwt->decode($token, $key, array('HS256'));
            $user = User::find()->where(['id' => $userinfo->data->id])->all();
            if ($user[0]['ActivateStatus'] == 'Activated') {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function logincheck()
    {
        $token = $this->actionGettrustkey();
        if ($token != 'trustisnotset')
        {
            $jwt = new JWT;
            require JWTCONF;
            if($jwt->decode($token,$key,array('HS256')))
            {
                return true;
            }else{
                return false;
            }
        } else{
            return false;
        }
    }

    public function getUser(){
        $jwt = new JWT;
        require JWTCONF;
        $token = $this->actionGettrustkey();
        $userinfo = $jwt->decode($token, $key, array('HS256'));
        return ($id = User::findOne(['id'=>$userinfo->data->id]));
    }

    protected function actionGettrustkey()
    {
        $key = \Yii::$app->request->cookies;
        return $key = $key->getValue('trust','trustisnotset');
    }

    public function actionLogout(){
        \Yii::$app->response->cookies->remove('trust');
        return $this->redirect(DOMAIN);

    }
}