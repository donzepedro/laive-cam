<?php
/**
 * Created by PhpStorm.
 * User: Fedor
 * Date: 20.07.2020
 * Time: 20:50
 */

namespace app\modules\api\controllers;


use app\modules\api\models\Cameras;
use app\modules\api\models\Devices;

class CamerasController extends CommonController
{
    public $enableCsrfValidation = false;


// ====================================================LIST CAMERAS====================================================
    public function actionListCameras(){
//        надо вынести в отдельный метод
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        //пытаемся расшифровать jwt -------
        $extrafield = $this->ExtraField($recivedata);
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
// --------------------------------------------------------------------------------------------------------------------
        //надо найти все устройства пользователя, и по ид устройств найти все камеры
        $device_bd = Devices::find()->where(['user_id'=>$userinfo->data->id])->all();
        $cameras =  array();
        $sortcameras = array();
        for($i=0; $i <= count($device_bd)-1; $i++)
        {
            array_push($cameras,Cameras::find()->where(['device_id'=>$device_bd[$i]['id']])->all());
        }
        foreach ($cameras as $key){
                foreach ($key as $k=>$v){
                    array_push($sortcameras,$v);
                }
        }
        $message = array(
            "Status"=>"Success",
            "Message"=>"List of all cameras",
            "List" => $sortcameras
        );
        if($extrafield != null) return array_merge($message,$extrafield); else return $message;
    }
// ====================================================ADD CAMERAS======================================================
    public function actionAddCamera(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        try {
            $deviceinfo = $this->DeviceJwt($recivedata->jwt);
        }catch (\Exception $e){
            $message = array(
                "Status" => "Failed",
                "Message" => "jwt is wrong",
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
        $device = Devices::findOne(['code'=>$deviceinfo->code,'guid' => $deviceinfo->guid]);
        if($device->user_id == null){
          return $this->returnMessage('Device is not bind',$extrafield,'Failed');
        }
        if(isset($recivedata->id_camera)){
            $camera = new Cameras();
            $camera->id_camera = $recivedata->id_camera;
            $camera->device_id = $device->id;
            if(isset($recivedata->title)){
                $camera->title=$recivedata->title;
            }elseif(isset($recivedata->model)){
                $camera->model=$recivedata->model;
            }elseif(isset($recivedata->type)){
                $camera->type = $recivedata->type;
            }elseif(isset($recivedata->description)){
                $camera->description = $recivedata->description;
            }
            if(!$camera->save()){
                return $this->returnMessage($camera->getErrors(),$extrafield,'Failed');
            }else{
                $camera = Cameras::findOne(['id_camera'=>$recivedata->id_camera]);
                return $this->returnMessage('Camera is added',$extrafield,'Succes',$camera->id);
            }
        }else{
            return $this->returnMessage('Key field id_camera missed',$extrafield,'Failed');
        }

    }





//    public function actionAddCamera(){
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//        $request=\Yii::$app->getRequest();
//        $recivedata=(object)$request->bodyParams;
//        //пытаемся расшифровать jwt -------
//        $extrafield = $this->ExtraField($recivedata);
//        try{
//            $userinfo = $this->JwtCheck($recivedata->jwt);
//        }catch (\Exception $e){
//            http_response_code(401);
//            if($extrafield != null) {
//                return (array_merge(array(
//                    "Status" => "Failed",
//                    "Message" => "jwt is wrong."),
//                    $extrafield)
//                );
//            }else{
//                return (array(
//                    "Status" => "Failed",
//                    "Message" => "jwt is wrong.",
//                ));
//            }
//        }
//        if(!isset($recivedata->device_id)){
//            $message = array(
//                "Status" => "Failed",
//                "Message" => "The key parameters device_id missed"
//            );
//            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
//        }
//        if(!isset($recivedata->camera_id)){
//            $message = array(
//                "Status" => "Failed",
//                "Message" => "The key parameters camera_id missed"
//            );
//            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
//        }
//
//        $device_bd = Devices::find()->where(['user_id'=>$userinfo->data->id])->all();
//        $flag = false;
//        for($i=0; $i <= count($device_bd)-1; $i++)
//        {
//            if($device_bd[$i]['id'] == $recivedata->device_id){
//                $flag = true;
//            }
//        }
//        if(!$flag){
//            $message = array(
//                "Status" => "Failed",
//                "Message" => "The user does not have a device with this device_id"
//            );
//            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
//        }
//        $camera = new Cameras();
//            $camera->id_camera = $recivedata->camera_id;
//            $camera->device_id = $recivedata->device_id;
//            if(isset($recivedata->title))
//            {
//                $camera->title = $recivedata->title;
//            }
//            if(isset($recivedata->model))
//            {
//                $camera->model = $recivedata->model;
//            }
//            if(isset($recivedata->type))
//            {
//                $camera->type = $recivedata->type;
//            }
//            if(isset($recivedata->description))
//            {
//                $camera->description = $recivedata->description;
//            }
//            if(!$camera->save()){
//                return $camera->getErrors();
//            }else{
//                $message = array(
//                    "Status" => "Success",
//                    "Message" => "Camera added",
//                    "Added camera" => $camera::findOne(['id_camera'=>$recivedata->camera_id])
//                );
//                if($extrafield != null) return array_merge($message,$extrafield); else return $message;
//            }
//
//    }
    // ====================================================EDIT CAMERAS=================================================
    public function actionEditCamera(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        //пытаемся расшифровать jwt -------
        $extrafield = $this->ExtraField($recivedata);
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
        if(!isset($recivedata->camera_id)){
            $message = array(
                "Status" => "Failed",
                "Message" => "The key parameters camera_id missed"
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
        $device_bd = Devices::find()->where(['user_id'=>$userinfo->data->id])->all();
        $flag = false;
        for($i=0; $i <= count($device_bd)-1; $i++)
        {
            if($device_bd[$i]['id'] == $recivedata->device_id){
                $flag = true;
            }
        }
        if(!$flag){
            $message = array(
                "Status" => "Failed",
                "Message" => "The user does not have a device with this device_id"
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
        $camera = Cameras::findOne(['id_camera'=>$recivedata->camera_id]);
        if(empty($camera)){
            $message = array(
                "Status" => "Failed",
                "Message" => "The user does not have a camera with this camera_id"
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
        $camera->id_camera = $recivedata->camera_id;
        $camera->device_id = $recivedata->device_id;
        if(isset($recivedata->title))
        {
            $camera->title = $recivedata->title;
        }
        if(isset($recivedata->model))
        {
            $camera->model = $recivedata->model;
        }
        if(isset($recivedata->type))
        {
            $camera->type = $recivedata->type;
        }
        if(isset($recivedata->description))
        {
            $camera->description = $recivedata->description;
        }
        if(!$camera->save()){
            return $camera->getErrors();
        }else{
            $message = array(
                "Status" => "Success",
                "Message" => "Camera Edited",
                "Added camera" => $camera::findOne(['id_camera'=>$recivedata->camera_id])
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }

    }
    // ====================================================DELETE CAMERAS===============================================
    public function actionDeleteCamera(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        //пытаемся расшифровать jwt -------
        $extrafield = $this->ExtraField($recivedata);
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
        if(!isset($recivedata->camera_id)){
            $message = array(
                "Status" => "Failed",
                "Message" => "The key parameters camera_id missed"
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
        $camera = Cameras::findOne(['id_camera'=>$recivedata->camera_id]);
        if(!$camera->delete()){
            return $camera->getErrors();
        }else{
            $message = array(
                "Status" => "Success",
                "Message" => "Camera deleted"
            );
            if($extrafield != null) return array_merge($message,$extrafield); else return $message;
        }
    }
}


