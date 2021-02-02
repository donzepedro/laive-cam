<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
$this->registerCssFile('/css/login/login.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);
$this->registerCssFile('/css/notification.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);

if(($note == 'yes') || ($note == 'no')||($note == 'nouser') || ($note == 'newpswd')) {
    $modal = Modal::begin([
            'class'=>'note',
        'header' => '<h3>Notification</h3>',
//      'toggleButton' => ['label' => 'click me'],
        'footer' => '<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" > Ok </button>',
        'clientOptions' => ['show' => true],
    ]);
    if($note == 'yes')
    {
        echo Yii::t('commonEn','Регистрация успешна! На ваш адрес электронной почты была отправлена ссылка для подтверждения.');
    }elseif($note == 'no'){
        echo Yii::t('commonEn','Для продолжения вам необходимо подтвердить свою почту!');
    }elseif($note == 'nouser'){
        echo Yii::t('commonEn','Неверный Логин или Пароль');
    }elseif ($note == 'newpswd'){
        echo Yii::t('commonEn','Ваш новый пароль успешно сохранен');
    }
    Modal::end();
}
?>

<div class="myform">
         <h3>Log in to Web Camera Pro</h3>
    <?php $form = ActiveForm::begin(['options'=>['id'=>'login-form'], 'method'=>'post', 'action'=>'']) ?>
    <div class="fields">
        <div class="field">
            <?= $form->field($logform,'login')->textInput(['placeholder'=>'Email']) ?>
        </div>
        <div class="field">
            <?= $form->field($logform,'password')->passwordInput(['placeholder'=>Yii::t('commonEn', 'Пароль')]) ?>
        </div>

    </div>
    <div class="mybtn">
            <?= Html::submitButton(Yii::t('commonEn', 'Логин'), ['class' => 'btn btn-primary']) ?>
    </div>
    <br>
    <?php ActiveForm::end() ?>
    <div style="clear: both"></div>
</div>
<div class="or">
    <a href="/guest/createaccount"><?= Yii::t('commonEn', 'Создать аккаунт'); ?></a>
    <?= Yii::t('commonEn', 'ИЛИ'); ?>
    <a href="/guest/pswdrestore"><?= Yii::t('commonEn', 'Забыли пароль ?'); ?></a>
</div>
