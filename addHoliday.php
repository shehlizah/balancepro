<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);

global $wpdb;
require_once dirname(__DIR__) . '/wp-load.php';

$insert_data = serialize($_POST);

function get_holiday($wpdb) {
    $chat_holiday = $wpdb->get_results("SELECT chat_id FROM wp_chat_setting WHERE chat_value LIKE '%toDatef%'");
    print_r($chat_holiday);
    if (empty($chat_holiday)) {
        return -1;
    }

    return $chat_holiday[0]->chat_id;
}

$chat_id = get_holiday($wpdb);
echo $chat_id;

if ($chat_id != -1) {
    $sql = $wpdb->prepare("UPDATE wp_chat_setting SET chat_value = %s WHERE chat_id = %d", $insert_data, $chat_id);
} else {
    $sql = $wpdb->prepare("INSERT INTO wp_chat_setting (chat_value) VALUES (%s)", $insert_data);
}

$wpdb->query($sql);

header("Location: chat.php");
exit;
?>
