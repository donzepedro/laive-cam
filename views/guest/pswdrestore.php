<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
$this->registerCssFile('/css/notification.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);

if(($note == 'nouser') || ($note == 'newpswd') || ($note =='wronglink') || ($note == 'linkonemail')) {
    $modal = Modal::begin([
        'class'=>'note',
        'header' => '<h3>Notification</h3>',
//      'toggleButton' => ['label' => 'click me'],
        'footer' => '<button class="btn btn-primary" data-dismiss="modal" aria-hidden="true" > Ok </button>',
        'clientOptions' => ['show' => true],
    ]);
    if($note == 'nouser')
    {
        echo Yii::t('commonEn','Пользователя с таким email не существует.');
    }elseif($note == 'newpswd'){
        echo Yii::t('commonEn','Придумайте новый пароль!');
    }elseif($note == 'wronglink') {
        echo Yii::t('commonEn', 'Ваша ссылка для востановления пароля повреждена!');
    }elseif ($note == 'linkonemail') {
        echo Yii::t('commonEn', 'На ваш адрес электронной почты была отправлена ссылка для сброса пароля.');
    }
    Modal::end();
}

?>
<div class="resetform" style="margin: 5%">
    <h2><?= Yii::t('commonEn','Сброс пароля') ?></h2>
    <?php if($note=='newpswd'): ?>
            <?php $form=ActiveForm::begin(['options'=>['id'=>'pswd-restore'], 'method'=>'post', 'action'=>'']) ?>
                <div class="input-area"   style="width: 30%">
                    <?= $form->field($pswdrestoreform,'newpassword',['inputOptions' => ['autofocus' => 'autofocus']])->passwordInput(); ?>
                    <?= $form->field($pswdrestoreform,'confirmpassword',['inputOptions' => ['autofocus' => 'autofocus']])->passwordInput(); ?>
                </div>
                <div class="mybtn">
                    <?= Html::submitButton(Yii::t('commonEn', 'Отправить'), ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end() ?>
    <?php else: ?>
            <h4>Please, enter yor email. We will send password reset link to your email.</h4>
            <?php $form=ActiveForm::begin(['options'=>['id'=>'pswd-restore'], 'method'=>'post', 'action'=>'']) ?>
            <div class="input-area"  style="width: 40%">
                <?= $form->field($pswdrestore,'email',['inputOptions' => ['autofocus' => 'autofocus']])->textInput(); ?>
            </div>
            <div class="mybtn">
                <?= Html::submitButton(Yii::t('commonEn', 'Отправить'), ['class' => 'btn btn-primary']) ?>
            </div>
    <?php ActiveForm::end() ?>
    <?php endif; ?>
</div>