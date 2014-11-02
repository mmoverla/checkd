<?php
////////////////////////////////////////////////////////////
// This class contains SystemSettings & other common function of admin panel
////////////////////////////////////////////////////////////
include('system/library/classes/dbclass.php');
##############################################
# Include Required Common File Here
##############################################

class AdminGlobal extends DbClass{
	var $post;
	var $get;
	var $request;
	var $dbclass;
	
	// This is the Constructor Function it will be called default when user declare the class object
	function AdminGlobal($dbObj){
		$this->dbclass = $dbObj;
		$this->post = &$_POST;
		$this->get = &$_GET;
		$this->request = &$_REQUEST;
	}	
	

	function getAllSystemSettingValue(){
		$sql = "SELECT value FROM settings WHERE ehide=1";
		$rslt = $this->dbclass->select($sql);
		for($i=0; $i<count($rslt); $i++)	{
			define($rslt[0]['varname'] , $rslt[0]['value']);
		}
		return NULL;
	}

	// This Function is used to get the setting variable value
	function getSystemSettingValue($code){
		
		$sql = "SELECT value 
				FROM settings
				WHERE varname = '".$code."'";
		$rslt = $this->dbclass->select($sql);
		
		return $rslt[0]['value'];
	}
	
	//This function is used to convert date format to site setting format ex. $getdate=2011-01-12
	function getSystemDateFormat($getdate){
		
		$systemformat	=	$this->getSystemSettingValue('DATE_FORMAT');
		if(isset($getdate) && $getdate!=""){
		
			$systemdate			=	date("$systemformat",strtotime($getdate));
		}
		else {
			$systemdate 		= 	"Invlid Date Input";
		}
		return $systemdate;
	}
	
	
	// This function is used to get the days combo
	function getDays($selDay=""){
		$strDays = "";
		$strDays = "<option value=''>00</option>";
		for($ind=1;$ind<=31;$ind++){
			if($ind == $selDay)
				$strSel = "selected";
			else
				$strSel = "";
				
			$strDays.="<option value=".$ind." $strSel>".(strlen($ind)==1?"0".$ind:$ind)."</option>";
		}
		
		return $strDays;
	}

	// This function is used to get the month combo
	function getMonth($selMonth=""){
		$strMonths = "";
		$strMonths = "<option value=''>00</option>";
		for($ind=1;$ind<=12;$ind++){
			if($ind == $selMonth)
				$strSel = "selected";
			else
				$strSel = "";
				
			$strMonths.="<option value=".$ind." $strSel>".(strlen($ind)==1?"0".$ind:$ind)."</option>";
		}
		
		return $strMonths;
	}

	// This function is used to get the year combo
	function getYearReg($selYear=""){
		//if($selYear=="")
		//	$selYear = date("Y");
		$strYear = "<option value=''>0000</option>";
		for($ind=1901;$ind<=date("Y")+5;$ind++){
			if($ind == $selYear)
				$strSel = "selected";
			else
				$strSel = "";
				
			$strYear.="<option value=".$ind." $strSel>".(strlen($ind)==1?"0".$ind:$ind)."</option>";
		}
		
		return $strYear;
	}
	
	// This function is used to get the month combo for the exp date
	function getExpMonth($selMonth=""){
	//	if($selMonth=="")
			//$selMonth = date("m");
		$monthArr = array(
			"Jan"=>"January",
			"Feb"=>"February",
			"Mar"=>"March",
			"Apr"=>"April",
			"May"=>"May",
			"Jun"=>"June",
			"Jul"=>"July",
			"Aug"=>"August",
			"Sep"=>"September",
			"Jan"=>"October",
			"Nov"=>"Nobember",
			"Dec"=>"December"
		);
		$strMonths = "";
		$strMonths = "<option value=''>Month</option>";
		foreach($monthArr as $key=>$val){

			if($key == $selMonth)
				$strSel = "selected";
			else
				$strSel = "";
			
			$strMonths.="<option value=".$key." $strSel>".$key."</option>";
		}		
		return $strMonths;

	}

	// This function is used to get the year combo for exp date
	function getExpYear($selYear=""){
		//if($selYear=="")
		//	$selYear = date("Y");
		$strYear = "<option value=''>0000</option>";
		for($ind=date("Y");$ind<=date("Y")+15;$ind++){
			if($ind == $selYear)
				$strSel = "selected";
			else
				$strSel = "";
				
			$strYear.="<option value=".$ind." $strSel>".(strlen($ind)==1?"0".$ind:$ind)."</option>";
		}
		
		return $strYear;
	}

	// This function is used to get the country comob
	function getCountryCombo($selCountry=""){
	$sql_sel = "select icountry_id, vcountry_code, vcountry_name from country where estatus='Active'";
		$rslt = $this->dbclass->select($sql_sel);
		
		$strCountry="";
		for($ind=0;$ind<count($rslt);$ind++){
			if($selCountry == $rslt[$ind]['vcountry_name'])
				
				$strSel = "selected";
			else
				$strSel = "";
				
			$strCountry.="<option value='".$rslt[$ind]['vcountry_name']."' $strSel >".$rslt[$ind]['vcountry_code']." - ".$rslt[$ind]['vcountry_name']."</option>";
		}
		return $strCountry;
	}

	function getListCountry(){
		$sql_sel = "select icountry_id, vcountry_code, vcountry_name from country where estatus='Active'";
		$rslt = $this->dbclass->select($sql_sel);
		return $rslt;
	}
	
	// method to set the error session when error occur.
	function setFormInSession($formName,$receiver){
		foreach($this->post as $key => $value){
			$_SESSION[$formName][$key]=$value;//echo $key . "   ".$value."<br>";
		}
	}
	
	function makePrice($text){
		$DISPLAYCURRENCY=$this->getSystemSettingValue("DISPLAYCURRENCY");
		return $DISPLAYCURRENCY." ".number_format($text,2,'.',',');
	}
	function setMonthType($lstMonth){
			if($lstMonth == 1)
				return 'January';
			elseif($lstMonth == 2)
				return 'February';
			elseif($lstMonth == 3)
				return 'March';
			elseif($lstMonth == 4)
				return 'April';
			elseif($lstMonth == 5)
				return 'May';
			elseif($lstMonth == 6)
				return 'June';
			elseif($lstMonth == 7)
				return 'July';
			elseif($lstMonth == 8)
				return 'August';
			elseif($lstMonth == 9)
				return 'September';
			elseif($lstMonth == 10)
				return 'October';
			elseif($lstMonth == 11)
				return 'November';
			elseif($lstMonth == 12)
				return 'December';

	}
	
	
	
	// method for selected value in combo.
	function  echoSelected($fieldname){
	#echo "199".$fieldname.$this->request['option']; exit;
		if($this->request['option']==$fieldname)
			echo " selected";
	}
	
	// method to set the error session when error occur.
	function adminSetFormInSession($formName){
		foreach($this->post as $key => $value){
			$_SESSION[$formName][$key]=$value;
		}
	}
	// method to set the error session when error occur.
	function memberSetFormInSession($formName){
		foreach($this->post as $key => $value){
			$_SESSION[$formName][$key]=$value;
		}
	}
	// method to set the error session when error occur.
	function greetingSetFormInSession($formName){
		foreach($this->post as $key => $value){
			$_SESSION[$formName][$key]=$value;
		}
	}
	// check  url include http:// or https:// in url.	
	function checkUrlString( $url )	
	{
		$url = trim($url);		
			if( 'http://' !=  strtolower( substr($url, 0, 7)) && 'https://' !=  strtolower(substr($url, 0, 8)) )
				return false;
			else					
				return true;
	}
	
	// Function for generate grid based on master table fields
	function generateGrid($sTable, $sIndexColumn , $aColumns , $action_prefix='')
	{
			$dbObj = $this->dbclass;
			
			//echo $sTable; exit;
			// For export functionality
			unset($_SESSION['setSearchTrue']);
			unset($_SESSION['grid_columns']);
		
			$_SESSION['grid_columns'] = $aColumns; 
				/* 
			 * Paging
			 */
			$sLimit = "";
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
					mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
						
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
				{
					if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
					{
						$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$st] ) ]."
						 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
				
				if($sTable == 'user' || $sTable == 'company'){
					$sOrder .= "ORDER BY created DESC" ;
				}
			}
			//echo $sOrder ; exit;
			
			/* 
			 * Filtering
			 * NOTE this does not match the built-in DataTables filtering which does it
			 * word by word on any field. It's possible to do here, but concerned about efficiency
			 * on very large tables, and MySQL's regex functionality is very limited
			 */
			
			$sWhere = "";
			if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $sTable == 'user') {
				$_SESSION['setSearchTrue'] = $sLimit;
				 $sWhere .= " WHERE company_id ='" .$_SESSION['sees_id']."' " ;
	            $sWhere .= "AND (";
	                $sWhere .= $_GET['search_by'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
	             $sWhere = substr_replace($sWhere, "", -3);
	            $sWhere .= ')';
	           
	        } 
			else if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				$_SESSION['setSearchTrue'] = $sLimit;
				$sWhere = "WHERE (";
				for ( $i=0 ; $i<count($aColumns)-1 ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			else if($sTable == 'user' || $sTable == 'equipment_type'){
				if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
					$sWhere .= " AND ";
				}else{
					$sWhere .= " WHERE ";
				}
				$sWhere .= " company_id ='" .$_SESSION['sees_id']."' " ;
				
				if($_SESSION['login_user'] != '' && $sTable == 'user'){
					$sWhere .= "AND  id !='" .$_SESSION['login_user']."' " ;
				}
			
			}
			/* Individual column filtering */
			for ( $i=0 ; $i<count($aColumns)-1; $i++ )
			{
				if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
				{
					if ( $sWhere == "" )
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				}
			}
			
			
			
			/*
			 * SQL queries
			 * Get data to display
			 */
			
			//unset($aColumns[count($aColumns)-1]);
			unset($aColumns[count($aColumns)]);
				
			 $sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
				FROM   $sTable 
				$sWhere
				$sOrder
				$sLimit
			";
			 
			 
			$rResult = $dbObj->select($sQuery);
			//echo $sQuery; exit;
			if($_SESSION['grid_query'] != ""){
				unset($_SESSION['grid_query']);
			}
			$_SESSION['grid_query'] = $sQuery;
			
			/* Data set length after filtering */
			$sQuery = "SELECT FOUND_ROWS()";
			$rResultFilterTotal = $dbObj->select($sQuery);
			$iFilteredTotal = $rResultFilterTotal[0]['FOUND_ROWS()'];
			
			/* Total data set length */
			$sQuery = "SELECT COUNT(".$sIndexColumn.") FROM   $sTable ";
			$rResultTotal = $dbObj->select($sQuery);
			$iTotal = $rResultTotal[0]['COUNT(id)'];
			
			
			/*
			 * Output
			 */
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
			
			for($aRowi=0; $aRowi < count($rResult); $aRowi++){	
				
				$aRow = $rResult[$aRowi];
				$row = array();
				for ( $i=0 ; $i<=count($aColumns) ; $i++ ){
					if($aColumns[$i] == "id"){	
						/*if($sTable=="equipment_type")
						{
							$user_id = $aRow[$aColumns[$i]];		
							$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";	
						}
						else*/ if($sTable!="company" || $sTable!="user" || $sTable!="equipment_type"){
							$user_id = $aRow[$aColumns[$i]];
						}else{
							$user_id = $aRow[$aColumns[$i]];		
							$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
						}	
					}elseif($aColumns[$i] == "user_parent"){	
						if(strstr($aRow[$aColumns[$i]],'c')){
							$company_id = str_replace('c','',$aRow[$aColumns[$i]]);
							$company_sql = 'SELECT `contact_person`  FROM `company` WHERE `id` = '.$company_id;
							$companyResult = $dbObj->select($company_sql);
							$parent_name = $companyResult[0]['contact_person'];
						}else{
							$user_id = $aRow[$aColumns[$i]];
							$user_sql = 'SELECT `name`  FROM `user` WHERE `id` = '.$user_id;
							$userResult = $dbObj->select($user_sql);
							$parent_name = $userResult[0]['name'];
						}
					
					
						$row[] = $parent_name;
					}else if ( $aColumns[$i] == "email" ){
						if(empty($aRow[$aColumns[$i]])){
							$row[]='-';
						}else{
						$row[] = '<a class="email" href="mailto:'.$aRow[$aColumns[$i]].'">'.$aRow[$aColumns[$i]].'</a>';
						}
					}
					else if($aColumns[$i] == "max_users_allowed"){
						if($aRow[0] != 0 && $aRow[$aColumns[$i]] == 0){
							$row[] = 'Unlimited' ;
						}else{
							$row[] = $aRow[$aColumns[$i]];
						}
					}
					else if ( $aColumns[$i] == "status" ){
						if($sTable == 'company') {
							if($aRow[$aColumns[$i]] == 'Approve') {
								$class = ' green' ;
								$text = 'Click here to decline' ;
								//$row[] = '<a title="'.$text.'" class="changestatus'.$class.'" href="index.php?file=useractions&status='.$aRow[$aColumns[$i]].'&task=changeStatus&id='.$user_id.'">'.$aRow[$aColumns[$i]].'</a>' ;
								$row[] = "<img src='images/Approve.png' height='20' width='20' title='Approve' alt='Approve' />" ;
							} else if($aRow[$aColumns[$i]] == 'Decline'){
								$class = ' red' ;
								$text = 'Click here to approve' ;
								//$row[] = '<a title="'.$text.'" class="changestatus'.$class.'" href="index.php?file=useractions&status='.$aRow[$aColumns[$i]].'&task=changeStatus&id='.$user_id.'">'.$aRow[$aColumns[$i]].'</a>' ;
								$row[] = "<img src='images/Decline.png' height='20' width='20' title='Decline' alt='Decline' />" ;
							} else if($aRow[$aColumns[$i]] == 'Pending'){
								$class = ' yellow' ;
								$text = 'Click here to Select' ;
								//$row[] = '<a title="'.$text.'" class="changestatus'.$class.'" href="javascript:yellow('.$user_id.');">'.$aRow[$aColumns[$i]].'</a><div id="pending-form-'.$user_id.'" style="display:none;"><form id="form_'.$user_id.'" method ="post"><select class="status_select" id="status_select_'.$user_id.'"><option value="Approve">Approve</option><option value="Decline">Decline</option></select></form></div>' ;
								$row[] = "<img src='images/Pending.png' height='20' width='20' title='Pending' alt='Pending' />" ;
							}
							if($aRow[$aColumns[$i]] == 'Pending'){
								$row[] = '<form id="form_'.$user_id.'" method ="post"><select class="status_select" id="status_select_'.$user_id.'"><option value="">Select Status</option><option value="Approve" style="color:green;">Approve</option><option value="Decline" style="color:red;">Decline</option></select></form>'	 ;
							}else if($aRow[$aColumns[$i]] == 'Approve'){
								$row[] = '<form id="form_'.$user_id.'" method ="post"><select class="status_select" id="status_select_'.$user_id.'"><option value="">Select Status</option><option value="Decline" style="color:red;">Decline</option></select></form>'	 ;
							}else if($aRow[$aColumns[$i]] == 'Decline'){
								$row[] = '<form id="form_'.$user_id.'" method ="post"><select class="status_select" id="status_select_'.$user_id.'"><option value="">Select Status</option><option value="Approve" style="color:green;">Approve</option></select></form>'	 ;
							}
						}else if($sTable == 'user'){
							if($aRow[$aColumns[$i]] == 'Active') {
								$class = ' green' ;
								$text = 'Click here to deactivate' ;
							} else{
								$class = ' red' ;
								$text = 'Click here to activate' ;
							}
							if(empty($aRow[$aColumns[$i]])){
								$row[] = '-';
							}else{
							$row[] = '<a title="'.$text.'" class="changestatus'.$class.'" href="index.php?file=useractions&status='.$aRow[$aColumns[$i]].'&task=changeUserStatus&id='.$aRow['id'].'">'.$aRow[$aColumns[$i]].'</a>' ;
							}
						}
					}else if ( $aColumns[$i] != ' ' ){						
						if($aColumns[$i] == "id"){
							$user_id = $aRow[$aColumns[$i]];
						}
					
						/* General output */	
						if($i==count($aColumns)){
							//if($sTable != 'user') {
								$row[]= "<a class='remove' href='javascript:deleteUser(".$aRow['id'].")'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a>";
							//}
						}							
						else {			
							if($aColumns[$i] == $action_prefix){
								if(empty($aRow[$aColumns[$i]])){
									$row[]='-';
								}else{
									$row[] = "<a href='index.php?file=".$sTable."addview&id=".$user_id."' >".$aRow[$aColumns[$i]]."</a>";
								}
							}
							else if($aColumns[$i] == "published" && $action_prefix != ''){
								if($aRow[$aColumns[$i]] == 1){
									$row[] = "<a class='published' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/published.png' height='20' width='20' title='Published' alt='Published' /></a>";
								}else{
									$row[] = "<a class='unpublished' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/unpublished.png' height='20' width='20' title='Unpublished' alt='Unpublished' /></a>";
								}
							}
							else if($aColumns[$i] == "id" && $sTable=="company"){
								
							}
							else if($aColumns[$i] == "id"){
								$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}else{
								$row[] = $aRow[$aColumns[$i]];
							}
						}
					}	
				}
				if($sTable == 'user'){
					foreach($row as $ukey=>$uvalue){
						if($uvalue == ''){
							$row[$ukey]='-';
						}
					}
				}
			
				$output['aaData'][] = $row;
			}
			echo json_encode( $output );	
		
		
	}
	
	function generateCustomGrid($sTable, $sIndexColumn , $aColumns , $action_prefix='')
	{
			$dbObj = $this->dbclass;
			
			//echo $sTable; exit;
			// For export functionality
			unset($_SESSION['setSearchTrue']);
			unset($_SESSION['grid_columns']);
		
			$_SESSION['grid_columns'] = $aColumns; 
			/*echo '<pre>' ; print_r($aColumns); 
			exit;*/
			
			$sWhere = "" ;
			if($sTable == 'equipment_log'){
       		
       			$sWhere .= " WHERE equipment_id = '".$_GET['id']."' AND take_away_date != '0000-00-00 00:00:00'";
			}else if($sTable == 'report_damage'){
				
				$sWhere .= " WHERE equipment_id = '".$_GET['id']."'";
			}
			
			/* 
			 * Paging
			 */
			$sLimit = "";
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
					mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
						
			/*
			 * Ordering
			 */
			$sOrder = "";
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
				{
					if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
					{
						$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$st] ) ]."
						 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
			if($sTable == 'equipment_log') {
				$sOrder = "ORDER BY id DESC" ;
			}else{
				$sOrder = "ORDER BY id DESC" ;
			}
			//echo $sOrder ; exit;
			
			/* 
			 * Filtering
			 * NOTE this does not match the built-in DataTables filtering which does it
			 * word by word on any field. It's possible to do here, but concerned about efficiency
			 * on very large tables, and MySQL's regex functionality is very limited
			 */
			
			
			if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $sTable == 'user') {
				$_SESSION['setSearchTrue'] = $sLimit;
				 $sWhere .= " WHERE company_id ='" .$_SESSION['sees_id']."' " ;
	            $sWhere .= "AND (";
	                $sWhere .= $_GET['search_by'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
	             $sWhere = substr_replace($sWhere, "", -3);
	            $sWhere .= ')';
	           
	        } 
			else if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				$_SESSION['setSearchTrue'] = $sLimit;
				$sWhere .= "AND  (";
				for ( $i=0 ; $i<count($aColumns)-1 ; $i++ )
				{
					$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}
			else if($sTable == 'user' || $sTable == 'equipment_type'){
				if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" ){
					$sWhere .= " AND ";
				}else{
					$sWhere .= " WHERE ";
				}
				$sWhere .= " company_id ='" .$_SESSION['sees_id']."' " ;
			}
			/* Individual column filtering */
			for ( $i=0 ; $i<count($aColumns)-1; $i++ )
			{
				if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
				{
					if ( $sWhere == "" )
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				}
			}
			
			
			
			/*
			 * SQL queries
			 * Get data to display
			 */
			
			//unset($aColumns[count($aColumns)-1]);
			unset($aColumns[count($aColumns)]);
				
			 $sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
				FROM   $sTable 
				$sWhere
				$sOrder
				$sLimit
			";
			//echo $sQuery; exit;
			$rResult = $dbObj->select($sQuery);
			
			/*echo '<pre>' ;
			print_r($rResult) ; 
			exit;*/
			if($_SESSION['grid_query'] != ""){
				unset($_SESSION['grid_query']);
			}
			$_SESSION['grid_query'] = $sQuery;
			
			/* Data set length after filtering */
			$sQuery = "SELECT FOUND_ROWS()";
			$rResultFilterTotal = $dbObj->select($sQuery);
			$iFilteredTotal = $rResultFilterTotal[0]['FOUND_ROWS()'];
			
			/* Total data set length */
			$sQuery = "SELECT COUNT(".$sIndexColumn.") FROM   $sTable ";
			$rResultTotal = $dbObj->select($sQuery);
			$iTotal = $rResultTotal[0]['COUNT(id)'];
			
			
			/*
			 * Output
			 */
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
			
			
			for($aRowi=0; $aRowi < count($rResult); $aRowi++){	
				
				$aRow = $rResult[$aRowi];
				$row = array();
				
				//$aColumns = array();
				
				if($sTable == 'report_damage'){
					$aColumns[0] = 'id';
					$aColumns[1] = 'created';
					$aColumns[2] = 'user_id';
					$aColumns[3] = 'user_type';
					$aColumns[4] = 'status';
					$aColumns[5] = 'image';
				}
				/*echo '<pre>' ;
				print_r($aColumns) ;
				exit;*/
				for ( $i=0 ; $i<=count($aColumns) ; $i++ ){
					if($aColumns[$i] == "id"){	
						if($sTable == "equipment_log" && $sTable != "report_damage"){
							$user_id = $aRow[$aColumns[$i]];
						}else if($sTable == 'report_damage'){
							$user_id = $aRow[$aColumns[$i]]; 
							$status  = $aRow[$aColumns[$i+4]] ;
							if(strtolower($status) == 'closed'){
								$row[] = "<input type='checkbox' disabled onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}else{
								$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}
						}else{
							$user_id = $aRow[$aColumns[$i]];		
							$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
						}	
					}else if ( $aColumns[$i] == "created" ){
						$row[] = $aRow[$aColumns[$i]];
					}
					else if ( $aColumns[$i] == "user_id" ){
						 if($sTable == "report_damage"){
						 	 $userId = $aRow[$aColumns[$i]];
						 	$userType = $aRow[$aColumns[$i+1]];
						 	if($userType == 'user'){
						 	$sql = "SELECT name as user_name, phone FROM `user` WHERE id = '".$userId."'" ;
							 }else{
							 	$sql = "SELECT contact_person as user_name, phone FROM `company` WHERE id = '".$userId."'" ;
							 }
						 $sqlRes = $dbObj->select($sql);
						 $phone =  $sqlRes[0]['phone'] ;
						 $row[] = $sqlRes[0]['user_name'] ;
						 }else{
						 	 $userId = $aRow[$aColumns[$i]];
						 }
						 
					}else if ( $aColumns[$i] == "user_type" ){
						 if($sTable == "report_damage"){
						 	$row[] = $phone ;
						 } else{
							 $userType = $aRow[$aColumns[$i]];
							 if($userType == 'user'){
							 	$sql = "SELECT name as user_name FROM `user` WHERE id = '".$userId."'" ;
							 }else{
							 	$sql = "SELECT contact_person as user_name FROM `company` WHERE id = '".$userId."'" ;
							 }
							 $sqlRes = $dbObj->select($sql);
							 $row[] = $sqlRes[0]['user_name'] ;
						 }
					}
					else if ( $aColumns[$i] == "take_away_date" ) {
						$row[] =  $aRow[$aColumns[$i]];
					}
					else if ( $aColumns[$i] == "delivery_date" ) {
						if(strstr($aRow[$aColumns[$i]],'0000-00-00')){
							$row[] = '&nbsp;';
						}else{
							$row[] =  $aRow[$aColumns[$i]];
						}
					}
					else if ( $aColumns[$i] == "latitude" ) {
						if($sTable == 'equipment_log'){
							$lat =  $aRow[$aColumns[$i]];
						}
					}
					else if ( $aColumns[$i] == "longitude" ){
						if($sTable == 'equipment_log'){
							$long =  $aRow[$aColumns[$i]];
						}
					}
					else if ( $aColumns[$i] == "location" )
					{
						if($sTable == 'equipment_log'){
							if($lat != '0' && $long != '0'){
								$row[] = '<a href="https://maps.google.com/maps?q='.$lat.','.$long.'" target="_blank">'.$aRow[$aColumns[$i]].'</a>';
							}else{
								$row[] = $aRow[$a1Columns[$i]];
							}
						}else{
							$row[] = $aRow[$aColumns[$i]];
						}
					} else if ( $aColumns[$i] == "image" ) {
						$row[] = '<a href="'.$aRow[$aColumns[$i]].'" target="_blank">View image</a>';
					}else if ( $aColumns[$i] != ' ' ){						
						if($aColumns[$i] == "id"){
							$user_id = $aRow[$aColumns[$i]];
						}
					
						/* General output */	
						if($i==count($aColumns)){
							if($sTable != 'equipment_log') {
								$row[]= "<a class='remove' href='javascript:deleteUser(".$user_id.")'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a>";
							} else if($sTable == 'equipment_log'){
									$sql = 'SELECT id FROM `external_users` WHERE equip_log_id = "'.$user_id.'" ORDER BY id desc LIMIT 1' ;
									$res = $dbObj->select($sql);
									if($res) {
										$row[] = '<a href="index.php?file=externaluserview&id='.$res[0]['id'].'&equipment_id='.$_GET['id'].'">View Details</a>' ;
									}else{
										$row[] = '' ;
									}
						  }else{
						  	$row[]= "<a class='remove' href='javascript:deletePhoto(".$user_id.")'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a>";
						  }
						}else {			
							if($aColumns[$i] == $action_prefix){
								{
									$row[]= "<a class='remove' href='javascript:deletePhoto(".$user_id.")'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a>";
								}
								
							}
							else if($aColumns[$i] == "published" && $action_prefix != ''){
								if($aRow[$aColumns[$i]] == 1){
									$row[] = "<a class='published' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/published.png' height='20' width='20' title='Published' alt='Published' /></a>";
								}else{
									$row[] = "<a class='unpublished' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/unpublished.png' height='20' width='20' title='Unpublished' alt='Unpublished' /></a>";
								}
							}
							else if($aColumns[$i] == "id" && $sTable != "equipment_log"){
								$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}else{
								$row[] = $aRow[$aColumns[$i]];
							}
						}
					}	
				}
				$output['aaData'][] = $row;
			}
			echo json_encode( $output );	
		
		
	}
	
	/*New Grid Function Created*/
	function generate_common_grid($mysql_params)
	{
		
		/*Variable Declaration*/
		//$aColumns = array($mysql_params->selected_columns);
		$dbObj = $this->dbclass;
		$sTable =  $mysql_params->selected_tbl_name;	
		$sIndexColumn = $mysql_params->selected_index;	
		$action_prefix = $mysql_params->selected_prefix;
		$hyper_link_column = $mysql_params->hyper_link_column;
		$chk_box_flag = $mysql_params->chkbox_column;
		$delete_flag = $mysql_params->chkbox_delete;
		$isJoin = $mysql_params->isJoin;
		$dynamic_query = "";
		$group_by_columns = "";
		$default_order_by = "";
		if($mysql_params->where_condition<>""){
		$dynamic_where_condition = stripslashes($mysql_params->where_condition);}
		if($isJoin=="true")
		{
			$dynamic_query = $mysql_params->dynamic_query;
			
			if($mysql_params->join_query_condition<>"")$query_condition = $mysql_params->join_query_condition;
			
		}
		$group_by_columns = $mysql_params->group_by_columns;
		if($group_by_columns<>""){$groupbyClause = "Group by ".$group_by_columns;}
		$default_order_by = $mysql_params->default_order_by;
		
		$indexID = str_replace("`","",str_replace(".","",strstr($sIndexColumn,".")));
		$HyperLink = str_replace("`","",str_replace(".","",strstr($hyper_link_column,".")));
		$selected_col = explode(",",$mysql_params->selected_columns);
		//var_dump($mysql_params->selected_columns);
		$total_selected_col = count($selected_col);
		$j=1;
		$i=0;
		$aocolumnlist = '';
		while($total_selected_col>$i)
		{
			$created_string = trim(str_replace("`","",str_replace(".","",strstr($selected_col[$i],"."))));
			if($j<$total_selected_col)
			{
				$aocolumnlist .= "'$selected_col[$i]'".",";
				$created_columns .= ("'$created_string'".",");
			}else {$aocolumnlist .= "'$selected_col[$i]'";
			$created_columns .= ("'$created_string'");
			
			}
			$j++;
			$i++;
		}
		$created_Columns = explode(",",$created_columns);
		$created_aColumns = $created_Columns;
		$aColumns = $selected_col;
	//echo "=> ".$selected_col;
 	//	print_r($aColumns);
		//print_r($created_aColumns);
		//die;
		
			
			// For export functionality
			unset($_SESSION['setSearchTrue']);
			unset($_SESSION['grid_columns']);
			
			$_SESSION['grid_columns'] = $aColumns; 
				/* 
			 * Paging
			 */
			$sLimit = "";
			if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
			{
				$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
					mysql_real_escape_string( $_GET['iDisplayLength'] );
			}
			/*echo $_GET['iDisplayStart'];
			echo $_GET['iDisplayLength'];
			echo "Limit == ".$sLimit ;*/
			/*
			 * Ordering
			 */
			$sOrder = "";
			
			if ( isset( $_GET['iSortCol_0'] ) )
			{
				$sOrder = "ORDER BY  ";
				for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
				{
					if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
					{
						$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
						 	".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
					}
				}
				
				$sOrder = substr_replace( $sOrder, "", -2 );
				if ( $sOrder == "ORDER BY" )
				{
					$sOrder = "";
				}
			}
		
			//echo $sOrder."<br>";
			//echo $default_order_by."<br>";
			/*if($default_order_by<>"" && $_GET['iSortCol_0']==1)
			{
				$sOrder = $default_order_by;
			}*/
			
			/* 
			 * Filtering
			 * NOTE this does not match the built-in DataTables filtering which does it
			 * word by word on any field. It's possible to do here, but concerned about efficiency
			 * on very large tables, and MySQL's regex functionality is very limited
			 */
			$sWhere = "";
			//echo $_GET['DateFilter'] ;
			if (isset($_GET['sSearch']) && $_GET['sSearch'] != "")
			{
					$_SESSION['setSearchTrue'] = $sLimit;
					$sWhere = "WHERE (";
					for ( $i=0 ; $i<count($aColumns)-1 ; $i++ )
					{
						
						$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
					}
					$sWhere = substr_replace( $sWhere, "", -3 );
					$sWhere .= ')';
				
				
			}
			//echo $sWhereCustom ;						
			/* Individual column filtering */
			for ( $i=0 ; $i<count($aColumns)-1; $i++ )
			{
				if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
				{
					if ( $sWhere == "" )
					{
						$sWhere = "WHERE ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
					
				}
				//echo "==>".$aColumns[$i]."<br>";
				//echo $sWhere;
			}
			/*Below code will only executes when where condition has been added while generating new grid*/
			if($dynamic_where_condition<>"" && $sWhere<>"")
			{
				$sWhere .= " AND ".$dynamic_where_condition;
			}
			else if($dynamic_where_condition<>"" && $sWhere=="")
			{
				$sWhere = " WHERE ".$dynamic_where_condition;
			}//End of dynamic where condition..
			
			/*
			 * SQL queries
			 * Get data to display
			 */
			//print_r($aColumns);
			//echo count($aColumns);
			//unset($aColumns[count($aColumns)-1]);
			if(isset($aColumns))
			{
				unset($aColumns[count($aColumns)]);
			}

			//			 $sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			 //$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".implode(", ", $aColumns)."	
			//echo "dynamic_query==>".$dynamic_query;
			
			/*Below code will generate dynamic query.*/
			$sQuery = "
				SELECT SQL_CALC_FOUND_ROWS ".implode(",", $aColumns)."
				FROM 
				"	;
			if($sTable<>""){$sQuery .= " $sTable";}
			if($query_condition<>""){$sQuery .= " $query_condition";}
			if($sWhere<>""){$sQuery.=" $sWhere";}
			if($sWhereCustom<>""){$sQuery.=" $sWhereCustom";}
			if($groupbyClause<>""){$sQuery.=" $groupbyClause";}
			if($default_order_by<>""){$sQuery.=" $default_order_by";}
			//echo $sQuery;exit;
			$ResultQuery = $dbObj->setquery1($sQuery);//This variable will return total result and that will be count and assign to iTotal variable
			$totalresulfound = count($ResultQuery);
			if($sLimit<>""){$sQuery.=" $sLimit";}
			//$sQuery = "SELECT SQL_CALC_FOUND_ROWS DISTINCT(name),`user`.`id`, `user`.`gender` ,`user`.`phone` ,`user`.`email` ,`user`.`published` FROM user  LIMIT 0, 10";
			//echo $sQuery;exit;
		//	die
			/*End of Query code*/
			//echo $sQuery;exit;
			$rResult = $dbObj->setquery1($sQuery);
			//print_r($rResult);
			if($_SESSION['grid_query'] != ""){
				unset($_SESSION['grid_query']);
			}
			$_SESSION['grid_query'] = $sQuery;
			
			/* Data set length after filtering */
			$sQuery = "SELECT FOUND_ROWS()";
			$rResultFilterTotal = $dbObj->select($sQuery);
			$iFilteredTotal = $rResultFilterTotal[0]['FOUND_ROWS()'];
			
			/* Total data set length */
			/*
			This code is commented just because its not required. Instead of this i have counted result from sQuery.
			if($dynamic_query<>"")
			{
				$sQuery = " SELECT COUNT(".$sIndexColumn.") as totalrow FROM   $sTable $query_condition $groupbyClause";
			}
			else
			{
				$sQuery = "SELECT COUNT(".$indexID.") as totalrow FROM   $sTable ";
			}
			//echo $sQuery;
			$rResultTotal = $dbObj->select($sQuery);
			$iTotal = $rResultTotal[0]['totalrow'];
			*/
			$iTotal = $totalresulfound;
			//echo "total row".$iTotal;
			
			/*
			 * Output
			 */
			$output = array(
				"sEcho" => intval($_GET['sEcho']),
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iFilteredTotal,
				"aaData" => array()
			);
			$displayIndexColumn = $mysql_params->display_indexcolumn;
			$indexing = 0;
			$startData = $_GET['iDisplayStart'];
			$startData =  $startData+1;
			$image_column_name = $mysql_params->image_column_name;
			$image_base_path = $mysql_params->image_base_path;
			$add_button = $mysql_params->add_icon;
			//echo $startData;
			for($aRowi=0; $aRowi < count($rResult); $aRowi++){	
				
				$aRow = $rResult[$aRowi];
				$row = array();
				if($startData>0)
				{					 
					$indexing = $startData++;
				}else{$indexing = $aRowi+1;}
			//	echo $indexing."<br>";
				//print_r($aRow);
				//echo "TotalCol=>".count($created_aColumns);
				//print_r($created_aColumns);
				if($displayIndexColumn=="yes")
				{
					$row[] = $indexing;
				}
				for ( $i=0 ; $i <= count($created_aColumns) ; $i++ ){//echo "i=".$i;
					//print_r($aRow[ $i ]);
					
					//echo "BasePath=".$image_base_path."<br>";
					//echo "==>".$created_aColumns[$i]."<br>";
					//echo "ColumnName".$image_column_name."<br>";
					/*if($created_aColumns[$i]=="'$image_column_name'")
					{
						echo "GOOD <br>";
					}*/
					if ( $created_aColumns[$i] == "version" ){
						/* Special output formatting for 'version' column */
						$row[] = ($aRow[ $i ]=="0") ? '-' : $aRow[ $i ];
						//echo "1=>".$aRow[$i];
					}else if ( $created_aColumns[$i] != ' ' ){
						
						if($created_aColumns[$i] == "'$indexID'"){
							$user_id = $aRow[$i];
							//echo "2=>".$aRow[$i];
						}
						//echo "->".$aRow[$i];
						//echo $created_aColumns[$i]."==".$indexID."<br>";
					//	echo "UserID == ".$user_id."<br>";
						
						/* General output */
						//echo "-->".$i."<br>total col=".count($created_aColumns);
						
							 
						if($i==count($created_aColumns)){
							
							
							if($add_button=="yes")
							{
								$action = "<a href='index.php?file=raceaddview&race_id=".$user_id."'>
								
								<img src='".ADMIN_IMAGES_URL_PATH."edit.png' height='20' width='20' title='Edit' alt='Edit' /></a>";
								
							
                            } 
                            
                          if($delete_flag=="yes"){
                            	if($add_button=="no")
                            	{
                            		$action = "<a class='remove' href='javascript:deleteUser(".$user_id.")'><img src='".ADMIN_IMAGES_URL_PATH."remove_user1.png'  title='Remove' alt='Remove' /></a>";
                            		
                            	}else if($add_button=="yes" && $delete_flag=="yes"){
                            		
                            		
                            	$action .= "<a class='remove' href='javascript:deleteUser(".$user_id.")'><img src='".ADMIN_IMAGES_URL_PATH."remove_user1.png'  title='Remove' alt='Remove' /></a>";
                            }
                            else{
                            	
                            	$action = "<a class='remove' href='javascript:deleteUser(".$user_id.")'><img src='".ADMIN_IMAGES_URL_PATH."remove_user1.png'  title='Remove' alt='Remove' /></a>";
                            
                            }
                            
                            }
                            $row[] = $action;
						}
						else {	
						//echo "hyper_link_column = ".$HyperLink;	
						//echo $created_aColumns[$i];
					//	exit();
						/*if(trim($created_aColumns[$i])=="name")
						{
							echo "remove space";
						}else { echo "still space";}*/
						//echo $action_prefix;
						
							if(trim($created_aColumns[$i]) == "'$HyperLink'" && $action_prefix=="grid")
							{
								$CUSTOM_QUERY = "select mysql_params from grid where name = '".$aRow[$i]."'";
								//echo $CUSTOM_QUERY;
								$rowgrid_namedata = $dbObj->select($CUSTOM_QUERY);
								//echo "\nTotal record = ".count($rowgrid_namedata)."\n";
								if(count($rowgrid_namedata)>0)
								{
									//echo "<pre>";print_r($rowgrid_namedata[0]['mysql_params']);
								$my_para = json_decode($rowgrid_namedata[0]['mysql_params']);
								//echo "\n<pre>";print_r($my_para);							
								//echo $i." == ".$my_para->selected_prefix."\n";
								//var_dump($my_para);
								}
								$row[] = "<a href='index.php?file=".$my_para->selected_prefix."listview' target='_blank' >".$aRow[$i]."</a>";
								//echo "==>".$action_prefix;
								//echo "4=>".$aRow[$i];
							}
							else if($created_aColumns[$i] == "'$HyperLink'" && $action_prefix<>"grid")
							{
								$row[] = "<a href='index.php?file=".$action_prefix."addview&id=".$user_id."' >".$aRow[$i]."</a>";
							}
							else if($created_aColumns[$i] == "'published'" && $hyper_link_column != ''){
								if($aRow[$i] == 1){
									$row[] = "<a class='published' href='index.php?file=".$action_prefix."actions&task=published&id=".$user_id."' >
									<img src='images/published.png' height='20' width='20' title='Published' alt='Published' /></a>";
									//echo "5=>".$aRow[$i];
								}else{
									$row[] = "<a class='unpublished' href='index.php?file=".$action_prefix."actions&task=published&id=".$user_id."' >
									<img src='images/unpublished.png' height='20' width='20' title='Unpublished' alt='Unpublished' /></a>";
								//echo "6=>".$aRow[$i];
								}
							}
							else if($created_aColumns[$i] == "'$indexID'" &&  $chk_box_flag == "yes"){
								$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
								//echo "7=>".$aRow[$i];
							}
							else if($image_base_path<>"" && $created_aColumns[$i]=="'$image_column_name'")
								{
									//echo "in image column";
									$rand = rand(0,100000);
									$row[] = "<img src='".$mysql_params->image_base_path."/".$aRow[$i]."?c={$rand}' width='50' height='50' />";									
								}
							else{
								if($created_aColumns[$i]=="'$indexID'" && $chk_box_flag == "no"){}
								else{
									
									$row[] = $aRow[$i];
									//echo "8=>".$aRow[$i];
										
								}
								
							}
							
							
							//print_r($row);
						}	
					}	
				}
				$output['aaData'][] = $row;
				//print_r($row);
			}
			
			echo json_encode( $output );	
		
		
	
	}
	
	// Function for generate grid based on master table fields AND Sub table
    function generateGridWithJoin($sTable, $sIndexColumn, $aColumns, $action_prefix , $jTable, $bColumns) {
    	
        ini_set('memory_limit', '-1');
        $dbObj = $this->dbclass;
      
        // For export functionality
        unset($_SESSION['setSearchTrue']);
        unset($_SESSION['grid_columns']);

        $_SESSION['grid_columns'] = $aColumns;
        $_SESSION['grid_columns'] = $bColumns;
        
        /*echo '<pre>' ;
        print_r($aColumns) ;
          echo '<pre>' ;
        print_r($bColumns) ;
        exit;*/
        
        /* condition whose feedback does not zero */
         $sWhere = "";
        if($sTable == 'equipment')
        {
            $e = 'e';
            $c = 'u';
            
			$sWhere .= " WHERE $e.company_id = '".$_SESSION['sees_id']."'";
            
            foreach ($aColumns as $key => $value) {

               $aColumns[$key] = "$e.$value";

            }
            
            foreach ($bColumns as $key => $value) {
				if($value=="name")
				{
					$bColumns[$key] = "$c.$value as username";
				}
	            else
	            {   
	            	$bColumns[$key] = "$c.$value";
	            }
            }
            
       }  
       
             		 
       if($sTable == 'user' && $jTable == 'report_damage'){
       		
       		$e = 'el';
            $c = 'u';
            
            $sWhere .= " WHERE $e.user_id = $c.id AND $e.equipment_id = '".$_GET['id']."'";
            
            foreach ($aColumns as $key => $value) {

               $aColumns[$key] = "$c.$value";

            }
            
             foreach ($bColumns as $key => $value) {

               $bColumns[$key] = "$e.$value";

            }
       }
       if($sTable == 'user' && $jTable != 'report_damage'){
       		
       		$e = 'el';
            $c = 'u';
            
            $sWhere .= " WHERE $e.user_id = $c.id AND $e.equipment_id = '".$_GET['id']."' AND $e.take_away_date != '0000-00-00' ";
            
            foreach ($aColumns as $key => $value) {

               $aColumns[$key] = "$c.$value";

            }
            
             foreach ($bColumns as $key => $value) {

               $bColumns[$key] = "$e.$value";

            }
       }
       
      /* print_r($aColumns);
       exit;*/
          
      
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
                    mysql_real_escape_string($_GET['iDisplayLength']);
        }


        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
        " . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

		if($sTable == 'user' && $jTable == 'equipment_log'){
			
			$sOrder = "ORDER BY el.id DESC";
		}
		if($sTable == 'user' && $jTable == 'report_damage'){
			
			$sOrder = "ORDER BY el.created DESC";
		}
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */

       if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $sTable == 'equipment' && $jTable=='user') {
        	$_SESSION['setSearchTrue'] = $sLimit;
            $sWhere .= "AND (";
            //$sWhere .= $_GET['search_by'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            $sWhere .= $_GET['search_by'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
             $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
       }
      else if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
        	$_SESSION['setSearchTrue'] = $sLimit;
            $sWhere .= "AND (";
           // for ($i = 0; $i < count($bColumns) - 1; $i++) {
           		if($sTable=='equipment' && $jTable=='company'){
           			$sWhere .= $aColumns['1'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
           		}else{
                	$sWhere .= $bColumns['0'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
           		}
            //}
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
       
       //echo $sWhere; exit;

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns) - 1; $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }
        
    
        
        /*
         * SQL queries
         * Get data to display
         */

        //unset($aColumns[count($aColumns)-1]);
        unset($aColumns[count($aColumns)]);
        
       
         if($sTable == 'user'){   
         	
         	 $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) .','.str_replace(" , ", " ", implode(", ", $bColumns)). "
    FROM   $sTable as $c , $jTable as $e  
    $sWhere
    $sOrder
    $sLimit
   ";
         } else if($sTable == 'equipment'){ 
         		$sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) .','.str_replace(" , ", " ", implode(", ", $bColumns)). "
    FROM   $sTable as $e  
    LEFT JOIN `$jTable` as $c ON($e.user_id = $c.id) 
    $sWhere
    $sOrder
    $sLimit
   ";
         		
         }else{
         	
       
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) .','.str_replace(" , ", " ", implode(", ", $bColumns)). "
    FROM   $sTable as $e , $jTable as $c  
    $sWhere
    $sOrder
    $sLimit
   ";
          }
//echo $sQuery  ; exit; 
        $rResult = $dbObj->select($sQuery);
      	//print_r($rResult); exit;
        if ($_SESSION['grid_query'] != "") {
            unset($_SESSION['grid_query']);
        }
        $_SESSION['grid_query'] = $sQuery;
        
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS()";
        $rResultFilterTotal = $dbObj->select($sQuery);
        $iFilteredTotal = $rResultFilterTotal[0]['FOUND_ROWS()'];

        /* Total data set length */
        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") FROM   $sTable ";
        $rResultTotal = $dbObj->select($sQuery);
        $iTotal = $rResultTotal[0]['COUNT(id)'];


        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        /*echo '<pre>'; print_r($output); exit;*/
        foreach ($aColumns as $key => $value) {
				$value=str_replace("e.","",$value);
				$value=str_replace("u.","",$value);
               $aColumns[$key] = $value;

            }
           // print_r($bColumns);
            //exit;
            
                        
            foreach ($bColumns as $key => $value) {
            	//echo $key."=>".$value."<br>";
				$value=str_replace("u.","",$value);
				$value=str_replace("el.","",$value);
				if($value=="name as username")
				{
					$bColumns[$key] = "username";
				}
				else 
				{
               		$bColumns[$key] = "$value";
				}
			}
           

          //echo "<pre>";
           //print_r($rResult);
            //echo "<br>";
           //exit;
         for($aRowi=0; $aRowi < count($rResult); $aRowi++){	
				
				$aRow = $rResult[$aRowi];
				//print_r($aRow); exit;
				$row = array();
				
				$a1Columns=array();
				
				//$a1Columns=array_merge($aColumns,$bColumns);
				if($sTable == 'user' && $jTable == 'report_damage'){
					$a1Columns[0] = 'id';
					$a1Columns[1] = 'created';
					$a1Columns[2] = 'name';
					$a1Columns[3] = 'phone';
					$a1Columns[4] = 'status';
					$a1Columns[5] = 'image';
				}else{
					$a1Columns=array_merge($aColumns,$bColumns);
				}
				//echo count($aColumns)."==="; exit;
				//print_r($a1Columns); exit;
				for ( $i=0 ; $i<=count($a1Columns) ; $i++ )
				{
									
					if ( $a1Columns[$i] == "version" )
					{
						/* Special output formatting for 'version' column */
						$row[] = ($aRow[ $a1Columns[$i] ]=="0") ? '-' : $aRow[ $a1Columns[$i] ];
					}else if($a1Columns[$i] == "id"){
						if($sTable == 'user' && $jTable == 'report_damage'){
							$user_id = $aRow[$a1Columns[$i]]; 
							$status  = $aRow[$a1Columns[$i+4]] ;
							if($status == 'Closed'){
								$row[] = "<input type='checkbox' disabled onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}else{
								$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}
						}else if($sTable == 'user' && $jTable == 'equipment_log'){
							$user_id = $aRow[$a1Columns[$i]];
							//$row[] = 'View Details';
						}else if($sTable == 'equipment' && $jTable == 'user'){
							$row[] = $aRow[$a1Columns[$i]];
							$user_id = $aRow[$a1Columns[$i]];
						}else if($jTable == 'user' ){
							$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' class='chkstatus' name='cid[]' id='cb".$aRowi."'>";
							$user_id = $aRow[$a1Columns[$i]];
						}else{
							$user_id = $aRow[$a1Columns[$i]];
						}
					}
					else if($a1Columns[$i] == "name"){
						
						if($sTable == 'user' && $jTable == 'equipment_log'){
							$user_id = $aRow["id"];
							$row[] = "<a href='index.php?file=equipmentlogdetail&id=".$user_id."&equip_id=".$_GET['id']."'>".$aRow[$a1Columns[$i]]."</a>";
						}else if($sTable == 'equipment' && $jTable == 'user'){
							$user_id = $aRow["id"];
							$row[] = "<a href='index.php?file=equipmentaddedit&id=".$user_id."'>".$aRow[$a1Columns[$i]]."</a>";
						}else{
							$row[] = $aRow[$a1Columns[$i]];
						}
					}
					else if ( $a1Columns[$i] == "image" )
					{
						$row[] = '<a href="'.$aRow[$a1Columns[$i]].'" target="_blank">View image</a>';
					}
					else if ( $a1Columns[$i] == "created" )
					{
						
						if($sTable == 'user' && $jTable == 'report_damage'){
							$row[] = '<a href="index.php?file=reportdamagedetail&id='.$user_id.'&equip_id='.$_GET['id'].'">'.$aRow[$a1Columns[$i]].'</a>';
						}
					}
					else if ( $a1Columns[$i] == "latitude" )
					{
						if($sTable == 'user' && $jTable == 'equipment_log'){
							$lat =  $aRow[$a1Columns[$i]];
						}
					}
					else if ( $a1Columns[$i] == "longitude" )
					{
						if($sTable == 'user' && $jTable == 'equipment_log'){
							$long =  $aRow[$a1Columns[$i]];
						}
					}
					else if ( $a1Columns[$i] == "location" )
					{
						if($sTable == 'user' && $jTable == 'equipment_log'){
							if($lat != '0' && $long != '0'){
								$row[] = '<a href="https://maps.google.com/maps?q='.$lat.','.$long.'" target="_blank">'.$aRow[$a1Columns[$i]].'</a>';
							}else{
								$row[] = $aRow[$a1Columns[$i]];
							}
						}else if($sTable == 'equipment' && $jTable == 'user'){
							$euipmentLog    = 'SELECT latitude, longitude FROM equipment_log WHERE equipment_id = "'.$user_id.'" ORDER BY id DESC LIMIT 1' ;
							$euipmentLogRes = $dbObj->select($euipmentLog);
							$lat  = $euipmentLogRes[0]['latitude'];
							$long = $euipmentLogRes[0]['longitude'];
							$row[] = '<a href="https://maps.google.com/maps?q='.$lat.','.$long.'" target="_blank">'.$aRow[$a1Columns[$i]].'</a>';
						} else{
							$row[] = $aRow[$a1Columns[$i]];
						}
					}
					
					else if ( $a1Columns[$i] != ' ' ) {
						if($a1Columns[$i] == "id"){
							$user_id = $aRow[$a1Columns[$i]];
						}
						
						
						/* General output */
						
						if($i==count($a1Columns)){
							if($sTable == 'equipment' && $jTable == 'user'){
								$row[] = "<a href='index.php?file=equipmentloglistview&id=".$user_id."'><img src='images/book-icon.png'  title='Equipment Log' alt='Equipment Log' /><a/>" ;
								$row[] .= "<a href='index.php?file=reportdamageview&id=".$user_id."'><img src='images/mark.png'  title='Report Damage' alt='Report Damage' /><a/>";
								$row[] .= "<a class='remove' href='javascript:deleteEquipment(/".$user_id."/)'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a><input type='hidden' id='id".$aRowi."' name='equipId' class='equipId' value='".$user_id."' />";
							}else if($sTable == 'user' && $jTable == 'equipment_log'){
								$sql = 'SELECT id FROM `external_users` WHERE equip_log_id = "'.$user_id.'" ORDER BY id desc LIMIT 1' ;
								$res = $dbObj->select($sql);
								if($res) {
									$row[] = '<a href="index.php?file=externaluserview&id='.$res[0]['id'].'">View Details</a>' ;
								}else{
									$row[] = '' ;
								}
							}else{
								$row[]= "<a class='remove' href='javascript:deletePhoto(".$user_id.")'><img src='images/remove_user1.png'  width='20' title='Remove' alt='Remove' /></a>";
							}
						}
						else {	
							if($a1Columns[$i] == $action_prefix){
								$row[] = "<a href='index.php?file=".$sTable."addview&id=".$user_id."' >".$aRow[$a1Columns[$i]]."</a>";
							}else if($a1Columns[$i] == "published" && $action_prefix != ''){
								if($aRow[$a1Columns[$i]] == 1){
									$row[] = "<a class='published' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/published.png' height='20' width='20' title='Published' alt='Published' /></a>";
								}else{
									$row[] = "<a class='unpublished' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/unpublished.png' height='20' width='20' title='Unpublished' alt='Unpublished' /></a>";
								}
							}else if($a1Columns[$i] == "id" ){
								//if($sTable == 'user' && $jTable == 'report_damage'){
									$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
								//}
							}else{	
								//echo 'test'	; exit;
								//echo $aRow[$a1Columns[$i]] ; exit;
								$row[] = $aRow[$a1Columns[$i]];
								///print_r($row) ; 
							}
						}
					}

				}
				//print_r($row) ; exit;
				//for ( $j=0 ; $j<=count($bColumns) ; $j++ )
				//{
				//	$row[] = $aRow[$bColumns[$j]];					
				//}	
				//print_r($row);
				//exit;
				$output['aaData'][] = $row;
			}
			//print_r($output['aaData']);
			//exit;
        $output['tablename'] = $sTable;
        echo json_encode($output);
        
        
    }
    
    // Function for generate grid based on master table fields AND Sub table
    function generateGridEquipWithJoin($sTable, $sIndexColumn, $aColumns, $action_prefix) {
    	
        ini_set('memory_limit', '-1');
        $dbObj = $this->dbclass;
      
        // For export functionality
        unset($_SESSION['setSearchTrue']);
        unset($_SESSION['grid_columns']);
				
        /*$a1Columns = array();
	    $a1Columns[0] = 'id';
	    $a1Columns [1] = 'name';
	    $a1Columns[2] = 'status';
	    $a1Columns[3] = 'location' ;*/
		
	    $bColumns = array();
	    $bColumns[0] = 'user_id';
	    $bColumns [1] = 'user_type';
	    $bColumns[2] = 'user_name';
	    $bColumns [3] = 'phone';
	    $bColumns[4] = 'company_name';
	    $bColumns [5] = 'contact_number';
	    
        $_SESSION['grid_columns'] = $aColumns;
        $_SESSION['grid_columns'] = $bColumns;
        
        
        /* condition whose feedback does not zero */
         $sWhere = "";
        if($sTable == 'equipment') {
           $sWhere .= " WHERE e.company_id = '".$_SESSION['sees_id']."'";
        }  
       
             		 
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
                    mysql_real_escape_string($_GET['iDisplayLength']);
        }

		//echo $sLimit; exit;
        /*
         * Ordering
         */
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . "
        " . mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

		
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */

       if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $sTable == 'equipment' && $_GET['search_by'] != '') {
        	$_SESSION['setSearchTrue'] = $sLimit;
            $sWhere .= "AND (";
            //$sWhere .= $_GET['search_by'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            if($_GET['search_by'] == 'u.name'){
            	$sWhere .= " u.name LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            	$sWhere .= " c.contact_person LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }else{
            	$sWhere .= $_GET['search_by'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
            }
             $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
       }
      else if (isset($_GET['sSearch']) && $_GET['sSearch'] != "" && $_GET['search_by'] != '') {
        	$_SESSION['setSearchTrue'] = $sLimit;
            $sWhere .= "AND (";
           // for ($i = 0; $i < count($bColumns) - 1; $i++) {
           		if($sTable=='equipment' && $jTable=='company'){
           			$sWhere .= $aColumns['1'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
           		}else{
                	$sWhere .= $bColumns['0'] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
           		}
            //}
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }
       
        
       //echo $sWhere; exit;

        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns) - 1; $i++) {
            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere = "WHERE ";
                } else {
                    $sWhere .= " AND ";
                }
                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch' . $i]) . "%' ";
            }
        }
        
    	if($_GET['search_by'] == '' && $_GET['sSearch'] != ""){
        	$sWhere .= " AND ( e.name LIKE '%{$_GET['sSearch']}%'  OR e.status LIKE '%{$_GET['sSearch']}%'  OR e.location LIKE '%{$_GET['sSearch']}%'  OR u.name like '%{$_GET['sSearch']}%' OR et.name LIKE '%{$_GET['sSearch']}%' ) ";
        	
        }
        
        /*
         * SQL queries
         * Get data to display
         */

        //unset($aColumns[count($aColumns)-1]);
        unset($aColumns[count($aColumns)]);
        
       
       if($sTable == 'equipment'){ 
         		/*$sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) .','.str_replace(" , ", " ", implode(", ", $bColumns)). "
    FROM   $sTable as $e  
    LEFT JOIN `$jTable` as $c ON($e.id = $c.equipment_id) 
    $sWhere
    ORDER BY c.id DESC
    $sLimit
   ";*/
    //echo $sLimit; exit;		
	    if($_GET['search_by'] == 'et.name' || $_GET['search_by'] == ''){
	    	$sQuery = "SELECT e.id, e.name, e.status, e.location, e.equipment_type_id,el.id AS eid,  el.user_id, el.user_type, u.name AS user_name, u.phone, c.contact_person AS company_name, c.phone as contact_number
			FROM `equipment` AS e
			LEFT JOIN `equipment_type` AS et ON (e.equipment_type_id = et.id)
			LEFT JOIN (
			SELECT id, user_id, user_type, equipment_id, created
				FROM `equipment_log`
				ORDER BY created DESC
			) AS el ON el.equipment_id = e.id
			LEFT JOIN `user` AS u ON (el.user_id = u.id AND el.user_type = 'user' )
			LEFT JOIN `company` AS c ON (el.user_id = c.id AND el.user_type = 'company' )
			$sWhere
			GROUP BY e.id
			ORDER BY FIElD(e.status, 'Free', 'Placed', 'In use', 'Reserved') ASC $sLimit 
			" ;
	    }else{
			$sQuery = "SELECT e.id, e.name, e.status, e.location,el.id AS eid,  el.user_id, el.user_type, u.name AS user_name, u.phone, c.contact_person AS company_name, c.phone as contact_number
			FROM `equipment` AS e
			LEFT JOIN (
			SELECT id, user_id, user_type, equipment_id, created
				FROM `equipment_log`
				ORDER BY created DESC
			) AS el ON el.equipment_id = e.id
			LEFT JOIN `user` AS u ON (el.user_id = u.id AND el.user_type = 'user' )
			LEFT JOIN `company` AS c ON (el.user_id = c.id AND el.user_type = 'company' )
			$sWhere
			GROUP BY e.id
			ORDER BY FIElD(e.status, 'Free', 'Placed', 'In use', 'Reserved') ASC $sLimit 
			" ;
	    }    		
    }else{
         	
       
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) .','.str_replace(" , ", " ", implode(", ", $bColumns)). "
    FROM   $sTable as $e , $jTable as $c  
    $sWhere
    $sOrder
    $sLimit
   ";
          }
//echo $sQuery  ; exit; 
 if($_GET['search_by'] == 'et.name' || $_GET['search_by'] == ''){
 	$sPaginationQuery = "SELECT COUNT(DISTINCT(e.id)) AS total
		FROM `equipment` AS e
		LEFT JOIN `equipment_type` AS et ON (e.equipment_type_id = et.id)
		LEFT JOIN (
		SELECT id, user_id, user_type, equipment_id, created
			FROM `equipment_log`
			ORDER BY created DESC
		) AS el ON el.equipment_id = e.id
		LEFT JOIN `user` AS u ON (el.user_id = u.id AND el.user_type = 'user' )
		LEFT JOIN `company` AS c ON (el.user_id = c.id AND el.user_type = 'company' )
		$sWhere";
 }else{
	$sPaginationQuery = "SELECT COUNT(DISTINCT(e.id)) AS total
		FROM `equipment` AS e
		LEFT JOIN (
		SELECT id, user_id, user_type, equipment_id, created
			FROM `equipment_log`
			ORDER BY created DESC
		) AS el ON el.equipment_id = e.id
		LEFT JOIN `user` AS u ON (el.user_id = u.id AND el.user_type = 'user' )
		LEFT JOIN `company` AS c ON (el.user_id = c.id AND el.user_type = 'company' )
		$sWhere";
 }

        $rResult = $dbObj->select($sQuery);
      	/*print_r($rResult); 
      	exit;*/
        if ($_SESSION['grid_query'] != "") {
            unset($_SESSION['grid_query']);
        }
        $_SESSION['grid_query'] = $sQuery;
        
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS()";
        $rResultFilterTotal = $dbObj->select($sPaginationQuery);
        //$iFilteredTotal = $rResultFilterTotal[0]['FOUND_ROWS()'];
        $iFilteredTotal = $rResultFilterTotal[0]['total'];

        /* Total data set length */
        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") FROM   $sTable ";
        $rResultTotal = $dbObj->select($sPaginationQuery);
        $iTotal = $rResultTotal[0]['total']; 
        //$iTotal = COUNT($rResult); 


        /*
         * Output
         */
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
       for($aRowi=0; $aRowi < count($rResult); $aRowi++){	
				
				$aRow = $rResult[$aRowi];
				$row = array();
				
				$a1Columns=array();
				$a1Columns=array_merge($aColumns,$bColumns);
				
				for ( $i=0 ; $i<=count($a1Columns) ; $i++ )
				{
									
					if($a1Columns[$i] == "id"){
						if($sTable == 'equipment'){
							if(empty($aRow[$a1Columns[$i]])){
								$row[]='-';
							}else{
								$row[] = "<a href='index.php?file=equipmentaddedit&id=".$aRow[$a1Columns[$i]]."'>".$aRow[$a1Columns[$i]]."</a>";
							}
							$user_id = $aRow[$a1Columns[$i]];
						}else{
							$user_id = $aRow[$a1Columns[$i]];
						}
					}
					else if ($a1Columns[$i] == "location"){
						$euipmentLog    = 'SELECT latitude, longitude FROM equipment_log WHERE equipment_id = "'.$user_id.'" ORDER BY id DESC LIMIT 1' ;
						$euipmentLogRes = $dbObj->select($euipmentLog);
						$lat  = $euipmentLogRes[0]['latitude'];
						$long = $euipmentLogRes[0]['longitude'];
						if(empty($aRow[$a1Columns[$i]])){
							$row[]= '&nbsp;';
						}else{
						$row[] = '<a href="https://maps.google.com/maps?q='.$lat.','.$long.'" target="_blank">'.str_replace(',',',<br>',htmlspecialchars($aRow[$a1Columns[$i]], ENT_QUOTES)).'</a>';	
						}			}
					else if($a1Columns[$i] == "user_id"){
						$notDisplay = $aRow[$a1Columns[$i]];
					}
					else if($a1Columns[$i] == "status"){
						$status = str_replace(" ","_",strtolower($aRow[$a1Columns[$i]]));
						//$row[] = $aRow[$a1Columns[$i]].'<input type="hidden" class="status" value="'.$status.'"/>';
						if(empty($aRow[$a1Columns[$i]])){
							$row[] = '<span class="'.$status.'1">&nbsp;</span>';
						}else{
							$row[] = '<span class="'.$status.'1">'.$aRow[$a1Columns[$i]].'</span>';
						}						
					}
					else if($a1Columns[$i] == "user_type"){
						$userType = $a1Columns[$i]	;
					}
					/*else if($a1Columns[$i] == "user_name"){
						if($aRow[$a1Columns[$i]] != ''){
							$row[] = $aRow[$a1Columns[$i]];
						}else{
							$row[] = $aRow[$a1Columns[$i+2]];
						}
					}
					else if($a1Columns[$i] == "phone"){
						if($aRow[$a1Columns[$i]] != ''){
							$row[] = $aRow[$a1Columns[$i]];
						}else{
							$row[] = $aRow[$a1Columns[$i+2]];
						}
					}*/
					
					else if($a1Columns[$i] == "user_name"){
						if($aRow[2] != 'Free'){
							if($aRow[$a1Columns[$i]] != ''){
								$row[] = $aRow[$a1Columns[$i]];
							}else{
								if(empty($aRow['company_name'])){
									$row[] = '-';
								}else{
									$row[] = $aRow['company_name'];
								}
							}
							
						}else{
							$row[] = '-';
						}
					}
					else if($a1Columns[$i] == "phone"){
						if($aRow[2] != 'Free'){
							if($aRow[$a1Columns[$i]] != ''){
								$row[] = $aRow[$a1Columns[$i]];
							}else{
								if(empty($aRow['contact_number'])){
									$row[] = '-';
								}else{
									$row[] = $aRow['contact_number'];
								}
							}
							
							
						}else{
							$row[] = '-';
						}
					}
					else if($a1Columns[$i] == "company_name"){
						$companyName = $aRow[$a1Columns[$i]];
							
					}
					else if($a1Columns[$i] == "contact_number"){
						$companyPhone = $aRow[$a1Columns[$i]];
					}
					else if ( $a1Columns[$i] != ' ' ) {
						if($a1Columns[$i] == "id"){
							$user_id = $aRow[$a1Columns[$i]];
						}
						
						
						/* General output */
						
						if($i==count($a1Columns)){
							if($sTable == 'equipment'){
								$externalsql = "SELECT `id` FROM `external_users` WHERE `take_away_type` = 'external' AND `equip_log_id` = {$aRow['eid']}";
								$externalResult = $dbObj->select($externalsql);
     							$externalId = $externalResult[0]['id'];
     							if(!empty($externalId) && strstr($aRow['status'],'use')){
     								$row[] = "<a href='index.php?file=equipmentloglistview&id=".$user_id."'><img src='images/iph_icon_cond.png'  title='Equipment Log' alt='Equipment Log' /><a/>" ;
     							}else{
								
								$row[] = "<a href='index.php?file=equipmentloglistview&id=".$user_id."'><img src='images/book-icon.png'  title='Equipment Log' alt='Equipment Log' /><a/>" ;
     							}
								
								/*take away image display*/
								$apisql = "SELECT status FROM `report_damage` WHERE `equipment_id` = '{$aRow['id']}'  AND status = 'Open' LIMIT 0,1";
								$apiResult = $dbObj->select($apisql);
     							$method = $apiResult[0]['status'];
								
								/*take away image display*/
								if($method == 'Open'){
									$row[] = "<a href='index.php?file=reportdamageview&id=".$user_id."'><img src='images/rmark.png'  title='Report Damage' alt='Report Damage' /><a/>";
								}else{
									$row[] = "<a href='index.php?file=reportdamageview&id=".$user_id."'><img src='images/mark.png'  title='Report Damage' alt='Report Damage' /><a/>";
								}
								$row[] .= "<a class='remove' href='javascript:deleteEquipment(/".$user_id."/)'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a><input type='hidden' id='id".$aRowi."' name='equipId' class='equipId ".$status."' value='".$user_id."' />";
							}else{
								$row[]= "<a class='remove' href='javascript:deletePhoto(".$user_id.")'><img src='images/remove_user1.png'  title='Remove' alt='Remove' /></a>";
							}
						}
						else {	
							if($a1Columns[$i] == $action_prefix){
								$row[] = "<a href='index.php?file=".$sTable."addview&id=".$user_id."' >".$aRow[$a1Columns[$i]]."</a>";
							}else if($a1Columns[$i] == "published" && $action_prefix != ''){
								if($aRow[$a1Columns[$i]] == 1){
									$row[] = "<a class='published' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/published.png' height='20' width='20' title='Published' alt='Published' /></a>";
								}else{
									$row[] = "<a class='unpublished' href='index.php?file=".$sTable."actions&task=published&id=".$user_id."' >
									<img src='images/unpublished.png' height='20' width='20' title='Unpublished' alt='Unpublished' /></a>";
								}
							}else if($a1Columns[$i] == "id" ){
								$row[] = "<input type='checkbox' onclick='isChecked(this.checked);' value='".$user_id."' name='cid[]' id='cb".$aRowi."'>";
							}else{	
								$row[] = $aRow[$a1Columns[$i]];
							}
						}
					}

				}
				if(empty($row[1])){
					$row[1]= '-';
				}
				$output['aaData'][] = $row;
			}
			
		$output['tablename'] = $sTable;
        echo json_encode($output);
        
        
    }
	// Set Error message.
	function setErrorMsgInDiv()
	{
	   //if($_SESSION['error'] != "")	
	  // {
			$display_div = ($_SESSION['error'] != "" ? "display:block;" : "display:none;");   
			$error = "<div id='error_div' style='$display_div'>".$_SESSION['error']."";
	            		unset($_SESSION['error']);
	        $error .= "</div>";
	   //}        
		return $error;	
	}
	
	// Set success message
	function setSuccessMsgInDiv()
	{
		//if($_SESSION['success'] != '')
		//{
			$display_div = ($_SESSION['success'] != "" ? "display:block;" : "display:none;");   
			$success = "<div id='success_div' style='$display_div'>".$_SESSION['success']."";
			unset($_SESSION['success']);
	        $success .= "</div>";
		//}
		return $success;	
	}
	
	
	function generateImage()
	{
		$length = 5;
		if(is_numeric($length) AND $length < 35 AND $length > 1) 
		{
				$i = 0;
				$random_str = '';
				//$a_to_z = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';
				$a_to_z = '0123456789';
				
				$max = strlen($a_to_z)-1;
				while($i != $length) 
				{
					$rand = mt_rand(0, $max);
					$random_str .= $a_to_z[$rand];
					$i++;
				}
				
		} 
		
		$random_number = $random_str;
		$_SESSION['random_number']	=	$random_str;
		
		
		// create the image
		$image = imagecreate(70, 30);
		
		// use white as the background image
		$bgColor = imagecolorallocate ($image, 230, 240, 212); 
		
		// the text color is black
		$textColor = imagecolorallocate ($image, 0, 0, 0); 
		//$_SESSION['image_random_value'] =$rand;
		// write the random number
		imagestring ($image, 5, 5, 8,$random_number, $textColor); 
		
		// send several headers to make sure the image is not cached 
		// taken directly from the PHP Manual
		
		// Date in the past 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		
		// always modified 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		
		// HTTP/1.1 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false); 
		
		// HTTP/1.0 
		header("Pragma: no-cache"); 
		
		// send the content type header so the image is displayed properly
		header('Content-type: image/jpeg');
		
		// send the image to the browser
		imagejpeg($image);
		
		// destroy the image to free up the memory
		imagedestroy($image);
		
		
		
	}
	
	

} // class end 
?>