<?php
////////////////////////////////////////////////////////////
	
// This Class contains functions for interacting with database operations.
	 
////////////////////////////////////////////////////////////

//_________define class_________________________// 
	class DbClass {	
		var $CONN;
		
		function dbclass() { //constructor
			$conn = mysql_connect('137.135.241.244','nklt','nklt');
			mysql_query("set names utf8");	
			if(!$conn){	
				$this->error("Connection attempt failed");
			}
			if(!mysql_select_db('nklt',$conn)) {	
				$this->error("Database Selection failed");		
			}
			$this->CONN = $conn;
			return true;
		}
		//_____________close connection____________//
		function close(){
			$conn = $this->CONN ;
			$close = mysql_close($conn);
			if(!$close){
			  $this->error("Close Connection Failed");	}
			return true;
		}
	    //______________catch error__________________//
		function error($text) {
			global $mailObj;
			 
			$no = mysql_errno();
			$msg = mysql_error();
			$msg2 = "<hr><font face=verdana size=2>";
			$msg2 .= "<b>Custom Message :</b> $text<br><br>";
			$msg2 .= "<b>Error Number :</b> $no<br><br>";
			$msg2 .= "<b>Error Message	:</b> $msg<br><br>";
			$msg2 .= "<hr></font>";
			//echo $msg2;
			//$mailObj->nicMailSet("kirti.valand@indianic.com","","Error",$msg2);
			//$mailObj->send();
			if($_SESSION['sql_error'] != ""){
				unset($_SESSION['sql_error']);
			}
			$_SESSION['sql_error'] = $msg2;
			//Redirect to error page
			//header("Location: index.php?file=errorpageview");	
			exit;
		}
		//_____________select records___________________//
		function select ($sql=""){	
			if(empty($sql)) { return false; }
			//echo preg_match("/^select/i",$sql);
			/*if(!preg_match("/^select/i",$sql)){	
			//echo $sql;
			  echo "Wrong Query<hr>$sql<p>";
					return false;		} */
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);			
			if((!$results) or empty($results))	{	return false;		}
			$count = 0;
			$data  = array();
			while ( $row = mysql_fetch_array($results))	{	
				$data[] = $row;
				$count++;		}
			mysql_free_result($results);
			return $data;
		}
		/*function new_select_query($sql=""){
		
			if(empty($sql)) { return false; }
			if(!preg_match("/^select/i",$sql)){	
			  echo "Wrong Query<hr>$sql<p>";
					return false;		} 
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);			
			if((!$results) or empty($results))	{	return false;		}
			$count = 0;
			$data  = array();
			while ( $row = mysql_fetch_array($results))	{	
				$data[] = $row;
				$count++;		}
			mysql_free_result($results);
			return $data;
		}*/
		function Find_Primary_Key($tableName)
		{
			$sql = "SHOW KEYS FROM ".$tableName." WHERE Key_name = 'PRIMARY'";
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);			
			if((!$results) or empty($results))	{	return false;		}
			$count = 0;
			$data  = array();
			while ( $row = mysql_fetch_array($results))	{	
				$data[] = $row;
				$count++;		}
			mysql_free_result($results);
			return $data;
		}
	    //__________total rows affected______________________//
	    function affected($sql="")	{
			if(empty($sql)) { return false; }
			if(!preg_match("/^select/i",$sql)){
			  	echo "Wrong Query<hr>$sql<p>";
					return false;		}
			if(empty($this->CONN)) 	{ 	return false; 	}
				
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);
			if( (!$results) or (empty($results)) ) 
				{	return false;	}
			$tot=0;
			$tot=mysql_affected_rows();
			return $tot;
		}
	    //________insert record__________________//
		function insert($sql=""){ 
			if(empty($sql)) { 
				return false; 
			}
			if(!preg_match("/^insert/i",$sql)){	
				return false;		
			}
			if(empty($this->CONN)){	
				return false;		
			}
			$conn = $this->CONN;			
			
			$results = mysql_query($sql,$conn);			
			
			if(!$results){
				$this->error("Insert Operation Failed..<hr>$sql<hr>");
				return false;		
			}
			$id = mysql_insert_id();
			return $id;
		}
	    //___________edit and modify record___________________//
		function edit($sql="")	{
			if(empty($sql)) { 	return false; 		}
			if(!preg_match("/^update/i",$sql)){	return false;		}
			if(empty($this->CONN)){	return false;		}
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);
			$rows = 0;
			$rows = @mysql_affected_rows();
			return $rows;
		}
		//____________generalize for all queries___________//
		function sqlQuery($sql="")	{	
			
			if(empty($sql)) { return false; }
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
			
			if(!$results){
			   $this->error("Query went bad ! <hr>$sql<hr>");
					return false;		}		
			if(!preg_match("/^select/i",$sql)){return true; 		}
			else {
		  	    $count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results,MYSQL_ASSOC)){	
					$data[$count] = $row;
					$count++;				
				}
				mysql_free_result($results);
				return $data;
		 	}
		}	
		
		function adder($sql="")	{	
			if(empty($sql)) 
				{ 	return false; 	}
			if(empty($this->CONN))
				{	return false;	}
				
			$conn = $this->CONN;
			$results = @mysql_query($sql,$conn);
	
			if(!$results)
				$id = "";  
			else
				$id = mysql_insert_id();
			return $id;
		}
		
				/**
		* @return array
		* @param string $tablename the tablename
		* @desc check if a table with the given name exists in DB
		*/
		function tableExists($tablename)
		{
			$conn = $this->CONN ;

			if(empty($conn)) { return false; }
			
			$results = mysql_list_tables(DB_DATABASE) or die("Could not access Table List...<hr>" . mysql_error());
			
			if(!$results){
				
				$message = "Query went bad!";
				//mysql_close($conn);
				die($message);
				return false;
				
			}else{
				
				$count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results)) {
					if ($row[0]==$tablename) {
						return true;
					//	mysql_close($conn);
						exit;
					}
				}
				mysql_free_result($results);
				//mysql_close($conn);
				return false;
			}
		}
		
		
		
	function extraQueries($sql="")	{	
			
			if(empty($sql)) { return false; }
			if(empty($this->CONN)) { return false; }
			$conn = $this->CONN;
			$results = mysql_query($sql,$conn) or $this->error("Something wrong in query<hr>$sql<hr>");
			
			if(!$results){
			   $this->error("Query went bad ! <hr>$sql<hr>");
					return false;		}		
			else {
		  	    $count = 0;
				$data = array();
				while ( $row = mysql_fetch_array($results))
				{	$data[$count] = $row;
					$count++;				}
				mysql_free_result($results);
				return $data;
		 	}
		}	
	
	function setQuery($sql=""){
		//echo $sql;exit;
		if(empty($sql)){ 
			return false; 
		}
		if(empty($this->CONN)){ 
			return false; 
		}
		
		$conn = $this->CONN;
		if(count(explode(';',$sql)) >1 ){
			$expsql = explode(';',$sql);
			mysql_query($expsql[0],$conn);
			$results = mysql_query($expsql[1],$conn) or $this->error("Something went wrong in the query<hr>$sql<hr>");
		}else{
			$results = mysql_query($sql,$conn) or $this->error("Something went wrong in the query<hr>$sql<hr>");	
		}
		
		if(!$results){
			$this->error("Query went bad ! <hr>$sql<hr>");
			return false;	
		}else{
			return	$results;
		}
	}
	
	function setquery1($sql=""){
		//echo $sql;exit;
		if(empty($sql)){ 
			return false; 
		}
		if(empty($this->CONN)){ 
			return false; 
		}
		
		$conn = $this->CONN;
		if(count(explode(';',$sql)) >1 ){
			$expsql = explode(';',$sql);
			mysql_query($expsql[0],$conn);
			$results = mysql_query($expsql[1],$conn) or $this->error("Something went wrong in the query<hr>$sql<hr>");
		}else{
			$results = mysql_query($sql,$conn) or $this->error("Something went wrong in the query<hr>$sql<hr>");	
		}
		
		if((!$results) or empty($results))	{	return false;		}
			$count = 0;
			$data  = array();
			while ( $row = mysql_fetch_array($results))	{	
				$data[] = $row;
				$count++;		}
			mysql_free_result($results);
			return $data;
	}
	
	
	
	
	
	
	
	
	function dbFetchRow($result) {
        return mysql_fetch_row($result);
	}
	
	function dbFetchArray($result) {
        return mysql_fetch_array($result);
	}
	
	function dbFreeResult($result) {
        @mysql_free_result($result);
	}
	
	function dbNumRows($result) {
       return mysql_num_rows($result);
	}
	
	function dbNumFields($result) {
       return mysql_num_fields($result);
	}
	
	function dbFieldName($result) {
       return mysql_field_name($result);
	}
	
	function dbFieldType($result) {
       return mysql_field_type($result);
	}
	
	function totalTrim(&$value , $replace_with='' , $replace_to=''){
		$value = trim($value);
		if($replace_to==''){ 	
			return NULL;
		}
	
		while(stripos($value, $replace_to) != '' )
		{
			$value = ereg_replace( $replace_to, $replace_with , $value);
		}
		return NULL;
	}
	/*This function will genrate list of table name from slected database*/
	function dbTablesName(){
		$data = array();
		$tablesname = mysql_list_tables(DB_DATABASE);
	
		$i=0;
		//var_dump($tablesname);
		while ($row = mysql_fetch_array($tablesname)){
			if($row[0]<>"admin" && $row[0]<>"grid")
			$data[$i] = $row[0];
			
			$i++;
		}

		return $data;
	}
	/*This function will populate fields of selected table*/
	function dbTableField($tableName){
		$data = array();
		$sql = "SHOW COLUMNS FROM ".$tableName;
		$result = mysql_query($sql);
		$i = 0;
		while($row = mysql_fetch_assoc($result)){
			$data[$i]['Field'] = $row['Field'];
			$data[$i]['Type'] = $row['Type'];
			$data[$i]['Null'] = $row['Null'];
			$data[$i]['Key'] = $row['Key'];
			$data[$i]['Default'] = $row['Default'];
			$data[$i]['Extra'] = $row['Extra'];
			$i++;
		}
		
		return $data;
	}

	} //________ends the class here__________//

?>