<?php 
require 'master.php';
$post = $_POST;
//print_r($post); exit;
$master = new Master();
if(isset($post['action']))
  {
  //echo "dfafsddf";exit;

  	switch($post['action'])
  	  {
  	  	 case 'updatengipfstatusps':
  	  	   $master->updatePsNgipfStatus($post);
  	  	 break;
  	  	 case 'updatengipfstatusblock':
  	  	   $master->updateBlockNgipfStatus($post);
  	  	 break;  
         case 'updatesalstatusblock':
           $master->updateBlockSalStatus($post);
         break;     
         case 'updatesalstatusps':
           $master->updatePsSalaryStatus($post);
         break;  
         case 'pspfsubscription':
           $master->pspfsubscription($post);
         break;     
         case 'gppfsubscription':
           $master->gppfsubscription($post);
         break;                
         case 'blockprevsalactive':
           $master->updateBlockPrevSal($post);
         break;                        	  	 
  	  }
  	exit;
  }
if(isset($post['save']))
{
  //echo "dfafsddf";exit;

	$master->saveBlock();
}

$master->showBlockHoo();
?>