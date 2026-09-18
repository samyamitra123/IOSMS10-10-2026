<?php
/*
 * @Created By		:  Parag Kr. Dhali
 * @
 * 
 * 
 * 
 */

$user_agent = $_SERVER['HTTP_USER_AGENT'];
class user_agent {
	

	//Get Operating system name
    public function getOS() { 

       
		global $user_agent;
        $os_platform    =   'Unknown OS Platform';

        $os_array       =   array (
        	
			'/windows/i'     		=> 'Unknown Windows',
			'/windows nt 6.3/i'     => 'Windows 8.1',
            '/windows nt 6.2/i'     => 'Windows 8',
            '/windows nt 6.1/i'     => 'Windows 7',
            '/windows nt 6.0/i'     => 'Windows Vista',
            '/windows nt 5.2/i'     => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     => 'Windows XP',
            '/windows xp/i'         => 'Windows XP',
            '/windows nt 5.0/i'     => 'Windows 2000',
            '/windows me/i'         => 'Windows ME',
            '/win98/i'              => 'Windows 98',
            '/win95/i'              => 'Windows 95',
            '/win16/i'              => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i'        => 'Mac OS 9',
            '/ppc mac/i'			=> 'Power PC Mac',
            '/linux/i'              => 'Linux',
            '/ubuntu/i'             => 'Ubuntu',
            '/iphone/i'             => 'iPhone',
            '/ipod/i'               => 'iPod',
            '/ipad/i'               => 'iPad',
            '/android/i'            => 'Android',
            '/blackberry/i'         => 'BlackBerry',
            '/webos/i'              => 'Mobile'

        );

        foreach ($os_array as $regex => $value) { 

            if (preg_match($regex, $user_agent)) $os_platform = $value;

        }   

        return $os_platform;

    }


	//Get Web Browser name
    public function getBrowser() {

        global $user_agent;

        $browser        =   "Unknown Browser";

        $browser_array  =   array (

            '/msie/i'       		=> 'Internet Explorer',
            '/internet explorer/i'	=> 'Internet Explorer',
	        '/firefox/i'    		=> 'Firefox',
	        '/Iceweasel/i'  		=> 'Iceweasel (Firefox)',
	        '/safari/i'     		=> 'Safari',
	        '/OPR/i'        		=> 'Opera',
	        '/opera/i'      		=> 'Opera',
	        '/chrome/i'     		=> 'Chrome',
	        '/netscape/i'   		=> 'Netscape',
	        '/maxthon/i'    		=> 'Maxthon',
	        '/konqueror/i'  		=> 'Konqueror',
	        '/mobile/i'     		=> 'Handheld Browser',
	        '/flock/i'				=> 'Flock',
	        '/shiira/i'				=> 'Shiira',
	        '/cimera/i'				=> 'Chimera',
	        '/phoenix/i'			=> 'Phoenix',
	        '/firebird/i'			=> 'Firebird',
	        '/camino/i'				=> 'Camino',
	        '/netscape/i'			=> 'Netscape',
	        '/omniWeb/i'			=> 'OmniWeb',
	        '/konqueror/i'			=> 'Konqueror',
	        '/icab/i'				=> 'iCab',
	        '/lynx/i'				=> 'Lynx',
	        '/links/i'				=> 'Links',
	        '/hotjava/i'			=> 'HotJava',
	        '/amaya/i'				=> 'Amaya',
	        '/ibrowse/i'			=> 'IBrowse'

        );

        foreach ($browser_array as $regex => $value) { 

            if (preg_match($regex, $user_agent)) $browser = $value;

        }

        return $browser;

    }
	public function getIP(){
		
		$ipaddress = '';
     if (getenv('HTTP_CLIENT_IP')){
         $ipaddress = getenv('HTTP_CLIENT_IP');
	 	}
     else if(getenv('HTTP_X_FORWARDED_FOR')){
         $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		}
     else if(getenv('HTTP_X_FORWARDED')){
         $ipaddress = getenv('HTTP_X_FORWARDED');
	 	}
     else if(getenv('HTTP_FORWARDED_FOR')){
         $ipaddress = getenv('HTTP_FORWARDED_FOR');
		}
     else if(getenv('HTTP_FORWARDED')){
        $ipaddress = getenv('HTTP_FORWARDED');
		}
     else if(getenv('REMOTE_ADDR')){
         $ipaddress = getenv('REMOTE_ADDR');
		}
     else{
         $ipaddress = 'UNKNOWN';
		}

     return $ipaddress; 
		
	}
	
	//Get Client port number
	public function getPort(){
		return $_SERVER['REMOTE_PORT'];
	}
	
	//Get Client request time
	public function getRqstTime(){
		return $_SERVER['REQUEST_TIME'];
	}
	
	//Ger server time
	public function getServerTime(){
		return time();
	}
	
	//Ger server time
	public function getfull(){
		
		global $user_agent;
		return $user_agent;
	}
	
	public function getAll(){
		
		$uarr  = array(
						'OS' 			=> $this->getOS(),
						'BROWSER'		=> $this->getBrowser(),
						'USER_IP'			=> $this->getIP(),
						'USER_PORT'			=> $this->getPort(),
						'REQUEST_TIME'	=> $this->getRqstTime(),
						'SERVER_TIME'	=> $this->getServerTime(),
						'USER_AGENT'	=> $this->getfull()
						);
		return $uarr;
	}
	

}
	$userInfo = new user_agent();
?>