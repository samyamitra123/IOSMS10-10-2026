<?php

session_start();
error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");


include '../../includes/config/config.php';
//---------------------------------- HEADER -------------------------------------------------------------------------------------
include '../layout/header.php';
include '../layout/menu.php';

?> 
<div class="content">
	<div style="height:350px; padding:0 0 0 70px;">
    <img src="<?=$config['base_url'] ?>themes/default/image/page_under_construction.gif" style="padding-left: 16%;padding-top: 4%;" /> 
        
		
		
       
      </div> 
      <div class="clear"></div>
      </div>
      
      
<?php
//----------------------------------- FOOTER --------------------------------------------------------------------------------------
//include 'layout/footer.php';
include '../layout/footer.php';?>
 
</body>
</html>
