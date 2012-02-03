<?php

require_once('../lib/db.php');
require_once('../lib/trigger.php');
print "<pre>";

if(isset($_GET['hash'])) {
	$hash = $_GET['hash'];
	$rv = query_row("SELECT id FROM links WHERE hashed=?",$hash);
	if($rv) {
		$info = trigger_clickding($rv['id']);

		$url = $info['url'];
		$surl = htmlentities($url);

		print "Found your link: <a href=\"$surl\">$surl</a><br>";
		var_dump($info);

	} else {
		print "Sorry no such link :(";
	}
} else {
	print "No hash";
}