<?php
header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, post-check=0, pre-check=0");
header("Pragma: no-cache");
error_reporting(0);
session_start();
require '../../../includes/config/config.php';
require_once '../../../includes/config/database.config.php';
include_once ('../../../includes/library/database.class.php');
include_once '../../../includes/library/myvalidation.class.php';
require '../../../includes/library/qrtstr_encrp.php';
include_once '../../../includes/library/cryptography.class.php';
include_once '../../../includes/library/file_cache.class.php';
//require '../../page_visite.php';
//print_r($_POST);
function dbdate($caldate){
				if($caldate=="" || $caldate=="0001-01-01"){
						$redate="0001-01-01";
				}else{
 						$tmp=explode("-",$caldate);
						$redate=$tmp[2]."-".$tmp[1]."-".$tmp[0];
				}
  return  $redate;
 }
?>
<?php 
		
	$sec_time_token=$_POST['sec_tok'];
    $session_token=$_SESSION['security_token'];
	$enc_session=md5('369'.$session_token);
	//echo $sec_time_token."<br>";
	//echo $enc_session;
	if($sec_time_token!=$enc_session){
		$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Time Out!..Please Try Again.</strong></div>';
		header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
		exit;
	}else{
			
			if(!isset($_POST['paychange_order_submit'])){
				
					header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
					exit;	
			}
			
			else if(isset($_POST['paychange_order_submit'])){
				
						$msg="";
						$date=date("Y-m-d");
						
						$txttype=$_POST['txttype'];
						$txttitle=$_POST['txttitle'];
						$txtkeyword=$_POST['txtkeyword'];
						$tashort=$_POST['tashort'];
						$talong=$_POST['talong'];
						$allowedExts = array("pdf","PDF"); 
						$extension = end(explode(".", $_FILES["order_file"]["name"]));
						
						//echo $extension;
						//echo in_array($extension, $allowedExts);exit;
						//$file_extension=explode('.',$_FILES['order_file']['name']) ;
						//echo $txttype;	
						/*echo $_FILES['order_file']['size'];exit;
						if($_FILES['order_file']['size']>'2097152'){
							$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>File size is too big.</strong></div>';
								header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
								exit;
						}*/
						
						if($_FILES['order_file']['size']>0){
							$flag=1;
								
						}
						else{
							$flag=2;
								
						}
						if($validator->blank_select($txttype)==FALSE ){
							
								$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Select Type</strong></div>';
								header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
								exit;
						}
						
						if(!$validator->blank_select($txttitle) ){
							
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Title</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
						}
						
						if(!$validator->blank_select($tashort) ){
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Short Description</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
						}
						 
						if(!$validator->blank_select($talong) ){
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Enter Long Description</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
						}
						
						if($validator->pattern_match_webmaster($txttitle)==FALSE){
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Remove Special Characters for Title Field.</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
							
						}
					
						if($validator->pattern_match_webmaster2($txtkeyword)==FALSE){
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Remove Special Characters for Keyword Field.</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
							
						}
							
						if($validator->pattern_match_webmaster($tashort)==FALSE){
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Remove Special Characters for Short Description Field.</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
							
						}
						
						if($validator->pattern_match_webmaster($talong)==FALSE){
									$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Please Remove Special Characters for Long Description Field.</strong></div>';
									header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
									exit;
							
						}
						
						if(!(in_array($extension, $allowedExts))){
				$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Only PDF files are allowed.</strong></div>';
							header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
							exit;
						}
						
						if($flag==2){
				$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry...Invalid File Size(Maximum Allowed File Size 2MB).</strong></div>';
							header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
							exit;
						}
					
						/*$finfo = finfo_open(FILEINFO_MIME_TYPE);
						$mime = finfo_file($finfo, $_FILES['order_file']['tmp_name']);*/
						//$mime = mime_content_type($_FILES['order_file']['tmp_name']) ;
				     	 $mime = mime_content_type($_FILES['order_file']['tmp_name']) ;
					 //echo $mime; 
				
						//echo 1; die;
						if($mime != "application/pdf"){
							
				$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Sorry..Invalid File Choosen.</strong></div>';
							header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
							exit;
						}
						
					
						  
				} 
		
				 $db=new database();
				 $cryptography=new cryptography();
				 
		//--------------------------------cache-----------------------------------------------		 
				 $ca = new cache();
				 $ca->delete("prd_upload_notice_index", "../../../cache/");
				 $ca->delete("prd_upload_news_index", "../../../cache/");
				 $ca->delete("prd_upload_notice_all", "../../../cache/");
				 $ca->delete("prd_upload_news_all", "../../../cache/");
				 $ca->delete("prd_upload_notice_length", "../../../cache/");
				 $ca->delete("prd_upload_news_length", "../../../cache/");
		//----------------------------------------------------------------------------------------
					if(isset($_POST['edit'])){
					
						
						$update = $db->update("
												UPDATE prd_upload
												SET
													date= now(),
													ip='".$_SESSION['user_agent']['USER_IP']."',
													page_title='".$txttitle."',
													description='".$talong."',
													meta_keyword='".$txtkeyword."',
													meta_description='".$tashort."'
													
												WHERE upload_id_pk = '".$_POST['edit']."';
												
											");	
						
						$fi = $db->fetch_table("select upload_id_fk from prd_file where upload_id_fk='".$_POST['edit']."'");	
												
												
									//echo "select upload_id_fk from ehrms_file where upload_id_fk='".$_POST['edit']."'";exit;
						if(sizeof($fi)!=0){
								
								if($_FILES['order_file']['name'] != '' && ($_FILES['order_file']['size'] != 0)){
									
									$delete = $db->update("
															UPDATE prd_file
															SET
																flag = 0
															WHERE upload_id_fk ='".$_POST['edit']."'
															
														");	
														
									if($delete){
											
										$insert_file=$db->insert_id("		
														 					INSERT INTO prd_file
																					(	upload_id_fk,
																						flag,
																						file_name
																					 )
																			  values (
																						'".$_POST['edit']."',
																						1,
																						'".$_FILES['order_file']['name']."'
																					  )
													");	
										
										
											
										$file_name=$insert_file.".pdf";//md5(microtime()).".pdf";		
										$allowedExts = array("pdf");
										$temp = explode(".", $_FILES["order_file"]["name"]);
										$extension = end($temp);
										
											/* echo $_FILES['order_file']['name']."<br>";
											 echo $_FILES['order_file']['size']."<br>";
											 echo $_FILES['order_file']['type']."<br>";
											 exit;*/
											 if($txttype==1){
													move_uploaded_file($_FILES["order_file"]["tmp_name"],	"../../../readwrite/upload/notice/" . $file_name);
											 }
											 else if($txttype==2)
													move_uploaded_file($_FILES["order_file"]["tmp_name"],	"../../../readwrite/upload/news_events/" . $file_name);
											 
										
									}
								}
								else if($_FILES['order_file']['name'] == ''){
								
										$delete = $db->update("
															UPDATE prd_file
															SET
																flag = 0
															WHERE upload_id_fk = ". $_POST['edit'].";
															
														");	
									
								}	
										
						}else{
											
												
										$insert_file=$db->insert_id("		
														 					INSERT INTO prd_file
																					(	upload_id_fk,
																						flag,
																						file_name
																					 )
																			  values (
																						'".$_POST['edit']."',
																						1,
																						'".$_FILES['order_file']['name']."'
																					  )


													");	
										
									
											
										$file_name=$insert_file.".pdf";//md5(microtime()).".pdf";		
										$allowedExts = array("pdf");
										$temp = explode(".", $_FILES["order_file"]["name"]);
										$extension = end($temp);
										
										/*if (($_FILES['order_file']['name'] != '') && 
											($_FILES['order_file']['size'] != 0) && 
											($_FILES['order_file']['type'] == 'application/pdf') &&
											($_FILES['order_file']['size'] <= 2097152))
										{*/
											 if($txttype==1)
											  		move_uploaded_file($_FILES["order_file"]["tmp_name"],	"../../../readwrite/upload/notice/" . $file_name);
											 else if($txttype==2)
											 		move_uploaded_file($_FILES["order_file"]["tmp_name"],	"../../../readwrite/upload/news_events/" . $file_name);
										/*}
										else {
											$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Select a Valid File</strong></div>';
											header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
											exit;
										}*/
										
						}
					/*	
						
						$update = $db->update("
												UPDATE ehrms_upload
												SET
													date= now(),
													ip='".$_SESSION['user_agent']['USER_IP']."',
													page_title='".$txttitle."',
													description='".$talong."',
													meta_keyword='".$txtkeyword."',
													meta_description='".$tashort."'
													
												WHERE upload_id_pk = '".$_POST['edit']."';
												
											");	
						if($_FILES['order_file']['name'] != ''){}					*/
			
											
						$msg=$cryptography->encode('Data Successfully Updated',3);
						$_SESSION['message']='<div class="alert alert-success" style="text-align:center"><strong>Data Successfully Updated...</strong></div>';
						
					}else{
						
						
			//-------------------------------------------------------------------------------------------------------------------------------------------------------	
			
					
					$insert=$db->insert_id("		
					 INSERT INTO prd_upload
												(	date,
													ip,
													page_title,
													description,
													meta_keyword,
													meta_description,
													flag,
													upload_category_id_fk
												 )
										  values (
													 now(),
													 '".$_SESSION['user_agent']['USER_IP']."',
													 '".$txttitle."',
													 '".$talong."',
													 '".$txtkeyword."',
													 '".$tashort."',
													 1,
													 '".$txttype."'
												  )
										");	
												
							//echo $insert;exit;
							if($insert)
							{ 
								
								if($_FILES['order_file']['name'] != '' || $_FILES['order_file']['name'] != NULL){
									$arr_id = $db->fetch_table("
												
															select upload_id_pk from prd_upload where oid='".$insert."'
														");
								
							
										
										$insert_file=$db->insert_id("		
														 INSERT INTO prd_file
																					(	upload_id_fk,
																						flag,
																						file_name
																					 )
																			  values (
																						'".$arr_id[0]['upload_id_pk']."',
																						1,
																						'".$_FILES['order_file']['name']."'
																					  )
													");	
										
										
											
										$file_name=$insert_file.".pdf";//md5(microtime()).".pdf";		
										$allowedExts = array("pdf");
										$temp = explode(".", $_FILES["order_file"]["name"]);
										$extension = end($temp);
										
										/*if (($_FILES['order_file']['name'] != '') && 
											($_FILES['order_file']['size'] != 0) && 
											($_FILES['order_file']['type'] == 'application/pdf') &&
											($_FILES['order_file']['size'] <= 2097152))
										{*/
											 if($txttype==1)
											  		move_uploaded_file($_FILES["order_file"]["tmp_name"],	"../../../readwrite/upload/notice/" . $file_name);
											 else if($txttype==2)
													move_uploaded_file($_FILES["order_file"]["tmp_name"],	"../../../readwrite/upload/news_events/" . $file_name);
											
										/*}
										else {
											$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Select a Valid File</strong></div>';
											header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
											exit;
										}*/
								}
							
								$msg=$cryptography->encode('Data Successfully Entered',3);
						$_SESSION['message']='<div class="alert alert-success" style="text-align:center"><strong>Data Successfully Entered...</strong></div>';
							}
							
							else
							{
								
								$_SESSION['error_msg1']='<div class="alert alert-danger" style="text-align:center"><strong>Data Insertion Failed</strong></div>';
								header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_add_new_notice.php");
								exit;
			
							}
					}
					//$_SESSION['message']='<div class="alert alert-success" style="text-align:center"><strong>Data Successfully Entered...</strong></div>';
					header('Location: '. $config['base_url'] . "page/intra_prd/webmaster/webmaster_notice.php");
					exit;
		}
?>
