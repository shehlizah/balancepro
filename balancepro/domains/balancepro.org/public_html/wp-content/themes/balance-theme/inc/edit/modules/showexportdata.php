<?php
//require_once( "/home/balancepro/domains/balancepro.org/public_html/wp-config.php" );
require_once( "C://xampp/htdocs/balancetest/wp-config.php" );
//require_once( "C://xampp/htdocs/balancetest/wp-content/themes/balance-theme/partner-reports.php" );
$username=DB_USER;
$password=DB_PASSWORD;
$database=DB_NAME;
$host=DB_HOST;
$con = mysqli_connect($host,$username,$password) or die( "Unable to Connect database");
mysqli_select_db($con,$database) or die( "Unable to select database");

//session_start();
//print_r($_POST);
if(isset($_POST['fromDateVal']) && isset($_POST['toDateVal']) && isset($_POST['wlwVal']) && isset($_POST['reportDateVal'])){
	var_dump($_POST['fromDateVal']); // Output the value of fromDateVal
    var_dump($_POST['toDateVal']); // Output the value of toDateVal
    var_dump($_POST['wlwVal']); // Output the value of wlwVal
    var_dump($_POST['reportDateVal']); // Output the value of reportDateVal
//$page = $_SESSION['pagedvl'] ;
//if(isset($_POST)){
if(isset($_POST['pageNum'])){
$_SESSION['pagedvl'] =$_POST['pageNum'];
$page = $_POST['pageNum']-1;
} else {
$_SESSION['pagedvl'] ="1";
$page = "";
}
$queryVar = $_SESSION["reportFilter"];
$rec_per_page = 30;
//$page =$page-1;
if($page == ""){
	$start = "0";
 } else {
	$start = $page*$rec_per_page;
}
$queryFrom = $_POST['fromDateVal'];
$queryTo = $_POST['toDateVal'];
$radioVal = $_POST['radioVal'];
$wlwVal = $_POST['wlwVal'];
$reportDateVal = $_POST['reportDateVal'];
$fromDateVal = date("Y-m-d", strtotime($queryFrom));
$toDateVal = date("Y-m-d", strtotime($queryTo));

if($reportDateVal=="1"){
        $selection = "Last Week";
} else if($reportDateVal=="2"){
        $selection = "Last Month";
} else if($reportDateVal=="3"){
        $selection = "Last Quarter";
} else if($reportDateVal=="4"){
        $selection = "Last Year";
} else if($reportDateVal=="0"){
        $selection = "";
        $toDateVal = date("Y-m-d", strtotime("+1 day", strtotime($queryTo)));
} else if($reportDateVal=="6"){
        $selection = "Custom Date Range";
        $toDateVal = date("Y-m-d", strtotime("+1 day", strtotime($queryTo)));
} else {
        $selection = "This Year";
}

/*if($reportDateVal=="1"){
$sqlq = "SELECT u.title,r.timestamp,wp.display_name,wp.user_email,p.post_title,crtf_name AS Certificate FROM wp_quiz_results AS r LEFT JOIN wp_quiz_certificates AS c ON r.ID=c.quiz_result_id  JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email WHERE r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp BETWEEN NOW() - INTERVAL 8 DAY AND NOW() ORDER BY r.ID DESC ";
        $sql = mysqli_query($con,"SELECT u.title,r.timestamp,wp.display_name,wp.user_email,p.post_title,crtf_name AS Certificate FROM wp_quiz_results AS r LEFT JOIN wp_quiz_certificates AS c ON r.ID=c.quiz_result_id  JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email WHERE r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp BETWEEN NOW() - INTERVAL 8 DAY AND NOW() ORDER BY r.ID DESC ");
	$sqlReportsTotal = mysqli_query($con,"SELECT COUNT(r.ID) AS cnt FROM wp_quiz_results AS r LEFT JOIN wp_quiz_certificates AS c ON r.ID=c.quiz_result_id  JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email WHERE r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp BETWEEN NOW() - INTERVAL 8 DAY AND NOW() ORDER BY r.ID DESC ");
} else {*/
$sqlq = "SELECT u.title,r.timestamp,wp.display_name,wp.user_email,p.post_title,crtf_name AS Certificate FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email where r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp>='$fromDateVal' AND r.timestamp<='$toDateVal' ORDER BY r.ID DESC  LIMIT $start, $rec_per_page";
	$sql = mysqli_query($con,"SELECT u.title,r.timestamp,wp.display_name,wu.lastname, wu.streetAddress, wu.city, wu.state, wu.zip,wp.user_email,p.post_title,r.score FROM wp_quiz_results r JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email where r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp>='$fromDateVal' AND r.timestamp<='$toDateVal' ORDER BY r.timestamp DESC  LIMIT  $start, $rec_per_page ");
	$sqlReportsTotal = mysqli_query($con,"SELECT r.ID FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email where r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp>='$fromDateVal' AND r.timestamp<='$toDateVal' ORDER BY r.ID DESC");
//}
error_log("SQL QUERY: ".$sqlq);
//$reportData = mysqli_fetch_array($sql);


$getWhitelabel = mysqli_query($con,"SELECT title,domain,wp_post_id FROM wp_white_label_websites WHERE white_label_website_id='$wlwVal'");
$whitelabelName = mysqli_fetch_assoc($getWhitelabel);
$partnerName = $whitelabelName['title'];
$whitelabelPostID = $whitelabelName['wp_post_id'];
$getFileName = str_replace(" ", "_",$whitelabelName['title']);
$pdf_file_path = 'http://'.$whitelabelName['domain'].'/uploads/';

$rowcount=mysqli_num_rows($sql);
error_log("ROWCOUNT: ".$rowcount);
$total_records = mysqli_num_rows($sqlReportsTotal);
error_log("RECORDS: ".$total_records);
$total_pages = ceil($total_records / $rec_per_page);
error_log("PAGES: ".$total_pages);

if($rowcount == 0){
echo "No data available";die;

} else {
 $output .= '';
 $output .=  '<thead>
			<tr>
	              <th class="manage-column column-_title">Credit Union</th>
	              <th class="manage-column column-_title">Date</th>
	              <th class="manage-column column-_title">First Name</th>
        	      <th class="manage-column column-_title">Last Name</th>
	              <th class="manage-column column-_title">Street Address</th>
                      <th class="manage-column column-_title">City</th>
                      <th class="manage-column column-_title">State</th>
                      <th class="manage-column column-_title">Zip</th>
                      <th class="manage-column column-_title">Email</th>
	              <th class="manage-column column-_title">Name of Quiz</th>
	              <th class="manage-column column-_title">Score</th>
			</tr>
		  </thead>
		  <tbody>';
		  $i=$start+1;
//print_r($reportData);
		 while($result_value = mysqli_fetch_array($sql)){
//		  foreach ($reportData as $result_key => $result_value) {
			$file = $pdf_file_path.$result_value['crtf_name'];
//			$postid = $result_value['wp_post_id'];
//			$wp_user_id = $result_value['wp_user_id'];
			$getName = explode(" ",$result_value['display_name']);
			$first_name = $getName[0];
                        $last_name = $getName[1];
                        $post_title = $result_value['post_title'];
			$wpUserEmail = $result_value['user_email'];
                        $streetAddress = $result_value['streetAddress'];
                        $city = $result_value['city'];
                        $state = $result_value['state'];
                        $zip = $result_value['zip'];
                        $score = $result_value['score'];

/*
			$meta_value = $wpdb->get_var( "SELECT meta_value FROM `wp_postmeta` WHERE `post_id`='$postid' AND `meta_key`='_page_edit_data'" );
			$datam = unserialize($meta_value);

			if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){
 				$user_email = $wpdb->get_var( "SELECT user_email FROM `wp_users` WHERE `ID`='$wp_user_id'" );
 			} else {
		                $user_email = $wpdb->get_var( "SELECT user_email FROM `wp_users` WHERE `ID`='$wp_user_id'" );
			}
			$first_name = $wpdb->get_var( "SELECT firstname FROM `whitelabel_users` WHERE `email`='$user_email' AND `white_label_website_id`='$white_label_website_id' " );

			$last_name = $wpdb->get_var( "SELECT lastname FROM `whitelabel_users` WHERE `email`='$user_email' AND `white_label_website_id`='$white_label_website_id'  " );
			$display_name = $first_name." ".$last_name;
			$score = round($result_value->score,2);*/
			//if($score >= $passing_percent) {
/*			if($result_value['crtf_name']!='') {
				$view = '<a href="'.$file.'" title="View Certificate" target="_blank">View</a>';
				$result='Pass';
			}
			else {
				$view = '';
				$result = 'Fail';
			}*/
			 $output .=  '<tr id= "reportTable">';
                           $output .=  '<td data-column="Date" style="text-align: left;">'.$partnerName.'</td>';
                           $output .=  '<td data-column="Date" style="text-align: left;">'.$result_value['timestamp'].'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$first_name.'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$last_name.'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$streetAddress.'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$city.'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$state.'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$zip.'</td>';
                           $output .=  '<td data-column="User" style="text-align: left;">'.$wpUserEmail.'</td>';
                           $output .= '<td data-column="Quiz" style="text-align: left;">'.$post_title.'</td>';
                           $output .= '<td data-column="Certificate" style="text-align: left;">'.$score.'</td>
			</tr>';
			$i++;
		 }

		 $pagination = '';
//		$output = '';

      $queryVariable = "%".str_replace(" ","+",$_SESSION["searchValue"])."%";
		 for($i=1; $i <= $total_pages; $i++){
			 if($i==$_SESSION['pagedvl'])
			 $pagination .= " $i &nbsp;";
			 else $pagination .= "<a onclick='getPageNum($i)' style='cursor:pointer'>$i</a> &nbsp;";
		 }
		 
		if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){
			$output .= '<tr><td colspan=9 style="text-align:center;font-weight:bold">'.$pagination.'</td> </tr>';
		}

		echo '</tbody>';
//error_log("SHOWDATA: ".$output);
print_r($output);die;
}
/*$getResult = explode("</tbody>" ,$output);
error_log($getResult);
print_r($getResult[0]);*/
}
//}
?>


