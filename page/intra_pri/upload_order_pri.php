<?php 
    session_start();
	require '../../includes/config/config.php';
	require '../../includes/config/database.config.php';
	require '../../includes/library/database.class.php';
	require '../../includes/library/cryptography.class.php';
	$cryptoGraph=new cryptography();
	$db=new database();
    $file = $_FILES;
    $post = $_POST;

    $forwardId = $cryptoGraph->decode($post['forwardId'],4);
    $officerId = $_SESSION['user_info']['officer_id_const'];

	$rand=rand(100000, 999999);
    $fileName = $rand.$_FILES["file"]["name"];
    //print($fileName); exit;
   // $this->fileName = $rand.'.pdf';
	$location = '../../readwrite/intra_pri_upload/comments/'.$fileName;
	if(move_uploaded_file($_FILES['file']['tmp_name'], $location))
	  {
	  	 $Query = "UPDATE intra_pri_forwarding SET msg_file='".$fileName."', orderupload=1 WHERE forwarding_id_pk='".$forwardId."' AND from_officer_id_const='".$officerId."' AND status= '2' ";
	  	 //print($Query); exit;
         $db->update($Query);	  	
         echo  json_encode(array('status'=>'success','data'=> 'Congratulation!Order Uploaded Successfully'));
	  }	
	 else
	  {
	  	 echo  json_encode(array('status'=>'falure','data'=>'Sorry!Order Upload Failed'));
	  } 
?>