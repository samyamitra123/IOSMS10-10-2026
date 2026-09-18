<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");
?>

<link rel="stylesheet" type="text/css" href="themes/default/slider/engine1/style_slider.css" />
	<!--SLIDER START-->
    <div class="slide">
        <div id="wowslider-container1">
            <div class="ws_images">
                <ul>
                  <li> <img src="themes/default/slider/data1/images/banner.jpg" alt="Human Resource Management System" title="Human Resource Management System" id="wows1_0"/> Education in India is provided by the public sector as well as the private sector, with control and funding coming from three levels: central, state, and local.<br /></li>
                </ul>
            </div>
            <span class="wsl"></span>
            <div class="ws_shadow"></div>
        </div>
    </div>
    <!--SLIDER END--> 
<script type="text/javascript" src="themes/default/slider/engine1/wowslider.js"></script>
<script type="text/javascript" src="themes/default/slider/engine1/script.js"></script>