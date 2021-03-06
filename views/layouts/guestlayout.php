<?php


use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
//$this->registerCssFile('/css/guest/guestlayout.css');
?>
<?php $this->beginPage() ?>
<!doctype html>
<html lang="<?= Yii::$app->language ?>">
<html lang="en">
<head>
    <?php $this->head() ?>
    <meta charset="UTF-8">
<title>Live Ai Camera</title>
<meta name="description" content="Video surveillance cloud. View video online from multiple USB Webcams or IP cameras, monitor screen and other video capture devices and view simultaneous images from all webcams in the main app window." />
<meta name="keywords" content="video surveillance cloud, web camera cloud, webcam cloud, live camera, ai camera, ai cam, live ai camera, web camera, surveillance cloud, webcam pro, webcam software, cloud video surveillance, video cloud" />
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrapper">
  <div class="header-row">
      <div class="header">
          <div class="headmenu">
              <div class="logo">
                  <a href="<?= DOMAIN ?>/guest"><img src="<?= DOMAIN ?>/public/images/logo1.png"></a>
              </div>
              <div class="nav1">
                  <ul>
                      <li ><a href="<?= DOMAIN ?>/guest/hitw"><?= Yii::t('commonEn', 'Как это работает?'); ?></a></li>
                      <li ><a href="<?= DOMAIN ?>/guest/solutions"><?= Yii::t('commonEn', 'Примеры'); ?></a></li>
                      <li ><a href="<?= DOMAIN ?>/guest/hitw"><?= Yii::t('commonEn', 'Скачать'); ?></a></li>
                  </ul>
              </div>
              <div class="accaountact">
                  <ul>
                      <li><a href="<?= DOMAIN ?>/guest/login"><?= Yii::t('commonEn', 'Логин'); ?></a></li>
                      <li><a href="<?= DOMAIN ?>/guest/createaccount"><?= Yii::t('commonEn', 'Регистрация'); ?></a></li>
                      <li class="langbar inline-block">
                          <img src="<?= DOMAIN ?>/public/images/lang.jpg">
                          <?php if(Yii::$app->language =='en'){
                              $lang_en = 'active';
                              $lang_ru = 'non-active';
                              $lang='English';
                          } else {
                              $lang_en = 'none-active';
                              $lang_ru = 'active';
                              $lang='Russian';
                          }
                          $lang_en_url = Yii::$app->urlManager->createUrl(array_merge(Yii::$app->request->get(),[Yii::$app->controller->route,'language'=>'en']));
                          $lang_ru_url = Yii::$app->urlManager->createUrl(array_merge(Yii::$app->request->get(),[Yii::$app->controller->route,'language'=>'ru']));
                          ?>
                         <p class="lang_caret iv-ws-template-base-header-lang"><?=$lang?><span class="caret"></span></p>

                          <nav class="iv-ws-template-base-header-lang__menu hide">
                              <div class="iv-ws-template-base-header-lang__list">
                                  <div class="iv-ws-template-base-header-lang__item">
                                      <a class="iv-ws-template-base-header-lang__link iv-ws-template-base-header-lang__link__m-state-hover <?=$lang_en?>" href="<?=$lang_en_url?>" data-lang="en">
                                          English
                                      </a>
                                  </div>
                                  <div class="iv-ws-template-base-header-lang__item">
                                      <a class="iv-ws-template-base-header-lang__link iv-ws-template-base-header-lang__link__m-state-hover <?=$lang_ru?>" href="<?=$lang_ru_url?>" data-lang="ru">
                                          Russian
                                      </a>
                                  </div>
                              </div>
                          </nav>
                      </li>
                  </ul>
              </div>
          </div>
      </div>
  </div>
  <div class="content-row">
      <div class="content">
          <?= $content ?>
      </div>
  </div>
  <div class="footer-row">
      <div class="footer">
          <div class="column1">
              <ul>
                  <li><a href="<?= DOMAIN ?>/guest"><img style="width: 120px; height: 75px" src="<?= DOMAIN ?>/public/images/logo2.png"></a></li>

                  <li class="soc-logo">


                      <a href="https://www.facebook.com/groups/webcameracloud/" target="_blank">
                          <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
                                      <desc>Facebook</desc>
                              <path style="fill:#ffffff;" d="M47.761,24c0,13.121-10.638,23.76-23.758,23.76C10.877,47.76,0.239,37.121,0.239,24c0-13.124,10.638-23.76,23.764-23.76C37.123,0.24,47.761,10.876,47.761,24 M20.033,38.85H26.2V24.01h4.163l0.539-5.242H26.2v-3.083c0-1.156,0.769-1.427,1.308-1.427h3.318V9.168L26.258,9.15c-5.072,0-6.225,3.796-6.225,6.224v3.394H17.1v5.242h2.933V38.85z"></path>
                                  </svg>
                      </a>

                      <a href="https://twitter.com/WebCameraCloud" target="_blank">
                          <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
                                      <desc>Twitter</desc>
                              <path style="fill:#ffffff;" d="M47.762,24c0,13.121-10.639,23.76-23.761,23.76S0.24,37.121,0.24,24c0-13.124,10.639-23.76,23.761-23.76 S47.762,10.876,47.762,24 M38.031,12.375c-1.177,0.7-2.481,1.208-3.87,1.481c-1.11-1.186-2.694-1.926-4.455-1.926 c-3.364,0-6.093,2.729-6.093,6.095c0,0.478,0.054,0.941,0.156,1.388c-5.063-0.255-9.554-2.68-12.559-6.367 c-0.524,0.898-0.825,1.947-0.825,3.064c0,2.113,1.076,3.978,2.711,5.07c-0.998-0.031-1.939-0.306-2.761-0.762v0.077 c0,2.951,2.1,5.414,4.889,5.975c-0.512,0.14-1.05,0.215-1.606,0.215c-0.393,0-0.775-0.039-1.146-0.109 c0.777,2.42,3.026,4.182,5.692,4.232c-2.086,1.634-4.712,2.607-7.567,2.607c-0.492,0-0.977-0.027-1.453-0.084 c2.696,1.729,5.899,2.736,9.34,2.736c11.209,0,17.337-9.283,17.337-17.337c0-0.263-0.004-0.527-0.017-0.789 c1.19-0.858,2.224-1.932,3.039-3.152c-1.091,0.485-2.266,0.811-3.498,0.958C36.609,14.994,37.576,13.8,38.031,12.375"></path>
                                  </svg>
                      </a>

                      <a href="https://vk.com/webcameracloud" target="_blank">
                          <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xml:space="preserve">
                                      <desc>VK</desc>
                              <path style="fill:#ffffff;" d="M47.761,24c0,13.121-10.639,23.76-23.76,23.76C10.878,47.76,0.239,37.121,0.239,24c0-13.123,10.639-23.76,23.762-23.76C37.122,0.24,47.761,10.877,47.761,24 M35.259,28.999c-2.621-2.433-2.271-2.041,0.89-6.25c1.923-2.562,2.696-4.126,2.45-4.796c-0.227-0.639-1.64-0.469-1.64-0.469l-4.71,0.029c0,0-0.351-0.048-0.609,0.106c-0.249,0.151-0.414,0.505-0.414,0.505s-0.742,1.982-1.734,3.669c-2.094,3.559-2.935,3.747-3.277,3.524c-0.796-0.516-0.597-2.068-0.597-3.171c0-3.449,0.522-4.887-1.02-5.259c-0.511-0.124-0.887-0.205-2.195-0.219c-1.678-0.016-3.101,0.007-3.904,0.398c-0.536,0.263-0.949,0.847-0.697,0.88c0.31,0.041,1.016,0.192,1.388,0.699c0.484,0.656,0.464,2.131,0.464,2.131s0.282,4.056-0.646,4.561c-0.632,0.347-1.503-0.36-3.37-3.588c-0.958-1.652-1.68-3.481-1.68-3.481s-0.14-0.344-0.392-0.527c-0.299-0.222-0.722-0.298-0.722-0.298l-4.469,0.018c0,0-0.674-0.003-0.919,0.289c-0.219,0.259-0.018,0.752-0.018,0.752s3.499,8.104,7.573,12.23c3.638,3.784,7.764,3.36,7.764,3.36h1.867c0,0,0.566,0.113,0.854-0.189c0.265-0.288,0.256-0.646,0.256-0.646s-0.034-2.512,1.129-2.883c1.15-0.36,2.624,2.429,4.188,3.497c1.182,0.812,2.079,0.633,2.079,0.633l4.181-0.056c0,0,2.186-0.136,1.149-1.858C38.281,32.451,37.763,31.321,35.259,28.999"></path>
                                  </svg>
                      </a>

                      <a href="https://www.youtube.com/WebCameraCloud" target="_blank">
                          <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25px" height="25px" viewBox="-455 257 48 48" enable-background="new -455 257 48 48" xml:space="preserve">
                                      <desc>Youtube</desc>
                              <path style="fill:#ffffff;" d="M-431,257.013c13.248,0,23.987,10.74,23.987,23.987s-10.74,23.987-23.987,23.987s-23.987-10.74-23.987-23.987S-444.248,257.013-431,257.013z M-419.185,275.093c-0.25-1.337-1.363-2.335-2.642-2.458c-3.054-0.196-6.119-0.355-9.178-0.357c-3.059-0.002-6.113,0.154-9.167,0.347c-1.284,0.124-2.397,1.117-2.646,2.459c-0.284,1.933-0.426,3.885-0.426,5.836s0.142,3.903,0.426,5.836c0.249,1.342,1.362,2.454,2.646,2.577c3.055,0.193,6.107,0.39,9.167,0.39c3.058,0,6.126-0.172,9.178-0.37c1.279-0.124,2.392-1.269,2.642-2.606c0.286-1.93,0.429-3.879,0.429-5.828C-418.756,278.971-418.899,277.023-419.185,275.093zM-433.776,284.435v-7.115l6.627,3.558L-433.776,284.435z"></path>
                                  </svg>
                      </a>

                      <a href="https://t.me/Webcameracloud" target="_blank">
                          <svg class="t-sociallinks__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0 0 60 60" xml:space="preserve">
                                      <desc>Telegram</desc>
                              <path style="fill:#ffffff;" d="M30 0C13.4 0 0 13.4 0 30s13.4 30 30 30 30-13.4 30-30S46.6 0 30 0zm16.9 13.9l-6.7 31.5c-.1.6-.8.9-1.4.6l-10.3-6.9-5.5 5.2c-.5.4-1.2.2-1.4-.4L18 32.7l-9.5-3.9c-.7-.3-.7-1.5 0-1.8l37.1-14.1c.7-.2 1.4.3 1.3 1z"></path>
                              <path style="fill:#ffffff;" d="M22.7 40.6l.6-5.8 16.8-16.3-20.2 13.3"></path>
                                  </svg>
                      </a>

                  </li>
                  <li style="color:#f3eeee;">© 2019 Web Camera Cloud</li>
              </ul>
          </div>
          <div class="column2">
              <ul>
                  <li class="fheader">MY ACCOUNT</li>
                  <li>Login</li>
                  <li>Create account</li>
                  <li>Recover password</li>
                  <li>Privacy Policy</li>
                  <li>Terms of Use</li>
                  <li>Support</li>
                  <li>Contacts</li>
              </ul>
          </div>
          <div class="column3">
              <ul>
                  <li class="fheader">PRODUCT</li>
                  <li>Home</li>
                  <li>Download</li>
                  <li>How it works</li>
                  <li>Solutions</li>
                  <li>Key Benefits</li>
                  <li>YouTube & Messenger</li>
                  <li>Knowledge base</li>
              </ul>
          </div>
          <div class="column4">
              <ul>
                  <li class="fheader">CASE STUDY</li>
                  <li>Video tutorials</li>
                  <li>Your yard</li>
                  <li>Elevator hall</li>
                  <li>Parking lot</li>
                  <li>Apartments</li>
                  <li>Facilities security</li>
                  <li>Nanny</li>
              </ul>
          </div>
      </div>
  </div>
</div>

<?php $this->endBody() ?>
<script type="text/javascript">
$(".iv-ws-template-base-header-lang").click(function() {
    $(".iv-ws-template-base-header-lang__menu").toggleClass('hide');
});
$(".content").click(function() {
    $(".iv-ws-template-base-header-lang__menu").addClass('hide');
});
</script>
</body>
</html>
<?php $this->endPage() ?>
