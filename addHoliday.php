<?php 
ini_set('display_error',1);
error_reporting(E_ALL);

global $wpdb;
	require_once dirname( __DIR__ ) . '/wp-load.php';

	$wp_chat_data = $wpdb->get_results( "SELECT * FROM wp_chat_setting" );
	$insert_data = serialize($_POST);

	//holidaysData
	if(!empty($wp_chat_data[0])){
		// $sql = $wpdb->prepare("UPDATE `wp_chat_setting` SET `chat_value` ='$insert_data' where chat_id=1");
		$sql = $wpdb->prepare("UPDATE `wp_chat_setting` SET `chat_value` ='$insert_data' where chat_id=1");
		$wpdb->query($sql);
	}
    else {
    	$sql = $wpdb->prepare("INSERT INTO `wp_chat_setting` (`chat_value`) values ('$insert_data')");
		$wpdb->query($sql);

    }
//print_r($insert_data);
header("location: chat.php");exit;
?>
