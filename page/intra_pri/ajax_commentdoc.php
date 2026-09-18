<?php 
    session_start();
	require '../../includes/config/config.php';
	require '../../includes/config/database.config.php';
	require '../../includes/library/database.class.php';
	require '../../includes/library/cryptography.class.php';
	$db=new database();
    $file = $_FILES;
    $post = $_POST;

    $application_id = $post['application_id'];
    $officerId = $_SESSION['user_info']['officer_id_const'];

	$rand=rand(100000, 999999);
    $fileName = $rand.$_FILES["file"]["name"];
    //print($fileName); exit;
   // $this->fileName = $rand.'.pdf';
	$location = '../../readwrite/intra_pri_upload/comments/'.$fileName;
	if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
	  {
	  	 $Query = "UPDATE intra_pri_forwarding SET msg_file='".$fileName."' WHERE application_id='".$application_id."' AND from_officer_id_const='".$officerId."' AND status= '1' ";
	  	 //print($Query); exit;
         $db->update($Query);	  	
         echo  json_encode(array('status'=>'success','data'=>$fileName));
	  }	
	 else
	  {
	  	 echo  json_encode(array('status'=>'falure','data'=>$fileName));
	  } 
?>