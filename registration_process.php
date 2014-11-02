<?php
include('system/config/configuration.php');
include('system/library/classes/dbclass.php');
include('system/library/classes/mail.cls.php');

function randomNumber($length) {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

$db = new DbClass();
$nicEmail = new NicMail ();
$email = $_POST['email'];
$nicEmail->from = 'post@checkd.it' ;

$sql_1 = 'SELECT id, "user" as usertype FROM user WHERE email = "'.$email.'" UNION SELECT id, "company" AS usertype FROM company WHERE email = "'.$email.'"';
$sqlRes = $db->select($sql_1); 
if(count($sqlRes) > 0){
	
	$nicEmail->to = $email;
    $nicEmail->subject = 'Welcome to CHECKD';
	$tmppath = MAIL_TMPL_PATH."maialforexistinguser.html";
    $set_replace_var['USERNAME'] = $email;
    $sent = $nicEmail->send($tmppath,$set_replace_var); // send mail with attachment
	if($sent == 1)
		$_SESSION['success'] = 'Please check your email for more information.' ;
    else 	
		$_SESSION['error']   = $message['mail']['mailSentError'];
        header('Location:registered.html');
    exit;		
}else{
	$password = 'checkd'.randomNumber(4);
	do {
		$equipCode = randomNumber(10); 
		$checkEquipment = 'SELECT id FROM equipment WHERE id = "'.$equipCode.'"' ;
		$checkEquipmentRes = $db->select($checkEquipment); 
		if(!$checkEquipmentRes){
			break;
		}
	} while (0);
	// create comapny admin
	$sql = 'INSERT INTO company (`subscription_plan_id`, `name`, `code`, `contact_person`, `email`, `password`, `password_show`, `address`,`city`,`country`,`phone`, `status`, `is_login`, `created`, `modified`, `equipment_code`) VALUES ("", "", "", "", "'.$email.'", "'.md5($password).'", "'.$password.'", "", "", "", "", "Approve", "0", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'", "'.$equipCode.'")';
	$res = $db->insert($sql); 
	if($res){
		$user_id = $res ; 
		$user_type = 'company' ;
				
		$url = "$equipCode";
		$Barcode_size = '150x150';
		$Character_encoding = 'UTF-8';
		$Encoding = $url;

		$Error_correction = 'H';

		$data = 'http://chart.apis.google.com/chart?'; 

		$data .= 'cht=qr&chs=' . $Barcode_size . '&chld=' . $Error_correction . '&choe=' .$Character_encoding . '&chl=' . $Encoding;
							
		$nicEmail->to = $email;
		$nicEmail->subject = 'Welcome to CHECKD';
		$tmppath = MAIL_TMPL_PATH."mailtemplatefornewregistration.html";
		$set_replace_var['PASSWORD'] = $password;
		$set_replace_var['USERNAME'] = $email;
		$set_replace_var['DATA'] = $data;
							
		$sent = $nicEmail->send($tmppath,$set_replace_var); // send mail with attachment
		if($sent == 1)
			$_SESSION['success'] = 'Please check your email for more information.' ;
		else 	
			$_SESSION['error']   = $message['mail']['mailSentError'];
		header('Location:registered.html');
		exit;	
	}
}
exit;
?>