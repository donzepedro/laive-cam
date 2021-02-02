<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?php $this->head() ?>

</head>
<body>
<?php $this->beginBody() ?>
<!--<div class="hat">-->
<!--        <ul>-->
<!--            <li id="horizont"><a href="/" class="logo">LOGO</a></li>-->
<!--            <li id="horizont"><a href="">Главная</a></li>-->
<!--            <li id="horizont"><a href="">О нас</a></li>-->
<!--            <li id="horizont"><a href="">Контакты</a></li>-->
<!--        </ul>-->
<!--</div>-->


<div id="header">
    <div class="logo">
        <a href="http://webcam.loc/web/guest"><img src="../../web/public/images/logo1.png"></a>
    </div>
    <ul class="navbar1">
        <li><a class="menu1" href="http://webcam.loc/web/guest/hitw">How it works</a></li>
        <li><a class="menu1" href="http://webcam.loc/web/guest/solutions">Solutions</a></li>
        <li><a class="menu1" href="http://webcam.loc/web/guest/download">Download</a></li>
    </ul>
    <ul class="navbar2">
        <li class="menu2"><a href="http://webcam.loc/web/guest/login">Login</a></li>
        <li class="menu2"><a href="http://webcam.loc/web/guest/createaccount">Create Account</a></li>
    </ul>

</div>
<div class="step"> </div>
        <div style="clear: both">
                <?= $content ?>
        </div>



<div id="footer">
    <div class="footerspace">
        <p>Footer 1</p>
        <p>Footer 1</p>
        <p>Footer 1</p>
        <p>Footer 1</p>
        <p>Footer 1</p>

    </div>

    <div class="footerspace" >
        <p>Footer 2</p>
        <p>Footer 2</p>
        <p>Footer 2</p>
        <p>Footer 2</p>
        <p>Footer 2</p>

    </div>

    <div class="footerspace" >
        <p>Footer 3</p>
        <p>Footer 3</p>
        <p>Footer 3</p>
        <p>Footer 3</p>
    </div>

    <div class="footerspace" >
        <p>Footer 4</p>
        <p>Footer 4</p>
        <p>Footer 4</p>
        <p>Footer 4</p>
    </div>

</div>


<?php $this->endBody() ?>


</body>

</html>
<?php $this->endPage() ?>
