<?php
/**
 * Created by PhpStorm.
 * User: Fedor
 * Date: 28.07.2020
 * Time: 9:15
 */

namespace app\modules\api\controllers;
use app\modules\api\models\MediaServer;

class MserverController extends CommonController
{
    public $enableCsrfValidation = false;

    public function actionListMserver()
    {
        $reqinfo = $this->baseApireq();
        $request = (object)\Yii::$app->getRequest()->bodyParams;
//        $reqinfo = array_shift($reqinfo);
        if (!$this->isAdmin($request->jwt)) {
            return $this->returnMessage('Forbiden. Only admin', $reqinfo['extrafield'], 'Failed');
            exit;
        }
        $mserver = MediaServer::find()->all();
        return $this->returnMessage('List of media servers', $reqinfo['extrafield'], 'Success', $mserver);

    }


    public function actionAddMserver()
    {
        $reqinfo = $this->baseApireq();
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        if (!$this->isAdmin($request->jwt)) {
            return $this->returnMessage('Forbiden. Only admin', $reqinfo['extrafield'], 'Failed');
            exit;
        }
        if ((isset($request->title)) && (isset($request->address)) && (isset($request->port)) && (isset($request->password)) && (isset($request->point))) {
            $mserver = New MediaServer();
            $mserver->title = $request->title;
            $mserver->address = $request->address;
            $mserver->port = $request->port;
            $mserver->point = $request->point;
            $mserver->password = $request->password;
            if (!$mserver->save()) {
                return $this->returnMessage($mserver->getErrors(), $reqinfo['extrafield'], 'Failed');
            }
            $addedmserver = MediaServer::findOne(['title' => $request->title, 'address' => $request->address, 'port' => $request->port, 'point' => $request->point, 'password' => $request->password]);
            return $this->returnMessage('Media server added', $reqinfo['extrafield'], 'Success', $addedmserver);
        } else {
            return $this->returnMessage('One of parameter title or address or port or password or point missed', $reqinfo['extrafield'], 'Failed');
        }
    }

    public function actionEditMserver()
    {
        $reqinfo = $this->baseApireq();
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        if (!$this->isAdmin($request->jwt)) {
            return $this->returnMessage('Forbiden. Only admin', $reqinfo['extrafield'], 'Failed');
            exit;
        }
        if (!(isset($request->id))) {
            return $this->returnMessage('Mserver id missed', $reqinfo['extrafield'], 'Failed');
        }
        $mserver_bd = MediaServer::findOne(['id' => $request->id]);
//        debug($request);
        if (empty($mserver_bd)) {
            return $this->returnMessage('There is no device with that id', $reqinfo['extrafield'], 'Failed');
        }
        try {
            $mserver_bd->title = $request->title;
            $mserver_bd->address = $request->address;
            $mserver_bd->point = $request->point;
            $mserver_bd->port = $request->port;
            $mserver_bd->password = $request->password;
        } catch (\Exception $e) {
            return $this->returnMessage('One of parameter missed or blank', $reqinfo['extrafield'], 'Failed');
        }

        if (!$mserver_bd->save()) {
            return $this->returnMessage($mserver_bd->getErrors(), $reqinfo['extrafield'], 'Failed');
        }
        return $this->returnMessage('Media server edited', $reqinfo['extrafield'], 'Success', $mserver_bd);
    }

    public function actionDeleteMserver(){
        $reqinfo = $this->baseApireq();
        $request = (object)\Yii::$app->getRequest()->bodyParams;
        if(!$this->isAdmin($request->jwt)){
            return $this->returnMessage('Forbiden. Only admin',$reqinfo['extrafield'],'Failed');
            exit;
        }
        if(isset($request->id)){
            $mserver = MediaServer::findOne(['id'=>$request->id]);
            if(empty($mserver)){
                return $this->returnMessage('There is no device with that id', $reqinfo['extrafield'], 'Failed');
            }
            if (!$mserver->delete()) {
                return $this->returnMessage($mserver->getErrors(), $reqinfo['extrafield'], 'Failed');
            }
            return $this->returnMessage('Media server deleted', $reqinfo['extrafield'], 'Success');
        }else{
            return $this->returnMessage('Mserver id missed', $reqinfo['extrafield'], 'Failed');
        }
    }
}