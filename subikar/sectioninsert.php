<?php 
      require 'master.php';
      //$myfile = fopen("jsondata.txt", "r") or die("Unable to open file!");
      $jsonData = file_get_contents("csv/GP_Section_Code_issue_06.10.2024_update.json");
      //fclose($myfile);
      $jsonDecodeData = json_decode($jsonData,true);
      $jsonDecodeData = $jsonDecodeData['Sheet1'];
      $updateQuery = array();
      foreach($jsonDecodeData as $data)
      {
         $updateQuery[] = "update prd_location_master_gp SET section_code='".$data['Section_Code']."' WHERE gp_code='".$data['GP_Code']."'";
      }
      $updateQuery = implode('; ',$updateQuery);
      print_r($updateQuery); 
 
      
?>