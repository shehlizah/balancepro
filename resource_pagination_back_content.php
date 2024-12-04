<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// SET HEADER
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include('../db/database.php');
// MAKE SQL QUERY
$personData = json_decode($_REQUEST['data']);
$dtype = $personData->type;
$dpager = $personData->pager;
$return_arr = array();
$list = 'true';
$level = '100';
$pgorder = '1';
$limit = '9';

if($dpager){
    $start = ($dpager - 1) * $limit; 
}else{
    $start = 0; 
} 

if($dtype == '0'){
// if resourcetype is 0 starts
	$query = "SELECT * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $start, $limit";
	$result = $db->prepare($query);
	$result->execute();

    $queryt = "SELECT * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	$result1 = $db->prepare($queryt);
	$result1->execute();
// if resourcetype is 0 ends
}else{
// if resourcetype has value starts
	$query = "SELECT * FROM wp_resources WHERE status = 'publish' AND type = '$dtype' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $start, $limit";
	$result = $db->prepare($query);
	$result->execute();

    $queryt = "SELECT * FROM wp_resources WHERE status = 'publish' AND type = '$dtype' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	$result1 = $db->prepare($queryt);
	$result1->execute();
// if resourcetype has value ends
}
//row count
$rcount = $result1->rowCount();
$totalpages = ceil( $rcount / $limit );

$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
$resources = 'https://'.$_SERVER['HTTP_HOST'].'/resources/';

while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$titlen = $row['post_title'];
	$dvaluen = $row['type'];
	$slug = $row['slug'];
        $postid = $row['wp_post_id'];
    if($dvaluen == 'article'){
    $seo_dvalue = $resources.'articles/';
    }else if($dvaluen == 'calculator'){
     $seo_dvalue = $resources.'calculators/';
    }else if($dvaluen == 'video'){
     $seo_dvalue = $resources.'videos/';
    }else if($dvaluen == 'newsletter'){
     $seo_dvalue = $resources.'newsletters/';
    }else if($dvaluen == 'podcast'){
     $seo_dvalue = $resources.'podcasts/';
    }else if($dvaluen == 'toolkit'){
     $seo_dvalue = $resources.'toolkits/';
    }else if($dvaluen == 'booklet'){
     $seo_dvalue = $resources.'booklets/';
    }else if($dvaluen == 'worksheet'){
     $seo_dvalue = $resources.'worksheets/';
    }else if($dvaluen == 'checklist'){
     $seo_dvalue = $resources.'checklists/';
    } 
            
        $output = '';
        $output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
		    <div class="col-sm-6 col-md-4">
		     <!-- resource block starts -->
		     <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
		   <div class="text-holder"><p class="dot-holder">'.$titlen.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT * FROM $table_name WHERE object_id='$postid' limit 3");
   	$resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
      	 $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tagid="'.$tagid.'" tags="'.$tagid.'">';
               $output .= $resultnn['name'];
               $output .='</a>';
           }
        }
   	//$output .= 'test';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div>
                    <!-- resource block ends -->
                    </div>
                    <!-- resource resource in resources starts -->';
        $output .= '</div>';
        $return_arr[] = array("message" => $output);
        
        //echo $output;
        //return $output;
}

// Encoding array in JSON format
echo json_encode($return_arr);
?>
