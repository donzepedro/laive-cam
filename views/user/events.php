<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use Firebase\JWT\JWT;
$jwt=new JWT;
$this->registerCssFile('/css/user/events.css',['depends' => [yii\bootstrap\BootstrapPluginAsset::class]]);
?>
<script type="text/javascript" src="/assets/js/clappr.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
<div class="alls">
    <div class="leftblock">
        <div class="datepickwrap">
            <div class="datepick">
                <div class="" id="datenow-panel">
                        <div class="panel-body">
                            <div class="h4" onclick="ShowHideCalendar('calendar_index');">
                                <span><img src="/078-calendar2_p2.png"></span>
                                <span style="margin-left: 5px;cursor:pointer" id="datenow_index"></span>
                            </div>
                            <div style="overflow: hidden; display: none;" id="calendar_index">
                                <div class="form-group">
                                    <div class="row input-group calendar">
                                        <div id="datetimepicker_index"><div class="bootstrap-datetimepicker-widget" style="display: block;"></div></div>
                                    </div>
                                </div>
                            </div>
                                <script type="text/javascript">
                                    var getpath = new URL(window.location.href);
                                    var date = new Date();
                                    var options = {year: 'numeric', month: 'long', day: 'numeric', weekday: 'long',
                                        timezone: 'UTC'};
                                    var dt = date.toLocaleString("en", options);
                                    var s = dt.substr(0,1);
                                    s = s.toUpperCase();
                                    dt = s + dt.substr(1);
                                    document.getElementById('datenow_index').innerHTML = dt;
                                </script>

                        </div>
                    </div>
            <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data','class'=>'datenow-form']]) ?>
            <?php
            $i=1;
            if (Yii::$app->language == 'ru') $lang = 'ru';
                $lang = Yii::$app->language == 'ru' ? 'ru' : 'en';
                $value = $filtdate ? date('d-m-Y',$filtdate) : date('d-m-Y');
                 DatePicker::widget([
                    'type'=>DatePicker::TYPE_COMPONENT_PREPEND,
                    'name' => 'check_issue_date',
                    'language'=>$lang,
                    'removeButton' => false,
                    'size' => 'lg',
                    'value' => $value,
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
            <input type="hidden" name="date" id="datenow_index1">
            </div>
            <div class="date-btn">
                <?= Html::submitButton('', ['class' => 'date-btn']) ?>
            </div>
        <?php ActiveForm::end(); ?>
        </div>
            <div class="reclistwrap">
                <div class="rec-head">
                    <div class="panel-heading" id="padding-panel" style="display: inline-flex;width: 100%">
                            <h4 class="panel-title" style="line-height: 22px;"><?=Yii::t('commonEn', 'События')?>:</h4>
                            <div class="search-title">
                              <form action="/user/events" method="get" class="form-title">
                                <input type="text" class="form-control" id="searchinput" placeholder="Search..." name="searchtitle">
                                <span class="glyphicon glyphicon-search form-control-feedback"></span>
                              </form>
                            </div>
                            <div class="filter-modal">
                              <a href="#" data-toggle="modal" data-target="#filter-modal"><span class="glyphicon glyphicon-filter form-control-feedback"></span>Filter</a>
                            </div>
                            <a class="event-refresh" href="/user/events" title="refresh streams"><span class="glyphicon glyphicon-refresh"></span> Refresh</a>
                        </div>
                </div>

                <span class="subject"># <?= Yii::t('commonEn', 'Тема'); ?></span>
                <span class="time"><?= Yii::t('commonEn', 'Время'); ?></span>
            <?php if($filtdate != null): ?>
                <?php if($filtdate != null) :?>
                    <?php $printdate = ''; ?>
                <?php else: ?>
                    <?php $printdate = $titles[0]['date'] ?>
                <div class="recdate">
                    <?= $printdate; ?>
                </div>
            <?php endif ?>
            <?php for($i = 1; $i <= count($titles); $i++):?>
                <?php if($filtdate == strtotime($titles[$i-1]['date'])): ?>
                    <?php if($printdate != $titles[$i-1]['date']): ?>
                        <?php $printdate = $titles[$i-1]['date'] ?>
                        <div class="recdate">
                            <?= $printdate ?>
                        </div>
                    <?php endif;?>
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
                        <?php if($i%2==0):?>
                            <div class="eachwrap2">
                        <?php else :?>
                            <div class="eachwrap1">
                        <?php endif;?>
                                <div class="num">
                                    <?= $i ?>
                                </div>
                                <div class="eachtitle">
                                    <?= $titles[$i-1]['title']; ?>
                                </div>
                                <div class="eachdate">
                                    <?= $titles[$i-1]['date']; ?><br>
                                    <?= $titles[$i-1]['time']; ?>
                                </div>
                            </div>
                    </a>
                    <?php endif;?>
            <?php endfor;?>
        <?php elseif(!empty($titles)) :?>
            <?php $printdate = $titles[0]['date'] ?>
            <div class="recdate">
                <?= $printdate; ?>
            </div>
            <?php for($i = 1; $i <= count($titles); $i++):?>
                <?php if($printdate != $titles[$i-1]['date']): ?>
                    <?php $printdate = $titles[$i-1]['date'] ?>
                    <div class="recdate">
                        <?= $printdate; ?>
                    </div>
                <?php endif;?>
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
                    <?php if($i%2==0):?>
                        <div class="eachwrap2">
                    <?php else :?>
                        <div class="eachwrap1">
                    <?php endif;?>
                            <div class="num">
                                <?= $i ?>
                            </div>
                            <div class="eachtitle">
                                <?= $titles[$i-1]['title']; ?>
                            </div>
                            <div class="eachdate">
                                <?= $titles[$i-1]['date']; ?><br>
                                <?= $titles[$i-1]['time']; ?>
                            </div>
                        </div>
                </a>
            <?php endfor;?>
        <?php endif; ?>
        <?php if(count($titles)==0): ?>
            <div class="recdate">
                <?= $created_at; ?>
            </div>
            <div class="eachwrap1">
                <div class="num">
                    <?= 1 ?>
                </div>
                <div class="eachtitle">
                  <span>Registration in Web Camera Pro</span>
                </div>
                <div class="eachdate">
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
</div>
<div class="">
    <?php
        $jwtkey = \Yii::$app->request->cookies;
        $jwtkey = $jwtkey->getValue('trust','trustisnotset');
        //    debug ($titles);
    ?>
    <?php if(count($titles)!=0): ?>
        <div class="playerswrap">
            <div class="rec-head">
                <div class="row">
                     <div class="col-sm-4">
                     </div>
                     <div class="col-sm-4">
                         <div class="text-center tariff-plan">Tariff:<a href="#" class="active">Free plan</a></div>
                     </div>
                     <div class="col-sm-4">
                         <a class="event-refresh" href="/user/events" title="refresh streams">
                         <span class="glyphicon glyphicon-refresh"></span> Refresh</a>
                     </div>
                </div>
            </div>
            <div class="eachplayer">
                <?php for($i=0; $i<=count($titles)-1; $i++):
                    $video_url='https://'.$titles[$i]['s_address'].':'.$titles[$i]['s_port'].'/vod/'.$user_id.'/'.$titles[$i]['camera_id'].'/'.$titles[$i]['name'].'/index.m3u8?key='.$jwtkey;
                    $video_img_url='https://'.$titles[$i]['s_address'].':'.$titles[$i]['s_port'].'/vod/'.$user_id.'/'.$titles[$i]['camera_id'].'/'.$titles[$i]['name'].'.png';
                ?>
                <?php if($filtdate && $filtdate == strtotime($titles[$i]['date'])):?>
                    <div class="eachrec col-sm-4">
                        <div class="playerwindow" id="player<?=$i?>"></div>
                        <script>
                            var player = new Clappr.Player({
                                source: "<?=$video_url?>",
				poster: "<?=$video_img_url?>",
                                parentId: "#player<?=$i?>",
                                mute: true,
                                autoPlay: false,
                                aspectRatio: 16/9,
                                width: 140,
                                height: 200,
                                responsive: true,
                                disableErrorScreen: true,
                                mediacontrol: {seekbar: "#E113D3", buttons: "#FFFFFF"}
                            });
                        </script>
                        <p class="cyan"><?= $titles[$i]['title']?></p>
                    </div>
                <?php elseif(!isset($filtdate)): ?>
                    <div class="eachrec col-sm-4">
                        <div class="playerwindow" id="player<?=$i?>"></div>
                        <script>
                            var player = new Clappr.Player({
                                source: "<?=$video_url?>",
				poster: "<?=$video_img_url?>",
                                parentId: "#player<?=$i?>",
                                mute: true,
                                autoPlay: false,
                                aspectRatio: 16/9,
                                width: 140,
                                height: 200,
                                responsive: true,
                                disableErrorScreen: true,
                                mediacontrol: {seekbar: "#E113D3", buttons: "#FFFFFF"}
                            });
                        </script>
                        <p class="cyan"><?= $titles[$i]['title']?></p>
                    </div>
                <?php else: ?>
                    <div class="eachrec col-sm-4">
                    </div>
                <?php endif; ?>
                <?php endfor;?>
                <?php if (count($titles) > 5): ?>
                    <div class="col-sm-12 text-center">
                        <button type="button" name="button" class="btn btn-primary" style="margin-bottom:15px;" id="showmore">More</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php elseif(count($titles)!=0):?>
        <div class="playerswrap">
            <div class="rec-head"> Refresh </div>
            <div class="eachplayer">
                <?php for($i=0; $i<=count($titles)-1; $i++):
                    if($filtdate == strtotime($titles[$i]['date'])):
                    //				$video_url='https://videolivejournal.com:1936/vod/'.$user_id.'/'.$titles[$i]['camera_id'].'/'.$titles[$i]['name'].'/index.m3u8?key='.$jwtkey;
                    $video_url='https://'.$titles[$i]['s_address'].':'.$titles[$i]['s_port'].'/vod/'.$user_id.'/'.$titles[$i]['camera_id'].'/'.$titles[$i]['name'].'/index.m3u8?key='.$jwtkey;
                    ?>
                    <div class="eachrec col-sm-4">
                        <div class="playerwindow" id="player<?=$i?>"></div>
                        <script>
                            var player = new Clappr.Player({
                                source: "<?=$video_url?>",
                                parentId: "#player<?=$i?>",
                                mute: true,
                                autoPlay: false,
                                aspectRatio: 16/9,
                                width: 140,
                                height: 200,
                                responsive: true,
                                disableErrorScreen: true,
                                mediacontrol: {seekbar: "#E113D3", buttons: "#FFFFFF"}
                            });
                        </script>
                        <p class="cyan"><?= $titles[$i]['title']?></p>
                    </div>
                    <?php endif ?>
                <?php endfor;?>
                <?php if (count($titles) > 5): ?>
                    <div class="col-sm-12 text-center">
                        <button type="button" name="button" class="btn btn-primary" style="margin-bottom:15px;" id="showmore">More</button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (count($titles)==0): ?>
        <div class="playerswrap">
            <div class="rec-head"> <div class="text-center tariff-plan">Tarif:<a href="#" class="active">Free plan</a></div></div>
            <div class="eachplayer">
                <div class="row">
                    <div class="col-sm-6 col-lg-6">
                        <iframe class="iFramePlayer" width="560" height="220" src="https://www.youtube.com/embed/ReNXx6jdD-A?rel=0&amp;enablejsapi=1" frameborder="0" allowfullscreen=""></iframe>
                        <span>Web Camera Cloud - Video Surveillance</span>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <iframe class="iFramePlayer" width="560" height="220" src="https://www.youtube.com/embed/WyxmbfqZcOc?rel=0&amp;enablejsapi=1" frameborder="0" allowfullscreen=""></iframe>
                        <span>Web Camera Cloud. Video surveillance over the Internet</span>
                    </div>
                </div>
                <div class="row paddingtop">
                    <div class="col-sm-6 col-lg-6">
                        <iframe class="iFramePlayer" width="560" height="220" src="https://www.youtube.com/embed/zN2agUUPKXU?rel=0&amp;enablejsapi=1" frameborder="0" allowfullscreen=""></iframe>
                        <span>Webcam Cloud  - Video surveillance</span>
                    </div>
                    <div class="col-sm-6 col-lg-6">
                        <iframe class="iFramePlayer" width="560" height="220" src="https://www.youtube.com/embed/V7HyHmpZ2dw?rel=0&amp;enablejsapi=1" frameborder="0" allowfullscreen=""></iframe>
                        <span>Web Camera Cloud.  Object Detection.  Computer vision</span>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>
</div>
<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-labelledby="filtermodalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content panel-primary filter-form">
      <div class="modal-header panel-heading">
        <h3 class="modal-title" id="exampleModalLabel">Filter</h3>
      </div>
      <?php $form=ActiveForm::begin(['method'=>'post', 'action'=>'']) ?>
        <div class="modal-body text-center row">
          <div class="col-sm-12">
            <div class="panel panel-primary" id="datenow-panel1">
                <div class="">
                    <div class="h4" onclick="ShowHideCalendar('calendar');">
                        <span><img src="/078-calendar2_p2.png"></span>
                        <span style="margin-left: 5px;cursor:pointer" id="datenow">Thursday, October 22, 2020</span>
                    </div>
                    <div style="overflow:hidden;display:none" id="calendar">
                        <div class="form-group">
                            <div class="row input-group calendar">
                                <div id="datetimepicker12"><div class="bootstrap-datetimepicker-widget" style="display: block;"></div></div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var date = new Date();
                        var options = {year: 'numeric', month: 'long', day: 'numeric', weekday: 'long',
                            timezone: 'UTC'};
                        var dt = date.toLocaleString("en", options);
                        var s = dt.substr(0,1);
                        s = s.toUpperCase();
                        dt = s + dt.substr(1);
                        document.getElementById('datenow1').innerHTML = dt;
                    </script>
                </div>
            </div>
            <input type="hidden" id="datenow1" value="2020-10-22" name="date">
            <p>Time</p>
               <div class="col-sm-12 inline-block date-files-block">

                  <label for="time_s">From</label>
                  <?= $form->field($model,'time_s')->textInput(['id'=>'time_s','class' =>'form-control','style'=>'width:220px'])?>
                 <!-- <input type="text" name="time_s" class="form-control" id="time_s">-->
                  <label for="time_po">To</label>
                  <?= $form->field($model,'time_po')->textInput(['id'=>'time_po','class' =>'form-control','style'=>'width:220px'])?>
                  <!--<input type="text" name="time_po" class="form-control" id="time_po">-->
              </div>

          </div>
          <div class="col-sm-10 col-sm-offset-1">
            <div class="search-tag">
                  <?= $form->field($model,'searchtitle')->textInput(['class' =>'form-control','id'=>'searchtag','placeholder'=>"Search..."])->label(false)?>
             <!-- <input type="text" class="form-control" id="searchtag" name="searchtitle" placeholder="Search...">-->
              <span class="glyphicon glyphicon-remove form-control-feedback" onclick="$('#searchtag').val('')"></span>
            </div>
          </div>
        </div>
      <div class="modal-footer text-center">
          <?= Html::submitButton("Apply", ['class' => 'btn btn-primary', 'onclick'=>'saveing()','style'=>'outline:none;']) ?>
          <?= Html::button("Reset", ['class' => 'btn btn-close', 'onclick'=>"$('#searchtag').val('');$('#datenow1').val('');
                                                                                               $('#time_s').val('');$('#time_po').val('')",'style'=>'outline:none;']) ?>
         <?= Html::button("Cancel", ['class' => 'btn btn-close', 'data-dismiss'=>'modal', 'style'=>'outline:none;']) ?>
        <!--<button type="submit" class="btn btn-primary" onclick="saveing()">Apply</button>
        <button type="button" class="btn btn-close" onclick="$('#searchtag').val('');$('#datenow1').val(''); $('#time_s').val('');$('#time_po').val('')">Reset</button>
        <button type="button" class="btn btn-close" data-dismiss="modal">Cancel</button>-->
      </div>
     <?php ActiveForm::end() ?>
    </div>
  </div>
</div>
<script type="text/javascript">
    $("#showmore").click(function() {
        $('.eachplayer .eachrec:hidden').slice(0, 15).show();
        if ($('.eachplayer .eachrec').length == $('.eachplayer .eachrec:visible').length) {
            $('#showmore').hide();
        }
    });
    function ShowHideCalendar(elem)
    {
        var d=document.getElementById(elem);
        if (d.style.display=='none')
            d.style.display='';
            else
            d.style.display='none';
    }
    if ($('#datetimepicker_index').length != 0) {
        $('#datetimepicker_index').datetimepicker({
            inline: true,
            sideBySide: false,
            defaultDate:  new Date()
        }).on("dp.change",function (e) {
            var selected = e.date._d;
            var options = {year: 'numeric', month: 'long', day: 'numeric', weekday: 'long',
                timezone: 'UTC'};
            var y1 = selected.getFullYear(), m1 = selected.getMonth()+1, d1 = selected.getDate();
            if (m1<10) {
                var currdate1 = y1+"-0"+m1+"-"+d1;
            } else {
                var currdate1 = y1+"-"+m1+"-"+d1;
            }
            $('#datenow_index1').val(currdate1);
            var dt = selected.toLocaleString("en", options);
            var y = selected.getFullYear(), m = selected.getMonth()+1, d = selected.getDate();
            if (m<10) {
                var currdate = y+"-0"+m+"-"+d;
            } else {
                var currdate = y+"-"+m+"-"+d;
            }
            $('#datenow_index1').val(currdate);
            $('.datenow-form').submit();
            $('#calendar_index').fadeOut();
            // location.reload();
        });
    }
    if ($('#datetimepicker12').length != 0) {
        $('#datetimepicker12').datetimepicker({
            inline: true,
            sideBySide: false,
            defaultDate:  new Date()
        }).on("dp.change",function (e) {
            var selected = e.date._d;
            var options = {year: 'numeric', month: 'long', day: 'numeric', weekday: 'long',
                timezone: 'UTC'};
            var y1 = selected.getFullYear(), m1 = selected.getMonth()+1, d1 = selected.getDate();
            if (m1<10) {
                var currdate1 = y1+"-0"+m1+"-"+d1;
            } else {
                var currdate1 = y1+"-"+m1+"-"+d1;
            }
            $('#datenow1').val(currdate1);
            var dt = selected.toLocaleString("en", options);
            var y = selected.getFullYear(), m = selected.getMonth()+1, d = selected.getDate();
            if (m<10) {
                var currdate = y+"-0"+m+"-"+d;
            } else {
                var currdate = y+"-"+m+"-"+d;
            }
            var currdate = y+"-0"+m+"-"+d;
            $(".eventall").attr('data-key',currdate);
            $('#datenow').html(dt);

            $('.action-group').fadeOut();
            $('.action-group#actions-'+currdate).fadeIn();

            $('.eventall .smallstream').each(function () {
                if ($(this).attr('data-key')==currdate) {
                  $(this).fadeIn();
                  $(this).addClass("showing");
                }
            });
            $('#calendar').fadeOut();

        });
    }
</script>
