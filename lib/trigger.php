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
	);
	$rv = query_update("UPDATE links SET clicks=clicks+1 WHERE id=?",$links['id']);
	$user = query_row("SELECT * FROM users WHERE id=?",$links['notify_user_id']);
	
	$info['user_id'] = $user['id'];
	$info['email'] = $user['email'];

	// TODO here - send an email to $user[email]
	$count = intval($info['click_count']);
	`say "Click $count"`;
	
	return $info;
}