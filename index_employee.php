<?php 
//header("Server:");
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
//header("X-Frame-Options: SAMEORIGIN");
//header("X-Content-Type-Options: nosniff");
//header("X-XSS-Protection: 1; mode=block");
/*header("Content-Security-Policy: default-src 'self' code.jequery.com 'unsafe-inline'; img-src 'self'; font-src 'self'; 
connect-src 'self'; 
form-action 'self'; frame-ancestors 'none'; ");*/

//header("Strict-Transport-Security: max-age=63072000");


session_start();
$_SESSION['test']='@#%2dsfg^&';
//print_r($_SESSION['user_info']);
$common['title'] = "P&RD | Govt. of West Bengal ";
//Meta tag variables
$common['meta']['keyword'] = 'West Bengal Panchayat and Rural Development Department, P&RD department';
$common['meta']['description'] = 'West Bengal Panchayat and Rural Development Department, P&RD department';
require 'includes/config/config.php';
require 'includes/config/database.config.php';
require 'includes/library/database.class.php';
require 'includes/library/cryptography.class.php';
$obj_crpto = new cryptography();

require 'page/layout/header.php' ?>

    <!-- Top menu -->
<?php require 'page/layout/menu.php' ?>

<?php 
//echo 'Current PHP version: ' . phpversion();
//echo 'Current PHP version: ' . apache_get_version();


$db=new database();
$notice=$db->fetch_table("select  
date(upload.date),
upload.page_title,
upload.meta_description,
upload.upload_id_pk,
ucategory.category,
upload_category_id_pk,
new_status
from prd_upload as upload
inner join prd_upload_category as ucategory
on ucategory.upload_category_id_pk=upload.upload_category_id_fk
where upload.upload_category_id_fk=ucategory.upload_category_id_pk AND upload.flag=1 AND ucategory.flag=1 AND ucategory.upload_category_id_pk='1'
order by upload.date DESC limit '7'");

$news=$db->fetch_table("select  date(upload.date),upload.page_title,upload.meta_description,upload.upload_id_pk,ucategory.category,upload_category_id_pk
from prd_upload as upload
inner join prd_upload_category as ucategory
on ucategory.upload_category_id_pk=upload.upload_category_id_fk
where upload.upload_category_id_fk=ucategory.upload_category_id_pk AND upload.flag=1 AND ucategory.flag=1 AND ucategory.upload_category_id_pk='2'
order by upload.date DESC limit '7'");
?>


  
    <div class="slider"> 
    	
		
		<style>
        @media screen and (min-width:1200px) {
        #containingDiv {
            width: 1002px;
            margin: 0 auto;
        }
        #bottomText {
            width: 100%;
            font: 18px 'Lato', sans-serif;
            color: #333333;
            border-top: 1px solid #cccccc;
            padding: 10px 0 0 0;
            margin-top: 100px;
            text-align: center;
        }
        }
        
        @media screen and (max-width:767px) {
        #containingDiv {
            width: 100%;
            margin: 0 auto;
        }
        #bottomText {
            width: 100%;
            font: 14px 'Lato', sans-serif;
            color: #333333;
            border-top: 1px solid #cccccc;
            padding: 10px 0 0 0;
            margin-top: 50px;
            text-align: center;
        }
        }
        /* --- */
        /* Tabs*/
            section {
                padding: 0px 0;
            }

            section .section-title {
                text-align: center;
                color: #007b5e;
                margin-bottom: 50px;
                text-transform: uppercase;
            }
            #tabs{
                    background: #007b5e;
                color: #eee;
            }
            #tabs h6.section-title{
                color: #eee;
            }

            #tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
                color: #f3f3f3;
                background-color: transparent;
                border-color: transparent transparent #f3f3f3;
                border-bottom: 4px solid !important;
                font-size: 20px;
                font-weight: bold;
            }
            #tabs .nav-tabs .nav-link {
                border: 1px solid transparent;
                border-top-left-radius: .25rem;
                border-top-right-radius: .25rem;
                color: #eee;
                font-size: 20px;
            }
        /* --- */
            .tab-pane li a {
                color: #ffffff !important;
                text-decoration: underline;
            }
            .nav-link.text-dark:hover {
                    color: #ffffff !important;
                    background: #4b8beb;
                }
                .nivo-controlNav {
                text-align: center;
                 padding: 0px 0 !important; 
            }
        </style>
		
        <script>
		jQuery(function() {

			jQuery('#allinone_carousel_powerful').owlCarousel({
				skin: 'powerful',
				width: 980,
				height: 500,
				responsive:true,
				autoPlay: 3,
				resizeImages:true,
				autoHideBottomNav:false,
				//easing:'easeOutBounce',
				numberOfVisibleItems:3,
				elementsHorizontalSpacing:250,
				elementsVerticalSpacing:25,
				verticalAdjustment:145,
				animationTime:0.6,
				showPreviewThumbs:false,
				showCircleTimer:false,
				nextPrevMarginTop:-52,
				playMovieMarginTop:0,
				bottomNavMarginBottom:-10
			});		
			
			
		});
	</script>
    
    	
   <div class="theme-default">
   <div id="slider" class="nivoSlider"> 

<img src="themes/default/img/slider_img1.jpg" title="" alt="" />  

<img src="themes/default/img/slider_img2.jpg" title="" alt="" />  
<img src="themes/default/img/slider_img3.jpg" title="" alt="" />  

</div>
    </div>
    </div>
    
  
    <div class="row" id="cont">
      <div class="col-lg-9 col-md-8 col-sm-8" id="sm-pad"> 
        <script type="text/javascript">
		
		
		$(document).ready(function() {
			
			
			
			$('#site_stop_modal').modal('show');
			
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});


    </script>
    
   
    
   <!--<img src="</?= $config['base_url']?>themes/default/image/new_animated.gif" style="vertical-align: text-top; margin-left: 2%;" width="50" height="16" />
							<h5 style="text-align: center;">
							<font color="red">
								Please use Latest version of Browser (MOZILLA FIREFOX /GOOGLE CHROME (etc.....)
							</font>
							
							</h5>-->
   
<!-- Tabs -->
<section id="tabs">
	<div class="container">
		<!--<h6 class="section-title h1">Tabs</h6>-->
		<div class="row">
			<div class="col-xs-12 ">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                              <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="fa fa-exclamation-circle"></i> NOTICE BOARD</button>
                            </li>
                            <li class="nav-item" role="presentation">
                              <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="fa fa-newspaper-o"></i> NEWS & EVENTS</button>
                            </li>
                            <!--<li class="nav-item" role="presentation">
                              <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Contact</button>
                            </li>-->
                          </ul>
                          <div class="tab-content py-3 px-3 px-sm-0" id="myTabContent">
                              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                  <?php 
			  /****************************************Changed By ANJAN Start *******************************************/
                                    //var_dump($notice); die;
                                    if(isset($notice)){
                                    foreach($notice as $key) { ?>
                          <li><a style="text-decoration:none" href="<?= $config['base_url']?>page/download.php?upload_id=<?= $key['upload_id_pk']?>&type=<?= $key['upload_category_id_pk']?>"><?=$key['page_title'] ?></a><?php  if(($key['new_status'])==0){ ?><img src="<?= $config['base_url']?>themes/default/image/new_animated.gif" style="vertical-align: text-top;"  /> <?php } ?></li>
                          <?php } 
                                    }
                                    ?>
                              </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"><?php 
			  //var_dump($news); die;
			  if(isset($news)){
			  foreach($news as $key) { ?>
                <li><a style="text-decoration:none" href="<?= $config['base_url']?>page/download.php?upload_id=<?= $key['upload_id_pk']?>&type=<?= $key['upload_category_id_pk']?>"><?=$key['page_title'] ?></a><img src="<?= $config['base_url']?>themes/default/image/new_animated.gif" style="vertical-align: text-top;"  /></li>			
			 <?php } 
			 }
			 /***********************************************END ****************************************************************/
			 ?></div>
                            <!--<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>-->
                          </div>
				
			
			</div>
		</div>
	</div>
</section>
<!-- ./Tabs -->                             
                            
        
      </div>
      
      <div class="col-lg-3 col-md-4 col-sm-4" id="sm-pad2">
        <?php require 'page/layout/right_sidebar_employee.php' ?>
      </div>
    </div>
    
     
    <div class="clear" style="margin-top:0;"></div>
    <?php require 'page/layout/footer.php' ?>
	
    
  
    
        
    <!--<div id="site_stop_modal" class="modal fade" text-align: center; >
    <div class="modal-dialog" >
    <div class="modal-content" style="width:195%; margin-left: -46%;">
    <div class="modal">
    <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
    </div>
    <div class="modal-body" >
    <h3 style="color:black;"> <b> <p>Important Notice:</p></b></h3>
    <form>
    <div class="form-group">
      <div class="col-sm-12" style="background-color:#F7FBFB;">
    
    <br>
    <h4 style="color: #035a95"><center><b>West Bengal State Emergency Relief Fund</b></center></h4>
    <h5><center><b>[The West Bengal State Emergency Relief Fund is a part of Chief Minister Relief Fund (PAN : AAAAC6443N)]</b></center></h5>
    <br>
    <h4 style="color: #035a95"><center>
    <span>The West Bengal Government appeals ALL to contribute generously to the</span><span><b> West Bengal State Emergency Relief Fund</b></span> <span> and assist in combating COVID-19 (CORONA). </span> </center></h4>
    
    <h4 style="color: rgb(73, 83, 9);"><center>The Contribution to this fund entitles you to 100% deduction under section 80G of the Income Tax Act.</center></h4>
    <b style="color: rgba(60, 119, 108, 0.87);"><center>The contribution can be made through Online Payment / NEFT / RTGS / UPI / Cheque / DD and in kind also.</center></b><br>
    <div class="col-sm-9">For making Payment to the Fund :</div>
     <br>
    <br>
    <div class="col-sm-9"><span>1.  Online Payment : Through Debit Card / Credit Card / UPI / Net Banking  </span>
      
    </div>
   
    <div class="col-sm-9">2.  For depositing Cheque / Demand Draft / Pay Order please deposit in any branch of ICICI Bank.</div>
    <br>
    <br>
    <br>
     <div class="col-sm-9">Details of Bank :</div>

    <div class="col-sm-9">
    <br>
    
    
    <table width="100%" border="1">
                     <tbody><tr></tr>

                      <tr><th>&nbsp;A/c Name :</th><td style="color: red;">&nbsp;West Bengal State Emergency Relief Fund</td></tr>
                      <tr><th>&nbsp;Bank :</th><td>&nbsp;ICICI Bank Ltd., Branch: Howrah,</td></tr>
                      <tr><th>&nbsp;A/c No : </th><td>&nbsp;628005501339</td></tr>
                      <tr><th>&nbsp;IFSC Code:  </th><td>&nbsp;ICIC0006280</td></tr>
                      <tr><th>&nbsp;MICR Code:   </th><td>&nbsp;700229010</td></tr>
                    </tbody>
    </table>
<br><br>
     <div>  3. For making payment from out of India &nbsp;:</div>
     <div>  Details of Bank &nbsp;:&nbsp; As above</div>
     <div>  SWIFT Code &nbsp;:&nbsp;&nbsp; ICICINBBCTS</div>
      <br><div>4. For contribution in kind contact:   &nbsp;<span style="color: blue;">wbsacs@gmail.com</span></div>
      <br> <div><b> For query,&nbsp; if any,&nbsp; Contact:</b></div>
      &nbsp;&nbsp;
       <div> 8777860955 &nbsp;/&nbsp; 7044075034 &nbsp;/&nbsp; 7980190741 &nbsp;/&nbsp; 6290907626</div>
      <div> 033 -7122-1088 &nbsp;/&nbsp; 7122-1089 &nbsp;/&nbsp; 7980190741 &nbsp;/&nbsp; 7122 -1090 </div>


    </div>   
   

  <div class="col-sm-3" style="border: 1px solid #dddddd;">
    <div>&nbsp;</div> 
    <span style="color: red;">Please CLICK the ICON below to make ONLINE PAYMENT</span> 
   
       <a target="_blank" rel="noopener noreferrer" class="btn btn-primary" style="width:180px;" href="https://eazypay.icicibank.com/eazypayLink?P1=m9BPa3/GAmP3nzLWEHC4zA==">West Bengal State<br> Emergency Relief Fund</a>
      <br><br><br>
         <div class="code_text" style="font-size: 20px; color: #035a95;font-weight: bold;">Use UPI ID : wbs.erf@icici or scan the QR code</div>
         
         <img src="<?= $config['base_url']?>themes/default/image/WhatsApp_Image.jpeg" style="vertical-align: text-top;width: 180px;height: 200px;">
 <br><br><br>
    <div class="ack_confirm" style="color:darkred"><b>Have you already contributed ? </b></div>
   
    <a href="https://excise.wb.gov.in/wbserf/Page/WBSERF_Generate_Receipt.aspx" style="width:188px;" target="_blank" rel="noopener noreferrer" class="btn btn-success">Download Acknowledgment<br> Receipt</a>
     
    
 <br><br>
       </div>   
    </div>
 &nbsp;
  

    
    
    </div>
    <div align="right">
    <button type="button" class="btn btn-warning" data-bs-dismiss="modal">Close</button> 
    </div>
    
    
    </form>
    </div>
    </div>
    </div>
    </div>-->