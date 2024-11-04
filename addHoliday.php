<?php 
ini_set('display_error',1);
error_reporting(E_ALL);

    global $wpdb;
	require_once dirname( __DIR__ ) . '/wp-load.php';

    
    function get_holiday($wpdb){
        $chat_holiday = $wpdb->get_results("select chat_id FROM wp_chat_setting where chat_value LIKE '%toDatef%'" );

        if(!$chat_holiday[0])
		    return -1;

		return $chat_holiday;
	}

	$chat_id = get_holiday($wpdb);
    // logging();
    print($chat_id);
	var_dump($chat_id);
    $query = "UPDATE `wp_chat_setting` SET `chat_value` ='$insert_data' where chat_id=$chat_id";
    var_dump($query);
    print_r($query);

	$insert_data = serialize($_POST);

	//holidaysData
    if($chat_id!=-1){
    	// $sql = $wpdb->prepare("UPDATE `wp_chat_setting` SET `chat_value` ='$insert_data' where chat_id=1");
		$sql = $wpdb->prepare("UPDATE `wp_chat_setting` SET `chat_value` ='$insert_data' where chat_id='$chat_id'");
        print($sql);
        
		$wpdb->query($sql);
	}
    else {
    	$sql = $wpdb->prepare("INSERT INTO `wp_chat_setting` (`chat_value`) values ('$insert_data')");
		$wpdb->query($sql);

    }
//print_r($insert_data);
header("location: chat.php");exit;
?>
