<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 09.04.2020
 * Time: 16:11
 */


namespace app\controllers;
use Yii;
use app\modules\api\models\Cameras;
use app\modules\api\models\Records;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use app\models\User;
#use app\models\Devices;
use app\modules\api\models\Devices;
use app\modules\api\models\MediaServer;
use app\models\Changepswd;
use app\models\Notifications;
use app\models\Account;
use app\models\AccountEmail;
use app\models\Archive;
use app\models\Filter;
use yii\web\Controller;
use Firebase\JWT\JWT;



class UserController extends CommonController
{
    public $layout  = 'userlayout';
//    public function behaviors(){
//        return [
//            'access' => [
//                'class' => \yii\filters\AccessControl::class,
//                'rules' => [
//                    [
//                        'allow' => true,
//                        'roles' => ['@'],
//                    ],
//                ],
//            ],
//        ];
//    }
//==============================================================USER---PROFILE=======================================

    public function actionUserprofile()
    {
        if(!($this->actionIsAdmin()) && $this->logincheck())
    // if(1==1)
        {
                $user = $this->getUser();
                // $user = \app\models\User::findOne(['id' => 10]);
                if(isset($_GET['options']))
                {
                    if($_GET['options'] == 'notifications')
                      {
                        $activepage = 'notifications';
                        $notifications = new Notifications();

                        if(isset($_POST['Notifications']))
                          {
                            $user->subscribe = $_POST['Notifications']['subscribe'];
                            $user->ysubscribe = $_POST['Notifications']['ysubscribe'];
                            $user->save();
                             $this->redirect(DOMAIN.'/en/user/userprofile?options=notifications');
                          }
                        if($user['subscribe']){
                            $checked = true;
                        }else{
                            $checked = false;
                        }
                        if($user['ysubscribe']){
                            $ychecked = true;
                        }else{
                            $ychecked = false;
                        }

                        return $this->render('userprofile',[
                            'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                            'name'=>mb_strtoupper($user['name']),
                            'user'=>$user,
                            'emailadress'=>$user['email'],
                            'notifications'=>$notifications,
                            'checked' => $checked,
                            'ychecked' => $ychecked,
                            'activepage'=>$activepage,
                        ]);
                     }
                 elseif($_GET['options'] == 'activate-email' && isset($_SESSION['new_email'])) {
                     $user->email = $_SESSION['new_email'];
                     $user->save();
                     $this->redirect(DOMAIN.'/user/userprofile?options=settings-account');
                 }
                  elseif($_GET['options'] == 'pswd')
                  {
                    $activepage = 'pswd';
                    $changepswd = new Changepswd();
                    if(isset($_POST['Changepswd']))
                    {
                        if($user['password']==passencode(clean_data($_POST['Changepswd']['oldpassword'])))
                        {
                            if(clean_data($_POST['Changepswd']['newpassword'] == clean_data($_POST['Changepswd']['confirmpassword'])))
                             {
                                $user->password = passencode(clean_data($_POST['Changepswd']['newpassword']));
                                $user->save();
                                return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                    'name'=>mb_strtoupper($user['name']),
                                    'user'=>$user,
                                    'emailadress'=>$user['email'],
                                    'changepswd'=>$changepswd,
                                    'note'=>'success'
                                ]);
                            }else{
                                return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                    'name'=>mb_strtoupper($user['name']),
                                    'user'=>$user,
                                    'emailadress'=>$user['email'],
                                    'changepswd'=>$changepswd,
                                    'note'=>'notequals'
                                ]);
                            }
                        }else{
                            return $this->render('userprofile',[
                                'activepage'=>$activepage,
                                'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                'name'=>mb_strtoupper($user['name']),
                                'user'=>$user,
                                'emailadress'=>$user['email'],
                                'changepswd'=>$changepswd,
                                'note'=>'wrongpswd'
                            ]);
                        }

                    }
                    return $this->render('userprofile',[
                        'activepage'=>$activepage,
                        'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                        'name'=>mb_strtoupper($user['name']),
                        'user'=>$user,
                        'emailadress'=>$user['email'],
                        'changepswd'=>$changepswd,
                        'note'=>''
                    ]);
                  }
                elseif(explode("-",$_GET['options'])[0]=='settings')
                {  $activepage="settings";
                    $active=$note='';
                    if(Yii::$app->session->hasFlash('account')) $note='success_account';
                    if(Yii::$app->session->hasFlash('password')) $note='success_pswd';
                    if(isset(explode("-",$_GET['options'])[1]))
                        $active=explode("-",$_GET['options'])[1];
                     else {
                          return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'active'=>'',
                                    'note'=>$note
                                ]);
                     }
                     if($active=="tariff"){
                         return $this->render('userprofile',[
                             'activepage'=>$activepage,
                             'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                             'name'=>mb_strtoupper($user['name']),
                             'user'=>$user,
                             'emailadress'=>$user['email'],
                             'active'=>$active,
                             'user_id'=>$user['id'],
                             'note'=>$note
                         ]);
                     }

                    if($active=="account")
                      {
                         $model = new Account();
                         $model_modal=new AccountEmail();

                          if(isset($_POST['Account']))
                          {
                                $user->phone=clean_data($_POST['Account']['phone']);
                                $user->name=clean_data($_POST['Account'] ['first_name']);
                                $user->surname=clean_data($_POST['Account']['surname']);
                                $user->country=clean_data($_POST['Account']['country']);
                                $user->city=clean_data($_POST['Account']['city']);
                                Yii::$app->getSession()->setFlash('account', '1');
                                  $user->save();
                                $this->redirect(DOMAIN.'/user/userprofile?options=settings-account');
                         }

                         if(isset($_POST['AccountEmail']))
                         {
                             if (User::findOne(['email'=>$_POST['AccountEmail']['new_email']])) {
                                 Yii::$app->getSession()->setFlash('email', '0');
                                 $note = "email_exist";
                                 return $this->render('userprofile',[
                                     'activepage'=>$activepage,
                                     'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                     'name'=>mb_strtoupper($user['name']),
                                     'user'=>$user,
                                     'emailadress'=>$user['email'],
                                     'active'=>$active,
                                     'model' =>$model,
                                     'model_modal' =>$model_modal,
                                     'user_id'=>$user['id'],
                                     'note'=>$note
                                 ]);
                             }
                             $_SESSION['new_email']=clean_data($_POST['AccountEmail']['new_email']);
                             Yii::$app->mailer->compose('accountemail')
                                  ->setFrom('zepedro@yandex.ru')
                                  ->setTo($_SESSION['new_email'])
                                  ->setSubject('Webcamera Account')
                                  ->send();
                               Yii::$app->getSession()->setFlash('email', '1');
                                $note='success_email_send';
                                return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                    'name'=>mb_strtoupper($user['name']),
                                    'user'=>$user,
                                    'emailadress'=>$user['email'],
                                    'active'=>$active,
                                    'model' =>$model,
                                    'model_modal' =>$model_modal,
                                    'user_id'=>$user['id'],
                                    'note'=>$note
                                ]);
                        }

                                return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                    'name'=>mb_strtoupper($user['name']),
                                    'user'=>$user,
                                    'emailadress'=>$user['email'],
                                    'active'=>$active,
                                    'model' =>$model,
                                    'model_modal' =>$model_modal,
                                    'user_id'=>$user['id'],
                                    'note'=>$note
                                ]);
                     }

                     if($active=="pswd")
                      {
                        $model = new Changepswd();
                        if(isset($_POST['Changepswd']))
                        {
                            if($user['password']==passencode(clean_data($_POST['Changepswd']['oldpassword'])))
                            {
                                if(clean_data($_POST['Changepswd']['newpassword'] == clean_data($_POST['Changepswd']['confirmpassword'])))
                                {
                                    $user->password = passencode(clean_data($_POST['Changepswd']['newpassword']));
                                    $user->save();
                                    Yii::$app->getSession()->setFlash('password', '1');
                                    $this->redirect(DOMAIN.'/user/userprofile?options=settings-account');
                                }else{

                                    return $this->render('userprofile',[
                                        'activepage'=>$activepage,
                                        'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                        'name'=>mb_strtoupper($user['name']),
                                        'user'=>$user,
                                        'emailadress'=>$user['email'],
                                        'note'=>'notequals',
                                        'active'=>$active,
                                        'model' =>$model,
                                        'user_id'=>$user['id'],
                                        'ip'=>$user['ip'],
                                        'last_login'=>date("d-m-Y",$user['actiontimestamp'])
                                    ]);
                                   }
                          }else{
                                return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                    'name'=>mb_strtoupper($user['name']),
                                    'user'=>$user,
                                    'emailadress'=>$user['email'],
                                    'note'=>'wrongpswd',
                                    'active'=>$active,
                                    'model' =>$model,
                                    'user_id'=>$user['id'],
                                    'ip'=>$user['ip'],
                                    'last_login'=>date("d-m-Y",$user['actiontimestamp'])
                                ]);
                               }
                     }
                   else{
                    return $this->render('userprofile',[
                        'activepage'=>$activepage,
                        'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                        'name'=>mb_strtoupper($user['name']),
                        'user'=>$user,
                        'emailadress'=>$user['email'],
                        'user_id'=>$user['id'],
                        'note'=>'',
                        'active'=>$active,
                        'model' =>$model,
                        'ip'=>$user['ip'],
                        'last_login'=>date("d-m-Y",$user['actiontimestamp'])
                    ]);
                }

                   }
                if($active== 'notifications')
                      {
                        $notifications = new Notifications();

                        if(isset($_POST['Notifications']))
                          {
                            $user->subscribe = $_POST['Notifications']['subscribe'];
                            $user->ysubscribe = $_POST['Notifications']['ysubscribe'];
                            $user->save();
                             $this->redirect(DOMAIN.'/en/user/userprofile?options=settings-notifications');
                          }
                        if($user['subscribe']){
                            $checked = true;
                        }else{
                            $checked = false;
                        }
                        if($user['ysubscribe']){
                            $ychecked = true;
                        }else{
                            $ychecked = false;
                        }

                        return $this->render('userprofile',[
                            'notifications'=>$notifications,
                            'checked' => $checked,
                            'ychecked' => $ychecked,
                            'activepage'=> $activepage,
                            'active'=>$active,
                            'note'=>''
                        ]);
                     }

                        if($active== 'archive')
                      {
                        $model = new Archive();
                         if(isset($_POST['Archive']))
                         {
                            $archives = Archive::find()->where(['user_id'=>$user['id']])->all();
                            foreach ($archives as $archive) {
                                $archive->delete();
                            }
                            Yii::$app->db->createCommand()->insert('user_archives', [
                                'user_id' =>$user['id'],
                                'month' =>clean_data($_POST['Archive']['month_count']),
                                'from_date' =>date("Y-m-d"),
                                'to_date' =>date("Y-m-d")
                            ])->execute();
                         }
                       return $this->render('userprofile',[
                                    'activepage'=>$activepage,
                                    'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                                    'name'=>mb_strtoupper($user['name']),
                                    'user'=>$user,
                                    'emailadress'=>$user['email'],
                                    'active'=>$active,
                                    'model' =>$model,
                                    'user_id'=>$user['id'],
                                    'note'=>$note
                                ]);
                     }
               }
           }
                else{
                    $activepage = '';
                    Yii::$app->getSession()->setFlash('activepage', 'settings');
                    return $this->render('userprofile',[
                        'activepage'=>$activepage,
                        'username'=>mb_strtoupper($user['name']. ' ' .$user['surname']),
                        'name'=>mb_strtoupper($user['name']),
                        'user'=>$user,
                        'emailadress'=>$user['email']
                    ]);
                }

        }else{
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

//==================================================================MyCameras===========================================
    public function actionMycameras()
    {
        $jwt =  new JWT;
//        debug(\Yii::$app->request->pathInfo);
//            debug(\Yii::$app->request->get('options'));
//            exit;
        if (!($this->actionIsAdmin()) && $this->logincheck()) {
            // if (1==1) {
            $user_info = $this->getUser();
            // $user_info = User::findOne(['id' => 10]);
//--------------FOR  DELETE ---------------------
            $devices = Devices::find()->select(['id'])->where(['user_id' => $user_info['id']])->all();
            $dev_id = array();
            foreach ($devices as $device) {
                foreach ($device as $key => $val) {
                    if (!empty($val))
                        array_push($dev_id, $val);
                }
            }
//            debug($dev_id);
//            exit;
            $camera_bd = array();

//            for ($i = 0; $i <= count($dev_id)-1; $i++) {
                array_push($camera_bd, Cameras::find()->where(['device_id' => $dev_id])->all());
//            }
//	    debug($camera_bd);
            $camera = array();
//            echo count($camera_bd[0]);
//            exit;
            for ($i = 0; $i <= count($camera_bd[0]) - 1; $i++) {
                array_push($camera, $camera_bd[0][$i]);
            }
//--------------------------------------------
	    $user_cams = Cameras::find()
		    ->joinWith(['device d' => function($q1) {
			    $q1->joinWith(['mserver s']);
		    }])
		    ->where(["d.user_id" => $user_info->id])
		    ->all();

            if (isset($_GET['options'])) {
                if (\Yii::$app->request->get('options') == 'all') {

                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ')
                    );

                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'cameras' => $user_cams, 'user_id' => $user_info['id'], 'note' => '5554']);

                }
    //======================================================ADD CAMERA(BIND DEVICE)====================================================
//
                if (\Yii::$app->request->get('options') == 'all/addcamera') {
                    $device = New Devices();
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ'),
                        '2st' => \Yii::t('commonEn', 'ДОБАВИТЬ КАМЕРУ')
                    );
                    if(!empty(\Yii::$app->request->post()))
                    {
                        $data = \Yii::$app->request->post();

                        $device_bd = Devices::findOne(['code'=>$data['Devices']['code']]);
                        if(empty($device_bd)){
                            return $this->render('mycameras',['pathinfo'=>$pathinfo, 'cameras' => $camera, 'model_device'=>$device, 'user_id' => $user_info['id'], 'note'=>'error','message'=>'There is no device with that ID']);
                        }
//                        debug($device_bd->user_id)
                        if($device_bd->user_id == null){
                            $user=$this->getUser();
//                            \debug($user->id);
                            $device_bd->user_id = $user->id;
//                            \debug($data['Devices']['description']);
                            if(!empty($data['Devices']['description'])){
                                $device_bd->description = $data['Devices']['description'];
                            }
                            if(!$device_bd->save()){
                                $device_bd->getErrors();
                            }
                            $pathinfo = array(
                                'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                                '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ'),
                            );
                            return $this->render('mycameras',['pathinfo'=>$pathinfo, 'cameras' => $camera, 'model_device'=>$device, 'user_id' => $user_info['id'], 'note'=>'added']);
                        }else{
                            return $this->render('mycameras',['pathinfo'=>$pathinfo, 'cameras' => $camera, 'model_device'=>$device, 'user_id' => $user_info['id'], 'note'=>'error','message'=>'Device already binded']);
                        }
                    }
                    return $this->render('mycameras',['pathinfo'=>$pathinfo, 'model_device'=>$device, 'user_id' => $user_info['id'], 'note'=>'']);
                }
//=========================================================WATCH ONLINE================================================
                if (preg_match("/all\/online\/id/", \Yii::$app->request->get('options'))) {
                    require JWTCONF;
                    $getjwt = substr(strstr(\Yii::$app->request->get('options'), 'id='), 3);
                    $cam_id = $jwt->decode($getjwt, $key, array('HS256'));
//                    debug($cam_id->data->id);
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ'),
                        '2st' => \Yii::t('commonEn', 'ПРОСМОТР ОНЛАЙН')
                    );

	            $user_info=$this->getUser();

	            $device_id = Cameras::find()->select(['device_id'])->where(['id'=>$cam_id->data->id])->one();
		    $mserver_id = Devices::find()->select(['mserver_id'])->where(['id'=>$device_id->device_id])->one();
	            $Mserv_info = MediaServer::find()->where(['id'=>$mserver_id->mserver_id])->one();

		    $key = \Yii::$app->request->cookies;
		    $key = $key->getValue('trust','trustisnotset');
		    $Mserv_info->port++;
		    $video_url='https://'.$Mserv_info->address.':'.$Mserv_info->port.'/live-hls/'.$user_info->id.'_'.$cam_id->data->id.'/index.m3u8?key='.$key.'';

		    return $this->render('watchcamera',['video_url'=>$video_url]);




//                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'cam_id' => $cam_id->data->id, 'note' => '']);
                }

//======================================================EDIT CAMERA====================================================
                if (preg_match("/all\/edit\/id/", \Yii::$app->request->get('options'))) {
                    require JWTCONF;
                    $getjwt = substr(strstr(\Yii::$app->request->get('options'), 'id='), 3);
                    $cam_id = $jwt->decode($getjwt, $key, array('HS256'));
                    $camera = new Cameras();
                    $camera_bd = $camera::findOne($cam_id->data->id);
                    if (!empty(\Yii::$app->request->post())) {

                        foreach (\Yii::$app->request->post('Cameras') as $key => $val) {
                            $data[$key] = clean_data($val);
                        }
//                        $camera_bd->id_camera = $data['id_camera'];
//                        $camera_bd->device_id = $data['device_id'];
                        $camera_bd->title = $data['title'];
//                        $camera_bd->model = $data['model'];
//                        $camera_bd->type = $data['type'];
                        $camera_bd->description = $data['description'];
                        if (!$camera_bd->save()) {
                            var_dump($camera_bd->getErrors());
                            exit;
                        }
                        $this->redirect(DOMAIN . '/user/mycameras?options=all/edited');
                    }
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ'),
                        '2st' => \Yii::t('commonEn', 'РЕДАКТИРОВАНИЕ')
                    );
                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'model_camera' => $camera, 'devices' => $devices, 'camera_bd' => $camera_bd, 'user_id' => $user_info['id'], 'note' => '']);
                }
//======================================================ARCHIVE=========================================================
                if (preg_match("/all\/archive\/id/", \Yii::$app->request->get('options'))) {
                    require JWTCONF;
                    $getjwt = substr(strstr(\Yii::$app->request->get('options'), 'id='), 3);
                    $cam_id = $jwt->decode($getjwt, $key, array('HS256'));
                    $record_bd = Records::find()->where(['camera_id' => $cam_id->data->id])->all();
                    if(empty($record_bd)){
                        $pathinfo = array(
                            'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                            '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ'),
                        );
                        return $this->render('mycameras', ['pathinfo' => $pathinfo, 'cameras' => $camera, 'note' => 'empty']);
                    }
//                    debug(count($record_bd));
//                    exit;
                    for ($i = 0; $i <= count($record_bd) - 1; $i++) {
                        $titles[$i] = [
                            "id" => $record_bd[$i]['id'],
                            "title" => $record_bd[$i]['title'],
                            "name" => $record_bd[$i]['file_name'],
                            "date" => stristr($record_bd[$i]['datetime'], ' ', true),
                            "time" => stristr($record_bd[$i]['datetime'], ' ')
                        ];
                    }
                    foreach ($titles as $key => $arr) {
                        $dateArray[$key] = $arr['date'];
                    }
                    array_multisort($dateArray, SORT_STRING, $titles);
                    $titles = array_reverse($titles);
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ'),
                        '2st' => \Yii::t('commonEn', 'АРХИВ')
                    );
                    if (!empty(\Yii::$app->request->post('check_issue_date')))
                    {

                        $checkdate = (\Yii::$app->request->post('check_issue_date'));
                        return $this->render('mycameras', [
                                                                    'pathinfo' => $pathinfo,
                                                                    'model_camera' => $camera,
                                                                    'devices' => $devices, 'camera_bd' => $camera_bd,
                                                                    'titles' => $titles, 'note' =>'',
                                                                    'checkdate'=> $checkdate
                        ]);
                    }
                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'model_camera' => $camera, 'devices' => $devices, 'camera_bd' => $camera_bd, 'titles' => $titles, 'note' => '']);
                }
//======================================================DELETE=========================================================
                if (preg_match("/all\/delete\/id/", \Yii::$app->request->get('options'),$matches))
                {
                    require JWTCONF;
                    $getjwt = substr(strstr(\Yii::$app->request->get('options'), 'id='), 3);
//                    var_dump($getjwt);
//                    exit;
                    $cam_id = $jwt->decode($getjwt, $key, array('HS256'));
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ')
                    );
                    return $this->render('mycameras', [
                                                                'pathinfo' => $pathinfo,
                                                                'cameras' => $camera,
                                                                'model_camera' => $camera,
                                                                'devices' => $devices,
                                                                'camera_bd' => $camera_bd,
                                                                'camjwt'=>$getjwt,
								'user_id' => $user_info['id'],
                                                                'note' =>'delete'
                    ]);
                }
                if (preg_match("/all\/delete\/yes\/id/", \Yii::$app->request->get('options'),$matches))
                {
                    require JWTCONF;
                    $getjwt = substr(strstr(\Yii::$app->request->get('options'), 'id='), 3);
                    $cam_id = $jwt->decode($getjwt, $key, array('HS256'));
                    $camerafordel = Cameras::findOne([$cam_id->data->id]);
                    if(!$camerafordel->delete())
                    {
                        $camerafordel->getErrors();
                    }
                    header('Location: '.DOMAIN.'/user/mycameras?options=all/deleted');
                }

                if (\Yii::$app->request->get('options') == 'all/edited') {
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ')
                    );
                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'cameras' => $camera, 'user_id' => $user_info['id'], 'note' => 'edited']);
                } elseif (\Yii::$app->request->get('options') == 'all/added') {
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ')
                    );
                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'cameras' => $camera, 'user_id' => $user_info['id'], 'note' => 'added']);
                }elseif (\Yii::$app->request->get('options') == 'all/deleted')
                {
                    $pathinfo = array(
                        'base' => \Yii::t('commonEn', 'МОИ КАМЕРЫ'),
                        '1st' => \Yii::t('commonEn', 'ВСЕ КАМЕРЫ')
                    );
                    return $this->render('mycameras', ['pathinfo' => $pathinfo, 'cameras' => $camera, 'user_id' => $user_info['id'], 'note' => 'deleted']);
                }

                echo ' sadfasdf';
                exit;
                return $this->render('mycameras');
            } else {
                throw new NotFoundHttpException(\Yii::t('yii', 'Page not found.'));
            }

        } else {
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

//============================================================EVENTS---PAGE=====================================

    public function actionEvents()
    {

        if(!($this->actionIsAdmin()) && $this->logincheck()){
    // if(1==1) {

        //из jwt берем юзер ид, по нему находим устройства для этого пользователя, по устройствам находим камеры по камерам находим видеозаписи.
	    $jwt = new JWT;
         $model = new Filter();
            require JWTCONF;
            $user_info = $this->getUser();
            // $user_info = \app\models\User::findOne(['id' => 10]);

// Нужно переделать запрос на такой
	    $sql123 = 'SELECT records.id as id,records.title as title, records.file_name as name , records.camera_id as camera_id, date(records.datetime) as date, time(records.datetime) as time, media_server.address as mserver, media_server.port+1 as mport FROM `records`,`cameras`,`devices`,`media_server` WHERE records.camera_id=cameras.id and cameras.device_id=devices.id and devices.mserver_id=media_server.id and devices.user_id = '.$user_info->id.' order by date,time';
//

            $dev_bd = Devices::find()->where(['user_id'=>$user_info->id])->all();

            $user_cameras_db = array();

            for($i=0; $i<=count($dev_bd)-1;$i++)
            {
                array_push($user_cameras_db,Cameras::find()->select('id')->where(['device_id'=>$dev_bd[$i]['id']])->all());
            }
//                $user_cameras[] = array();
		foreach ($user_cameras_db as $array) {
                    foreach ($array as $key => $val) {
                        $user_cameras[] = $val;
                    }
                }
//	    debug($user_cameras);
            $rec_list_db=array();
//    	    if(!isset($user_cameras)){return $this->render('events',['records' => null, "allrecords" => null,"filtdate"=>null,'titles'=>null]);}

        if(!isset($user_cameras)){$user_cameras=[];};
	    if(!isset($user_cameras)){$user_cameras=[];};
	    for($i=0; $i<=count($user_cameras)-1;$i++)
            {
                array_push($rec_list_db,Records::find()->where(['camera_id'=>$user_cameras[$i]['id']])->all());
            }
            $rec_list=[];
            if (!empty($rec_list_db)) {
                $rec_list = call_user_func_array('array_merge',$rec_list_db);
            }
//            debug($rec_list);
            $records = new Records();
            $rec_list = Records::find()->all();
            $cur_rec = [];
            for ($i = 0; $i <= count($rec_list) - 1; $i++) {
                $titles[$i] = [
                    "id" => $rec_list[$i]['id'],
                    "title" => $rec_list[$i]['title'],
                    "name" => $rec_list[$i]['file_name'],
                    "date" => stristr($rec_list[$i]['datetime'], ' ', true),
                    "time" => stristr($rec_list[$i]['datetime'], ' '),
		    "camera_id" => $rec_list[$i]['camera_id']
//		    "device_id" => $rec_list[$i]['camera_id'] -> camera -> device_id
                ];
            }

            if (isset($titles)) {
                foreach ($titles as $key => $arr) {
                    $dateArray[$key] = $arr['date'];
                }
                array_multisort($dateArray, SORT_STRING, $titles);
                $titles = array_reverse($titles);
            } else {
                $titles = [];
            }



            if (isset($_POST['date'])) {
            // $controldate = strtotime($_POST['date']);
            $filterdate = strtotime($_POST['date']);
//		debug (strtotime('16-09-2020'));
            }else{
                $filterdate = null;
            }

// new query
	    if (!$filterdate){

		$user_recs = Records::find()
					->joinWith(["camera c" => function($q) {
    					    $q->joinWith(['device d' => function($q1) {
    						$q1->joinWith(['mserver s']);
					    }]);
					}])
					->where(["d.user_id" => $user_info->id])
					->orderby("datetime DESC")
					->all();
	    }
	    else {
		$user_recs = Records::find()
					->joinWith(["camera c" => function($q) {
    					    $q->joinWith(['device d' => function($q1) {
    						$q1->joinWith(['mserver s']);
					    }]);
					}])
//					->where(["d.user_id" => $user_info->id, "UNIX_TIMESTAMP(date(datetime))" => $filterdate ])
					->where(["d.user_id" => $user_info->id])
					->orderby("datetime DESC")
					->all();
//		debug($filterdate);
	    }
//	    debug ($user_recs);
	    $titles1 = array();
        $filter=new Filter();
        if ($filterdate) {
            $j=0;
            for ($i = 0; $i <= count($user_recs) - 1; $i++){
               // if(!isset($_POST['Filter'])){
                      $titles1[$j] = [
                          "id" => $user_recs[$i]['id'],
                          "title" => $user_recs[$i]['title'],
                          "name" => $user_recs[$i]['file_name'],
                          "date" => stristr($user_recs[$i]['datetime'], ' ', true),
                          "time" => stristr($user_recs[$i]['datetime'], ' '),
                         "camera_id" => $user_recs[$i]['camera_id'],
                         "device_id" => $user_recs[$i]['camera'] -> device_id,
                         "s_address" => $user_recs[$i]['camera'] -> device -> mserver -> address,
                         "s_port" => $user_recs[$i]['camera'] -> device -> mserver -> port + 1
                      ];
                      $j++;
                // }
          }
        } elseif(isset($_POST['Filter'])){
            $j=0;
            if($_POST['Filter']['time_s']!='' && $_POST['Filter']['time_po']!=''  && $_POST['Filter']['searchtitle']!=''){
                        $time_s=clean_data($_POST['Filter']['time_s']);
                        $time_po=clean_data($_POST['Filter']['time_po']);
                        $searchtitle=clean_data($_POST['Filter']['searchtitle']);
                  for ($i = 0; $i <= count($user_recs) - 1; $i++){
                     if(strtotime($user_recs[$i]['datetime'])>=strtotime($time_s) && strtotime($user_recs[$i]['datetime'])<=strtotime($time_po) && !empty(strstr($user_recs[$i]['title'],$searchtitle))){
                            $titles1[$j] = [
                                "id" => $user_recs[$i]['id'],
                                "title" => $user_recs[$i]['title'],
                                "name" => $user_recs[$i]['file_name'],
                                "date" => stristr($user_recs[$i]['datetime'], ' ', true),
                                "time" => stristr($user_recs[$i]['datetime'], ' '),
                               "camera_id" => $user_recs[$i]['camera_id'],
                               "device_id" => $user_recs[$i]['camera'] -> device_id,
                               "s_address" => $user_recs[$i]['camera'] -> device -> mserver -> address,
                               "s_port" => $user_recs[$i]['camera'] -> device -> mserver -> port + 1
                            ];
                            $j++;
                      }
                }
           }
           if($_POST['Filter']['time_s']!='' && $_POST['Filter']['time_po']!=''){
                $time_s=clean_data($_POST['Filter']['time_s']);
                $time_po=clean_data($_POST['Filter']['time_po']);
                 for ($i = 0; $i <= count($user_recs) - 1; $i++){
                    if(strtotime($user_recs[$i]['datetime'])>=strtotime($time_s) && strtotime($user_recs[$i]['datetime'])<=strtotime($time_po)){
                           $titles1[$j] = [
                               "id" => $user_recs[$i]['id'],
                               "title" => $user_recs[$i]['title'],
                               "name" => $user_recs[$i]['file_name'],
                               "date" => stristr($user_recs[$i]['datetime'], ' ', true),
                               "time" => stristr($user_recs[$i]['datetime'], ' '),
                              "camera_id" => $user_recs[$i]['camera_id'],
                              "device_id" => $user_recs[$i]['camera'] -> device_id,
                              "s_address" => $user_recs[$i]['camera'] -> device -> mserver -> address,
                              "s_port" => $user_recs[$i]['camera'] -> device -> mserver -> port + 1
                           ];
                           $j++;
                     }
               }
          }
           if($_POST['Filter']['searchtitle']!=''){
                   $searchtitle=clean_data($_POST['Filter']['searchtitle']);
                    for ($i = 0; $i <= count($user_recs) - 1; $i++){
                       if(!empty(strstr($user_recs[$i]['title'],$searchtitle))){
                             $titles1[$j] = [
                                 "id" => $user_recs[$i]['id'],
                                 "title" => $user_recs[$i]['title'],
                                 "name" => $user_recs[$i]['file_name'],
                                 "date" => stristr($user_recs[$i]['datetime'], ' ', true),
                                 "time" => stristr($user_recs[$i]['datetime'], ' '),
                                "camera_id" => $user_recs[$i]['camera_id'],
                                "device_id" => $user_recs[$i]['camera'] -> device_id,
                                "s_address" => $user_recs[$i]['camera'] -> device -> mserver -> address,
                                "s_port" => $user_recs[$i]['camera'] -> device -> mserver -> port + 1
                             ];
                             $j++;
                       }
                    }
           }
           if($_POST['Filter']['time_s']=='' && $_POST['Filter']['time_po']==''  && $_POST['Filter']['searchtitle']==''){
                    for ($i = 0; $i <= count($user_recs) - 1; $i++){
                             $titles1[$i] = [
                                 "id" => $user_recs[$i]['id'],
                                 "title" => $user_recs[$i]['title'],
                                 "name" => $user_recs[$i]['file_name'],
                                 "date" => stristr($user_recs[$i]['datetime'], ' ', true),
                                 "time" => stristr($user_recs[$i]['datetime'], ' '),
                                "camera_id" => $user_recs[$i]['camera_id'],
                                "device_id" => $user_recs[$i]['camera'] -> device_id,
                                "s_address" => $user_recs[$i]['camera'] -> device -> mserver -> address,
                                "s_port" => $user_recs[$i]['camera'] -> device -> mserver -> port + 1
                             ];
                    }
           }
        }else {
    	    for ($i = 0; $i <= count($user_recs) - 1; $i++) {
                        $titles1[$i] = [
                            "id" => $user_recs[$i]['id'],
                            "title" => $user_recs[$i]['title'],
                            "name" => $user_recs[$i]['file_name'],
                            "date" => stristr($user_recs[$i]['datetime'], ' ', true),
                            "time" => stristr($user_recs[$i]['datetime'], ' '),
                		    "camera_id" => $user_recs[$i]['camera_id'],
                		    "device_id" => $user_recs[$i]['camera'] -> device_id,
                		    "s_address" => $user_recs[$i]['camera'] -> device -> mserver -> address,
                		    "s_port" => $user_recs[$i]['camera'] -> device -> mserver -> port + 1
                        ];
                }
        }
//	    debug($titles1);
//
//---------------
            //return $this->render('events', ['records' => $records, "allrecords" => $allrecords,"filtdate"=>$filterdate,'titles'=>$titles, 'user_id' => $user_info['id']]);
	    return $this->render('events', ["filtdate"=>$filterdate,'titles'=>$titles1, 'user_id' => $user_info['id'],'created_at' => $user_info['created_at'],'model'=>$model]);
        }else{
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    public function actionWatchcamera()
    {
        //if(!($this->actionIsAdmin()) && $this->logincheck())
    if(1==1) {
            $jwt = new JWT;
            require JWTCONF;
            $user_info=$this->getUser();


            $id = $jwt->decode(\Yii::$app->getRequest()->get('id'),$key,array('HS256'));
            $recdata = Records::find()->where(['id'=>$id->data->id])->one();
//            $recdata->id;
            $camera_id = Records::find()->select(['camera_id'])->where(['id'=>$recdata->id])->one();
//            $camera_id->camera_id
            $device_id = Cameras::find()->select(['device_id'])->where(['id'=>$camera_id->camera_id])->one();
	    $mserver_id = Devices::find()->select(['mserver_id'])->where(['id'=>$device_id->device_id])->one();
            $Mserv_info = MediaServer::find()->where(['id'=>$mserver_id->mserver_id])->one();

	    $key = \Yii::$app->request->cookies;
	    $key = $key->getValue('trust','trustisnotset');
	    $video_url='https://'.$Mserv_info->address.':1936/vod/'.$user_info->id.'/'.$camera_id->camera_id.'/'.$recdata->file_name.'/index.m3u8?key='.$key.'';

//	    return $this->render('watchcamera',['recdata'=>$recdata,'camera_id'=>$camera_id->camera_id,'user_id'=>$user_info->id,'Mserv_info'=>$Mserv_info]);
	    return $this->render('watchcamera',['video_url'=>$video_url]);

        }else {
            throw new ForbiddenHttpException(\Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }


    public function actionExit()
    {
        $this->actionLogout();
    }

}
