<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
$this->registerCssFile('/css/notification.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);
$this->registerCssFile('/css/user/userprofile.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);

?>
<div class="content-body col-sm-12">
    <?php if($activepage != 'settings'): ?>
      <div class="usermenu col-sm-4">
        <ul class="t-header">
            <li style="font-weight: bold; font-size: 18px; "><?= $username?></li>
            <li style="color: #4e555b "><?= $emailadress?></li>
        </ul>
        <ul class="menulist">
            <li style="background: #ffffff"><a href=<?=DOMAIN?>/user/userprofile?options=streamoptions><?= Yii::t('commonEn', 'Настройки видеопотока'); ?></a></li>
            <li style="background: #ffffff"><a href=<?=DOMAIN?>/user/userprofile?options=info><?= Yii::t('commonEn', 'Персональные данные'); ?></a></li>
            <li style="background: #ffffff"><a href=<?=DOMAIN?>/user/userprofile?options=pswd><?= Yii::t('commonEn', 'Смена пароля'); ?></a></li>
            <li><a href=<?=DOMAIN?>/user/userprofile?options=notifications><?= Yii::t('commonEn', 'Опция подписки'); ?></a></li>
        </ul>
     </div>

    <?php endif; ?>
    <?php if($activepage =='settings'): ?>
    <?php
        if(($note == 'success_pswd') || ($note == 'success_account') || ($note == 'success_email_send'))
        {
            $modal = Modal::begin([
                'class'=>'note',
                'header' => '<h3>Notification</h3>',
                'clientOptions' => ['show' => true],
            ]);
            if($note == 'success_email_send'){
                echo Yii::t('commonEn','Письмо с подтверждением отправлено на ваш  адрес электронной почты ․');
            }
            elseif($note == 'success_account'){
                echo Yii::t('commonEn','Информация о пользователе обновлена');
            }else{
                echo Yii::t('commonEn','Пароль изменен');
            }

            Modal::end();
        }
    ?>
    <?php if($active =='account'): ?>
    <?php
        if($note=="email_exist")
        {
            $modal = Modal::begin([
                'class'=>'note',
                'header' => '<h3>Notification</h3>',
                'clientOptions' => ['show' => true],
            ]);
            echo Yii::t('commonEn','Этот email используется для входа в приложение, а также для рассылки уведомлений от сервиса.');
            Modal::end();
            $note="";
        }
    ?>
    <?php endif;?>

      <div class="usermenu col-sm-3">
        <ul class="menulist">
            <li <?php if($active == 'tariff'){
                                          echo 'class="activepage"';
                                        }?>>
                <a href=<?=DOMAIN?>/user/userprofile?options=settings-tariff><?= Yii::t('commonEn', 'Тарифный План'); ?></a>
            </li>
            <li <?php if($active == 'account'){
                                          echo 'class="activepage"';
                                        }?>>
                <a href=<?=DOMAIN?>/user/userprofile?options=settings-account><?= Yii::t('commonEn', 'Общие данные'); ?></a>
            </li>
            <li <?php if($active == 'notifications'){
                                            echo 'class="activepage"';
                                          }?>>
                 <a href=<?=DOMAIN?>/user/userprofile?options=settings-notifications><?= Yii::t('commonEn', 'Уведомление'); ?></a>
            </li>
            <li <?php if($active == 'pswd'){
                                             echo 'class="activepage"';
                                          }?>>
                <a href=<?=DOMAIN?>/user/userprofile?options=settings-pswd><?= Yii::t('commonEn', 'Смена пароля'); ?></a>
            </li>

            <li <?php if($active == 'archive'){
                                             echo 'class="activepage"';
                                          }?>>
                    <a href=<?=DOMAIN?>/user/userprofile?options=settings-archive><?=Yii::t('commonEn', 'Настройка архива'); ?></a>
            </li>
        </ul>
     </div>
     <?php if($active == 'tariff'): ?>
             <div class="content-area col-sm-9">
                    <div class="t-header">
                       <div class="row">
                             <div class="col-sm-8" >
                             </div>
                              <div class="col-sm-4 text-right" >
                               <li><b><?= $name?></b>
                               <?= $emailadress?></li>
                             </div>
                       </div>
                   </div>
            <div class="content-area-body">
                    <div class="container-fluid">
                       <div class="row">
                           <div class="col-sm-8">
                               <div class="row">
                                   <div class="col-sm-8">
                                      <label style="font-weight:bold;font-size: 18px;" class="tariff-row"><?=Yii::t('commonEn','Текущий план')?></label>
                                      <label style="font-weight:bold;font-size: 18px; style" class="tariff-row"><?=Yii::t('commonEn','Бесплатно')?></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <?= Html::button(Yii::t('commonEn','Обновить'), ['class' => 'btn btn-primary',
                                           'style'=>'width: 120px;margin-left:7px']) ?>
                                    </div>
                               </div>
                           </div>
                      </div>

                      <div class="row">
                         <div class="col-sm-8 tariff-row">
                             <table  class="tariff-table">
                                 <tr>
                                     <td><?=Yii::t('commonEn','Хранилище')?></td>
                                     <td>100 MB <?=Yii::t('commonEn','Хранилище')?></td>
                                     <td><?=Yii::t('commonEn','Используемый')?></td>
                                     <td>5Mb</td>
                                 </tr>
                                 <tr>
                                     <td><?=Yii::t('commonEn','Скачать Bandwidth')?></td>
                                     <td>100 MB <?=Yii::t('commonEn','в месяц')?></td>
                                     <td><?=Yii::t('commonEn','Используемый')?></td>
                                     <td>5Mb</td>
                                 </tr>
                             </table>
                         </div>
                     </div>

                     <div class="row tariff-started">
                         <div class="col-sm-6">
                                       <label style="font-weight:bold;" class="tariff-row"><?=Yii::t('commonEn','Началось')?>:</label>
                                       <span> August 01,2020</span>
                         </div>
                     </div>
                     <div class="row tariff-buy">
                         <div class="col-sm-8">
                             <div class="row">
                                 <div class="col-sm-8">
                                       <span><?=Yii::t('commonEn','Дополнительное хранилище')?></span>
                                       <span class="tariff-mb">0 Mb</span>
                                 </div>
                                 <div class="col-sm-4">
                                     <?= Html::button(Yii::t('commonEn','Купить'), ['class' => 'btn btn-primary',
                                        'style'=>'width: 120px;margin-left: -9px;']) ?>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
          </div>
      </div>
      <?php endif; ?>

        <?php if($active == 'account'): ?>
                <div class="content-area col-sm-9">
                       <div class="t-header">
                          <div class="row">
                                <div class="col-sm-2" >
                                   <li ><?=Yii::t('commonEn','Активный профиль')?>:</li>
                                </div>
                                <div class="col-sm-2" >
                                  <li><?=Yii::t('commonEn','Номер')?>: <?= $user_id?></li>
                                </div>
                                 <div class="col-sm-4">
                                </div>
                                 <div class="col-sm-4 text-right" >
                                  <li><b><?= $name?></b>
                                  <?= $emailadress?></li>
                                </div>
                          </div>
                      </div>
               <div class="content-area-body">

                <?php $form=ActiveForm::begin(['method'=>'post', 'action'=>'',
                    'options' => ['class' => 'form-horizontal','id'=>'account_form',],]) ?>

                          <div class="container-fluid">
                          <div class="row form-group">
                            <div class="col-sm-2" style="padding-left:0">
                               <label style="font-weight:bold;font-size: 16px;line-height:normal;    display: contents;text-align: left;"><?=Yii::t('commonEn','Эл. почта')?>:*</label>
                            </div>
                             <div class="col-sm-4" >
                                  <label style="font-weight:bold;font-size: 16px;line-height:normal;"><?= $user->email?></label>
                            </div>
                            <div class="col-sm-6">
                                <?= Html::button(Yii::t('commonEn','Изменить'), ['class' => 'btn btn-primary','data-toggle'=>'modal','data-target'=>"#myModal",
                                'style'=>'width: 90px;outline:none;display:block;margin-top: -8px;','id'=>'change']) ?>
                                <?= Html::submitbutton(Yii::t('commonEn','Сохранить'), ['class' => 'btn btn-success',
                                'style'=>'width: 90px;outline:none;display:none;','id'=>'save'])?>
                            </div>
                         </div>

                         <div class="row">
                             <div class="col-sm-7">
                                 <?= $form->field($model,'phone')->textInput(
                                 ['value'=>$user->phone,'class' =>'form-control'])->label(Yii::t('commonEn','Телефон').'*')?>
                                     <?= $form->field($model,'first_name')->textInput(
                                    ['value'=>$user->name,'class' =>'form-control'])->label(Yii::t('commonEn','Имя').'*')?>
                                    <?= $form->field($model,'surname')->textInput(
                                    ['value'=>$user->surname,'class' =>'form-control'])->label(Yii::t('commonEn','Фамилия').'*')?>
                             </div>
                        </div>

                         <div class="row">
                             <div class="col-sm-3" >
                                <?= $form->field($model,'country')->textInput(
                                ['value'=>$user->country,'class' =>'form-control', 'style'=>'position: relative;right: -82px;'])->label(Yii::t('commonEn','Страна'))?>
                            </div>
                              <div class="col-sm-3" style="position: relative;right: -115px;">
                                    <?= $form->field($model,'city')->textInput(
                                ['value'=>$user->city,'class' =>'form-control'])->label(Yii::t('commonEn','Город'))?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-left">
                                * -<?=Yii::t('commonEn','обязательные поля')?>
                            </div>
                            <div class="col-sm-4 text-right" style="padding-right:25px">
                                <?= Html::submitButton(Yii::t('commonEn','Сохранить'), ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                    </div>
                  <?php ActiveForm::end() ?>
                  <!-- The Modal -->
                  <?php $form=ActiveForm::begin(['method'=>'post', 'action'=>'',
                              'options' => ['class' => 'form-horizontal']]) ?>

                        <div class="modal fade modal-email" id="myModal" tabindex="-1" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content">

                              <!-- Modal Header -->
                              <div class="modal-header col-sm-12">
                                <?= Html::button("&times", ['class' => 'close','data-dismiss'=>'modal','style'=>'padding:10px;','id'=>'close_button','aria-hidden'=>'true',"aria-label"=>"Close"]) ?>
                                <h5 class="modal-title" style="text-align: left;color:white;padding:20px;font-size:16px;"><?php  echo Yii::t('commonEn','Сменить Имейл'); ?></h5>
                              </div>

                              <!-- Modal body -->
                              <div class="modal-body" style="position:static">
                                 <div class="container-fluid">
                                     <div class="row">
                                      <p style="font-size: 13px;margin-top:40px;text-align: left;">
                                          <?php  echo Yii::t('commonEn','Этот email используется для входа в приложение, а также для рассылки уведомлений от сервиса.'); ?>
                                      </p>
                                     </div>
                                     <div class="col-sm-12">
                                        <label style="font-weight:bold;font-size: 14px;float:left; margin-left:-10px">
                                         <?php  echo Yii::t('commonEn','Текущая email-адрес'); ?></label>
                                     </div>
                                     <div class="col-sm-12">
                                         <?= $form->field($model_modal,'current_email')->textInput(
                                         ['value'=>$user->email,'class' =>'form-control','style'=>'margin-top:-20px;outline:none','disabled' => 'disabled'])?>
                                     </div>
                                     <div class="col-sm-12">
                                        <label style="font-weight:bold;font-size: 14px;float:left; margin-left:-10px">
                                             <?php  echo Yii::t('commonEn','Новый email-адрес'); ?></label>
                                     </div>
                                     <div class="col-sm-12">
                                         <?= $form->field($model_modal,'new_email')->textInput(
                                           ['class' =>'form-control','style'=>'margin-top:-20px'])?>
                                     </div>
                                     <div class="col-sm-12">
                                              <?= Html::submitButton(Yii::t('commonEn','Сменить Имейл'), ['class' => 'btn modal-change-button']) ?>
                                     </div>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                 <?php ActiveForm::end() ?>
             </div>
         </div>
         <?php endif; ?>

        <?php if($active == 'pswd'): ?>
             <?php
        if(($note == 'wrongpswd') || ($note == 'notequals'))
        {
            $modal = Modal::begin([
                'class'=>'note',
                'header' => '<h3>Notification</h3>',
                'clientOptions' => ['show' => true],
            ]);
            if($note == 'wrongpswd')
            {
                echo Yii::t('commonEn','Неверный пароль');
            }else{
                echo Yii::t('commonEn','Пароли не совпадают');
            }

            Modal::end();
        }
    ?>
        <div class="content-area col-sm-9">
            <div class="t-header">
                <div class="row">
                    <div class="col-sm-2" >
                        <li><?=Yii::t('commonEn','Активный профиль')?>:</li>
                    </div>
                    <div class="col-sm-2" >
                        <li><?=Yii::t('commonEn','Номер')?>: <?= $user_id?></li>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 text-right">
                        <li><b><?= $name?></b> <?= $emailadress?></li>
                    </div>
                </div>
            </div>
            <div class="content-area-body">
                <?php $form=ActiveForm::begin(['method'=>'post', 'action'=>'',
                    'options' => ['class' => 'form-horizontal',],]) ?>
                       <div class="container-fluid">
                          <div class="row form-group">
                            <div class="col-sm-2" >
                               <div style="font-weight:bold;font-size: 16px;">Email: *</div>
                            </div>
                             <div class="col-sm-2" >
                               <div style="font-weight:bold;font-size: 16px;"><?= $emailadress?></div>
                            </div>
                         </div>
                         <br>
                        <div class="row">
                                <div class="col-sm-2">
                                 <label>Old Password</label>
                                </div>
                                 <div class="col-sm-3">
                                   <?= $form->field($model,'oldpassword')->passwordInput(
                                  ['class' =>'form-control','style'=>'margin-top:-30px'])?>
                                </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                             <label>New Password</label>
                            </div>
                             <div class="col-sm-3" >
                                 <?= $form->field($model,'newpassword')->passwordInput(
                                  ['class' =>'form-control','style'=>'margin-top:-30px'])?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">
                             <label style="line-height: normal;">Enter new password again</label>
                            </div>
                             <div class="col-sm-3" >
                                     <?= $form->field($model,'confirmpassword')->passwordInput(
                                  ['class' =>'form-control','style'=>'margin-top:-30px'])?>
                            </div>
                        </div>

                         <div class="row">
                            <div class="col-sm-4">
                            </div>
                             <div class="col-sm-4" >
                               <?= Html::submitButton(Yii::t('commonEn','Изменить'), ['class' => 'btn btn-primary',
                               'style'=>'width:100px']) ?>
                            </div>

                        </div><br>
                        <div class="row">
                          <div class="col-sm-4">
                               <label>Last Login: </label> <?= $last_login?>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>IP Address: </label> <?= $ip?>
                            </div>
                        </div>

                    </div>
                  <?php ActiveForm::end() ?>
             </div>
         </div>
         <?php endif; ?>
        <?php if($active == 'notifications'): ?>
            <div class="content-area col-sm-9">
                <ul class="t-header">
                    <li style="font-weight: 600; font-size: 20px;padding-bottom:0;" >Notifications</li>
                    <li>Select notification you'd like to receive</li>
                </ul>
                <?php $form=ActiveForm::begin(['options'=>['id'=>'note-form'], 'method'=>'post', 'action'=>'']) ?>
                    <div class="content-area-body">
                        <?= $form->field($notifications,'ysubscribe')->checkbox(['checked'=> $ychecked,'label'=>Yii::t('commonEn','Подписан на канал Web Camera Cloud Channel'),
                        'onchange'=>'document.getElementById("note-form").submit()']) ?>
                        <?= $form->field($notifications,'subscribe')->checkbox(['checked ' => $checked,'label'=>Yii::t('commonEn', 'Подписан на новости'),'onchange'=>'document.getElementById("note-form").submit()']) ?>
                    </div>
                <?php ActiveForm::end() ?>
            </div>
         <?php endif; ?>
           <?php if($active == 'archive'): ?>
                <div class="content-area col-sm-9">
                   <div class="t-header">
                          <div class="row">
                                <div class="col-sm-2" >
                                    <li><?=Yii::t('commonEn','Активный профиль')?>:</li>
                                </div>
                                <div class="col-sm-2" >
                                    <li><?=Yii::t('commonEn','Номер')?>: <?= $user_id?></li>
                                </div>
                                 <div class="col-sm-4"></div>
                                 <div class="col-sm-4 text-right" >
                                      <li><b><?= $name?></b> <?= $emailadress?></li>
                                </div>
                          </div>
                      </div>
                      <?php $form=ActiveForm::begin(['method'=>'post', 'action'=>'','options' => ['class' => 'form-horizontal']]) ?>
                          <div class="content-area-body">
                                <div class="row">
                                      <div class="col-sm-12 inline-block month-block">
                                          <label>Auto-delete old files after</label>
                                          <?= $form->field($model,'month_count')->textInput(['value'=>$user->getArchiveMonth(),'class' =>'form-control'])?>
                                          <label> months</label>
                                          <?= Html::submitButton(Yii::t('commonEn','Сохранить'), ['class' => 'btn btn-primary'])?>
                                      </div>
                                 </div>
                                <div class="row">
                                   <div class="col-sm-12 inline-block date-files-block">
                                       <label>Delete files from</label>
                                       <?= $form->field($model,'from_date')->textInput(['class' =>'form-control','placeholder'=>date("Y-m-d")])?>
                                       <label>to</label>
                                       <?= $form->field($model,'to_date')->textInput(['class' =>'form-control','placeholder'=>date("Y-m-d")])?>
                                       <?= Html::button(Yii::t('commonEn','Старт'), ['class' => 'btn btn-primary'])?>
                                   </div>
                                 </div>
                        </div>
                  <?php ActiveForm::end() ?>
              </div>
         <?php endif; ?>
    <?php endif; ?>
    <?php if($activepage == 'streamoptions'): ?>
    <?php endif; ?>
    <?php if($activepage == 'info'): ?>
    <?php endif; ?>
    <?php if($activepage == 'pswd'): ?>
    <?php
        if(($note == 'wrongpswd') || ($note == 'notequals') || ($note == 'success'))
        {
            $modal = Modal::begin([
                'class'=>'note',
                'header' => '<h3>Notification</h3>',
                'footer' => '<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" > Ok </button>',
                'clientOptions' => ['show' => true],
            ]);
            if($note == 'wrongpswd')
            {
                echo Yii::t('commonEn','Неверный пароль');
            }elseif($note == 'notequals'){
                echo Yii::t('commonEn','Пароли не совпадают');
            }elseif($note == 'success'){
                echo Yii::t('commonEn','Пароль изменен');
            }

            Modal::end();
        }
    ?>

        <div class="content-area col-sm-8">
            <ul class="t-header">
                <li style="font-weight: 500; font-size: 18px; letter-spacing: 1.5px;" >Password and security</li>
                <li>Fill in the fields for changing the password</li>
            </ul>
            <div class="content-area-body">
                <?php $form=ActiveForm::begin(['options'=>['id'=>'change-pswd'], 'method'=>'post', 'action'=>'']) ?>
                <div class="pswdchange-form">
                        <?= $form->field($changepswd,'oldpassword')->passwordInput(['placeholder'=>Yii::t('commonEn', 'Старый пароль')]) ?>
                        <?= $form->field($changepswd,'newpassword')->passwordInput(['placeholder'=>Yii::t('commonEn', 'Новый пароль')]) ?>
                        <?= $form->field($changepswd,'confirmpassword')->passwordInput(['placeholder'=>Yii::t('commonEn', 'Подвердите пароль')]) ?>
                </div>
            </div>
            <div class="btn-area">
                <?= Html::submitButton(Yii::t('commonEn', 'Сохранить изменения'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    <?php endif; ?>
    <?php if($activepage == 'notifications'): ?>
        <div class="content-area col-sm-8">
            <ul class="t-header">
                <li style="font-weight: 500; font-size: 18px; letter-spacing: 1.5px;" >Notifications</li>
                <li>Select notification you'd like to receive</li>
            </ul>
            <div class="content-area-body">
            <?php $form=ActiveForm::begin(['options'=>['id'=>'note-form'], 'method'=>'post', 'action'=>'']) ?>
            <?= $form->field($notifications,'ysubscribe')->checkbox(['checked'=> $ychecked,'label'=>Yii::t('commonEn','Подписан на канал Web Camera Cloud Channel')]) ?>
            <?= $form->field($notifications,'subscribe')->checkbox(['checked ' => $checked,'label'=>Yii::t('commonEn', 'Подписан на новости')]) ?>
            </div>
            <div class="btn-area">
                <?= Html::submitButton(Yii::t('commonEn', 'Сохранить изменения'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>

        </div>
    <?php endif; ?>
</div>
