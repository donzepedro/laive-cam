<?php
/**
 * Created by PhpStorm.
 * User: Fedor
 * Date: 25.07.2020
 * Time: 21:55
 */

namespace app\modules\api\controllers;
use app\modules\api\models\User;
use app\modules\api\models;
use app\modules\api\controllers\CommonController;

class UserController extends CommonController
{
    public $enableCsrfValidation = false;

    public function actionListUser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        $extrafield = $this->ExtraField($request);
        try {
            $userinfo = $this->JwtCheck($request->jwt);
        } catch (\Exception $e) {
            http_response_code(401);
            return $this->returnMessage('jwt is wrong', $extrafield, 'Failed');
        }
        if ($this->isAdmin($request->jwt)) {
            return models\User::find()->all();
        }
	else {
	    return models\User::findOne(['id' => $userinfo->data->id]);
	}
#	else return $this->returnMessage('Forbiden, not admin jwt', $extrafield, 'Failed');
    }


    public function actionAddUser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        $extrafield = $this->ExtraField($request);
        try {
            $userinfo = $this->JwtCheck($request->jwt);
        } catch (\Exception $e) {
            http_response_code(401);
            return $this->returnMessage('jwt is wrong', $extrafield, 'Failed');
        }
        if ($this->isAdmin($request->jwt)) {
//            return $request;
            $user = new User();


            foreach ($request as $k => $v) {
                if (!((preg_match("~^^_[a-zA-Z0-9]+$~i", $k)) || ($k == 'jwt'))) {
                    if ($k == 'password') {
			    $v = passencode($v) ;
		    }
		    try {
                        $user->$k = $v;
                    }catch (\Exception $e){
                        return $this->returnMessage($k . ' Unknown Property', $extrafield, 'Failed');
                    }
                }
            }
            if (!$user->save()) {
                return $this->returnMessage($user->getErrors(), $extrafield, 'Failed');
            }else return $this->returnMessage('User added', $extrafield, 'Success','id ' . $user->id);
        } else return $this->returnMessage('Forbiden, not admin jwt', $extrafield, 'Failed');
    }

    public function actionEditUser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        $extrafield = $this->ExtraField($request);
        try {
            $userinfo = $this->JwtCheck($request->jwt);
        } catch (\Exception $e) {
            http_response_code(401);
            return $this->returnMessage('jwt is wrong', $extrafield, 'Failed');
        }
        if(!isset($request->id)) {
            return $this->returnMessage('id missed', $extrafield, 'Failed');
        }
        if ($this->isAdmin($request->jwt)) {
                $user = User::findOne(['id' => $request->id]);
            foreach ($request as $k => $v) {
                if (!((preg_match("~^^_[a-zA-Z0-9]+$~i", $k)) || ($k == 'jwt') || ($k == 'id'))) {
                    if ($k == 'password') {
			    $v = passencode($v) ;
		    }

		    try {
                        $user->$k = $v;
                    }catch (\Exception $e){
                        return $this->returnMessage($k . ' Unknown Property', $extrafield, 'Failed');
                    }
                }
            }
            if (!$user->save()) {
                return $this->returnMessage($user->getErrors(), $extrafield, 'Failed');
            }return $this->returnMessage('User edited', $extrafield, 'Success',$user);
        } elseif($userinfo->data->id == $request->id){
                $user = User::findOne(['id' => $request->id]);
                foreach ($request as $k => $v) {
                    if (!((preg_match("~^^_[a-zA-Z0-9]+$~i", $k)) || ($k == 'jwt') || ($k == 'id'))) {
                        if ($k == 'password') {
			    $v = passencode($v) ;
			}
			try {
                            $user->$k = $v;
                        }catch (\Exception $e){
                            return $this->returnMessage($k . ' Unknown Property', $extrafield, 'Failed');
                        }
                    }
                }
                if (!$user->save()) {
                    return $this->returnMessage($user->getErrors(), $extrafield, 'Failed');
                }return $this->returnMessage('User edited', $extrafield, 'Success',$user);
        }else return $this->returnMessage('Forbiden, id for edit and user id do not match', $extrafield, 'Failed');
    }

    public function actionDeleteUser()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        $extrafield = $this->ExtraField($request);
        try {
            $userinfo = $this->JwtCheck($request->jwt);
        } catch (\Exception $e) {
            http_response_code(401);
            return $this->returnMessage('jwt is wrong', $extrafield, 'Failed');
        }
        if(!isset($request->id)) {
            return $this->returnMessage('id missed', $extrafield, 'Failed');
        }
        if ($this->isAdmin($request->jwt)) {
            $user = User::findOne(['id'=>$request->id]);
            if (!$user->delete()) {
                return $this->returnMessage($user->getErrors(), $extrafield, 'Failed');
            }return $this->returnMessage('User deleted', $extrafield, 'Success');
        } else return $this->returnMessage('Forbiden, not admin jwt', $extrafield, 'Failed');
    }

}