<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 08.04.2020
 * Time: 17:23
 */

namespace app\controllers;

use yii;
//use app\models\testmodel;
use app\models\User;
use app\models\Pswdrestore;
use app\models\Pswdrestoreform;
use yii\web\Controller;
use app\models\LogForm;
use app\models\RegForm;
use Firebase\JWT\JWT;
use yii\web\ForbiddenHttpException;

class GuestController extends CommonController
{

    public $layout='guestlayout';
    public $result;
    public $respinfo;


    private function PostRequest($request_url, $post_data)
    {
        $out = '';

        if ($curl = curl_init()) {
            curl_setopt($curl, CURLOPT_URL, $request_url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
            $out = curl_exec($curl);
            if (!$out) {
                \Yii::$app->response->statusCode = 212;
                $this->errorMSG = 'FAIL: curl_exec()';
                return FALSE;
            }
            $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($status_code != 200 and $status_code != 302) {
                \Yii::$app->response->statusCode = 212;
                $this->errorMSG = 'Response with Status Code [' . $status_code . '].';
                return FALSE;
            }
            curl_close($curl);
        } else {
            \Yii::$app->response->statusCode = 212;
            $this->errorMSG = 'FAIL: curl_init()';
            return FALSE;
        }
        $this->result = $out;
        \Yii::$app->response->statusCode = 200;
        return true;
    }
    public function getIPAddress() {
        //whether ip is from the share internet
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from the proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from the remote address
        else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function actionIndex()
    {
//        $bd = User::find()->orderBy('name')->all();
//        print_r($bd);
        return $this->render('guest');
    }

    public function actionHitw()
    {
        return $this->render('hitwen');
//        return $this->render('hitwru');
    }

    public function actionSolutions()
    {
//        $testmodel = new testmodel();
        return $this->render('solutions');
    }

    public function actionDownload()
    {
        return $this->render('download');
    }

    public function actionLogin()
    {
        $logform = new LogForm();
        $note = '';
        // if($this->logincheck() && ($this->actionIsAdmin()))
        // {
        //     return $this->redirect(DOMAIN . '/admin/adminprofile');
        // }elseif ($this->logincheck() && (!$this->actionIsAdmin())){
        //     return $this->redirect(DOMAIN . '/user/events');
        // }else {
            $jwt = new JWT;
            $response = array();
            if (isset($_POST['LogForm'])) {
                if ((!empty($_POST['LogForm']['login'])) && (!empty($_POST['LogForm']['login']))) {
                    $request_url = 'https://videolivejournal.com/getjwt/givetoken';
                    $post_data['login'] = clean_data($_POST['LogForm']['login']);
                    $post_data['password'] = passencode(clean_data($_POST['LogForm']['password']));
                    if ($this->PostRequest($request_url, $post_data)) {
                        $response = json_decode($this->result);
			if (isset($response->token)) {
                            if ($this->actionIsAdmin($response->token))
                            {
                                if($this->actionIsActivated($response->token))
                                {
                                    \Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'trust', 'value' => $response->token]));
                                    return $this->redirect(DOMAIN . '/admin/adminprofile');
                                }else{
                                    $note = 'no';
                                    return $this->render('login', ['logform'=>$logform,'note'=>$note]);
                                }
                            } else {
                                if($this->actionIsActivated($response->token))
                                {
                                \Yii::$app->response->cookies->add(new \yii\web\Cookie(['name' => 'trust', 'value' => $response->token]));
                                $user = \app\models\User::findOne(['email'=>clean_data($_POST['LogForm']['login'])]);
                                $user['ip']=$this->getIPAddress();
                                $user->save();
                                return $this->redirect(DOMAIN . '/user/events');
                                }else{
                                    $note = 'no';
                                    return $this->render('login', ['logform'=>$logform,'note'=>$note]);
                                }
                            }

                        } elseif ((isset($response->error_code)) && ($response->error_code == 403)) {
                            $note = 'nouser';
                            $logform->login=clean_data($post_data['login']); $logform->password=clean_data($_POST['LogForm']['password']);
                            return $this->render('login', ['logform' => $logform, 'message' => $response->message, 'note' => $note]);
                        } else {
                            echo 'Unknown ERROR';
                            Yii::$app->response->statusCode = 404;
                        }
                    }
                }
            } else {
                if((isset($_GET['act'])) && ($_GET['act'] == 'yes'))
                    {
                        $note='yes';
                    }
                if((isset($_GET['newpswd'])) && ($_GET['newpswd'] == 'yes'))
                {
                    $note='newpswd';
                }
                return $this->render('login', ['logform'=>$logform,'note'=>$note]);
            }
        // }
    }


    public function actionCreateaccount()
    {
        $regform = new RegForm();
        $jwt = new JWT;
        $response = array();
        if(isset($_POST['RegForm']))
        {
            if(isset($_POST['RegForm']['email']))
            {
                $email = clean_data(($_POST['RegForm']['email']));
                $data = array();
                if(!(regform::find()->where(['email'=>$email])->all())){
                    foreach ($_POST['RegForm'] as $key => $value){
                        $data[$key] = clean_data($value);
                    }
                    require JWTCONF;
                    $token = array(
                        "iss" => $iss,
                        "aud" => $aud,
                        "iat" => $iat,
                        "nbf" => $nbf,
                        "data" => array(
                            "name" => $data['name'],
                            "email" => $data['email']
                        )
                    );
                    $usersave = new RegForm();
                    $usersave->email=$data['email'];
                    $usersave->password=passencode($data['password']);
                    $usersave->name=$data['name'];
                    $usersave->phone=$data['phone'];
                    $usersave->subscribe = $data['newsagree'];
                    $usersave->ActivateStatus = strstr($jwt->encode($token,$key),'.',true);
                    if(!$usersave->save()){
                        var_dump($usersave->getErrors());
                    }
                    $usersave->sendername = 'laivecam.com';
                    $usersave->subject = Yii::t('commonEn','Подверждение пароля');
                    $usersave->body = yii::t('commonEn','Для подтверждения адреса электронной почты, перейдите по данной ссылке:'). DOMAIN .'/guest/activate?Authkey='.$jwt->encode($token,$key);
//                    if (Yii::$app->language == 'ru')
//                    {
//
//                        $usersave->subject = 'Подверждение пароля';
//                        $usersave->body = 'Для подтверждения адреса электронной почты, перейдите по данной ссылке: http://webcam.loc/guest/activate?Authkey='.$jwt->encode($token,$key);
//                    }
//                    if (Yii::$app->language == 'en')
//                    {
//
//                        $usersave->subject = 'Подверждение пароля';
//                        $usersave->body = 'Для подтверждения адреса электронной почты, перейдите по данной ссылке: http://webcam.loc/guest/activate?Authkey='.$jwt->encode($token,$key);
//                    }

                    if(!($usersave->contact($data['email'])))
                    {
                        var_dump($usersave->getErrors());
                    }
                    return $this->redirect(DOMAIN . '/guest/login?act=yes');

//                    $data=$_POST['RegForm'];
//                    debug($data);
                }else{
                    $message = Yii::t('commonEn','Пользователь с таким email уже существует');
                    return $this->render('createaccount',compact('regform', 'message'));
//                    return $this->redirect(DOMAIN . '/guest/createaccount');
                }
//                var_dump($data);
            }


//            debug($data);
//            die;
        }
        return $this->render('createaccount',compact('regform', 'message'));
    }

    public function actionPswdrestore(){
        $pswdrestore = new Pswdrestore();
        $pswdrestoreform = new Pswdrestoreform();
        $jwt = new JWT;
        require JWTCONF;
        if(isset($_GET['reset']))
        {
            try
            {
                $token = $jwt->decode($_GET['reset'],$key,array('HS256'));
                if(isset($_POST['pswdrestoreform']))
                {
//                    debug($_POST['pswdrestoreform']);
//                    die;
                    if((clean_data($_POST['pswdrestoreform']['newpassword'])) == (clean_data($_POST['pswdrestoreform']['confirmpassword'])))
                    {
                        $user = User::findOne(['email' => $token->data->email]);
                        $user->password = passencode(clean_data($_POST['pswdrestoreform']['newpassword']));
                        $user->save();
                        return $this->redirect(DOMAIN . '/guest/login?newpswd=yes');
                    }
                }

                return $this->render('pswdrestore',['pswdrestoreform'=>$pswdrestoreform,'note'=>'newpswd']);
            }catch (\Exception $e){
                return $this->render('pswdrestore',['pswdrestore'=>$pswdrestore,'note'=>'wronglink']);
            }



        }

        if(isset($_POST['pswdrestore']))
        {
           if($useremail = User::findOne(['email' => $_POST['pswdrestore']['email']]))
           {

               $token = array(
                   "iss" => $iss,
                   "aud" => $aud,
                   "iat" => $iat,
                   "nbf" => $nbf,
                   "data" => array(
                       "name" => $useremail['name'],
                       "email" => $useremail['email']
                   )
               );
                $token= $jwt->encode($token,$key);
               $pswdrestore->email = $useremail['email'];
               $pswdrestore->sendername = 'laivecam.com';
               $pswdrestore->subject = 'Restore password link';
               $pswdrestore->body = 'Restore password link:' . DOMAIN .'/en/guest/pswdrestore?reset='.$token;
               if(!($pswdrestore->contact($useremail['email'])))
               {
                   debug($pswdrestore->getErrors());

               }
               return $this->render('pswdrestore',['pswdrestore'=>$pswdrestore,'note'=>'linkonemail']);
           }else{
               return $this->render('pswdrestore',['pswdrestore'=>$pswdrestore,'note'=>'nouser']);
           }
        }
        return $this->render('pswdrestore',['pswdrestore'=>$pswdrestore,'note'=>'']);
    }

    public function actionActivate()
    {
        $jwt = new JWT;
        require JWTCONF;
        try {
            $data = $jwt->decode(clean_data($_GET['Authkey']), $key, array('HS256'));
        }catch (\Exception $e){
            $errormessage = ' Your Authkey is invalid';
            return $this->render('activate',compact('errormessage'));
        }

        if($user = user::findOne(['name'=>$data->data->name,'email'=>$data->data->email]))
        {
           $user->ActivateStatus = 'Activated';
           $user->save();
            return $this->redirect(DOMAIN . '/guest/login');
        }

        return $this->render('activate');
    }

    public function actionAgreement()
    {
        return $this->render('agreement');
    }
}
