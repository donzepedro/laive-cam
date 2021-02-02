<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use Firebase\JWT\JWT;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
$this->registerCssFile('/css/user/mycameras.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);
$this->registerJsFile('/js/myfunc.js',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);
$jwt=new JWT;
//debug($pathinfo);

if(($note == 'edited') || ($note == 'added')||($note == 'error') || ($note == 'deleted') || ($note == 'empty')) {
    $modal = Modal::begin([
        'class'=>'note',
        'header' => Yii::t('commonEn','Уведомление'),
//      'toggleButton' => ['label' => 'click me'],
        'footer' => '<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" > Ok </button>',
        'clientOptions' => ['show' => true],
    ]);
    if($note == 'edited')
    {
        echo Yii::t('commonEn','Информация о камере успешно изменена');
    }elseif($note == 'added'){
        echo Yii::t('commonEn','Камера успешно добавлена');
    }elseif($note == 'error'){
        print_r($message);
//        echo Yii::t('commonEn','Неверный Логин или Пароль');
    }elseif ($note == 'deleted'){
        echo Yii::t('commonEn','Камера успешно удалена');
    }elseif ($note == 'empty'){
        echo Yii::t('commonEn','Нет видеозаписей для этой камеры');
    }
    Modal::end();
}
if(($note == 'delete')){
    $modal = Modal::begin([
        'class'=>'note',
        'header' => Yii::t('commonEn','Уведомление'),
//      'toggleButton' => ['label' => 'click me'],
        'footer' => '<button onclick="redirect()" class="btn btn-primary" data-dismiss="modal" aria-hidden="true" > '.Yii::t('commonEn','Отменить').' </button>
                        <button  class="btn btn-primary" ><a style="text-decoration: none;color: white" href="'.DOMAIN.'/user/mycameras?options=all/delete/yes/id='.$camjwt.'"> '.Yii::t('commonEn','OK').' </a></button>',
        'clientOptions' => ['show' => true],
    ]);
    echo Yii::t('commonEn','Удалить?');
    Modal::end();
}


?>



<div class="nav">
    <a href="<?= DOMAIN ?>/user/mycameras?options=all"><?=\Yii::t('commonEn','Мои камеры')?> </a>
    >
    <a class="btn btn-primary" href="<?= DOMAIN ?>/user/mycameras?options=all/addcamera"><?=\Yii::t('commonEn','Добавить камеру')?> </a>
</div>

<!--=====================================WATCH ONLINE=====================================================================-->
<?php if (isset($pathinfo['2st']) and (($pathinfo['2st'] == 'ПРОСМОТР ОНЛАЙН') or ($pathinfo['2st'] == 'WATCH CAMERA') )): ?>
<!--    --><?php //debug($cameras) ?>
    <div class="online">
         camera id = <?= $cam_id ?>
        <img class="playerwindow" src="<?= DOMAIN ?>/public/images/minplayerimg.png">
    </div>


    <!--=====================================  EDIT ======================================================================-->
<?php elseif (isset($pathinfo['2st']) and (($pathinfo['2st'] == 'РЕДАКТИРОВАНИЕ') or ($pathinfo['2st'] == 'EDIT') )): ?>
<div class="myform">

    <?php  $form = ActiveForm::begin(['options'=>['id'=>'cam-edit'], 'method'=>'post', 'action'=>''])?>
    <?php   $items = ArrayHelper::map($devices,'id','id'); ?>
    <div class="fields">
         <div class="field">
            <?= $form->field($model_camera,'title')->textInput(['value'=>$camera_bd->title])->label(\Yii::t('commonEn','Название'),['class'=>'lables']) ?>
         </div>
         <div class="field">
            <?= $form->field($model_camera,'description')->textarea(['value'=>$camera_bd->description])->label(\Yii::t('commonEn','Описание'),['class'=>'lables']) ?>
         </div>
    </div>
    <div class="mybtn">
        <?= Html::submitButton(Yii::t('commonEn', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end()?>
    <div class="mybtn">
        <a href="<?= DOMAIN ?>/user/mycameras?options=all"><button class="btn btn-primary"><?=Yii::t('commonEn', 'Отменить')?> </button></a>
    </div>
</div>



    <!--===================================== ADD CAMERA ======================================================================-->
<?php elseif (isset($pathinfo['2st']) and (($pathinfo['2st'] == 'ДОБАВИТЬ КАМЕРУ') or ($pathinfo['2st'] == 'ADD CAMERA') )): ?>
        <div class="myform-binddev">
            <?php $form = ActiveForm::begin(['options'=>['id'=>'bind-dev'], 'method'=>'post', 'action'=>''])?>
                <div style="float: left;">
                    <img width="320px" height="260px" src="<?= DOMAIN ?>/public/images/adddevsample.png">
                </div>
            <div style="overflow: hidden; margin-left: 27%">
                    <div class="field-binddev">
                        <?= $form->field($model_device,'code')->textInput(['placeholder'=>\Yii::t('commonEn','Введите ID устройства')])->label(\Yii::t('commonEn','Введите ID устройства'),['class'=>'lables']) ?>
                    </div>
                    <div class="field-binddev">
                        <?= $form->field($model_device,'description')->textInput(['placeholder'=>\Yii::t('commonEn','Камера 1')])->label(\Yii::t('commonEn','Название (произвольный текст)'),['class'=>'lables']) ?>
                    </div>
                    <div class="btns">
                            <?= Html::submitButton(Yii::t('commonEn', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
                    </div>
                    <div class="btns">
                            <?= Html::Button(Yii::t('commonEn', 'Тест'), ['class' => 'btn btn-success']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
                    <a href="<?= DOMAIN ?>/user/mycameras?options=all"><div class="btns">
                            <?= Html::button(Yii::t('commonEn', 'Отменить'), ['class' => 'btn btn-danger']) ?>
                    </div></a>
            </div>
        </div>
    <!--    ==========================================ARCHIVE===============================================================-->
<?php elseif (isset($pathinfo['2st']) and (($pathinfo['2st'] == 'АРХИВ') or ($pathinfo['2st'] == 'ARCHIVE') )): ?>
    <div class="datepickwrap">
        <div class="datepick">
            <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]) ?>
            <?php
            $i=1;

            if (Yii::$app->language == 'ru') $lang = 'ru';
            $lang = Yii::$app->language == 'ru' ? 'ru' : 'en';
            echo DatePicker::widget([
                'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
                'name' => 'check_issue_date',
                'language'=>$lang,
                'removeButton' => false,
                'size' => 'lg',
                'value' => date('d-m-Y'),
                'options' => [
                    'placeholder' => 'Select issue date ...',
                    'class'=>'date-field',
                ],
                'pluginOptions' => [
                    'autoclose'=>'true',
                    'format' => 'dd-mm-yyyy',
                    'todayHighlight' => true,
                ]
            ]);
            ?>
        </div>
        <div class="date-btn">
            <?= Html::submitButton('', ['class' => 'date-btn']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="reclistwrap">
        <div class="rec-head"><?= Yii::t('commonEn', 'АРХИВ'); ?> : </div>
        <span class="subject"># <?=Yii::t('commonEn', 'Тема'); ?></span>
        <span class="time"><?= Yii::t('commonEn', 'Время'); ?></span>
        <?php if(isset($checkdate)) :?>

            <?php for($i=1; $i<= count($titles);$i++):?>
                <?php if(strtotime($titles[$i-1]['date']) == strtotime($checkdate)):?>
                <a href="<?= DOMAIN ?>/user/watchcamera?id=<?php
                require JWTCONF;
                $token = array(
                    "iss" => $iss,
                    "aud" => $aud,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "data" => array(
                            "id" => $titles[$i-1]['id'],
                        )
                    );

                $id = $jwt->encode($token,$key)?><?=$id ?>">
                    <div class="eachwrap">
                        <div class="num">
                            <?= $i ?>
                        </div>
                        <div class="eachtitle">
                            <?= $titles[$i-1]['title']; ?>
                        </div>
                        <div class="eachdate">
                            <?= $titles[$i-1]['date']; ?>
                        </div>
                        <div class="eachname">
                            <?= $titles[$i-1]['name']; ?>
                        </div>
                        <div class="eachtime">
                            <?= $titles[$i-1]['time']; ?>
                        </div>
                    </div>
                </a>
                <?php endif; ?>
            <?php endfor;?>
        <?php else: ?>
        <div class="reclist">
            <?php for($i=1; $i<= count($titles);$i++):?>
                <a href="<?= DOMAIN ?>/user/watchcamera?id=<?php
                require JWTCONF;
                $token = array(
                    "iss" => $iss,
                    "aud" => $aud,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "data" => array(
                        "id" => $titles[$i-1]['id'],
                    )
                );

                $id = $jwt->encode($token,$key)?><?=$id ?>">
                    <div class="eachwrap">
                     <div class="num">
                         <?= $i ?>
                     </div>
                     <div class="eachtitle">
                         <?= $titles[$i-1]['title']; ?>
                     </div>
                     <div class="eachdate">
                         <?= $titles[$i-1]['date']; ?>
                     </div>
                     <div class="eachname">
                         <?= $titles[$i-1]['name']; ?>
                     </div>
                     <div class="eachtime">
                         <?= $titles[$i-1]['time']; ?>
                     </div>
             </div>
             </a>
            <?php endfor;?>
        </div>

    </div>
    <?php endif;?>



<?php else: ?>

<!--    --><?php //debug(count($cameras)) ?>
<?php //debug($cameras); exit;?>
    <div class="allcamerasblock">
    <script type="text/javascript" src="/assets/js/clappr.min.js"></script>
    <?php
    $jwtkey = \Yii::$app->request->cookies;
    $jwtkey = $jwtkey->getValue('trust','trustisnotset');
    for($i=0;$i<=count($cameras)-1;$i++):
//	$video_url='https://videolivejournal.com:1936/live-hls/'.$user_id.'_'.$cameras[$i]->id.'/index.m3u8?key='.$jwtkey;
	$video_url='https://'.$cameras[$i]->device->mserver->address.':'.($cameras[$i]->device->mserver->port+1).'/live-hls/'.$user_id.'_'.$cameras[$i]->id.'/index.m3u8?key='.$jwtkey;


?>
                            <div class="eachcam" >
				        <div class="playerwindow" id="player<?=$i?>"></div>
					    <script>
					    var player = new Clappr.Player({
					    source: "<?=$video_url?>",
					    parentId: "#player<?=$i?>",
					    mute: true,
					    autoPlay: false,
					    aspectRatio: 16/9,
					    width: 241,
					    height: 180,
					    responsive: true,
					    disableErrorScreen: true,
					    mediacontrol: {seekbar: "#E113D3", buttons: "#FFFFFF"}
					    });
					    </script>

                                    <div style="float: left;margin-top: 21px;">
                                        <button class="btn btn-primary"><a style="color: white" href="<?= DOMAIN ?>/user/mycameras?options=all/online/id=<?php
                                            require JWTCONF;
                                            $token = array(
                                                "iss" => $iss,
                                                "aud" => $aud,
                                                "iat" => $iat,
                                                "nbf" => $nbf,
                                                "data" => array(
                                                    "id" => $cameras[$i]->id,
                                                )
                                            );
                                            $id = $jwt->encode($token,$key)?><?=$id?>
                                          ?>"><?=\Yii::t('commonEn','Просмотр онлайн')?></a></button>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary"><a style="color: white" href="<?= DOMAIN ?>/user/mycameras?options=all/edit/id=<?php
                                            require JWTCONF;
                                            $token = array(
                                                "iss" => $iss,
                                                "aud" => $aud,
                                                "iat" => $iat,
                                                "nbf" => $nbf,
                                                "data" => array(
                                                    "id" => $cameras[$i]->id,
                                                )
                                            );
                                            $id = $jwt->encode($token,$key)?><?=$id?>
                                          ?>"><?=\Yii::t('commonEn','Редактирование')?></a></button>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary"><a style="color: white" href="<?= DOMAIN ?>/user/mycameras?options=all/archive/id=<?php
                                            require JWTCONF;
                                            $token = array(
                                                "iss" => $iss,
                                                "aud" => $aud,
                                                "iat" => $iat,
                                                "nbf" => $nbf,
                                                "data" => array(
                                                    "id" => $cameras[$i]->id,
                                                )
                                            );
                                            $id = $jwt->encode($token,$key)?><?=$id?>
                                          "><?=\Yii::t('commonEn','Архив')?></button>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary"><a style="color: white" href="<?= DOMAIN ?>/user/mycameras?options=all/delete/id=<?php
                                            require JWTCONF;
                                            $token = array(
                                                "iss" => $iss,
                                                "aud" => $aud,
                                                "iat" => $iat,
                                                "nbf" => $nbf,
                                                "data" => array(
                                                    "id" => $cameras[$i]->id,
                                                )
                                            );
                                            $id = $jwt->encode($token,$key)?><?=$id?>"><?=\Yii::t('commonEn','Удалить')?></a></button>
                                    </div>
        				    <div class="camtitle">
                                	<p><?php if($cameras[$i]->title == NULL) echo 'empty title'; else echo($cameras[$i]->title)?></p>
                            	    </div>
                            </div>
    <?endfor;?>
    </div>
<?endif;?>
