<?php
/**
 * Created by PhpStorm.
 * User: SRT
 * Date: 19.05.2020
 * Time: 5:59
 */

namespace app\controllers;
use Yii;


class ApiController extends CommonController // - это мой контроллер который наследует контроллер Yii2.
                                            //  в CommonController я просто добавил коекакие свои методы.
{
    public function actionGetdevicelist()
    {

        $req=Yii::$app->request;
        $post = $req->method;
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; - когда юзаю это то в ответе слешами экранируются кавычки json-a
        return json_encode(array(
            "text"=>'test',
            "test" => $post
        ));
    }

}