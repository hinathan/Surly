<?php

define('kProductName','SomeProduct');
define('kProductDomain',$_SERVER['SERVER_NAME']);


require_once('../lib/db.php');
require_once('../lib/trigger.php');

if(isset($_GET['verify'])) {
	$hash = $_GET['hash'];
	$verify = $_GET['verify'];

	$info = query_row("SELECT * FROM links WHERE hashed=? AND verify=?",$hash,$verify);
	if($info && $info['verified']) {
		print "Already verified.\n";
	} else {
		query_update("UPDATE links SET verified=1 WHERE hashed=? AND verify=?",$hash,$verify);
		$info = query_row("SELECT * FROM links WHERE hashed=? AND verify=?",$hash,$verify);
		if($info) {
			print "Thanks, verified your link and email.\n";
			print " <a href=\"http://".kProductDomain."/$info[hashed]\">http://".kProductDomain."/$info[hashed]<a>\n\n";
		} else {
			print "Something went wrong with verifying your link.\n";
		}
	}
} else if(isset($_GET['hash'])) {
	$hash = $_GET['hash'];
	$rv = query_row("SELECT id FROM links WHERE hashed=?",$hash);
	if($rv) {
		$info = trigger_clickding($rv['id']);

		$url = $info['url'];
		$surl = htmlentities($url);

		if($info && !$info['verified']) {
			print "Sorry, that link isn't active yet.";
			exit;
		}

		header("Location: $url");
		print "Found your link: <a href=\"$surl\">$surl</a><br>";
	} else {
		print "Sorry no such link :(";
	}
} else if(isset($_POST['url'])) {
	$verify = munge(rand(),10);
	
	$hashed = munge(rand());
	$message = $_POST['message'];
	$url = $_POST['url'];
	$email = $_POST['email'];
	$user = query_row("SELECT * FROM users WHERE email=?",$email);
	if(!$user) {
		query_update("INSERT INTO users (email) VALUES (?)",$email);
		$user = query_row("SELECT * FROM users WHERE email=?",$email);
	}
	
	query_update("INSERT INTO links (hashed,verify,url,notify_user_id,active,clicks,verified,message) VALUES (?,?,?,?,1,0,0,?)",$hashed,$verify,$url,$user['id'],$message);
	$info = query_row("SELECT * FROM links WHERE hashed=?",$hashed);
	$info['email'] = $email;
	send_verify_mail($info);
	//print "<pre>";var_dump($info);
	print "Check your mail for a verification";
	
	
} else {
	print "Let's create a ClickDing<sup>TM</sup> link!";
	print "<style> form * { font-size:20px;display:block; }</style>";
	print '<form method=POST>';
	print '<input name=url size=40 value="" autofocus="yes" placeholder="URL to click to" >';
	print '<input name=email size=40 value="" placeholder="Email to notify" >';
print '<input name=message size=40 value="" placeholder="Message to yourself (included in email)" >';
	print '<input type=submit>';

}