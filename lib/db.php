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