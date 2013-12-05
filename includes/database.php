<?php
/*
 * database.php: Contains database related functions
 */

// Connect to the MySQL server
$conn = mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
mysql_select_db(MYSQL_DB, $conn) or die(mysql_error());	

// The following functions execute the respective queries to the database
// Where $fields are required, the column should be the key
function select($columns, $from = "", $cond = "") {
	if (empty($from) && empty($cond))
		$sql = "SELECT $columns";
	/*else if (empty($cond))
		$sql = "SELECT * FROM $columns $from";*/
	else
		$sql = "SELECT $columns FROM $from $cond";
    return query($sql);
}
function insert($table, $fields) {
    foreach($fields as $col => $val) {
        $columns .= "$col,";
        if ($val == NULL) {
        	$values .= "NULL,";
		} else
			$values .= "'$val',";
    }
    $columns = substr($columns, 0, strlen($columns) - 1);
    $values = substr($values, 0, strlen($values) - 1);
    
    $sql = "INSERT INTO $table($columns) VALUES($values);";
    return query($sql);
}
function replace($table, $fields) {
    foreach($fields as $col => $val) {
        $columns .= $col . ",";
        $values .= "'".$val."',";
    }
    $columns = substr($columns, 0, strlen($columns) - 1);
    $values = substr($values, 0, strlen($values) - 1);
    
    $sql = "REPLACE INTO $table($columns) VALUES($values)";
    return query($sql);
}
function delete($table, $cond) {
    if (is_array($cond)) {
	    foreach($cond as $col => $val) {
	        if (!isset($where)) $where = "$col = ' $val '";
	        else $where .= "AND $col = '$val'";
	    }
	    
	    $sql = "DELETE FROM $table WHERE $where";
    } else
    	$sql = "DELETE FROM $table $cond";
    	
    return query($sql);
}
function update($table, $fields, $cond) {
    foreach($fields as $col => $val) {
    	// remove quotes if the first char in the value is a quote
        if (substr($val, 0, 1) == "'")
	        $colval .= $col . " = " . substr($val, 1, strlen($val)) . ",";
	    else 
	        $colval .= $col . " = '" . $val . "',";
    }
    $columns = substr($colval, 0, strlen($colval)-1);
    $sql = "UPDATE $table SET $columns $cond";
    return query($sql);
}


function esc($text) {return mysql_escape_string($text);}
function unesc($text) {return stripslashes($text);}
function assoc($result) {return mysql_fetch_assoc($result);}
function rows($result) {return mysql_num_rows($result);}

function query($sql) {
    global $conn;
    $query = mysql_query($sql, $conn);
    if (!$query) {
        debug_backtrace();
        die(mysql_error());
	}
        //system("echo $sql;" . mysql_error() . " >> log/sql_errors.txt");
	
	
	if (substr($sql, 0, 6) == "INSERT") {
		return mysql_insert_id($conn);
	} else 
    	return $query;
}
?>