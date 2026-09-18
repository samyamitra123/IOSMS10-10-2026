<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
/*header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self' https://code.jequery.com; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");

header("Strict-Transport-Security: max-age=63072000");*/
?>
<div id="footer">
      <!--<div class="row">
        <div class="col-sm-12 text-center">
          <p class="copy">WBPRD © 2015 - 2016 Integrated Online Salary Management System - All Rights Reserved</p>
          <p>Designed and Developed by <img src="</div>/?php //echo $config['base_url']; ?>themes/default/img/logo.png"></p>
        </div>
      </div>-->
      
      
      <!--<div class="col-sm-12" style="background-color:white;">

                    <br>
                    <h4 style="color: #035a95"><b>
The West Bengal State Government Appeals ALL to contribute in West Bengal State Emergency Relief Fund and assist the State in prevention and control of situation arising out of unforeseen emergencies like COVID-19 (CORONA).</b> </h4>
                  
                   <div class="col-sm-9">
                  <b style="color: rgba(60, 119, 108, 0.87);">The contributions can be made Through Cheque / DD / Debit Card / Credit Card / UPI and kind.
Bank details for Cheque / DD / Debit Card / Credit Card / UPI contribution are as follows:</b><br><table width="100%" border="1">
                     <tbody><tr></tr>

                      <tr><th>A/c Name :</th><td>West Bengal State Emergency Relief Fund</td></tr>
                      <tr><th>Bank :</th><td>ICICI Bank Ltd., Branch: Howrah,</td></tr>
                      <tr><th>A/c No : </th><td>628005501339</td></tr>
                      <tr><th>IFSC Code:  </th><td>ICIC0006280</td></tr>
                       <tr><th>Swift Code :  </th><td>ICICINBBCTS</td></tr>
                      <tr><th>MICR Code:   </th><td>700229010</td></tr>
                      <tr><th>For contribution in kind contact:   </th><td>wbsacs@gmail.com</td></tr>

                  </tbody></table>
                   
                   </div>   
                   <div class="col-sm-3">
                   <br><br><br><br>
                    <span style="color: red;">Please CLICK the ICON below to make ONLINE PAYMENT</span> 
                    <br>
                    <a target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width:180px;" href="https://eazypay.icicibank.com/eazypayLink?P1=m9BPa3/GAmP3nzLWEHC4zA==">West Bengal State<br> Emergency Relief Fund</a>
                   </div> 
                   
 <div>&nbsp;</div> 
  <div>&nbsp;</div> 

</div>-->
      
     <!-- <div>&nbsp;</div> -->
      
      <p>Designed and Developed By IT And Statistical Cell, Panchayat & Rural Dev. Dept., Govt. of West Bengal </p>
      <div class="row">
          <div class="col-sm-8">
          <?php $date=date_create("Y");?>
          	<b>WBPRD © 2015 - <?php echo  date_format($date,"Y"); ?> Integrated Online Salary Management System - All Rights Reserved</b>
          </div>
           <div class="col-sm-4 text-right">
           		<!--<b>Designed and Developed by</b>--> <!--<img src="<?php echo $config['base_url']; ?>themes/default/img/logo.png">-->
           </div>
      </div>
      <div>&nbsp;</div> 
    </div>

<!--<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"themes/default/SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
</script>
-->

</div>

<link rel="stylesheet" type="text/css" href="<?php echo $config['base_url']; ?>themes/default/css/nivo-slider_3.2.css">





<script type="text/javascript" src="<?php echo $config['base_url']; ?>themes/default/js/jquery.nivo.slider_3.2.js"></script> 
    
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider({
		effect: 'fade', //change this from 'random' to whatever
slices: 15,
boxCols: 8,
boxRows: 4,
animSpeed: 500,
pauseTime: 3000,
startSlide: 0,
directionNav: true,
directionNavHide: true,
controlNav: true,
controlNavThumbs: false,
controlNavThumbsFromRel: false,
controlNavThumbsSearch: '.jpg',
controlNavThumbsReplace: '_thumb.jpg',
keyboardNav: true,
pauseOnHover: true,
manualAdvance: false,
captionOpacity: 0.8,
prevText: 'Prev',
nextText: 'Next',
randomStart: false,
beforeChange: function(){},
afterChange: function(){},
slideshowEnd: function(){},
lastSlide: function(){},
afterLoad: function(){}
		
		
		});
    });
    </script>


<script>
function session_timeout(){
 $(document).ready(function(e) {
    $("#timeout").modal('show');
 });
}
function session_destroy(){
	window.location.replace('<?php echo $config['base_url'] ?>page/logout.php');
}
</script>
</body>
</html>

<?php
//session_start();

if(!empty($_SESSION['user_info']['stake_abbr']))
{
if($_SESSION['user_info']['stake_abbr']=='DA' || $_SESSION['user_info']['stake_abbr']=='EO' || $_SESSION['user_info']['stake_abbr']=='GP' || $_SESSION['user_info']['stake_abbr']=='BDO' || $_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Establishment)' ||  $_SESSION['user_info']['stake_abbr']=='SECRETARY' ||  $_SESSION['user_info']['stake_abbr']=='AEO' ||  $_SESSION['user_info']['stake_abbr']=='DEALING ASSISTANT (Account)' ||  $_SESSION['user_info']['stake_abbr']=='ACCOUNTANT' ||  $_SESSION['user_info']['stake_abbr']=='FC&CAO' ||   $_SESSION['user_info']['stake_abbr']=='ADMINISTRATOR' )
{
	//$_SESSION['ct']=1;
	//echo time() - trim($_SESSION['LAST_ACTIVITY']);
	
	if (isset($_SESSION['LAST_ACTIVITY']) && (time() - trim($_SESSION['LAST_ACTIVITY'])    > 1800)) {
//if (time() - trim($_SESSION['LAST_ACTIVITY']) > 1800)
 //{
	//$_SESSION['ct']++;
	
    $_SESSION['LAST_ACTIVITY']; 
 ?>
 
    <script>
    	setInterval(session_timeout(),300);
    </script>
<?php
 }
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
 }
}
?>






<!---------------------------------Logout Modal--------------------------------------------- -->

<div class="modal fade logout-modal-sm" id="timeout" tabindex="-1" data-keyboard="false" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
     <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Session Timeout</h4>
      </div>
      <div class="modal-body timeout"> 
      <p><strong>Session timeout occur, please login again</strong></p>
      </div>
      <div class="modal-footer">
        <a class="btn btn-success btn-sm" onclick="session_destroy()">OK</a>       
      </div>
    </div>
  </div>
</div>