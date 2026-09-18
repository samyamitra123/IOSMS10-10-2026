<?php 

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

  
session_start();
require '../../includes/config/config.php';
require_once '../../includes/config/database.config.php';
include_once '../../includes/library/database.class.php';
include_once '../../includes/library/cryptography.class.php';


include 'change_password_form_intra_pri.php';
?>
<?php
//----------------------------------- FOOTER ----------------------------------------------------------------------------------
require '../../page/layout/footer.php';
//----------------------------------------------------------------------------------------------------------------------------
?>
