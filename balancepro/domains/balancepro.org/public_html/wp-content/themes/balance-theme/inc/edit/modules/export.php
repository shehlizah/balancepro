
<?php
/*require_once( "/home/balancepro/domains/balancepro.org/public_html/wp-config.php" );
$username=DB_USER;
$password=DB_PASSWORD;
$database=DB_NAME;
$host=DB_HOST;
error_log("username:".$username."password: ".$password."database: ".$database."host: ".$host);
$con = mysqli_connect($host,$username,$password,$database) or die( "Unable to Connect database");
//mysqli_select_db($con,$database) or die( "Unable to select database");

require_once("/home/balancepro/domains/balancepro.org/public_html/includes/SimpleXLSXGen.php");
use Shuchkin\SimpleXLSXGen;*/
// Table Name that you want
// to export in csv
//require_once("/home/balancepro/domains/balancepro.org/public_html/includes/SimpleXLSXGen.php");
require_once("C://xampp/htdocs/balancetest/includes/SimpleXLSXGen.php");
use Shuchkin\SimpleXLSXGen;
if(isset($_GET['fromDateVal']) && isset($_GET['toDateVal']) && isset($_GET['radioVal']) && isset($_GET['wlwVal']) && isset($_GET['reportDateVal'])){
if($_GET['radioVal']=="html" || $_GET['radioVal']=="excel" || $_GET['radioVal']=="csv"){

$_SESSION['views'] = $_SESSION['views'] + 1;
$getViews = $_SESSION['views'];
//error_log("VIEWS: ".$getViews);

if($getViews==1){

//require_once( "/home/balancepro/domains/balancepro.org/public_html/wp-config.php" );
//require_once( "/home/balancepro/domains/balancepro.org/public_html/wp-config.php" );
require_once( "C://xampp/htdocs/balancetest/wp-config.php" );
$username=DB_USER;
$password=DB_PASSWORD;
$database=DB_NAME;
$host=DB_HOST;

/*require_once( "/home/balancepro/domains/balancepro.org/public_html/includes/db/database.php" );
$username=$db_user;
$password=$db_pass;
$database=$db_database;
$host=$db_host;*/
error_log("username:".$username."password: ".$password."database: ".$database."host: ".$host);
$con = mysqli_connect($host,$username,$password,$database);
// or print_r( "Unable to Connect database");
//mysqli_select_db($con,$database) or die( "Unable to select database");

//require_once("/home/balancepro/domains/balancepro.org/public_html/includes/SimpleXLSXGen.php");
//use Shuchkin\SimpleXLSXGen;


$queryFrom = $_GET['fromDateVal'];
//}
//if(isset($_GET['toDateVal'])){
$queryTo = $_GET['toDateVal'];
//}
//if(isset($_GET['radioVal'])){
$radioVal = $_GET['radioVal'];
//}
//if(isset($_GET['wlwVal'])){
$wlwVal = $_GET['wlwVal'];
//} 
//if(isset($_GET['reportDateVal'])){
$reportDateVal = $_GET['reportDateVal'];
//}

//if($queryFrom!="" && $queryTo!="" && $radioVal!="" && $wlwVal!="" && $reportDateVal!=""){
$fromDateVal = date("Y-m-d", strtotime($queryFrom));
$toDateVal = date("Y-m-d", strtotime($queryTo));
error_log("PDF NOT CALLED");

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


// because debug_backtrace() give an object, put the string result to buffer and write to log file
ob_start();
print_r(debug_backtrace()); // 
$trace = ob_get_contents();
ob_end_clean();
error_log("Export.php".$trace);

$sqlQuery = "SELECT u.title,r.timestamp,wp.display_name,wp.user_email,p.post_title,crtf_name FROM wp_quiz_results AS r LEFT JOIN wp_quiz_certificates AS c ON r.ID=c.quiz_result_id  JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID WHERE r.wlwid='$wlwVal' AND r.timestamp>='$fromDateVal' AND r.timestamp<='$toDateVal' ";
$sqlq = "SELECT u.title,r.timestamp,wu.first_name,wu.last_name,wp.user_email,p.post_title,crtf_name AS Certificate FROM wp_quiz_results AS r LEFT JOIN wp_quiz_certificates AS c ON r.ID=c.quiz_result_id  JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email WHERE r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp>='$fromDateVal' AND r.timestamp<='$toDateVal' ORDER BY r.ID DESC ";

/*if($reportDateVal=="1"){
	$sql = mysqli_query($con,"SELECT u.title,r.timestamp,wu.firstname,wu.lastname,wp.user_email,p.post_title,crtf_name AS Certificate FROM wp_quiz_results AS r LEFT JOIN wp_quiz_certificates AS c ON r.ID=c.quiz_result_id  JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email WHERE r.wlwid='$wlwVal'' AND wu.white_label_website_id='$wlwVal' AND r.timestamp BETWEEN NOW() - INTERVAL 8 DAY AND NOW() ORDER BY r.ID DESC ");
} else {*/
	$sql = mysqli_query($con,"SELECT u.title,r.timestamp,wu.firstname,wu.lastname, wu.streetAddress, wu.city, wu.state, wu.zip,wp.user_email,p.post_title,r.score FROM wp_quiz_results AS r JOIN wp_white_label_websites AS u ON u.white_label_website_id='$wlwVal' JOIN wp_users AS wp ON r.wp_user_id=wp.id JOIN wp_posts AS p ON r.wp_post_id=p.ID JOIN whitelabel_users AS wu ON wu.email=wp.user_email  WHERE r.wlwid='$wlwVal' AND wu.white_label_website_id='$wlwVal' AND r.timestamp>='$fromDateVal' AND r.timestamp<='$toDateVal' ORDER BY r.timestamp DESC ");
//}
$rowcount=mysqli_num_rows($sql);
$getWhitelabel = mysqli_query($con,"SELECT title,domain FROM wp_white_label_websites WHERE white_label_website_id='$wlwVal'");
$whitelabelName = mysqli_fetch_assoc($getWhitelabel);

$getFileName = str_replace(" ", "_",$whitelabelName['title']);
$pdf_file_path = 'http://'.$whitelabelName['domain'].'/uploads/';
/***************************EXPORT TO CSV*****************************************/
if($radioVal == "csv"){
$FileName = "balanceTrackReport-".$getFileName.".csv";
header('Content-Description: File Transfer');
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="'.$FileName.'";');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
ob_clean();
$file = fopen('php://output', 'w');
// Save headings alon
        $HeadingsTitle =array();
	 $HeadingsTitle[] = "Partner : ".$whitelabelName['title']."     Report Date: ".$selection."     From: ".$queryFrom."     To: ".$queryTo;
	fputcsv($file,$HeadingsTitle);

        
	$HeadingsArray=array();
                        $HeadingsArray[]= "Credit Union";
                        $HeadingsArray[]= "Date";
                        $HeadingsArray[]= "First Name";
                        $HeadingsArray[]= "Last Name";
                        $HeadingsArray[]= "Street Address";
                        $HeadingsArray[]= "City";
                        $HeadingsArray[]= "State";
                        $HeadingsArray[]= "Zip";
                        $HeadingsArray[]= "Email";
                        $HeadingsArray[]= "Name of Quiz";
                        $HeadingsArray[]= "Score";

	fputcsv($file,$HeadingsArray); 
	
// Save all records without headings

	while($row = mysqli_fetch_assoc($sql)){
	$valuesArray=array();
		foreach($row as $name => $value){
	                $valuesArray[]=str_replace("cert_",$pdf_file_path."cert_",$value);
		}
	fputcsv($file,$valuesArray); 
	}
	fclose($file);
die();
}
/*****************************END OF EXPORT TO CSV*********************************/
/*****************************EXPORT TO EXCEL*********************************/
if($radioVal == "excel"){
$FileName = "balanceTrackReport-".$getFileName.".xlsx";
ob_start();
// Save headings alon
	$rows = [];
	$title = [];
	$title[] = "Report Date: ".$whitelabelName['title'];
	$title[] = "Report Date: ".$selection;
	$title[] = "From: ".$queryFrom;
	$title[] = "To: ".$queryTo;

	$heading = [];
	$heading[] = "Credit Union";
	$heading[] = "Date";
	$heading[] = "First Name";
	$heading[] = "Last Name";
	$heading[]= "Street Address";
        $heading[]= "City";
        $heading[]= "State";
        $heading[]= "Zip";
        $heading[] = "Email";
	$heading[] = "Name of Quiz";
	$heading[] = "Score";
	$rows[] = $title;
	$rows[] = $heading;


// Save all records without headings

	while($row = mysqli_fetch_assoc($sql)){
		$rows[] = str_replace("cert_",$pdf_file_path."cert_",$row);
	}
error_log(print_r($rows));
SimpleXLSXGen::fromArray($rows)->downloadAs($FileName);
}
/*****************************END OF EXPORT TO EXCEL*********************************/
/*****************************EXPORT TO HTML*********************************/
if($radioVal == "html"){
$FileName = "balanceTrackReport-".$getFileName.".html";
$file = fopen($FileName, 'w');

// Save headings alon
	$valuesArray ="<!DOCTYPE html>
    <html>
    <head>
    <style> table, tr,td{border:none;}
	    div{text-align: left;
            font-weight: bold; 
            padding: 10px; 
            background-color: #f9f9f9; 
            margin-bottom: 20px;}
	body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
	    font-size: 13px;
        }
        th, td {
            border: none;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #19a69b;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #fff;
        }
        tr:nth-child(odd) {
            background-color: #e0f4f3; /* Lightened #19a69b for readability */
        }
	</style>
	</head>
	<body>
    <div>";
	$valuesArray .= "Partner : ".$whitelabelName['title']." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Report Date: ".$selection." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From: ".$queryFrom." &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;To: ".$queryTo;
	$valuesArray .= "</div>
    <table>
	<tr>
	<th>Credit Union</th>
	<th></th>
	<th style='width: 100px;'>Date</th>
	<th></th>
	<th>First Name</th>
	<th></th>
        <th>Last Name</th>
        <th></th>
	<th>Street Address</th>
        <th></th>
        <th>City</th>
        <th></th>
        <th>State</th>
        <th></th>
        <th style='width: 100px;'>Zip</th>
        <th></th>
        <th style='width: 200px;'>Email</th>
	<th></th>
	<th style='width: 200px;'>Name of Quiz</th>
	<th></th>
	<th style='width: 70px;'>Score</th>
	<th></th>
";
		$valuesArray .= "</tr>";
// Save all records without headings
	while($row = mysqli_fetch_assoc($sql)){
	$valuesArray .= "<tr>";
		foreach($row as $name => $value){
//		$valuesArray .= "<td>".$value."<td>";
			if (strpos($value, 'cert_') === 0) {
				$file = $pdf_file_path.$value;
		                $valuesArray .= '<td><a href="'.$file.'" title="View Certificate" target="_blank">View</a><td>';
			} else {
		                $valuesArray .= "<td>".$value."<td>";
			}
		}
		$valuesArray .= "</tr>"; 
	}
	$valuesArray .= "</table>
    </body>
    </html>";

//fwrite($file, $valuesArray);
//fclose($file);
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Length: ". filesize("$FileName").";");
header("Content-Disposition: attachment; filename=$FileName");
header("Content-Type: application/octet-stream; "); 
header("Content-Transfer-Encoding: binary");
print_r($valuesArray);
}
/*****************************END OF EXPORT TO HTML*********************************/
$_SESSION['views'] = 1;
}

}
}
?>
