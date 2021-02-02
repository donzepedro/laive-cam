<html>
<script src="/assets/js/clappr.min.js"></script>

<div id="player"></div>
    <script>
    var player = new Clappr.Player({
    source: "https://127.0.0.7:1936/live-hls/7_61/playlist.m3u8?key=1",
    parentId: "#player",
    autoPlay: true,
    aspectRatio: 16/9,
    width: 640,
    height: 360,
    responsive: true,
    mediacontrol: {seekbar: "#E113D3", buttons: "#FFFFFF"},
    });
    window.onload = player.play();
    </script>
            </div>


<script src="/assets/2c450766/jquery.js"></script>
<script src="/assets/ae561945/yii.js"></script>
<script src="/assets/9a36b8e1/js/bootstrap.js"></script></body>
</html>