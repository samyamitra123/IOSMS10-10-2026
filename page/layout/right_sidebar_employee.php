
<?php

session_start();
error_reporting(0);

header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");

$crypto = new cryptography();

?>




<style>






a:focus,
a:hover,
a:active {
  outline: 0;
  text-decoration: none;
}

.panel {
  border-width: 0 0 1px 0;
  border-style: solid;
  border-color: #fff;
  background: none;
  box-shadow: none;
}

.panel:last-child {
  border-bottom: none;
}

.panel-group > .panel:first-child .panel-heading {
  border-radius: 4px 4px 0 0;
}

.panel-group .panel {
  border-radius: 0;
}

.panel-group .panel + .panel {
  margin-top: 0;
}

.panel-heading {
  background-color: #4eccd4;
  border-radius: 0;
  border: none;
  color: #fff;
  padding: 0;
}

.panel-title a {
  display: block;
  color: #fff;
  padding: 15px;
  position: relative;
  font-size: 16px;
  font-weight: 400;
}

.panel-body {
  background: #fff;
}

.panel:last-child .panel-body {
  border-radius: 0 0 4px 4px;
}

.panel:last-child .panel-heading {
  border-radius: 0 0 4px 4px;
  transition: border-radius 0.3s linear 0.2s;
}

.panel:last-child .panel-heading.active {
  border-radius: 0;
  transition: border-radius linear 0s;
}
/* #bs-collapse icon scale option */

.panel-heading a:before {
  content: '\e146';
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  font-size: 24px;
  transition: all 0.5s;
  transform: scale(1);
}

.panel-heading.active a:before {
  content: ' ';
  transition: all 0.5s;
  transform: scale(0);
}

#bs-collapse .panel-heading a:after {
  content: ' ';
  font-size: 24px;
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  transform: scale(0);
  transition: all 0.5s;
}

#bs-collapse .panel-heading.active a:after {
  content: '\e909';
  transform: scale(1);
  transition: all 0.5s;
}
/* #accordion rotate icon option */

#accordion .panel-heading a:before {
  content: '\e316';
  font-size: 24px;
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  transform: rotate(180deg);
  transition: all 0.5s;
}

#accordion .panel-heading.active a:before {
  transform: rotate(0deg);
  transition: all 0.5s;
}

</style>

<script>
    $(document).ready(function() {
		$('.office_emp_login_gp').hide();
		$('.office_emp_login_ps').hide();
		$('.office_emp_login_zp').hide();
		
  $('.collapse.in').prev('.panel-heading').addClass('active');
  $('#accordion, #bs-collapse')
    .on('show.bs.collapse', function(a) {
      $(a.target).prev('.panel-heading').addClass('active');
    })
    .on('hide.bs.collapse', function(a) {
      $(a.target).prev('.panel-heading').removeClass('active');
    });
});


   </script> 


<style>

.phone{
	height:100%;
	display:inline;
	}
.span{
	
font-size: 18px;
flex-basis: -moz-available;
margin-top:5px;
font-style: normal;
color:#728CB0;
display:inline-block;

/*font-family:"Palatino Linotype", "Book Antiqua", Palatino, serif;*/
}

.right_panel_login{/*border:#c2c2c2 1px solid;*/ padding:10px; padding-top:0px; border-radius:5px; background:#fff;-webkit-box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.47);
-moz-box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.47);
box-shadow: 0px 0px 5px 1px rgba(0,0,0,0.47);}
	
.right_panel_login a{
    font-size: 13px;
    display: block;
    background: #4eccd4;
    border-radius: 5px;
	border:#24aeb7 1px solid;
	transition:all 0.3s;
	margin-top:0px;
	color:#fff;
	width: 50%;
}

.right_panel_login a i{background: transparent; font-size:14px; padding-right:8px;font-size: 16px;}

.right_panel_login a:hover{
    background: #24aeb7;
}



</style>


<style>
.button {
  background-color: #4f86f7;
  border: none;
  color: white;
  padding: 16px 32px;
  text-align: center;
  font-size: 18px;
  margin: 4px 2px;
  opacity: 0.6;
  transition: 0.3s;
  display: inline-block;
  text-decoration: none;
  cursor: pointer;
  border-radius: 8px;
}

.button:hover {opacity: 1}
</style>


  <div class="botton">
          <ul>
          <?php 
				//var_dump($_SESSION);
				if(!isset($_SESSION['user_info']['stake_user'])){ 
		  
$gp='gp';
$ps='ps';
$zp='zp';
$emp='emp';
$intra_pri='intra_pri';

?>


<div class="right_panel_login">
	<span class="span">Login</span>
	      
		<input type="button" class="button" style="width: 100%" onclick="open_login_gp();" value="Gram Panchayat"/>
		  
			<div class="office_emp_login_gp" >
			  <a style="margin-top:3px;" href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $crypto->encode($gp,3); ?>"><i class="fa fa-key" style="color:red;"></i>Office Login</a>
			  <?php /*<a style="margin-top: -38px; margin-left: 53%;" href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $crypto->encode($emp,3); ?>"><i class="fa fa-key" style="color:#FF8A65;"></i>Employee Login</a>*/?>
			</div> 
		
		<input type="button" class="button" style="width: 100%" onclick="open_login_ps();" value="Panchayat Samiti"/>

			<div class="office_emp_login_ps" >
			  <a style="margin-top:5px;" href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $crypto->encode($ps,3); ?>"><i class="fa fa-key" style="color:#FF8A65;"></i>Office Login</a>
			  <?php /*<a style="margin-top: -38px; margin-left: 53%;" href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $crypto->encode($emp,3); ?>"><i class="fa fa-key" style="color:#FF8A65;"></i>Employee Login</a>*/ ?>
			</div>	
			
		<input type="button" class="button" style="width: 100%" onclick="open_login_zp();" value="Zilla Parishad"/>
		
			<div class="office_emp_login_zp" >
			  <a style="margin-top:5px;" href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $crypto->encode($zp,3); ?>"><i class="fa fa-key" style="color:#FBE9E7;"></i>Office Login</a>
			  <?php /*<a style="margin-top: -38px; margin-left: 53%;" href="<?=$config['base_url'] ?>page/login.php?val=<?php echo $crypto->encode($emp,3); ?>"><i class="fa fa-key" style="color:#FF8A65;"></i>Employee Login</a>*/?>
			</div>
			
	   	<?php /*<a type="button" class="button" style="width: 100%; background-color: #4f86f7;" href="<?=$config['base_url'] ?>page/intra_pri/login_intra_pri.php?val=<?php echo $crypto->encode($emp,3); ?>">Intra-PRD</a> */?>
       
</div>


      
      <!-- end of panel -->


<!--	==============================================    -->    
		    
		    
		    
                  
            <?php } 
			else { ?>
					<a href="<?=$config['base_url'] ?>page/dashboard.php">
						 <li><i class="fa fa-tachometer"></i> <b>Dashboard</b></li>
					</a>
               <?php } ?>
               
              
            </ul>
            
            
            
            
               <!--<span class="span">Technical Support</span>-->
            
            <ul>
             

            
             <!--<div style="margin-top:13px; padding-bottom:1px;" class="right_panel_login">-->
            <div style="margin-top:13px; padding-bottom:1px;">
            <!--<span class="span">Technical Support</span>-->
            <!--<img src="<?= $config['base_url'] ?>themes/default/img/tech_support_text.jpg" style="margin:3px 0 10px;" />-->
            <a href="http://43.239.110.159:8092/" target="_blank"><img src="<?= $config['base_url'] ?>themes/default/img/techsupport.png" style="margin:3px 0 10px;" /></a>
	      <!--<p><i class="fa fa-envelope-o"></i> support.priemp-wb@gov.in</p>
          <p><i class="fa fa-phone"></i> 9831519878 (Mon-Fri,10am-5pm)</p>-->

            </div>
          </ul><b style="font-size:12px;font-weight: bold; color:#a55134;"></b>
        </div>



<script>


function open_login_gp(){
	
	$('.office_emp_login_gp').show();
	$('.office_emp_login_ps').hide();
	$('.office_emp_login_zp').hide();
}

function open_login_ps(){
	
	$('.office_emp_login_gp').hide();
	$('.office_emp_login_ps').show();
	$('.office_emp_login_zp').hide();
}

function open_login_zp(){
	
	$('.office_emp_login_gp').hide();
	$('.office_emp_login_ps').hide();
	$('.office_emp_login_zp').show();
}
</script>