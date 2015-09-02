<?php
	class db{
		var $con     = null;
		var $error   = null;
		function __construct($host,$user,$pass,$db){
			$this->con = mysql_connect($host,$user,$pass);
			mysql_select_db($db,$this->con);
		}
		function query($sql){
			$queryid = mysql_query($sql);
			$err = mysql_error($this->con);
			if($err!=""){
				$this->error=$err;
			}
			unset($err);
			return $queryid;
		}
		function esc($str){
			return mysql_real_escape_string($str,$this->con);
		}
		function affected(){
			return mysql_affected_rows($this->con);
		}
		function count($q){
			return mysql_num_rows($q);
		}
		function fetch($query){
			if($query != false)
				return mysql_fetch_array($query);
			else
				return false;
		}
		function error(){
			return $this->error;
		}
		function get_id(){
			return mysql_insert_id($this->con);
		}
	}
?>
