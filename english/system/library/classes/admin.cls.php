<?php

////////////////////////////////////////////////////////////
// This class is used to call common function of admin panel
////////////////////////////////////////////////////////////
class Admin extends AdminGlobal{
	
	var $post;
	var $get;
	var $request;
	var $dbclass;
	
	// This is the Constructor Function it will be called default when user declare the class object
	function Admin($dbObj){
		$this->dbclass = $dbObj;
		$this->post = &$_POST;
		$this->get = &$_GET;
		$this->request = &$_REQUEST;
	}
	
	// This Function is used to get the setting variable value
	function checkSuperAdminLogin(){
		
		global $message;
		
		$sql = 'SELECT * FROM admin WHERE email = "'.$this->post["email"].'" AND password ="'.$this->post["password"].'"' ;
	 	$sqlRes = $this->dbclass->select($sql);
		
	 	// check the admin username & password
	 	//if(ADMINEMAIL == $this->post["email"] AND ADMINPASSWORD == $this->post["password"]) {
	 	if(count($sqlRes) > 0) {
	 	
			$sess_adminid 		= $sqlRes[0]['admin_id'];
			$_SESSION["sess_adminid"] = $sess_adminid;
						
			// Check checkbox is chacked or not
			if($_REQUEST["rememberme"]=='on'){
				setcookie("cookie_sadmin", $this->post["email"], time()+60*60*24*30);
				setcookie ("cookie_svpassword",  $this->post["password"], time()+60*60*24*30);
			}else{
				setcookie("cookie_sadmin", '');
				setcookie ("cookie_svpassword",'', (time()-3600) );
			}
			//header('Location:index.php?file=dashboardview');
			header('Location:index.php?file=userlistview');
			exit;
		} else {
			
			$_SESSION['error'] = $message['logIn']['notmatched'];
			header('Location: index.php?file=loginview');
			exit;
		}	
		
	}
	
	function chkAdminLogin(){
		
		global $message;
		
		$email 			= $this->post['email'];
        $password 		= md5($this->post['password']);
        
        //$sql = 'SELECT id, email, "user" as usertype FROM user WHERE email = "'.$email.'" AND password = "'.$password.'" UNION SELECT id,email, "company" AS usertype FROM company WHERE email = "'.$email.'" AND password = "'.$password.'"';
        $sql = 'SELECT * FROM company WHERE email = "'.$email.'" AND password = "'.$password.'" AND status = "Approve"';
        $sqlRes = $this->dbclass->select($sql); 
        
        $user_sql = 'SELECT * FROM user WHERE email = "'.$email.'" AND password = "'.$password.'" AND user_type = "Admin" AND status = "Active"';
        $userRes = $this->dbclass->select($user_sql); 
		//print_r($sqlRes); exit;
		$company_count = count($sqlRes);
		$user_count = count($userRes);
		$status=1;
		
		if($company_count == 0 && $user_count == 0){
			$status = 0;
		}
	
		
		if(count($sqlRes) > 0 || count($userRes) > 0 ) {
			if($status == 0){
				$_SESSION['error'] = $message['logIn']['status'];
				header('Location: index.php?file=loginview');
				exit;
			}else{
				$_SESSION['login_user']='';
				if($user_count > 0){
					$sql = 'SELECT * FROM company WHERE id = "'.$userRes[0]['company_id'].'" ';
					$sqlRes = $this->dbclass->select($sql); 
					$_SESSION['login_user']=$userRes[0]['id'];
					$_SESSION['user_name']=$userRes[0]['name'];
				}
			
			
				$sees_id 		= $sqlRes[0]['id'] ; 
				$sees_name 		= $sqlRes[0]['name'] ; 
				$sees_email     = $sqlRes[0]['email'] ;
				$sees_code      = $sqlRes[0]['code'] ;
				
				$_SESSION['sees_id'] 		= $sees_id ;
				$_SESSION['sees_name'] 		= $sees_name ;
				$_SESSION['sees_email'] 	= $sees_email ;
				$_SESSION['sees_code'] 	    = $sees_code ;
				$_SESSION['company_name']=$sqlRes[0]['company_name'];
				
				if($_REQUEST["rememberme"]=='on'){
					setcookie("cookie_admin", $this->post["email"], time()+60*60*24*30);
					setcookie ("cookie_vpassword",  $this->post["password"], time()+60*60*24*30);
				}else{
					setcookie("cookie_admin", '');
					setcookie ("cookie_vpassword",'', (time()-3600) );
				}
			
				header('Location:index.php?file=equipmentlistview');
				exit;
			}
		} else {
			$_SESSION['error'] = $message['logIn']['notmatched'];
			header('Location: index.php?file=loginview');
			exit;
		}
	}
	
	function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}
	
	//check the email address and get the detail
	function forgotPass(){
		global $message , $mailObj;
				
		$email = $this->post["forgot_email"] ; 
		
		$sql = "select * from company where email='".$email."'" ;
		$result = $this->dbclass->select($sql);
		
		$user_sql = "select * from user where email='".$email."' AND user_type='Admin' AND status= 'Active'" ;
		$user_result = $this->dbclass->select($user_sql);
		
		if(count($result)>0) {
				
			$newPassword = $this->randomPassword();
			$contactName = $result[0]['contact_person'] ;
			
			$query = "UPDATE `company` SET `password` = '".md5($newPassword)."', `password_show` =  '".$newPassword."' WHERE email = '".$email."'";
			$this->dbclass->setQuery($query);
			
			$mailObj->to = $email;
			$mailObj->subject = $message['mail']['forgot_pass_title'];
			
			$tmppath = MAIL_TMPL_PATH."mailforgetpassword.html";
			$set_replace_var['name'] = $contactName ;	
			$set_replace_var['password'] = $newPassword;
			
			$sent = $mailObj->send($tmppath,$set_replace_var); // send mail with attachment
			
			if($sent == 1)
				$_SESSION['success'] = $message["mail"]["mailSent"];
			else 	
				$_SESSION['error']   = $message['mail']['mailSentError'];
				
			header('Location:index.php?file=loginview');
			exit;
			
		}elseif(count($user_result)>0) {
				
			$newPassword = $this->randomPassword();
			$contactName = $user_result[0]['name'] ;

			
			$query = "UPDATE `user` SET `password` = '".md5($newPassword)."' WHERE email = '".$email."'";
			$this->dbclass->setQuery($query);
			
			$mailObj->to = $email;
			$mailObj->subject = $message['mail']['forgot_pass_title'];
			
			$tmppath = MAIL_TMPL_PATH."mailforgetpassword.html";
			$set_replace_var['name'] = $contactName ;	
			$set_replace_var['password'] = $newPassword;
			
			$sent = $mailObj->send($tmppath,$set_replace_var); // send mail with attachment
			
			if($sent == 1)
				$_SESSION['success'] = $message["mail"]["mailSent"];
			else 	
				$_SESSION['error']   = $message['mail']['mailSentError'];
				
			header('Location:index.php?file=loginview');
			exit;
			
		} else {
			$_SESSION['error'] = $message['logIn']['mailidNotMatched'];
			header('Location:index.php?file=loginview&action=forgetpassword&msg=mailnotmatched');
			exit;
		}
	}
	
	function forgotSuperadminPass(){
		global $message , $mailObj;
				
		$email = $this->post["forgot_email"] ; 
		
		$sql = "select * from admin where email='".$email."'" ;
		$result = $this->dbclass->select($sql);
		if(count($result)>0) {
				
			//$newPassword = $this->randomPassword();
			$contactName = $result[0]['first_name'] ;
			
			//$query = "UPDATE `admin` SET `password` = '".md5($newPassword)."' WHERE email = '".$email."'";
			//$this->dbclass->setQuery($query);
			
			$mailObj->to = $email;
			$mailObj->subject = $message['mail']['forgot_pass_title'];
			
			$tmppath = MAIL_TMPL_PATH."mailforgetpassword.html";
			$set_replace_var['name'] = $contactName ;	
			$set_replace_var['password'] = $result[0]['password'];
			
			$sent = $mailObj->send($tmppath,$set_replace_var); // send mail with attachment
			
			if($sent == 1)
				$_SESSION['success'] = $message["mail"]["mailSent"];
			else 	
				$_SESSION['error']   = $message['mail']['mailSentError'];
				
			header('Location:index.php?file=loginview');
			exit;
			
		} else {
			$_SESSION['error'] = $message['logIn']['mailidNotMatched'];
			header('Location:index.php?file=loginview&action=forgetpassword#tabs-2');
			exit;
		}
	}
	
	function get_last_user_details(){
		
		$sql = "SELECT * FROM company ORDER BY id DESC LIMIT 1" ; 
		$sqlRes = $this->dbclass->select($sql); 
		
		if($sqlRes) {
	  		return $sqlRes;
	  	}else{
	  		return false;
	  	}
  	
	}
	function randomNumber($length) {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}
	
	//company admin registration using only email
	function registrationNew(){
		
		global $message , $mailObj;
		//$equipCodeExist = true ;
		$email = $this->post['email'];
		$sql_1 = 'SELECT id, "user" as usertype FROM user WHERE email = "'.$email.'" UNION SELECT id, "company" AS usertype FROM company WHERE email = "'.$email.'"';
		$sqlRes = $this->dbclass->select($sql_1); 
		if(count($sqlRes) > 0){
			
			//$_SESSION['error']  = 'It appear that your email address has already been registered.' ;
			//echo ("<SCRIPT LANGUAGE='JavaScript'>
				//window.alert('It appear that your email address has already been registered.')
				//window.location.href='http://staging.indianic.com/checkd_control';
				//</SCRIPT>");
				$mailObj->to = $email;
                        $mailObj->subject = 'Welcome to CHECKD';

                        $tmppath = MAIL_TMPL_PATH."maialforexistinguser.html";
                        //$set_replace_var['PASSWORD'] = $password;
                        $set_replace_var['USERNAME'] = $email;
                        //$set_replace_var['DATA'] = $data;

                        $sent = $mailObj->send($tmppath,$set_replace_var); // send mail with attachment

                        if($sent == 1)
                                $_SESSION['success'] = 'Please check your email for more information.' ;
                        else 	
                                $_SESSION['error']   = $message['mail']['mailSentError'];
                        header('Location:index.php?file=loginview#tab2');
                        exit;	
			//header('Location:'.SERVER_URL_PATH);
			// exit;	
			
		}else{
			$password = 'checkd'.$this->randomNumber(4);
			do {
				$equipCode = $this->randomNumber(10); 
				$checkEquipment = 'SELECT id FROM equipment WHERE id = "'.$equipCode.'"' ;
				$checkEquipmentRes = $this->dbclass->select($checkEquipment); 
				if(!$checkEquipmentRes){
					break;
				}
			} while (0);
			
			// create comapny admin
			$sql = 'INSERT INTO company (`subscription_plan_id`, `name`, `code`, `contact_person`, `email`, `password`, `password_show`, `address`,`city`,`country`,`phone`, `status`, `is_login`, `created`, `modified`, `equipment_code`) VALUES ("", "", "", "", "'.$email.'", "'.md5($password).'", "'.$password.'", "", "", "", "", "Approve", "0", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'", "'.$equipCode.'")';
			$res = $this->dbclass->insert($sql); 
			if($res){
				$user_id = $res ; 
				$user_type = 'company' ;
				
				//create project for comapny admin
				$projectName = explode('@',$email) ;
				$projectName = $projectName[0] ;
				$createProject = 'INSERT INTO chkd_projects (`company_id`, `name`, `location`, `status`, `created`, `modified`) VALUES ("'.$user_id.'", "'.$projectName.'", "", "active", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'")';
				$projectRes = $this->dbclass->insert($createProject); 
				if($projectRes){
					
					//assign checklist to company admin
					$assignChecklist = 'INSERT INTO chkd_company_checklists (`company_id`, `checklist_id`, `created`) VALUES 
					("'.$user_id.'", "'.DEMO_CHECKLIST1_ID.'", "'.date('Y-m-d H:i:s').'"),
					("'.$user_id.'", "'.DEMO_CHECKLIST2_ID.'", "'.date('Y-m-d H:i:s').'")';
					$assignChecklistRes = $this->dbclass->insert($assignChecklist); 
					if($assignChecklistRes){
						//assign checklists to project
						$assignChecklistToProject = 'INSERT INTO chkd_projects_checklists (`company_id`, `project_id`, `checklist_id`) VALUES 
						("'.$user_id.'", "'.$projectRes.'", "'.DEMO_CHECKLIST1_ID.'"),
						("'.$user_id.'", "'.$projectRes.'", "'.DEMO_CHECKLIST2_ID.'")';
						$assignChecklistToProjectRes = $this->dbclass->insert($assignChecklistToProject); 
						if($assignChecklistToProjectRes){
							
							$url = "$equipCode";
							$Barcode_size = '150x150';
							$Character_encoding = 'UTF-8';
							$Encoding = $url;

							$Error_correction = 'H';

							$data = 'http://chart.apis.google.com/chart?'; 

							$data .= 'cht=qr&chs=' . $Barcode_size . '&chld=' . $Error_correction . '&choe=' .$Character_encoding . '&chl=' . $Encoding;
							
							$mailObj->to = $email;
							$mailObj->subject = 'Welcome to CHECKD';
							
							$tmppath = MAIL_TMPL_PATH."mailtemplatefornewregistration.html";
							$set_replace_var['PASSWORD'] = $password;
							$set_replace_var['DATA'] = $data;
							
							$sent = $mailObj->send($tmppath,$set_replace_var); // send mail with attachment
							
							if($sent == 1)
								$_SESSION['success'] = 'Registration successfully done.' ;
							else 	
								$_SESSION['error']   = $message['mail']['mailSentError'];
							header('Location:index.php?file=loginview#tab2');
							exit;	
						}
					}
				}
			}
		}
		exit;
	}
	
	
	//register company admin
	function register(){
		
		global $message , $mailObj;
		
		$email = $this->post['company_email'];
		
		$sql_1 = 'SELECT id, "user" as usertype FROM user WHERE email = "'.$email.'" UNION SELECT id, "company" AS usertype FROM company WHERE email = "'.$email.'"';
		$sqlRes = $this->dbclass->select($sql_1); 
		if(count($sqlRes) > 0){
			
			$_SESSION['error']  = 'It appear that your email address has already been registered.' ;
			header('Location:index.php?file=loginview#tab2');
			exit;	
			
		}else{
			
			$comapnyName = strtoupper(substr($this->post['company_name'], 0, 3));
			$companyDetails = $this->get_last_user_details();
			$companyCode = $companyDetails[0]['code'] ; 
			if($companyCode){
		    	$code = strtoupper(substr($companyCode, 0, 3));
		    	$number = explode($code,$companyCode);
		    	$number = (int)$number[1] + 1 ;
		    	$newCompanyCode = sprintf("%05d", $number);
		    	
		    	$newCompanyCode = $comapnyName.$newCompanyCode ;
		    }else{
		    	$newCompanyCode = $comapnyName.'00001' ;
		    }
		    //echo $newOrderNumber ; exit;
			$sql = 'INSERT INTO company (`subscription_plan_id`, `name`, `code`, `contact_person`, `email`, `password`, `password_show`, `address`,`city`,`country`,`phone`, `status`, `is_login`, `created`, `modified`) VALUES ("'.$this->post['subscription_plan_id'].'", "'.$this->post['company_name'].'", "'.$newCompanyCode.'", "'.$this->post['company_person'].'", "'.$this->post['company_email'].'", "'.md5($this->post['company_password']).'", "'.$this->post['company_password'].'", "'.$this->post['address'].'", "'.$this->post['city'].'", "'.$this->post['country'].'", "'.$this->post['phone'].'", "Pending", "0", "'.date('Y-m-d H:i:s').'", "'.date('Y-m-d H:i:s').'")';
			$res = $this->dbclass->insert($sql); 
			if($res){
				$last_insert_id = $res ;
				
				$_SESSION['success']  = 'Registration Successfully Done.' ;
				//header('Location:'.ADMIN_URL_PATH.'payex/recurring-process.php?id='.$last_insert_id.'&orderID='.$orderID);
				if($this->post['subscription_plan_id'] == 0){
					
					$superAdminData    = 'SELECT email FROM admin WHERE admin_id = "1"';
					$superAdminDataRes = $this->dbclass->select($superAdminData);
					
					//sent mail to superadmin
					
					$from = 'NKLT QR';
					$to = $superAdminDataRes[0]['email'].',tem@mulighet.no';
					//$to = 'kausha.shah@indianic.com' ;
					$subject = 'New Registration made.';
					$body = 'Et nytt firma �nsker � v�re bruke NKLT. Godta og send login detaljer. <br/> Nedenfor finner du din registrerings informasjon.<br/>
					Company Name : '.$this->post['company_name'].'<br/>
					Company Code : '.$this->post['company_code'].'<br/>
					Contact Person : '.$this->post['company_person'].'<br/>
					Email : '.$this->post['company_email'].'<br/>
					Phone : '.$this->post['phone'].'<br/>
					' ;
					$headers = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type:text/html;charset=iso-8859-1' . "\r\n";
					$headers .= 'From: '.$from."\r\n";
					$headers .= 'Reply-To: '.$from."\r\n";
					$headers .= 'Return-Path: '.$from."\r\n";
					
					if(mail($to, $subject, $body, $headers)) {
				
					/*$companyData  = 'SELECT * FROM company WHERE id = "'.$orderId.'"';
					$companyDataRes = $dbObj->select($companyData);*/
					
					//reset($mailObj);
					
					//sent mail to company admin
					$mailObj->to = $this->post['company_email'];
					$mailObj->subject = "Registration made for you";
					
					$tmppath1 = MAIL_TMPL_PATH."mailforadminnewregistraion.html";
					$set_replace_var['name'] = $this->post['company_person'] ;	
					$sent = $mailObj->send($tmppath1,$set_replace_var); // send mail with attachment
					
						if($sent == 1) {
							//header('Location:index.php?file=loginview');
							header('Location:'.ADMIN_URL_PATH.'payex/successfully.php');
							//header('Location:http://localhost/nklt_qr/nklt_qr/admin/payex/successfully.php');
							exit;
						}
					}
				} else{
					header('Location:'.ADMIN_URL_PATH.'payex/recurring-process.php?id='.$last_insert_id);
					exit;
				}
				
			}else{
				$_SESSION['error']  = 'Problem to register.' ;
				header('Location:index.php?file=loginview#tab2');
				exit;
			}
		}
		

	}
	
	function forgotEventPass(){
		
		global $message , $mailObj;
		
		$email = $this->post["email"] ; 
		
		$sql = "select * from event where email='".$email."'" ;
		$result = $this->dbclass->select($sql);
	
		if(count($result)>0) {
				
			$newPassword = $this->randomPassword();
			$organizerName = $result[0]['organizer_name'] ;
			
			$query = "UPDATE `event` SET `password` = '".md5($newPassword)."' WHERE email = '".$email."'";
			$this->dbclass->setQuery($query);
			
			$mailObj->to = $email;
			$mailObj->subject = $message['mail']['forgot_pass_title'];
			
			$tmppath = MAIL_TMPL_PATH."mailforgetpassword.html";
			$set_replace_var['name'] = $organizerName ;	
			$set_replace_var['password'] = $newPassword;
			
			$sent = $mailObj->send($tmppath,$set_replace_var); // send mail with attachment
			
			if($sent == 1)
				$_SESSION['success'] = $message["mail"]["mailSent"];
			else 	
				$_SESSION['error']   = $message['mail']['mailSentError'];
				
			header('Location:index.php?file=loginview');
			exit;
			
		} else {
			$_SESSION['error'] = $message['logIn']['mailidNotMatched'];
			header('Location:index.php?file=loginview&action=forgetpassword');
			exit;
		}
	}
	
	function passwordChange(){	
		global $message , $mailObj;
		$status = $this->checkPassword();
		if($status==1) {
			$finalStatus = $this->changePassword();
			$_SESSION['success'] = $message['chnagePass']['password'];
			header('Location: index.php?file=changepasswordview');
		}else{
			$_SESSION['error'] = $message['chnagePass']['passNotMatched'];
			header('Location: index.php?file=changepasswordview');
		}	
	}
	
	/********************** Event Panel Start *************************/
	
	//login function for event panel
	function checkEventAdminLogin(){
		
		global $message;
		
		// validate captcha
		if(SHOW_CAPTCHA==1) {
			if($_REQUEST['captchaData'] != $_SESSION['random_number']) {
				$_SESSION['error'] = $message['logIn']['captcha'];
				header('Location:index.php?file=loginview');
				exit;
			}
		}
		
	 	// check the admin username & password
	 	$sql = "select * from event where email='".$this->post["email"]."' AND password='".md5($this->post["vpassword"])."' AND status='Active' ";
	 	$result = $this->dbclass->select($sql);
		if(count($result)>0) 
		{
			$sess_eventid 		= $result[0]["id"];
			$sess_email 		= $result[0]["email"];
			$sess_name 	= $result[0]["name"];
			$sess_organizer_name 	= $result[0]["organizer_name"];
			
			$_SESSION["sess_eventid"] = $sess_eventid;
			$_SESSION["sess_vemail"]  = $sess_email;
			$_SESSION["sess_first_name"] = $sess_name;
			$_SESSION["sess_last_name"] = $sess_organizer_name;
			
			// Check checkbox is chacked or not
			if($_REQUEST["rememberme"]=='on'){
				setcookie("cookie_event", $this->post["email"], time()+60*60*24*30);
				setcookie ("cookie_event_password",  $this->post["vpassword"], time()+60*60*24*30);
			}else{
				setcookie("cookie_event", '');
				setcookie ("cookie_event_password",'', (time()-3600) );
			}
			header('Location:index.php?file=dashboardview');
			exit;
		} else {
			$_SESSION['error'] = $message['logIn']['notmatched'];
			header('Location: index.php?file=loginview');
			exit;
		}	
		
	}
}
?>