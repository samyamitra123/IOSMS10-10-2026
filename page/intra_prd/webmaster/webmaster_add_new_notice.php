<?php
session_start();
error_reporting(0);
if($_SERVER['HTTP_REFERER']==''){
	header("Location:../../dashboard.php");
}
?>

<?
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
require '../../../includes/library/database.class.php';
require '../../../includes/library/cryptography.class.php';
//require '../../../page_visite.php';
if (
	  !isset($_SESSION['user_info']['stake_user'])
	|| !isset($_SESSION['user_info']['stake_level'])
	|| !isset($_SESSION['user_info']['flag'])

	){
	header('Location: '. $config['base_url'] . "page/login.php");
	exit;
}
error_reporting(0);
$time_token=time();
$_SESSION['security_token']=$time_token;
$enc_token=md5('369'.$time_token);
//---------------------------- LIBRARY INCLUDE ----------------------------
//copy this two lines to every page
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
$common['title'] = "Profile Form| PRD | Govt. of West Bengal ";
//------------------------------------------------------- HEADER --------------------------------------------------------------
require '../../../page/layout/header.php';
//---------------------------------- MENU -------------------------------------------------------------------------------------
require '../../../page/layout/menu.php';
//-----------------------------Business Logic----------------------------------------------------------------------------------
$cryptoGraph=new cryptography();
if($_GET['confirm'] == 'success'){
	$msg='<div class="alert alert-success" style="text-align:center;"><strong>Upload Successful...</strong></div>';
}else if($_GET['confirm'] == 'false'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Data insertion failed. Please try again...</strong></div>';
}
else if($_GET['confirm'] == 'fails'){
	$msg='<div class="alert alert-danger" style="text-align:center;"><strong>Sorry !!! Upload Fails...</strong></div>';
}

$db=new database();
$data=$db->fetch_table("select upload_category_id_pk,category from prd_upload_category");

			if(isset($_GET['edit'])){
				
				$edit=$cryptoGraph->decode($_GET['edit'],4);
				$txttype=$cryptoGraph->decode($_GET['type'],4);
				
							$arr=$db->fetch_table("
											select file.oid,upload.*,ucategory.upload_category_id_pk,ucategory.category
	
											from prd_upload as upload
											inner join prd_upload_category as ucategory
												on ucategory.upload_category_id_pk=upload.upload_category_id_fk
											left join prd_file as file
												on file.upload_id_fk=upload.upload_id_pk
											where
												upload.flag in (1,2) AND
												ucategory.upload_category_id_pk='".$txttype."'  AND
												upload.upload_id_pk='".$edit."'
											order by 
												upload.upload_id_pk DESC
								");
				
			}


?>




<div class="content">
<? require '../../../page/common_back_btns.php'; ?>
  <div class="welcome_msg">
                      <h2>WELCOME:  <?php echo $_SESSION['user_info']['stake_abbr']; ?></h2>
               
</div>
<!-- Latest compiled and minified JavaScript -->
<div class="row" id="cont">
      <div class="col-lg-12 col-md-8 col-sm-8" id="sm-pad"> 
        <div class="col-sm-12">
<h1 class="heading">Upload Notice/News & Events</h1>
<div class="border"></div>
</br>
 <?php
		 
		 if(isset($error_msg)){
              	echo $error_msg;
          }
		 if(isset($_SESSION['error_msg1'])){
              	echo $_SESSION['error_msg1'];
				unset($_SESSION['error_msg1']);
          }
		 $cryptography=new cryptography();
        if(isset($_GET['msg']))
         {
			$msg2=$cryptography->decode($_GET['msg'],3);
	?>
    	<div class="alert alert-success" style="text-align:center"><strong><?php echo $msg2;?></strong></div><br>

<?php
          }
      else{
	$msg2="";
          }
?>
<strong style="color:#E93437;margin-left:25%"><noscript>This Form Is Blocked. Enable Javascript In Your Browser To View The Form.</noscript></strong>
<div id="form_show" class="dashcontenr invisible"> 
<form class="form-horizontal" id="notice_form" name="notice_form" method="post" enctype="multipart/form-data" action="webmaster_notice_form_submit.php" onsubmit="return valid_code();">
        <input type="hidden" name="sec_tok" id="sec_tok" value="<?=$enc_token?>" />
        <?php if(isset($_GET['edit'])){ ?>
          <input type="hidden" name="edit" value="<?php echo $edit; ?>" />
        <?php } ?>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Type<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <select class="form-control" name="txttype" id="txttype">
      <option value="">PLEASE SELECT</option>
      <? foreach($data as $item) { ?>
      <option value="<?=$item['upload_category_id_pk'] ?>" <? if($arr[0]['upload_category_id_fk']==$item['upload_category_id_pk']) { echo "selected"; } ?>><?=$item['category'] ?></option>
      <? } ?>
      </select>
    </div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Title<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="text" class="form-control" autocomplete="off" name="txttitle"  id="txttitle" placeholder="Enter A Suitable Title " onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ');" value="<?php if(!empty($_GET['edit'])){ echo $arr[0]['page_title']; }  ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
     <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Keywords</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" autocomplete="off" name="txtkeyword"  id="txtkeyword" placeholder="Enter Keywords seperated by 'comma'" onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz ,');" value="<?php if(isset($_GET['edit'])){ echo $arr[0]['meta_keyword']; }  ?>">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Short Description<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <textarea class="form-control" maxlength="250"  autocomplete="off" name="tashort"  id="tashort" placeholder="Enter Short Description"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .,');"><?php if(isset($_GET['edit'])){ echo $arr[0]['meta_description']; }  ?></textarea>
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Full Description<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <textarea style="height:420px;" class="form-control" autocomplete="off" name="talong"  id="talong" placeholder="Enter Content here"  onKeyPress="return keyRestrict(event,'0123456789abcdefghijklmnopqrstuvwxyz .,');"><?php if(isset($_GET['edit'])){ echo $arr[0]['description']; }  ?></textarea>
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-2"></div>
    <label for="inputPassword3" class="col-sm-3 control-label">Attached Document<span class="star_color">*</span></label>
    <div class="col-sm-4">
      <input type="file" class="form-control upper_case" autocomplete="off" name="order_file"  id="order_file" value="">
    </div>
    <div class="col-sm-3"></div>
    </div>
    <div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
      <span style="color:#838683;font-weight:bold;">File Size must not be 0 and maximum file size should be 2MB.</span>
    </div>
  </div>
    <div class="form-group">
    <div class="col-sm-offset-5 col-sm-7">
      <button type="submit" name="paychange_order_submit" id="btnSubmit" class="btn btn-info">SUBMIT</button>
    </div>
  </div>
</form>
<div class="clear"></div>
</div>
</div>
</div>
</div>
</div>
<? require '../../../page/layout/footer.php'; ?>

<script>
$(document).ready(function(){
		if($('#form_show').css("visibility")=="hidden"){
				$('#form_show').removeClass("invisible").css('height', 'auto');
			}
		});


</script>
<script type="text/javascript">
$(document).ready(function (e)
{
$("#notice_form").submit(function(e) {
	
			var file=$("#order_file").val(); 
			//alert(file);
				//var exts = ['doc','docx','rtf','odt'];
		var exts = ['pdf'];
		if($("#txttype").val()=="")
		{
			alert("Please Enter Type");
			$("#txttype").focus();
			return false;
		}
		if($("#txttitle").val()=="")
		{
			alert("Please Enter Title");
			$("#txttitle").focus();
			return false;
		}
		if($("#tashort").val()=="")
		{
			alert("Please Enter Short Description");
			$("#tashort").focus();
			return false;
		}
		if($("#talong").val()=="")
		{
			alert("Please Enter Long Description");
			$("#talong").focus();
			return false;
		}

		if( file == '')
		{
			alert("Please Select a File");
			$("#order_file").focus();
			return false;
		}
		if (file) 
		{
			var filesize=$("#order_file")[0].files[0].size;
			//alert(filesize);
        	var get_ext = file.split('.');
	        get_ext = get_ext.reverse();
			//alert(get_ext) ;
			
	        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) == -1 )
			{
    	      	//$("#error_file").html('Sorry Invalid File Choosen'); //proper file choosen with extn
				alert("Sorry Invalid File Choosen");
				return false;
        	}
			if(filesize > 41943040)
			{
				$("#error_file").html('Sorry File is too Large'); //size check
				return false;
			}
      	}
		// validation end
		
	});
});


</script>
