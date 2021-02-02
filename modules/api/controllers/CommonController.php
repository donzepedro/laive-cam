<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 09.06.2020
 * Time: 11:29
 */

namespace app\modules\api\controllers;


use Firebase\JWT\JWT;
use app\modules\api\models\User;

class CommonController extends \yii\web\Controller
{
    public function JwtCheck($token)
    {
        $jwtobj = NEW JWT;
        require JWTCONF;
        $userinfo = $jwtobj->decode($token, $key, array('HS256'));
        return $userinfo;
    }

    public function isAdmin($token)
    {
        $jwtobj = NEW JWT;
        require JWTCONF;
        $userinfo = $jwtobj->decode($token, $key, array('HS256'));
        $user = User::find()->where(['id'=>$userinfo->data->id])->all();
//        $isAdmin = $user[0]['isAdmin'] == '1' ? '1' : '0';
//        return $isAdmin;
        return $user['0']['isAdmin'];
    }


    public function ExtraField($bodyparams)
    {
        foreach ($bodyparams as $k => $v)
        {
            if (preg_match("~^^_[a-zA-Z0-9]+$~i", $k)) {
                $newmass[$k] = $v;
            }
        }
        return $newmass=isset($newmass)?$newmass:null;
    }

    public function DeviceJwt($devicejwt){
        $jwtobj = NEW JWT;
        require JWTCONF;
        $token = $jwtobj->decode($devicejwt,$key,array('HS256'));
        if((isset($token->code) && isset($token->guid))){
            return $token;
        }else{
//            return array(
//                "Status"=>"Failed",
//                "Message" => "jwt is wrong!",
//            );
            return 0;
        }
    }
    public function returnMessage($text,$extrafield,$status,$third = ''){
        if ($third != ''){
            $message = array(
                "Status" => $status,
                "Message" => $text,
                "Additional info" => $third
            );
        }else {
            $message = array(
                "Status" => $status,
                "Message" => $text,
            );
        }
        if($extrafield != null) return array_merge($message,$extrafield); else return $message;

    }
}