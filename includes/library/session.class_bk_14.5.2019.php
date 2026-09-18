<?php
/**
 * 
 */
class Session {
	
	function __construct() {

	}
	function nic_session_start($path) {
		require $path;
			
	    ini_set('session.use_only_cookies', 1); // Forces sessions to only use cookies. 
	    //ini_set('session.cache_limiter', 'none');
	    $cookieParams = session_get_cookie_params(); // Gets current cookies params.
	    session_set_cookie_params($cookie["lifetime"], $cookie["path"], $cookie["domain"], $cookie["secure"], $cookie["httponly"] ); 
	    //session_name($cookie["session_name"]); // Sets the session name to the one set above.
	    session_start(); // Start the php session
	    session_regenerate_id($cookie["random_id"]); // regenerated the session, delete the old one.     
	}
}


?>