<?php 
ini_set('display_error',1);
error_reporting(E_ALL);

	global $wpdb;
	require_once dirname( __DIR__ ) . '/wp-load.php';

	
	function get_chat($wpdb){
		$chat = $wpdb->get_results("select chat_id FROM wp_chat_setting where chat_value LIKE '%disable_chat%'" );
        if(!$chat)
		    $chat=-1;

		return $chat;
	}

	$chat_id = get_chat($wpdb);

	// error_reporting("TimeSettings Chat Id = ".$chat_id);

	$insert_data = serialize($_POST);

    if($chat_id!=-1){
    	$sql = $wpdb->prepare("UPDATE `wp_chat_setting` SET `chat_value` ='$insert_data' where chat_id= $chat_id");
		$wpdb->query($sql);
    } else {
    	$sql = $wpdb->prepare("INSERT INTO `wp_chat_setting` (`chat_value`) values ('$insert_data')");
		$wpdb->query($sql);

    }
//print_r($insert_data);
header("location: chat.php");exit;
?>
