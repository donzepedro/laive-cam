<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use Firebase\JWT\JWT;
?>

<?php
//M_serv_addr
//M_serv_port
//M_serv_pp
//M_serv_pass
//user_id - можго получить из куки
//id_camera - можно получить по ид записи
//по ид камеры можно получить девайс ид из таблицы камер, по ид девайсов можно получить ид м_сервера, из этого получаем информацию по м_серверам

//debug($recdata);
//debug($camera_id);
//debug($user_id);
//debug($Mserv_info);
?>
<script type="text/javascript" src="/assets/js/clappr.min.js"></script>
<?php
//$this->registerJsFile('/assets/js/clappr.min.js');

$code='
    <div>
    <br>
    URL For watch: '.$video_url.'
    </div>
    <div id="player"></div>
    <script>
    var player = new Clappr.Player({
    source: "'.$video_url.'",
    parentId: "#player",
    mute: true,
    autoPlay: true,
    aspectRatio: 16/9,
    width: 640,
    height: 360,
    responsive: true,
    disableErrorScreen: true,
    mediacontrol: {seekbar: "#E113D3", buttons: "#FFFFFF"},
    events: {
        onError: function(e) {
            setTimeout(() => { player.play()}, 500);
            }
        }
    });
    window.onload = player.play();
    </script>
    ';
echo $code;
?>