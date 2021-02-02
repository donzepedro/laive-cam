<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->registerCssFile('/css/registration/registration.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);

if (Yii::$app->language == 'ru') {
    $agrement = "пользовательское соглашение";
    $subscribe = "Подписаться на новости";
    $accept = "Принять";

} else {
    $accept = "Accept";
    $agrement = "agreement";
    $subscribe = "Subscribe to news";
}
?>
<div class="myform">
    <h3><?=Yii::t('commonEn','Создать новый аккаунт')?></h3>
        <?php $form=ActiveForm::begin(['options'=>['id'=>'login-form'], 'method'=>'post', 'action'=>'']) ?>
            <div class="fields">
                <div class="field">
                    <?php if(isset($message)) : ?>
                    <span class="ermess"><?= $message ?></span>
                    <?php endif; ?>
                    <?= $form->field($regform,'email')->textInput(['placeholder'=>'Email'.' ('.Yii::t('commonEn','Логин').')']) ?>
                </div>
                <div class="field">
                    <?= $form->field($regform,'password')->passwordInput(['placeholder'=>Yii::t('commonEn', 'Пароль')]) ?>
                </div>
                <div class="field">
                    <?= $form->field($regform,'name')->textInput(['placeholder'=>Yii::t('commonEn', 'Имя')]) ?>
                </div>
                <div class="field">
                    <?= $form->field($regform,'phone')
		    // 		    ->widget(\yii\widgets\MaskedInput::className(), [
            // 			'mask' => '+9(999)999-99-99',
		    // ])
		    ->textInput(['placeholder'=>Yii::t('commonEn', 'Ваш телефон')])
 ?>
                </div>
                <?= $form->field($regform,'newsagree')->checkbox(['label'=>Yii::t('commonEn', 'Подписаться на новости')]) ?>
                <?= $form->field($regform,'useragreement')->checkbox(['label'=>Yii::t('commonEn','Принять'). ' ' . Html::a(Yii::t('commonEn','пользовательское соглашение'),"https://online.webcameracloud.com/agreement")]) ?>
            </div>
            <div class="mybtn">
                <?= Html::submitButton(Yii::t('commonEn', 'Создать аккаунт'), ['class' => 'btn btn-primary']) ?>
            </div>
            <br>


        <?php ActiveForm::end() ?>
</div>
