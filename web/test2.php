<script src="/assets/js/clappr.min.js"></script>
<div id="player"></div>
    <script>
    var player = new Clappr.Player({
    source: "https://eduvideo.petrsu.ru:1936/zoomTest/_definst_/test/playlist.m3u8",
    parentId: "#player",
    autoPlay: true,
    aspectRatio: 16/9,
    width: 640,
    height: 360,
    responsive: true,
    mediacontrol: {seekbar: "#E113D3", buttons: "#66B2FF"}
    });
    </script>
