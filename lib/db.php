<?php

function db_handle() {
	static $handle = null;
	if($handle) {
		return $handle;
	}
	$path = realpath(__DIR__ . "/../db/development.sqlite3");
	$handle = new PDO('sqlite:' . $path);
	return $handle;
}


function query_update($sql) {
	$args = func_get_args();
	$sql = array_shift($args);
	$dbh = db_handle();
	$stmt = $dbh->prepare($sql);
	//$stmt->debugDumpParams();
	return $stmt->execute($args);
}

function query_row($sql) {
	$args = func_get_args();
	$sql = array_shift($args);
	$dbh = db_handle();
	$stmt = $dbh->prepare($sql);
	$rv = array();
	if ($stmt->execute($args)) {
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$rv = $row;
		}
	}
	return $rv;
}

function munge($string,$len=5) {
	$alphabet = 'bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXZ0123456789';
	$rv = '';
	$seed = sha1('test' . $string);
	for($i=0;$i<strlen($seed);$i+=2) {
		$slab = hexdec($seed[$i] . $seed[$i+1]);
		$val = $alphabet[$slab % strlen($alphabet)];
		$rv .= $val;
		if(strlen($rv) >= $len) {
			return $rv;
		}
	}
}