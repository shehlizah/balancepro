<?php
ob_start();
error_reporting(0);
// Set PHP configurations
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 180);

// Include TCPDF library
require_once 'C://xampp/htdocs/balancetest/includes/tcpdf/tcpdf.php';

// DB Credentials
require_once( "/C://xampp/htdocs/balancetest/includes/db/database.php" );
/*require_once( "/home/balancepro/domains/balancepro.org/public_html/wp-config.php" );
 $username=DB_USER;
            $password=DB_PASSWORD;
            $database=DB_NAME;
            $host=DB_HOST;
            $con = mysqli_connect($host, $username, $password, $database);
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);*/

$con = mysqli_connect($db_host, $db_user, $db_pass, $db_database);


// Define the log file path
$logFilePath = fopen("/home/balancepro/domains/balancepro.org/logs/pdftime.log", "w");

// Create a new TCPDF instance
$pdf = new TCPDF();

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(true);

// Extend TCPDF class to include custom footer
class MYPDF extends TCPDF {
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $pageNumber = $this->getAliasNumPage();
        $totalPages = $this->getAliasNbPages();
        // Set row count
        $rowsPerPage = $this->rowsPerPage; // To be set dynamically

        // Footer content
        $this->Cell(0, 10, 'Page '.$pageNumber.' of '.$totalPages.' | Rows per page: '.$rowsPerPage, 0, 0, 'C');
    }

    public $rowsPerPage = 13; // Number of rows per page
}
 $time_pre = microtime(true);


// Set margins
$pdf->SetMargins(7, 7, 7);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(7);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);
if (isset($_GET['fromDateVal']) && isset($_GET['toDateVal']) && isset($_GET['radioVal']) && isset($_GET['wlwVal']) && isset($_GET['reportDateVal']) && $_GET['radioVal'] == "pdf"){
        // Get values
	 $queryFrom = $_GET['fromDateVal'];
            $queryTo = $_GET['toDateVal'];
            $radioVal = $_GET['radioVal'];
            $wlwVal = $_GET['wlwVal'];
            $reportDateVal = $_GET['reportDateVal'];


      
        $fromDateVal = date("Y-m-d", strtotime($queryFrom));
        $toDateVal = date("Y-m-d", strtotime($queryTo));

        if ($reportDateVal == "1") {
            $selection = "Last Week";
        } else if ($reportDateVal == "2") {
            $selection = "Last Month";
        } else if ($reportDateVal == "3") {
            $selection = "Last Quarter";
        } else if ($reportDateVal == "4") {
            $selection = "Last Year";
        } else if ($reportDateVal == "0") {
            $selection = "";
            $toDateVal = date("Y-m-d", strtotime("+1 day", strtotime($queryTo)));
        } else if ($reportDateVal == "6") {
            $selection = "Custom Date Range";
            $toDateVal = date("Y-m-d", strtotime("+1 day", strtotime($queryTo)));
        } else {
            $selection = "This Year";
        }

        /* Execute query
        $sql = mysqli_query($con, "SELECT u.title, u.domain, r.timestamp, wu.firstname, wu.lastname, wu.streetAddress, wu.city, wu.state, wu.zip, wp.user_email, p.post_title, crtf_name AS Certificate 
                                    FROM wp_quiz_results AS r 
                                    LEFT JOIN wp_quiz_certificates AS c ON r.ID = c.quiz_result_id 
                                    JOIN wp_white_label_websites AS u ON u.white_label_website_id = '$wlwVal' 
                                    JOIN wp_users AS wp ON r.wp_user_id = wp.id 
                                    JOIN wp_posts AS p ON r.wp_post_id = p.ID 
                                    JOIN whitelabel_users AS wu ON wu.email = wp.user_email 
                                    WHERE r.wlwid = '$wlwVal' 
                                    AND wu.white_label_website_id = '$wlwVal' 
                                    AND r.timestamp >= '$fromDateVal' 
                                    AND r.timestamp <= '$toDateVal' 
                                    ORDER BY r.ID DESC");*/
        // Execute query
        $sql = mysqli_query($con, "SELECT u.title, u.domain, r.timestamp, wu.firstname, wu.lastname, wu.streetAddress, wu.city, wu.state, wu.zip, wp.user_email, p.post_title, r.score
                                    FROM wp_quiz_results AS r
                                    JOIN wp_white_label_websites AS u ON u.white_label_website_id = '$wlwVal'
                                    JOIN wp_users AS wp ON r.wp_user_id = wp.id
                                    JOIN wp_posts AS p ON r.wp_post_id = p.ID
                                    JOIN whitelabel_users AS wu ON wu.email = wp.user_email
                                    WHERE r.wlwid = '$wlwVal'
                                    AND wu.white_label_website_id = '$wlwVal'
                                    AND r.timestamp >= '$fromDateVal'
                                    AND r.timestamp <= '$toDateVal'
                                    ORDER BY r.timestamp DESC");

        $resultData = [];
        while ($row = mysqli_fetch_assoc($sql)) {
            $resultData[] = $row;
        }

        $getWhitelabel = mysqli_query($con, "SELECT title, domain FROM wp_white_label_websites WHERE white_label_website_id = '$wlwVal'");
        $whitelabelName = mysqli_fetch_assoc($getWhitelabel);

        $getFileName = str_replace(" ", "_", $whitelabelName['title']);
        $pdf_file_path = 'http://' . $whitelabelName['domain'] . '/uploads/';

	

        // Add a page
        $pdf->AddPage('L', 'A4'); // Portrait orientation with A4 size

        // Set font
        $pdf->SetFont('helvetica', 'B', 9);

        // Add header content
        $topData = 'Partner: ' . $whitelabelName["title"] .'     Report Date: '.$selection. '     From: ' . $queryFrom .          '     To: ' . $queryTo;
        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(1, 1, 1, 1);

        // set color for background
        $pdf->SetFillColor(255, 255, 255);
        $pdf->MultiCell(200, 5, $topData, 0, 'L', 1, 0, '', '', true);

        $pdf->SetCellHeightRatio(2); // Adjust the height of the text lines
        $pdf->SetCellPadding(2);


        // Set font
        $pdf->SetFont('helvetica', '', 9);

        // set cell padding
        $pdf->setCellPaddings(1, 1, 1, 1);

        // set cell margins
        $pdf->setCellMargins(0, 0, 0, 0);

        // set color for background
        $pdf->SetFillColor(16, 152, 157);
        $pdf->SetTextColor(255, 255, 255); // white text color
        $pdf->Ln(8);

        //$cellWindth = ;
        $w = array(38, 20, 25, 25, 35, 25, 20, 15, 33, 40, 11);

        $pdf->MultiCell($w[0], 5, "Credit Union", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[1], 5, "Date", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[2], 5, "First Name", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[3], 5, "Last Name", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[4], 5, "Street Address", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[5], 5, "City", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[6], 5, "State", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[7], 5, "Zip", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[8], 5, "Email", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[9], 5, "Name of Quiz", 0, 'L', 1, 0, '', '', true);
        $pdf->MultiCell($w[10], 5, "Score", 0, 'L', 1, 0, '', '', true);

        $pdf->Ln(8);

        // Initialize row counter
        $rowCount = 0;

        foreach ($resultData as $item => $value) {
            if ($rowCount >= 13) {
                $pdf->AddPage('L', 'A4'); // Add a new page
                // Re-add the header on the new page
                $pdf->SetFont('helvetica', 'B', 9);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->MultiCell(200, 5, $topData, 0, 'L', 1, 0, '', '', true);
         	 $pdf->SetFont('helvetica', '', 9);

                $pdf->SetFillColor(16, 152, 157);
                $pdf->SetTextColor(255, 255, 255); // white text color
                $pdf->Ln(8);
                $pdf->MultiCell($w[0], 5, "Credit Union", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[1], 5, "Date", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[2], 5, "First Name", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[3], 5, "Last Name", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[4], 5, "Street Address", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[5], 5, "City", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[6], 5, "State", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[7], 5, "Zip", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[8], 5, "Email", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[9], 5, "Name of Quiz", 0, 'L', 1, 0, '', '', true);
                $pdf->MultiCell($w[10], 5, "Score", 0, 'L', 1, 0, '', '', true);
                $pdf->Ln(8);
                $rowCount = 0; // Reset row counter
            }

            $title = $value['title'];
            $timestamp = $value['timestamp'];
            $firstname = $value['firstname'];
            $lastname = $value['lastname'];
            $streetAddress = $value['streetAddress'];
            $city = $value['city'];
            $state = $value['state'];
            $zip = $value['zip'];
            $user_email = $value['user_email'];
            $post_title = $value['post_title'];
            $score = $value['score'];
            $certificateUrl = '';

            if (strpos($certificate, 'cert_') === 0) {
                $certificateUrl = "http://" . $value["domain"] . "/uploads/" . $certificate;
            } else {
                $certificateUrl = '';
            }

            // set cell padding and margins
            $pdf->setCellPaddings(1, 3, 1, 1);
            $pdf->setCellMargins(0, 0, 0, 0);
            $pdf->SetCellHeightRatio(1.30); // Adjust the height of the text lines

            $height = 13;
            $fill = $item % 2 == 0 ? true : false;
            $pdf->SetFillColor($fill ? 224 : 255, $fill ? 244 : 255, $fill ? 243 : 255);
            $pdf->SetTextColor(0, 0, 0); // Black text color

            // Add row data
            $pdf->MultiCell($w[0], $height, $title, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[1], $height, $timestamp, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[2], $height, $firstname, 0, 'L', $fill, 0, '', '', true);
               // Continue from previous section
            $pdf->MultiCell($w[3], $height, $lastname, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[4], $height, $streetAddress, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[5], $height, $city, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[6], $height, $state, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[7], $height, $zip, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[8], $height, $user_email, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[9], $height, $post_title, 0, 'L', $fill, 0, '', '', true);
            $pdf->MultiCell($w[10], $height, $score, 0, 'L', $fill, 0, '', '', true);
            /* Handle certificate column
            if (strpos($certificate, 'cert_') === 0) {
                $pdf->Cell($w[6], $height, 'View', '0', 0, 'C', $fill, $certificateUrl, true); 
            } else {
                $pdf->Cell($w[6], $height, '', '0', 0, 'C', $fill, '', true);
            }*/


            // Move to the next line
            $pdf->Ln($height);

            // Increment row counter
           $rowCount++;
}

// Output HTML content
 $time_post = microtime(true);
 $exec_time = $time_post - $time_pre;
 $time_pre = microtime(true);

gc_collect_cycles();

fwrite($logFilePath,"\n\n HTML GENERATION TIME: ".$exec_time);
error_log("HTML GENERATION TIME: " . $exec_time);

// Calculate and log PDF generation time

// Output the PDF
$PDFFileName = "balanceTrackReport-" . $getFileName . ".pdf";
$pdf->Output($PDFFileName, 'D');

// Calculate and log PDF generation time
$time_post = microtime(true);
$exec_time = $time_post - $time_pre;
gc_collect_cycles();

fwrite($logFilePath,"\n\n PDF GENERATION TIME: " . $exec_time);
error_log("PDF GENERATION TIME: " . $exec_time);
        fclose($logFilePath);


/*$logfile = "/home/balancepro/domains/balancepro.org/logs/pdftime.log";
$openfile = null;
function error_log1($error){
 $logfile = "/home/balancepro/domains/balancepro.org/logs/pdftime.log";
//      if(empty($openfile)){
                $openfile = fopen($logfile,"w");
//      }
        fwrite($openfile,$error);
         fflush($openfile);
        fclose($openfile);

}*/

}

?>




