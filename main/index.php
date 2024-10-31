<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body scroll="no" style="overflow: hidden" class="cb-slideshow">
  <div class="content">
  <h3 class="top-title"></h3>
  <h1 class="main-title">Blacklagoongaming</h1>
  <a href="http://rust.blacklagoongaming.com">
  <div class="st-div" >
	<img src= "/img/RustLogo.png" style="width:175px; height:175px; margin-top: 40px">
    <h4 style="margin-top: -0px">Play Rust</h4>
  </div>
  </a>
  <a href="http://silkroad.blacklagoongaming.com">
  <div class="nd-div">
	<img src= "/img/SilkroadLogo.png" style="width:175px; height:175px; margin-top: 40px">
    <h4 style="margin-top: -0px">SilkRoad Online</h4>
  </div>
  </div>
  </a>
  
  <video id="my-video" class="video" muted loop>
    <source src="video/video-good.mp4" type="video/mp4">
    <!--<source src="media/demo.ogv" type="video/ogg">
    <source src="media/demo.webm" type="video/webm">-->
  </video>

  <script>
    (function() {
      /**
       * Video element
       * @type {HTMLElement}
       */
      var video = document.getElementById("my-video");

      /**
       * Check if video can play, and play it
       */
      video.addEventListener( "canplay", function() {
        video.play();
      });
    })();
  </script>

</body>
</html>
