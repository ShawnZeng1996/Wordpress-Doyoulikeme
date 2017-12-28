<?php
header("Content-Type: application/json; charset=utf-8");
require('../../../wp-load.php');
$action = $_GET['action'];
$id = 1;
$ip = get_client_ip();

if($action=='add'){
	likes($id,$ip);
}else if($action=='get'){
	echo jsons($id);
} else {
	exit();
}

define('VOTES_NUM', $wpdb->prefix . 'votes_num');
define('VOTES_IP', $wpdb->prefix . 'votes_ip');
global $wpdb;

function likes($id,$ip){
	global $wpdb;
	$likes = $wpdb->get_var($wpdb->prepare("SELECT likes FROM " . VOTES_NUM . " WHERE ID = %d;",$id)); 
	$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM " . VOTES_IP . " WHERE IP = '%s';",$ip));
	if(0==$count){
		$likes = $likes+1;
		$wpdb->update( VOTES_NUM, 
			array( 'likes' => $likes ), 
			array( 'id' => '1' ), 
			array( '%d' ), 
			array( '%d' ) 
		); 
        $data['ip'] = $ip;
		$wpdb->insert(VOTES_IP, $data);
		echo jsons($id);
	}else{
		$msg = 'repeat';
		$arr['success'] = 0;
		$arr['msg'] = $msg;
		echo json_encode($arr);
	}
}

function jsons($id){
	global $wpdb;
	$likes = $wpdb->get_var($wpdb->prepare("SELECT `likes` FROM " . VOTES_NUM . " WHERE `id` = %d ", $id));
	$arr['success'] = 1;
	$arr['like'] = $likes;	
	return json_encode($arr);
}

//获取用户真实IP
function get_client_ip() {
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
		$ip = getenv("HTTP_CLIENT_IP");
	else
		if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else
			if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
				$ip = getenv("REMOTE_ADDR");
			else
				if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
					$ip = $_SERVER['REMOTE_ADDR'];
				else
					$ip = "unknown";
	return ($ip);
}

?>
