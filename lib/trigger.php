<?php


function trigger_clickding($clicked_id) {
	$links = query_row("SELECT * FROM links WHERE id=?",$clicked_id);
	if(!$links) {
		return false;
	}

	$info = array(
		'url'=>$links['url'],
		'remote_ip'=>$_SERVER['REMOTE_ADDR'],
		'click_count'=>$links['clicks'] + 1,
		'hashed'=>$links['hashed'],
		'verified'=>$links['verified'],
		'verify'=>$links['verify'],
		'message'=>$links['message'],
	);
	
	if(!$info['verified']) {
		return $info;
	}
	
	$rv = query_update("UPDATE links SET clicks=clicks+1 WHERE id=?",$links['id']);
	$user = query_row("SELECT * FROM users WHERE id=?",$links['notify_user_id']);
	
	$info['user_id'] = $user['id'];
	$info['email'] = $user['email'];

	// TODO here - send an email to $user[email]
	
	send_trigger_mail($info);
	$count = intval($info['click_count']);
	// `say "Click $count"`;
	
	return $info;
}


function send_trigger_mail($info) {
	$mailFrom = "test@lensu.com";
    $mailTo = $info['email'];
	if(!$info['email']) {
		return false;
	}
	
    $mailSubject = "Clicked notice for " .kProductName. "";
 
    $mailSignature = "\n\n-- \n";
    $mailSignature .= "Your friendly Neighborhood web application.\n";
    $mailSignature .= "For help and other information, see http://".kProductDomain."/help\n";


    $mailBody = "Somebody clicked on your link $info[url]\n";
	$mailBody .= "\nClick count is now: $info[click_count]\n";
	$mailBody .= "\nYour message to yourself:\n";
	$mailBody .= $info['message'];
	//$mailBody .= print_r($info,1);

    $mailBody .= $mailSignature;

	$mailHeader = "";
    $mailHeader  = "From: $mailFrom\r\n";
    $mailHeader .= "Reply-To: $mailFrom\r\n";
    $mailHeader .= "X-Mailer: ".kProductDomain."\r\n";    
    $mailHeader .= "X-Sender-IP: {$_SERVER['REMOTE_ADDR']}\r\n";
	#$mailHeader .= "Bcc: ".MONITORADDRESS."\r\n";	
	
    $mailParams = "-f$mailFrom";
	$args = array($mailTo,$mailSubject,$mailBody,$mailHeader,$mailParams);
	//var_dump($args);return false;
	
    $mailResult = call_user_func_array('mail',$args);
	error_log("sending mail: " . print_r($args,1));
	error_log("result from mail: " . print_r($mailResult,1));
    return $mailResult;
}

function send_verify_mail($info) {
	$mailFrom = "test@lensu.com";
    $mailTo = $info['email'];
	if(!$info['email']) {
		return false;
	}
	
    $mailSubject = "Verify your link on " .kProductName. "";
 
    $mailSignature = "\n\n-- \n";
    $mailSignature .= "Your friendly Neighborhood web application.\n";
    $mailSignature .= "For help and other information, see http://".kProductDomain."/help\n";


    $mailBody ="Hi.\n";
	$mailBody .= "Please verify your link here: ";
	$mailBody .= " http://" . kProductDomain . "/v/" . $info['hashed'] . "/" . $info['verify'];
	$mailBody .= "\n\nThanks!\n\n";

    $mailBody .= $mailSignature;

    $mailHeader  = "From: $mailFrom\r\n";
    $mailHeader .= "Reply-To: $mailFrom\r\n";
    $mailHeader .= "X-Mailer: ".kProductDomain."\r\n";    
    $mailHeader .= "X-Sender-IP: {$_SERVER['REMOTE_ADDR']}\r\n";
	#$mailHeader .= "Bcc: ".MONITORADDRESS."\r\n";	
	
    $mailParams = "-f$mailFrom";
	$args = array($mailTo,$mailSubject,$mailBody,$mailHeader,$mailParams);
	//var_dump($args);return false;
	
    $mailResult = call_user_func_array('mail',$args);
    return $mailResult;
}