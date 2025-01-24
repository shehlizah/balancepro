<?php
/*
 *  WP Edit module: White Label Websites Admins
 */
function white_label_websites_admins_module_form( $key, $visible_on = 'all', $module_title = '', $custom_settings = array() ) {
	global $data, $wpdb, $post;
// error_log('white_label_websites_admins_module_form called');
  //  error_log('Key: ' . print($key));
    //error_log('Module Title: ' . print($module_title));
    //error_log('Custom Settings: ' . print($custom_settings));
	// read the existing data from the whitelabel_admins table.
	$prefix = $wpdb->prefix;
	$post_table_id = $prefix . 'post_id';
	$white_label_websites_table_name = $prefix . 'white_label_websites';
	$white_label_website_id = $wpdb->get_var( "SELECT white_label_website_id FROM $white_label_websites_table_name WHERE $post_table_id = '$post->ID';" );
	$results = $wpdb->get_results( "SELECT * FROM whitelabel_admins WHERE  white_label_website_id = '$white_label_website_id'", OBJECT );
	foreach ($results as $result_key => $result_value) {
		$data['wlw_admins_module'][$key]['admin'][$result_key] = array(
			'email' => $result_value->email
		);
	}

    //error_log('White Label Website ID: ' . print_r($white_label_website_id, true));

	$output = simple_edit_module( $key, 'wlw_admins', array(
			array(
				'type' => 'repeater',
				'name' => 'admin',
				'label' => 'Admin',
				'default_value' => array(),
				'fields' => array(
					array(
						'type' => 'email',
						'name' => 'email',
						'label' => 'Email',
						'default_value' => ''
					),
				)
			)
		), $data, $module_title, $visible_on );
		
	//error_log(print_r($output,true));		

		if (!session_id()) {
			session_start();
		}
		
		$page = $_SESSION['pagedvl'] ;
		
		$passing_percent = $data['passing_percent'];
		$rec_per_page = 30;
		$page =$page-1;
		$start = $page*$rec_per_page;
		
		$domain = $wpdb->get_var( "SELECT domain FROM $white_label_websites_table_name WHERE $post_table_id = '$post->ID';" );
		$pdf_file_path = 'http://'.$domain.'/uploads/';

	$queryVariable = "%".str_replace(" ","+",$_SESSION["searchValue"])."%";

	if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){		
		$sqlPastQuizesTotal = "SELECT COUNT(r.ID) AS cnt FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
	} else {
$sqlPastQuizesTotal = "SELECT COUNT(r.ID) AS cnt 
                       FROM wp_quiz_results r 
                       LEFT JOIN wp_quiz_certificates c ON r.ID = c.quiz_result_id 
                       WHERE r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW() 
                         AND r.wlwid = '$white_label_website_id'
                         AND r.wp_user_id IN (
                             SELECT ID 
                             FROM wp_users 
                             WHERE user_email LIKE '$queryVariable'
                         )";

//              $user_id = $wpdb->get_var( "SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'" );
//		$sqlPastQuizesTotal = "SELECT COUNT(r.ID) AS cnt FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wp_user_id IN (SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable') AND r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
	}

		$total_records = $wpdb->get_var($sqlPastQuizesTotal);
		$total_pages = ceil($total_records / $rec_per_page);
		
	if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){	
		$sqlPastQuizes = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW() ORDER BY ID DESC LIMIT $start, $rec_per_page";
		} else {

  //              $sqlPastQuizes = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_websit$
                        $user_id = $wpdb->get_var( "SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'" );
	$userQuery = "SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'";
$sqlPastQuizes = "SELECT r.*, c.crtf_name 
                  FROM wp_quiz_results r 
                  LEFT JOIN wp_quiz_certificates c ON r.ID = c.quiz_result_id 
                  WHERE r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW() 
                    AND r.wlwid = '$white_label_website_id'
                    AND r.wp_user_id IN (
                        SELECT ID 
                        FROM wp_users 
                        WHERE user_email LIKE '$queryVariable'
                    )";


//                      $sqlPastQuizes = "SELECT r.*,crtf_name,w.ID FROM wp_quiz_results as r,wp_quiz_certificates as c, wp_users as w WHERE r.ID=c.quiz_result_id, w.ID=r.w$
//	$sqlPastQuizes = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wp_user_id IN (SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable') AND r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
}

		$resultsPastQuizes = $wpdb->get_results( $sqlPastQuizes, OBJECT );
		
		 //if(!empty($resultsPastQuizes)){
//		error_log("SQL Query: " . $sqlPastQuizes);
if (empty($resultsPastQuizes)) {
    error_log("No results found.");
} else {
    error_log("Results found: " . print_r($resultsPastQuizes,true));
}

		//$pagedvl = $wp_session['pagedvl'];
		$wpPostId = $post->ID;
		$output .='<div class="module-wrapper wlw_admins-module-module-wrapper-0" data-visible-on="tab-8">';
		$output .='<div style="text-align:center;font-size:15px;margin-bottom: 10px;font-weight: bold;" >[Quiz results are available for 90 days]</div>';
        $output .= '<input type="text" name="query" id="query" placeholder="Search Email">';
	$output .= '<button type="button" id="searchButton" style="margin-left: 10px;">Search</button>&nbsp&nbsp';
$output .= '<span id="result-count" style="font-weight: bold;">' . $total_records . ' matching ' . ($total_records === 1 ? 'record' : 'records') . ' found!</span>';
$output .= '<script type="text/javascript">
jQuery(document).ready(function () {
    // Restore query value from session storage if it exists
    var querySessionText = sessionStorage.getItem("queryValue");
    if (querySessionText) {
        jQuery("#query").val(querySessionText);
    }

    // Attach click event to the button
    jQuery("#searchButton").on("click", function () {
        var queryText = jQuery("#query").val();
        sessionStorage.setItem("queryValue", queryText); // Save query value to session storage
    console.log("Button clicked. Query text:", queryText); // Debug line
        window.location.href = "/wp-admin/post.php?post=' . $post->ID . '&action=edit&query=" + encodeURIComponent(queryText);
    });
});
</script>';
//$output .=' window.location.href = "/wp-admin/post.php?post='.$post->ID.'&action=edit&query="+queryText;
//});
//</script>';
		$output .='<table class="wp-list-table widefat fixed striped posts" style="margin-bottom: 30px;">
		  <thead>
			<tr>
			  <th class="manage-column column-_title" style="width:35px;">Sr.No</th>      
			  <th class="manage-column column-_title">Name</th>
			  <th class="manage-column column-_title">User</th>
			  <th class="manage-column column-_title">Quiz</th>
			  <th class="manage-column column-_title">Date/Time</th>
			  <th class="manage-column column-_title" style="width: 42px;">Per(%)</th>     
			  <th class="manage-column column-_title" style="width: 40px;">Result</th>     
			  <th class="manage-column column-_title" style="width: 60px;">Correct Answers</th>
			  <th class="manage-column column-_title" style="width: 65px;">Certificate</th>
			</tr>
		  </thead>
		  <tbody>';
		  $i=$start+1;
		  foreach ($resultsPastQuizes as $result_key => $result_value) {
			$file = $pdf_file_path.$result_value->crtf_name;
			$postid = $result_value->wp_post_id;
			$wp_user_id = $result_value->wp_user_id;
			$meta_value = $wpdb->get_var( "SELECT meta_value FROM `wp_postmeta` WHERE `post_id`='$postid' AND `meta_key`='_page_edit_data'" );
			$datam = unserialize($meta_value);

//			if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){
 				$user_email = $wpdb->get_var( "SELECT user_email FROM `wp_users` WHERE `ID`='$wp_user_id'" );
 //			} else {
//                $user_email = $wpdb->get_var( "SELECT user_email FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'" );
//			}
			$first_name = $wpdb->get_var( "SELECT firstname FROM `whitelabel_users` WHERE `email`='$user_email' AND `white_label_website_id`='$white_label_website_id' " );

			$last_name = $wpdb->get_var( "SELECT lastname FROM `whitelabel_users` WHERE `email`='$user_email' AND `white_label_website_id`='$white_label_website_id'  " );
			$display_name = $first_name." ".$last_name;
//			error_log("Display Name :" .$display_name);
//error_log("Email :" .$user_email);
			$score = round($result_value->score,2);
			//if($score >= $passing_percent) {
			if($result_value->crtf_name!='') {
				$view = '<a href="'.$file.'" title="View Certificate" target="_blank">View</a>';
				$result='Pass';
			}
			else {
				$view = '';
				$result = 'Fail';
			}		
			$output .='<tr>
			  <td data-column="sr.no" >'.$i.'</td> ';     
			  $output .='<td data-column="User" style="text-align: left;">'.$display_name.'</td>';
			  $output .='<td data-column="User" style="text-align: left;">'.$user_email.'</td>';
			  $output .='<td data-column="Quiz" style="text-align: left;">'.$datam['post_title'].'</td>';
			  $output .='<td data-column="Date" style="text-align: left;">'.date('d M Y, h:i A',strtotime($result_value->timestamp)).'</td>';
			  $output .='<td data-column="Percentage" style="text-align: center;">'.round($result_value->score,2).'%</td>';
			  $output .='<td data-column="Percentage" style="text-align: center;">'.$result.'</td>';
			  $output .='<td data-column="Correct Answers" style="text-align: center;">'.$result_value->num_correct.'</td>';    
			  $output .='<td data-column="Certificate" style="text-align: center;">'.$view.'</td>
			</tr>';
			$i++;
		 }
		 $pagination = '';
		 
		
		 for($i=1; $i <= $total_pages; $i++){
			 if($i==$_SESSION['pagedvl'])
			 $pagination .= " $i &nbsp;";
			 else $pagination .= "<a href='post.php?post=$post->ID&action=edit&paged=$i&tab=8'>$i</a> &nbsp;";
		 }
		 
		if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){ 
			$output .='<tr><td colspan=9 style="text-align:center;font-weight:bold">'.$pagination.'</td> </tr>';
		}
		$output .='</tbody></table>';
		$output .='</div>';


/********************************************START OF REPORTS MODULE*****************************************************************/
                $page = $_SESSION['pagedvl'] ;
                $passing_percent = $data['passing_percent'];
                $rec_per_page = 30;
                $page =$page-1;
                $start = $page*$rec_per_page;

                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                $base_url = $protocol . $_SERVER['HTTP_HOST'] . '/';


                $domain = $wpdb->get_var( "SELECT domain FROM $white_label_websites_table_name WHERE $post_table_id = '$post->ID';" );
                $PartnerTitle = $wpdb->get_var( "SELECT title FROM $white_label_websites_table_name WHERE $post_table_id = '$post->ID';" );
                $pdf_file_path = 'http://'.$domain.'/uploads/';

        $queryVar = $_SESSION["reportFilter"];
        $explodeSearch = explode("-", $queryVar);
        $queryReport = $explodeSearch[0];
        $queryFrom = $explodeSearch[1];
        $queryTo = $explodeSearch[2];
        $queryFormat = $explodeSearch[3];
        $queryDateFrom = date("Y-m-d", strtotime($queryFrom));
        $queryDateTo = date("Y-m-d", strtotime($queryTo));

/*      if ($queryVar == "" || $queryVar == null || $queryVar == "%%"){
                $sqlReportsTotal = "SELECT COUNT(r.ID) AS cnt FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
        } else*/
        if ($queryVar != "" || $queryVar != null || $queryVar != "%%") {
//              $user_id = $wpdb->get_var( "SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'" );
                $sqlReportsTotal = "SELECT COUNT(r.ID) AS cnt FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp>='$queryDateFrom' AND r.timestamp<='$queryDateTo'";
        }
//error_log("REPORTS SQL: ".$sqlReportsTotal);
                $total_records = $wpdb->get_var($sqlReportsTotal);
                $total_pages = ceil($total_records / $rec_per_page);
//error_log("REPORTSTOTAL: ".$total_records." REPORTS PAGES: ".$total_pages);

/*      if ($queryVar == "" || $queryVar == null || $queryVar == "%%"){
                $sqlReports = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 90 DAY AND NOW() ORDER BY ID DESC LIMIT $start, $rec_per_page";
        } else*/
        if ($queryVar != "" || $queryVar != null || $queryVar != "%%") {

                $user_id = $wpdb->get_var( "SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'" );
                $userQuery = "SELECT ID FROM `wp_users` WHERE `user_email` LIKE '$queryVariable'";

                if($queryReport=="0"){
                        $queryDateTo = date("Y-m-d", strtotime("+1 day", strtotime($queryTo)));
                        $sqlReports = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 8 DAY AND NOW() ORDER BY r.ID DESC LIMIT $start, $rec_per_page";
                }
/*               if($queryReport=="1"){
                        $sqlReports = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp BETWEEN NOW() - INTERVAL 8 DAY AND NOW() ORDER BY r.ID DESC LIMIT $start, $rec_per_page";
                } else {*/
                        $sqlReports = "SELECT r.*,crtf_name FROM wp_quiz_results r left join wp_quiz_certificates c on r.ID=c.quiz_result_id where r.wlwid='$white_label_website_id' AND r.timestamp>='$queryDateFrom' AND r.timestamp<='$queryDateTo' ORDER BY r.timestamp DESC LIMIT $start, $rec_per_page";
//              }
        }

                $resultsReports = $wpdb->get_results( $sqlReports, OBJECT );

                 //if(!empty($resultsPastQuizes)){

$reportFilter = $_SESSION["reportFilter"];
                //$pagedvl = $wp_session['pagedvl'];
                $wpPostId = $post->ID;
                $getFileName = str_replace(" ", "_",$PartnerTitle);
                $output .='<div class="module-wrapper wlw_admins-module-module-wrapper-0" data-visible-on="tab-11">';
                $output .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"   />';
                $output .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>';
$output .= '<input type="hidden" name="wlwid" id="wlwid" value="'.$white_label_website_id.'">';
  $output .= '    <b>Report Date:</b> <select name="reportDate" id="reportDate">';
  $output .= '    <option value="0"></option>';
if(isset($queryReport) && $queryReport=="1"){
  $output .= '    <option value="1" selected>Last Week</option>';
} else {
  $output .= '    <option value="1">Last Week</option>';
}
if(isset($queryReport) && $queryReport=="2"){
  $output .= '    <option value="2" selected>Last Month</option>';
} else {
  $output .= '    <option value="2">Last Month</option>';
}
if(isset($queryReport) && $queryReport=="3"){
  $output .= '    <option value="3" selected>Last Quarter</option>';
} else {
  $output .= '    <option value="3">Last Quarter</option>';
}
if(isset($queryReport) && $queryReport=="4"){
  $output .= '    <option value="4" selected>Last Year</option>';
} else {
  $output .= '    <option value="4">Last Year</option>';
}
if(isset($queryReport) && $queryReport=="5"){
  $output .= '    <option value="5" selected>This Year</option>';
} else {
  $output .= '    <option value="5">This Year</option>';
}
if(isset($queryReport) && $queryReport=="6"){
  $output .= '    <option value="6" selected>Custom Date Range</option>';
} else {
  $output .= '    <option value="6">Custom Date Range</option>';
}
        $output .= '    </select>';
if(isset($queryFrom)){
        $output .= '<b>&nbsp;&nbsp;&nbsp;From:</b> <input type="text" name="fromDate" id="fromDate" class="datetimepicker" value='.$queryFrom.' disabled>';
} else {
        $output .= '<b>&nbsp;&nbsp;&nbsp;From:</b> <input type="text" name="fromDate" id="fromDate" class="datetimepicker" autocomplete="off" disabled>';
}
if(isset($queryTo)){
        $output .= '<b>&nbsp;&nbsp;&nbsp;To:</b> <input type="text" name="toDate" id="toDate" class="datetimepicker" value='.$queryTo.' disabled>';
} else {
        $output .= '<b>&nbsp;&nbsp;&nbsp;To:</b> <input type="text" name="toDate" id="toDate" class="datetimepicker" autocomplete="off" disabled>';
}
        $output .= '<b>&nbsp;&nbsp;&nbsp;Report Format:</b>';

        $output .= '<input type="radio" id="html" class="fav_language" name="fav_language" value="html"><label for="html">HTML</label>';
        $output .= '&nbsp;&nbsp;&nbsp;<input type="radio" id="pdf" class="fav_language" name="fav_language" value="pdf"><label for="pdf">PDF</label>';
        $output .= '&nbsp;&nbsp;&nbsp;<input type="radio" id="excel" class="fav_language" name="fav_language" value="excel"><label for="excel">Excel</label>';
        $output .= '&nbsp;&nbsp;&nbsp;<input type="radio" id="csv" class="fav_language" name="fav_language" value="csv"><label for="csv">CSV</label>';
        $output .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="generateReport" style="display: inline-block;padding: 5px 10px;font-size: 16px;font-weight: 600;text-align: center;text-decoration: none;color: #333;background-color: #e0e0e0;border: 1px solid #000;border-radius: 4px;transition: background-color 0.2s, color 0.2s, border-color 0.2s;">Generate Report</span>';
        $output .='<script type="text/javascript">
        jQuery("#generateReport").click(function(){
        if( jQuery("input[type=radio]").is(":checked") && jQuery("#fromDate").val()!="" && jQuery("#toDate").val()!="" ){ // check if the radio is checked
//            var radioVal = jQuery(this).val(); // retrieve the value
            var radioVal = jQuery(".fav_language:checked").val();
            var fromDateVal = jQuery("#fromDate").val(); // retrieve the value
            var toDateVal = jQuery("#toDate").val(); // retrieve the value
            var reportDateVal = jQuery("#reportDate").val(); // retrieve the value
            var wlwVal = jQuery("#wlwid").val();
var reportFilterValue = reportDateVal+"-"+fromDateVal+"-"+toDateVal+"-"+radioVal;
sessionStorage.setItem("reportFilter",reportFilterValue);


                if(radioVal=="pdf"){
		';
//		error_log("PDF CALLED");
		$output .= '

                    var rootFolder = "/wp-content/themes/balance-theme/inc/edit/modules/exportpdf.php?fromDateVal="+fromDateVal+"&toDateVal="+toDateVal+"&radioVal="+radioVal+"&wlwVal="+wlwVal+"&reportDateVal="+reportDateVal;
                } if(radioVal!="pdf") {';
//                error_log("PDF NOT CALLED");
		$output .='
                    var rootFolder = "/wp-content/themes/balance-theme/inc/edit/modules/export.php?fromDateVal="+fromDateVal+"&toDateVal="+toDateVal+"&radioVal="+radioVal+"&wlwVal="+wlwVal+"&reportDateVal="+reportDateVal;
                }
jQuery(".fav_language:checked").removeAttr("checked");


/*
                var sendData = "fromDateVal="+fromDateVal+"&toDateVal="+toDateVal+"&radioVal="+radioVal;
                jQuery.ajax({
                        url: rootFolder,
//                      data: sendData,
/*                        data: {
                            fromDateVal: fromDateVal,
                            toDateVal: toDateVal,
                            radioVal: radioVal,
                            wlwVal: wlwVal
                          },*
                        type: "GET",
                        success: function(data) {
if(radioVal == "html"){*/
//            window.url(rootFolder);
';
 ob_start();
  print_r(debug_backtrace()); //
  $trace = ob_get_contents();
  ob_end_clean();
  //error_log("edit Module.php: \n".$trace);
$output .='
window.location.replace(rootFolder);
/*}
 if(radioVal == "pdf"){
            window.open(rootFolder);
}
 if(radioVal == "excel"){
            window.open(rootFolder);
}
 if(radioVal == "csv"){
            window.open(rootFolder);
}

                        },
                        cache: false,
                });*/
';
$output .='}
    });

jQuery("#toDate").change(function () {
            var fromDateVal = jQuery("#fromDate").val(); // retrieve the value
            var toDateVal = jQuery("#toDate").val(); // retrieve the value
            var reportDateVal = jQuery("#reportDate").val(); // retrieve the value
            var wlwVal = jQuery("#wlwid").val();

jQuery.ajax({
                        url: "/wp-content/themes/balance-theme/inc/edit/modules/showexportdata.php",
                        type: "POST",
                        data: {
                            fromDateVal: fromDateVal,
                            toDateVal: toDateVal,
                            wlwVal: wlwVal,
                            reportDateVal: reportDateVal
                          },
                        success: function(result) {
if(reportDateVal=="0"){
jQuery( "span#generateReport" ).css({"pointer-events": "none","cursor":"default"});
        jQuery("#reportTable").html("Please Select Report Date");

}else if(result.trim()=="No data available"){
        jQuery("#reportTable").html("No data available");
jQuery( "span#generateReport" ).css({"pointer-events": "none","cursor":"default"});
}
if(result.trim()!="No data available") {

jQuery( "span#generateReport" ).css({"cursor": "pointer","pointer-events": "auto"});
        jQuery("#reportTable").html(result);
}
                                jQuery("#reportTable").innerHTML=result;

                        }

                });
});

        jQuery("#reportDate").change(function () {
                var conceptName = jQuery("#reportDate").find(":selected").val();
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
                var now = new Date();

           if(conceptName=="0"){
                jQuery("#fromDate").val("");
                jQuery("#toDate").val("");
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
           } else if(conceptName=="1"){
var dateLimit = new Date(new Date().setDate(now.getDate() - 7));
var nextWeekStart = now.getDate() - now.getDay() - 7;
var nextWeekFrom = new Date(now.setDate(nextWeekStart));
var nextWeekEnd = now.getDate() - now.getDay() + 6;
var nextWeekTo = new Date(now.setDate(nextWeekEnd));

                jQuery("#fromDate").datepicker("setDate",nextWeekFrom);
                jQuery("#toDate").datepicker("setDate",nextWeekTo);
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
                } else if(conceptName=="2"){
var prevMonthLastDate = new Date(now.getFullYear(), now.getMonth(), 0);
var prevMonthFirstDate = new Date(now.getFullYear() - (now.getMonth() > 0 ? 0 : 1), (now.getMonth() - 1 + 12) % 12, 1);

                jQuery("#fromDate").datepicker("setDate",prevMonthFirstDate);
                jQuery("#toDate").datepicker("setDate",prevMonthLastDate);
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
                } else if(conceptName=="3"){
const quarter = Math.floor((now.getMonth() / 3));
var startFullQuarter = new Date(now.getFullYear(), quarter * 3 - 3, 1);
var prevMonthLastDate = new Date(startFullQuarter.getFullYear(), startFullQuarter.getMonth() + 3, 0);

                jQuery("#fromDate").datepicker("setDate",startFullQuarter);
                jQuery("#toDate").datepicker("setDate",prevMonthLastDate);
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
                } else if(conceptName=="4") {
var prevMonthLastDate = new Date((now.getFullYear() - 1), 11, 31);
var prevMonthFirstDate = new Date((now.getFullYear() - 1), 0, 1);

                jQuery("#fromDate").datepicker("setDate",prevMonthFirstDate);
                jQuery("#toDate").datepicker("setDate",prevMonthLastDate);
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
                } else if(conceptName=="5") {
var prevMonthFirstDate = new Date(now.getFullYear(), 0, 1);

                jQuery("#fromDate").datepicker("setDate",prevMonthFirstDate);
                jQuery("#toDate").datepicker("setDate",now);
                jQuery("#fromDate").attr("disabled","disabled");
                jQuery("#toDate").attr("disabled","disabled");
                } else if(conceptName=="6") {
                jQuery("#fromDate").datepicker("setDate","");
                jQuery("#toDate").datepicker("setDate","");
                jQuery("#fromDate").removeAttr("disabled");
                jQuery("#toDate").removeAttr("disabled");
                }




            var fromDateVal = jQuery("#fromDate").val(); // retrieve the value
            var toDateVal = jQuery("#toDate").val(); // retrieve the value
            var reportDateVal = jQuery("#reportDate").val(); // retrieve the value
            var wlwVal = jQuery("#wlwid").val();
jQuery.ajax({
                        url: "/wp-content/themes/balance-theme/inc/edit/modules/showexportdata.php",
                        type: "POST",
                        data: {
                            fromDateVal: fromDateVal,
                            toDateVal: toDateVal,
                            wlwVal: wlwVal,
                            reportDateVal: reportDateVal
                          },
                        success: function(result) {
if(reportDateVal=="0"){
jQuery( "span#generateReport" ).css({"pointer-events": "none","cursor":"default"});
        jQuery("#reportTable").html("Please Select Report Date");

}else if(result.trim()=="No data available"){
        jQuery("#reportTable").html("No data available");
jQuery( "span#generateReport" ).css({"pointer-events": "none","cursor":"default"});
}
if(result.trim()!="No data available") {
jQuery( "span#generateReport" ).css({"cursor": "pointer","pointer-events": "auto"});
        jQuery("#reportTable").html(result);
}
//                                jQuery("#reportTable").innerHTML=result;

                        }

                });
        });
        jQuery(".datetimepicker").each(function () {
            jQuery(this).datepicker();
        });

function getPageNum(e){
        var fromDateVal = jQuery("#fromDate").val(); // retrieve the value
            var toDateVal = jQuery("#toDate").val(); // retrieve the value
            var reportDateVal = jQuery("#reportDate").val(); // retrieve the value
            var wlwVal = jQuery("#wlwid").val();
            var pageNum = e;
        jQuery.ajax({
                        url: "/wp-content/themes/balance-theme/inc/edit/modules/showexportdata.php",
                        type: "POST",
                        data: {
                            fromDateVal: fromDateVal,
                            toDateVal: toDateVal,
                            wlwVal: wlwVal,
                            reportDateVal: reportDateVal,
                            pageNum : pageNum
                          },
                        success: function(result) {
                        jQuery("#reportTable").innerHTML=result;
                        jQuery("#reportTable").html(result);

                        }

                });
}

jQuery(document).ready( function () {
var querySessionText = sessionStorage.getItem("reportFilter");
});
';
$output .= '</script>';
	

                $output .='<table class="wp-list-table widefat striped posts" id="reportTable" style="margin-bottom: 30px;">
                  <thead>
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
                $exportData = "";

                  foreach ($resultsReports as $result_key => $result_value) {
                        $file = $pdf_file_path.$result_value->crtf_name;
//                        $file = $result_value->crtf_name;
                        $postid = $result_value->wp_post_id;
                        $wp_user_id = $result_value->wp_user_id;
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
                        $score = round($result_value->score,2);
                        //if($score >= $passing_percent) {
                        if($result_value->crtf_name!='') {
//                              $view = $file;
                                $view = '<a href="'.$file.'" title="View Certificate" target="_blank">View</a>';
                                $result='Pass';
                        }
                        else {
                                $view = '';
                                $result = 'Fail';
                        }
$exportData .= $i.",".$display_name.",".$user_email.",".$datam['post_title'].",".date('d M Y, h:i A',strtotime($result_value->timestamp)).",".round($result_value->score,2).",".$result.",".$result_value->num_correct.",".$view;
                        $output .='<tr>
                          <td data-column="sr.no" >'.$PartnerTitle.'</td> ';
                          $output .='<td data-column="Date" style="text-align: left;">'.$result_value->timestamp.'</td>';
                          $output .='<td data-column="User" style="text-align: left;">'.$first_name.'</td>';
                          $output .='<td data-column="User" style="text-align: left;">'.$last_name.'</td>';
                          $output .='<td data-column="User" style="text-align: left;">'.$user_email.'</td>';
                          $output .='<td data-column="Quiz" style="text-align: left;">'.$datam['post_title'].'</td>';
                          $output .='<td data-column="Certificate" style="text-align: left;">'.$view.'</td>';
                        $output .='</tr>';
                        $i++;
                 }
                 $pagination = '';


                 for($i=1; $i <= $total_pages; $i++){
                         if($i==$_SESSION['pagedvl'])
                         $pagination .= " $i &nbsp;";
                         else $pagination .= "<a href='post.php?post=$post->ID&action=edit&paged=$i&tab=11&reportFilter=1-$queryFrom-$queryTo'>$i</a> &nbsp;";
                 }

                if ($queryVariable == "" || $queryVariable == null || $queryVariable == "%%"){
                        $output .='<tr><td colspan=9 style="text-align:center;font-weight:bold">'.$pagination.'</td> </tr>';
                }

                $output .='</tbody></table>';
                $output .='</div>';

	return $output;

}
