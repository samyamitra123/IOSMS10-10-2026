<?php
    include_once('master.php');
    $master = new Master();
    $type = $_GET['type'];
    switch($type)
     {
      case 'noappogp':
       $master->getEmployeeNoAppoGP();
       break; 
      case 'noappops':
       $master->getEmployeeNoAppoPS();
       break; 
      case 'noappozp':
       $master->getEmployeeNoAppoZP();
       break;        
        case 'gp':
     	 $master->getEmployee();
     	 break;
        case 'gpcount':
         $master->getEmployee('count');
         break;         
        case 'gpall':
         //print("sy"); exit;
         $master->getEmployee('all');
         break;    
        case 'zpall':
         //print("sy"); exit;
         $master->getZpEmployee('all');
         break;               
     	case 'ps':
     	 $master->getPsEmployee();
     	 break; 
        case 'psall':
         $master->getPsEmployee('all');
         break;  
        case 'pscount':
         $master->getPsEmployee('count');
         break;                      
     	case 'zp':
     	 $master->getZpEmployee();
     	 break;      	  	  
     }
?>