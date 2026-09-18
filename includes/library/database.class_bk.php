<?php
class database {
	//mysql connection String
	private function con_str_mysql() {
		return mysql_connect(HOST_NAME, DB_USER, DB_PASS);
	}

	//postgres connection String
	private function con_str_pg() {
		return pg_connect("host=" . HOST_NAME . " port=" . PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASS . "");

		//host=localhost port=5432 dbname=dbdemo user=postgres password=padmin
	}

	//Database fetch
	function fetch_table($SqlQu) {
		$ar = NULL;
		if (DBMS == "mysql") {//mysql database fetch
			$con = $this -> con_str_mysql();
			if (!$con) {
				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db(DB_NAME, $con);
			$result = mysql_query($SqlQu);
			while ($row = mysql_fetch_assoc($result)) {
				$ar[] = $row;
			}
			return $ar;
			mysql_close($con);
		} elseif (DBMS == "postgres") {//postgres database fetch
			$con = $this -> con_str_pg();
			if (!$con) {
				die('Could not connect: ');
			}
			$result = pg_query($con, $SqlQu);
			while ($row = pg_fetch_assoc($result)) {
				$ar[] = $row;
			}
			return $ar;
			pg_close($con);
		}
	}

	//Database Insert
	function insert($regSql) {
		if (DBMS == "mysql") {//mysql database insert
			$con = $this -> con_str_mysql();
			if (!$con) {
				return false;
			}
			mysql_select_db(DB_NAME, $con);
			$sql = $regSql;
			if (!mysql_query($sql, $con)) {
				return false;
			} else {
				return true;
			}
			mysql_close($con);
		} elseif (DBMS == "postgres") {//postgres database insert
			$con = $this -> con_str_pg();
			if (!$con)
			  {
			  	return false;
			  }
			
			//mysql_select_db("my_db", $con);
			
			$sql=$regSql;
			
			if (!pg_query($con, $sql))
			  {
			  	return false;
			  } else{
				  
				return true;
			  }
			
			pg_close($con);
		}
	}
	function update($regSql) {
		return $this->insert($regSql);
	}
	function delete($regSql) {
		return $this->insert($regSql);
	}

}

//$db = new database();
?>