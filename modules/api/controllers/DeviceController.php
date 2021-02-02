<?php

namespace app\modules\api\controllers;
use app\modules\api\controllers\CommonController;
use app\modules\api\models\Devices;
use app\modules\api\models\MediaServer;
use phpDocumentor\Reflection\DocBlock\Description;
use yii\db\Exception;

class DeviceController extends CommonController
{
    public $enableCsrfValidation = false;
  // ТЕСТОВЫЙ КОД =================================================================================================
    public function actionIndex()
    {
        echo 'asdas'; exit;
        return $this->render('index');
    }

    public function actionAddDevice()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $devices = new Devices();
        $devices->scenario = Devices::SCENARIO_CREATE;
        $devices->attributes = \Yii::$app->request->post();
//        $jwt = \Yii::$app->request->post('jwt');

        if ($devices->validate())
        {
            return array('status' => true,'attr' => $devices->attributes);
        }else{
            return array('status' => false, 'Message' => $devices->getErrors());
        }
    }
//==================================================================================================================

// СПИСОК УСТРОЙСТВ ВСЕХ И ДЛЯ ПОЛЬЗОВАТЕЛЯ============================================================================================
    public function actionListDevice()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        //пытаемся расшифровать jwt -------
        $extrafield = $this->ExtraField($recivedata);
        // Если JWT девайса, то вывод данных по этому девайсу
	if($deviceinfo = $this->DeviceJwt($recivedata->jwt))
        {
	    $devicedb = new Devices();;
	    $device = $devicedb::findOne(['code'=>$deviceinfo->code, "guid"=>$deviceinfo->guid]);
	    $message = array(
                "Status"=>"Success",
                "Message" => "Device info",
                "List" => $device
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
	}

	try{
            $userinfo = $this->JwtCheck($recivedata->jwt);
        }catch (\Exception $e){
            http_response_code(401);
            if($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            }else{
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong.",
                ));
            }
        }
        //---------------------------------
        //проверка что jwt админа
        try{
            $this->isAdmin($recivedata->jwt);
        }catch (\Exception $e){
            http_response_code(401);
            if($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            }else{
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong. It must be a JWT user",
                ));
            }
        }
        if($this->isAdmin($recivedata->jwt)){
            $devices = Devices::find()->all();
            $message = array(
                "Status"=>"Success",
                "Message"=>"List of all devices",
                "List" => $devices
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }else{ // если jwt не админа то выводим список утройств для поьлзователя с этим jwt
            $userdevice = Devices::find()->where(['user_id'=>$userinfo->data->id])->all();
            $message = array(
                "Status"=>"Success",
                "Message" => "List of user devices",
                "List" => $userdevice
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
//        $info = $this->ExtraField($resevedata);
//        return $userinfo;
//        return $this->asJson(array($userinfo['isAdmin'],$info));
        // Далее что-то делаем с полученными данными
//        var_dump($info);exit;
//        return $this->asJson(array($userinfo,$info));
//        return $this->render('index');
    }


    // РЕГИСТРАЦИЯ УСТРОЙСТВ============================================================================================
    public function actionRegistrationDevice(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        try{
            $deviceinfo = $this->DeviceJwt($recivedata->jwt);
        }catch (\Exception $e){
            if($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            }else{
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong.",
                ));
            }
        }
//        $test = Devices::findOne(['guid'=>$deviceinfo->guid,'code'=>$deviceinfo->code]);
        if(Devices::findOne(['guid'=>$deviceinfo->guid,'code'=>$deviceinfo->code]) != null)
        {
            return array(
                "Status"=>"Failed",
                "Message" => "device with that code and guid already exist",
            );
        }else{
            $mservid = MediaServer::findBySql('SELECT id FROM media_server ORDER BY id DESC LIMIT 1')->one();
            $device = new Devices();
            $device->guid = $deviceinfo->guid;
            $device->code = $deviceinfo->code;
            $device->mserver_id = $mservid->id;
            if($device->save())
            {
                return array(
                    "Status"=>"Success",
                    "Message" => "device is registered",
                );
            }else{
                return array(
                    "Status"=>"Failed",
                    "Message" => "registration fault",
                );
            }
        }
    }
//===================================================EDIT DEVICE==================================================

    public function actionEditDevice(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        try{
            $this->DeviceJwt($recivedata->jwt);
        }catch (\Exception $e){
            if($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            }else{
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong.",
                ));
            }
        }
        if($deviceinfo = $this->DeviceJwt($recivedata->jwt))
        {
            $device = new Devices();
//            $device_bd = $device::find()->where(['code'=>$deviceinfo->code, "guid"=>$deviceinfo->guid])->all();
            $device_bd = $device::findOne(['code'=>$deviceinfo->code, "guid"=>$deviceinfo->guid]);
            if(empty($device_bd)){
                if($extrafield != null) {
                    return (array_merge(array(
                        "Status" => "Failed",
                        "Message" => "probably you changed code or guid. Please generate new jwt"),
                        $extrafield)
                    );
                }else{
                    return array(
                        "Status" => "Failed",
                        "Message" => "probably you changed code or guid. Please generate new jwt"
                    );
                }
            }
            if(isset($recivedata->guid))
            {
                $device_bd->guid = $recivedata->guid;
            }
            if(isset($recivedata->code))
            {
                $device_bd->code = $recivedata->code;
            }
            if(isset($recivedata->mserver_id))
            {
                $device_bd->mserver_id = $recivedata->mserver_id;
            }
            if(isset($recivedata->user_id))
            {
                $device_bd->user_id = $recivedata->user_id;
            }
            if(isset($recivedata->description))
            {
                $device_bd->description = $recivedata->description;
            }
            if(!$device_bd->save()){
                return $device_bd->getErrors();
            }else
                if($extrafield != null){
                    return (array_merge(array(
                        "Status"=>"Success",
                        "Message" => "Device edited!",
                        "Edited device:" => $device_bd = $device::findOne(['code'=>$recivedata->code, "guid"=>$recivedata->guid])),
                        $extrafield)
                    );
                }else{
                    return array(
                        "Status"=>"Success",
                        "Message" => "Device edited!",
                        "Edited device:" => $device_bd = $device::findOne(['code'=>$recivedata->code, "guid"=>$recivedata->guid]),
                    );
                }
        }else{
            if($extrafield !=null) {
                return(array_merge(array(
                    "Status" => "Failed",
                    "Message" => "wrong JWT!"),
                    $extrafield)
                );
            }else{
               return array(
                   "Status" => "Failed",
                   "Message" => "wrong JWT!"
               );
            }
        }

    }
// ============================================================BIND DEVICE==============================================
public function  actionBindDevice(){
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $request=\Yii::$app->getRequest();
    $recivedata=(object)$request->bodyParams;
    $extrafield = $this->ExtraField($recivedata);
    try {
            $userinfo = $this->JwtCheck($recivedata->jwt);
        }catch (\Exception $e){
        $message = array(
            "Status" => "Failed",
            "Message" => "jwt is wrong",
        );
        if($extrafield != null) return array_merge($message,$extrafield); else return $message;
    }
     if(isset($recivedata->code))
    {
//        $device = Devices::find()->where(['code'=>$recivedata->code])->all();
        $device = Devices::findOne(['code'=>$recivedata->code]);
        if(empty($device)){
            $message = array(
                "Status" => "Failed",
                "Message" => "There is no device with that code",
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }elseif($device->user_id!=null){
            $message = array(
                "Status" => "Failed",
                "Message" => "Device already bind",
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }else{
            $device->user_id = $userinfo->data->id;
            if(!$device->save()){
                $message = array(
                    "Status" => "Failed",
                    "Message" => $device->getErrors(),
                );
                if($extrafield != null) return array_merge($message,$extrafield); else return $message;
            }else{
                $message = array(
                    "Status" => "Success",
                    "Message" => "Device binded",
                    "id" => $device->id,
                );
                if($extrafield != null) return array_merge($message,$extrafield); else return $message;
            }
        }
    }else{
        $message = array(
            "Status" => "Failed",
            "Message" => "Field code missed",
        );
        if($extrafield != null) return array_merge($message,$extrafield); else return $message;

    }
}
// ============================================================DELETE DEVICE============================================
    public function actionDeleteDevice(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        try{
            $this->DeviceJwt($recivedata->jwt);
        }catch (\Exception $e){
            if($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            }else{
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong.",
                ));
            }
        }
        $deviceinfo = $this->DeviceJwt($recivedata->jwt);
        $device_bd = Devices::findOne(['code'=>$deviceinfo->code, "guid"=>$deviceinfo->guid]);
        if(!$device_bd->delete()){
            return $device_bd->getErrors();
        }else{
            $message = array(
                "Status" => "Success",
                "Message" => "Device deleted"
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
    }

}
