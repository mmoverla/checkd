<?php
////////////////////////////////////////////////////////////
//***** GENERAL SETTINGS *********************************//
////////////////////////////////////////////////////////////
error_reporting(E_ALL ^ E_NOTICE);  									// display all errors except notices
@ini_set('display_errors', '1'); 										// display all errors
@ini_set('register_globals', 'Off');									// make globals off runtime
@ini_set('magic_quotes_runtime', 'Off');								// Magic quotes for 																		


/////////////////////////////////////////////////////////////
//***** SITE CONFIGURATION ********************************//
/////////////////////////////////////////////////////////////
$path_http = pathinfo('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$arrDirPath = explode("/", $path_http["dirname"]); 						//server path is deined here
if($arrDirPath[count($arrDirPath)-1] == "admin" || $arrDirPath[count($arrDirPath)-1] == "web_service" || $arrDirPath[count($arrDirPath)-1] == "superadmin"){
	// server root path is created from here
	define("SERVER_ROOT_DIR_PATH", substr(getcwd(), 0, (strlen(getcwd())-strlen($arrDirPath[count($arrDirPath)-1])))); 
	$serverPath = $arrDirPath;
	array_pop($serverPath);
	$serverUrl = implode("/",$serverPath);
	define("SERVER_URL_PATH", $serverUrl."/"); 								// server path is deined here
}else{
	define("SERVER_ROOT_DIR_PATH", getcwd()."/"); 		  					// server root path is deined here
	$serverUrl = implode("/",$arrDirPath);
	define("SERVER_URL_PATH", $serverUrl."/"); 								// server path is deined here
	$path_https = pathinfo('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
}

$path_https = pathinfo('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);	
define("SERVER_SSL_PATH", $path_https["dirname"]."/");					// server https path is deined here

/////////////////////////////////////////////////////////////
//***** DATABASE CONFIGURATION ****************************//
/////////////////////////////////////////////////////////////

define("DB_SERVER", "10.10.0.5");										// server name set here
define("DB_USERNAME", "nklt");								// server username set here
define("DB_PASSWORD", "nklt");								// server password set here
define("DB_DATABASE", "nklt");								// server database set here

//define("DB_SERVER", "10.2.1.68");										// server name set here
//define("DB_USERNAME", "checkdcontrol");								// server username set here
//define("DB_PASSWORD", "checkdcontrol");								// server password set here
//define("DB_DATABASE", "checkdcontrol");


/////////////////////////////////////////////////////////////
//***** ALL ADMIN VARIABLE SET HERE  FOR ADMIN PANEL************************//
/////////////////////////////////////////////////////////////
define("ADMIN_DIR_PATH", SERVER_ROOT_DIR_PATH.'admin/');				// admin path set here
define("ADMIN_URL_PATH", SERVER_URL_PATH.'admin/');						// admin path set here
define("ADMIN_JS_DIR_PATH", ADMIN_DIR_PATH.'js/');				// admin script directory path set here	
define("ADMIN_JS_URL_PATH", ADMIN_URL_PATH.'js/');				// java script url path set here		
define("ADMIN_CSS_URL_PATH", ADMIN_URL_PATH.'css/');				// style url path set here
define("CONFIG_DIR_PATH", SERVER_ROOT_DIR_PATH.'system/config/');		// main configuration path set here

/////////////////////////////////////////////////////////////
//***** MAIL TEMPLATE VARIABLE SET HERE FOR ADMIN PANEL************************//
/////////////////////////////////////////////////////////////
define("MAIL_TMPL_PATH", SERVER_ROOT_DIR_PATH.'/');

define("DEMO_CHECKLIST1_ID",'306');
define("DEMO_CHECKLIST2_ID",'307');
?>
