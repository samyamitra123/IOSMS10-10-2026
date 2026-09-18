<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

?>




<div class="botton">
          <ul>
            <a  href="" data-bs-toggle="modal" data-bs-target="#logout">
            <li><i class="fa fa-sign-out" ></i> <b>Logout</b></li>
            </a><a>
           
            <li>
            <i class="fa fa-life-ring"></i> <img src="<?= $config['base_url'] ?>themes/default/image/support_mail.png" style="width:160px" /></li>
            </a>
           
            <a>
				<li><i class="fa fa-phone"></i> 
				<span style="font-size:14px;font-weight:bolder;">9831519878<small class="text-right"> (Mon-Fri,10am-5pm)</small></span></li>
            </a>
            <?php 
			/*
			if(strlen($_SESSION['user_info']['stake_user']) == 7 || strlen($_SESSION['user_info']['stake_user']) == 10) {?>
            <a href="<?=$config['base_url'] ?>readwrite/iOSMS_prd_manual.pdf" target="_blank" rel="noopener noreferrer">
				<li><i class="fa fa-book"></i>  <span style="font-size:14px; font-weight:bolder;">Operations Manual</span></li>
            </a>
            
            <a href="<?=$config['base_url'] ?>readwrite/ifms_user_manual.pdf" target="_blank" rel="noopener noreferrer">
				<li><i class="fa fa-book"></i> <span style="font-size:12px; font-weight:bolder;">Salary Intregration Operations Manual</span></li></a>
            
            <a href="<?=$config['base_url'] ?>readwrite/User Manual of Seamless Integration in e-Billing.pdf" target="_blank" rel="noopener noreferrer">
				<li><i class="fa fa-book"></i> <span style="font-size:12px; font-weight:bolder;">Manual for IFMS Intregration of GP</span></li></a>
            <a href="<?=$config['base_url'] ?>readwrite/ROPA_2019_GP-converted.pdf" target="_blank" rel="noopener noreferrer">
				<li><i class="fa fa-book"></i> <span style="font-size:12px; font-weight:bolder;">User Manual For ROPA 2019</span></li>
                
            </a>
			
			
            
            <?php }
			 if(strlen($_SESSION['user_info']['stake_user']) == 4 && ($_SESSION['user_info']['stake_level']=='50' || $_SESSION['user_info']['stake_level']=='51' || $_SESSION['user_info']['stake_level']=='52' || $_SESSION['user_info']['stake_level']=='53' || $_SESSION['user_info']['stake_level']=='54' || $_SESSION['user_info']['stake_level']=='55'))
			 {
			 ?>
             	<a href="<?=$config['base_url'] ?>readwrite/iOSMS_prd_manual_zp.pdf" target="_blank" rel="noopener noreferrer">
                <li><i class="fa fa-book"></i>  <span style="font-size:14px; font-weight:bolder;">Operations Manual</span></li>
                </a>
            <?php } ?>
            <?php if(strlen($_SESSION['user_info']['stake_user']) == '4' && $_SESSION['user_info']['stake_level']=='53') {?> 
			 <a href="<?=$config['base_url'] ?>readwrite/manual_for_uploading_files_ifms.pdf" target="_blank" rel="noopener noreferrer">
            <li><i class="fa fa-info-circle"></i> <span style="font-size:14px; font-weight:bolder;">Manual for IFMS Intregration</span></li>
            </a>
            
            
            
             <?php }if($_SESSION['user_info']['stake_level']=='55' || $_SESSION['user_info']['stake_level']=='54' || $_SESSION['user_info']['stake_level']=='53') {?> 
			 <a href="<?=$config['base_url'] ?>readwrite/ROPA_2019_ZP-converted.pdf" target="_blank" rel="noopener noreferrer">
            <li><i class="fa fa-book"></i>  <span style="font-size:14px; font-weight:bolder;">User Manual For ROPA 2019</span></li>
            </a>
            
            
           
            
            
           
            
		 <?php	
        }if(strlen($_SESSION['user_info']['stake_user']) == 8) { ?>
	       <a href="<?=$config['base_url'] ?>readwrite/iOSMS_prd_manual_ps.pdf" target="_blank" rel="noopener noreferrer">
            <li><i class="fa fa-book"></i>  <span style="font-size:14px; font-weight:bolder;">Operations Manual</span></li>
            </a>
	         <a href="<?=$config['base_url'] ?>readwrite/manual_for_uploading_files_ifms.pdf" target="_blank" rel="noopener noreferrer">
            <li><i class="fa fa-info-circle" style="color:red"></i> <span style="font-size:11px; font-weight:bolder;">Manual for IFMS Intregration</span></li>
            </a>
             <a href="<?=$config['base_url'] ?>readwrite/iosms_to_ifms_user_manual.pdf" target="_blank" rel="noopener noreferrer">
		 <li><i class="fa fa-info-circle" style="color:red"></i> <span style="font-size:11px; font-weight:bolder;">Manual for Intregration</span>  </li>
            </a>
            <a href="<?=$config['base_url'] ?>readwrite/Copy_of_PS_which_do_not_have_999998_salary_scheme.pdf" target="_blank" rel="noopener noreferrer">
            <li><i class="fa fa-book"></i>  <span style="font-size:14px; font-weight:bolder;">Treasury Operator Code</span></li>
            </a>
            <a href="<?=$config['base_url'] ?>readwrite/ROPA_2019_PS-converted.pdf" target="_blank" rel="noopener noreferrer">
				<li><i class="fa fa-book"></i> <span style="font-size:12px; font-weight:bolder;">User Manual For ROPA 2019</span></li>
            </a>
	      <?php }  */ ?>
        
          </ul>
        </div>
<!---------------------------------Logout Modal--------------------------------------------- -->





<style>
#span_blink {
  opacity: 0;
  animation: blinking 1s linear infinite;
}

@keyframes blinking {
  from,
  49.9% {
    opacity: 0;
  }
  50%,
  to {
    opacity: 1;
  }
}
</style>
<div class="modal fade logout-modal-sm" id="logout" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
	 <h4 class="modal-title" id="myModalLabel">Confirm Logout</h4>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
      </div>
      <div class="modal-body"> 
      <p><strong>Are You Sure You Want To Logout ?</strong></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-success btn-sm" href="<?=$config['base_url'] ?>page/intra_pri/logout_intra_pri.php">YES</a> 
        <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">NO</button>      
      </div>
    </div>
  </div>
</div>