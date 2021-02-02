<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 29.06.2020
 * Time: 18:45
 */

namespace app\modules\api\controllers;
use app\modules\api\controllers\CommonController;
use app\modules\api\models\Devices;
use app\modules\api\models\Cameras;
use app\modules\api\models\Records;
use app\modules\api\models\MediaServer;
use phpDocumentor\Reflection\DocBlock\Description;
use yii\db\Exception;

class FilesController extends CommonController
{
    public $enableCsrfValidation = false;

    //=======================================================СПИСОК ВСЕХ ФАЙЛОВ=========================================
    public function actionListFiles(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request=\Yii::$app->getRequest();
        $recivedata=(object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata); // дополнительное поле вида "_чтото":"чтото";
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
        $cameras = array();
        $records_bd =array();
        $records =array();
        //если jwt нормальный то возьмем из jwt user_id найдем device_id для этого юзера по divice_id
        // найдем все camera_id, по camera_id найдем все записи.
        if(isset($userinfo->data->id)){
            $device = Devices::find()->where(['user_id'=>$userinfo->data->id])->all();
            $cameras_bd = array();

            for($i=0; $i <= count($device)-1; $i++)
            {
                $camera = Cameras::find()->where(['device_id'=>$device[$i]->id])->all();
                array_push($cameras_bd,$camera);
            }
            foreach ($cameras_bd as $key)
            {
                foreach ($key as $k=>$v)
                {
                 array_push($cameras,$v);
                }
            }
            for($i=0; $i <= count($cameras)-1; $i++)
            {
                $record = Records::find()->where(['camera_id'=>$cameras[$i]->id])->all();
                array_push($records_bd,$record);
            }
            foreach ($records_bd as $key)
            {
                foreach ($key as $k=>$v)
                {
                    array_push($records,$v);
                }
            }
            if($extrafield != null)
            {
                return array_merge(array("Status"=>"Success","Message"=>"List of m_files for user",$extrafield,"List"=>$records));
            }else{
                return array_merge(array("Status"=>"Success","Message"=>"List of m_files for user","List"=>$records));
            }
        }
        if($deviceinfo = $this->DeviceJwt($recivedata->jwt))
        {
            //по device info находим device_id, далее по device_id находим все id камер для этого устройства
            // далее по camera_Id находим записи и выводим список.
            $device = Devices::find()->where(['code'=>$deviceinfo->code,'guid'=>$deviceinfo->guid])->all();
	    $cameras = Cameras::find()->where(['device_id'=>$device[0]->id])->all();
            for($i=0; $i<=count($cameras)-1; $i++)
            {
                $record = Records::find()->where(['camera_id'=>$cameras[$i]->id])->all();
                array_push($records_bd,$record);
            }
//            return ($records_bd);
            foreach ($records_bd as $key)
            {
                foreach ($key as $k=>$v)
                {
                    array_push($records,$v);
                }
            }
            if($extrafield != null)
            {
                return array_merge(array("Status"=>"Success","Message"=>"List of m_files for device",$extrafield,"List"=>$records));
            }else{
                return array_merge(array("Status"=>"Success","Message"=>"List of m_files for device","List"=>$records));
            }
        }else{
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

    }

//=====================================================ДОБАВЛЕНИЕ МЕТАДАННЫХ ДЛЯ ФАЙЛОВ=================================
    //по jwt device-a находим id камер, проверяем есть ли переданный id среди тех что считали из БД. Если да то сохраняем в бд данные
    public function actionMdataCreate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->getRequest();
        $recivedata = (object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        $check = false;
        if ($deviceinfo = $this->DeviceJwt($recivedata->jwt)) {
            $dev_bd = Devices::find()->where(['code'=>$deviceinfo->code,'guid'=>$deviceinfo->guid])->all();
	    $cameras_bd = Cameras::find()->where(['device_id'=>$dev_bd[0]->id])->all();

            for($i=0;$i <= count($cameras_bd)-1; $i++)
            {
                if(($cameras_bd[$i]->id == $recivedata->camera_id))
                {
                    $check = true;
                }
            }
            if(!$check){
                if ($extrafield != null) {
                        return (array_merge(array(


                            "Status" => "Failed",
                            "Message" => "Camera is not associated with this device."),
                            $extrafield)
                        );
                    } else {
                        return (array(
                            "Status" => "Failed",
                            "Message" => "Camera is not associated with this device.",
                        ));
                    }
            }
            $newrecord = new Records();
            $newrecord->camera_id = $recivedata->camera_id;
            $newrecord->title = $recivedata->title;
            $newrecord->file_name = $recivedata->file_name;
            $newrecord->size = $recivedata->size;
            if(isset($recivedata->description)) $newrecord->description = $recivedata->description;
             if(!$newrecord->save())
             {
                 return $newrecord->getErrors();
             }else{
                 if ($extrafield != null) {
                     return (array_merge(array(
                         "Status" => "Success",
                         "Message" => "Meta data saved.",
			 "id" => $newrecord->id),
                         $extrafield)
                     );
                 } else {
                     return (array(
                         "Status" => "Success",
                         "Message" => "Meta data saved.",
			 "id" => $newrecord->id
                     ));
                 }
             }

        } else {
            if ($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            } else {
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong.",
                ));
            }
        }
    }
    public function actionMdataEdit(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = \Yii::$app->getRequest();
        $recivedata = (object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        return $recivedata;
    }
    public function actionUploadFile(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $width='640';
        $height='360';
        $request = \Yii::$app->getRequest();
        $recivedata = (object)$request->bodyParams;
        $extrafield = $this->ExtraField($recivedata);
        if ($deviceinfo = $this->DeviceJwt($recivedata->jwt)) {
            $dev_bd = Devices::findOne(['code'=>$deviceinfo->code,'guid'=>$deviceinfo->guid]);
            $mdata = new Records();
            if(!empty($mdata_bd = $mdata::findOne(['id'=>$recivedata->id]))){
                if(file_exists('../uploads/'.$dev_bd['user_id'].'/'.$recivedata->camera_id))
                {
                    $uploaddir = '../uploads/'.$dev_bd['user_id'].'/'.$recivedata->camera_id;
                }else{
                    mkdir('../uploads/'.$dev_bd['user_id'] .'/'.$recivedata->camera_id, 0777,true );
                    $uploaddir = '../uploads/'.$dev_bd['user_id'].'/'.$recivedata->camera_id;
                }
                $filename = $mdata::find()->select('file_name')->where(['id'=>$recivedata->id])->one();
                $uploadfile = $uploaddir .'/'.$filename->file_name;
		$uploadfileImg = $uploaddir .'/'.$filename->file_name.'.png';
		$uploadfileTmp = $uploadfile.'.tmp';

                if (copy($_FILES['uploadfile']['tmp_name'], $uploadfileTmp))
                {
                    if(file_exists($uploadfile)) {
			if ($extrafield != null) {
                    	    return (array_merge(array(
				"Status" => "Failed",
                        	"Message" => "file already exists."),
                        	$extrafield)
                    	    );
                	} else {
                    	    return (array(
                        	"Status" => "Failed",
                        	"Message" => "file alredy exists.",
                    	    ));
            		}
		    }
		    $is_uploaded = $mdata::find()->where(['id'=>$recivedata->id])->one();
                    $is_uploaded->is_uploaded = 1;
                    $is_uploaded->save();
		    $transcodeComand='../vendor/ffmpeg -y -i '.$uploadfileTmp.' -vcodec copy -strict -2 -acodec copy -f mp4 '.$uploadfile.' && rm -f '.$uploadfileTmp.' && ../vendor/ffmpeg -y -i '.$uploadfile.' -ss 00:00:03 -f image2 -vframes 1 -s '.$width.'x'.$height.' '.$uploadfileImg;
#		    $transcodeComand='/var/www/html/webcam/vendor/ffmpeg -y -i '.$uploadfileTmp.' -vcodec copy -strict -2 -acodec copy -f mp4 '.$uploadfile.' && rm -f '.$uploadfileTmp;
		    system($transcodeComand, $status);
		    if ($status==0) {
                	if ($extrafield != null) {
                    	    return (array_merge(array(
                        	"Status" => "Saccess",
                        	"Message" => "file is upload."),
                        	$extrafield)
                    	    );
                	} else {
                    	    return (array(
                        	"Status" => "Saccess",
                        	"Message" => "file is upload.",
                    	    ));
                	}
		    } else {
			if ($extrafield != null) {
                    	    return (array_merge(array(
				"Status" => "Failed",
                        	"Message" => "failed to convert file or create preview image."),
                        	$extrafield)
                    	    );
                	} else {
                    	    return (array(
                        	"Status" => "Failed",
                        	"Message" => "failed to convert file or create preview image.",
                    	    ));
            		}
		    }
                }else{
                    if ($extrafield != null) {
                        return (array_merge(array(
                            "Status" => "Failed",
                            "Message" => "uploading error."),
                            $extrafield)
                        );
                    } else {
                        return (array(
                            "Status" => "Failed",
                            "Message" => "uploading error.",
                        ));
                    }
                }

            }else{
                if ($extrafield != null) {
                    return (array_merge(array(
                        "Status" => "Failed",
                        "Message" => "meta-data with thad id does not exist."),
                        $extrafield)
                    );
                } else {
                    return (array(
                        "Status" => "Failed",
                        "Message" => "meta-data with thad id does not exist.",
                    ));
                }
            }

//            return array("file"=>$_FILES, "data"=>$recivedata);
            //получаем jwt для устройства, находим  по нему id пользователя
        }else {
            if ($extrafield != null) {
                return (array_merge(array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong."),
                    $extrafield)
                );
            } else {
                return (array(
                    "Status" => "Failed",
                    "Message" => "jwt is wrong.",
                ));
            }
        }

    }
}