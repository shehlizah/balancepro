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
$dvalue = $personData->type;
$page = $personData->page;
$limit = 9;
if($page){
    $start = ($page - 1) * $limit; 
}else{
    $start = 0; 
}  
$return_arr = array();
$list = 'true';
$level = '100';
$pgorder = '1';

$tags = $personData->tags;
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);

if(empty($tags) or $tags == '' ){
    $tags = '0' ; 
}

if(empty($dvalue)){
    $dvalue = '0' ;  
}

if($dvalue == '0'){
// if resourcetype is 0 starts
  if($tags == '0') {
  $query = "SELECT DISTINCT * FROM wp_resources as w WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' limit $start, $limit";
  $queryt = "SELECT DISTINCT * FROM wp_resourcesas w WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder'";
  } else {
  $query = "SELECT DISTINCT * FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND  w.wp_post_id = wtr.object_id AND `wtr.term_taxonomy_id` IN ('$advancedkeywords') limit $start, $limit";
  $queryt = "SELECT DISTINCT * FROM wp_resourcesas w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND  w.wp_post_id = wtr.object_id AND `wtr.term_taxonomy_id` IN ('$advancedkeywords')";
  }
// if resourcetype is 0 ends
}else{
// if resourcetype has value starts
  if($tags == '0') {
  $query = "SELECT DISTINCT * FROM wp_resources as w WHERE w.status = 'publish' AND w.type = '$dvalue' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' limit $start, $limit";
  $queryt = "SELECT DISTINCT * FROM wp_resources as w WHERE w.status = 'publish' AND w.type = '$dvalue' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder'";
  } else {
  $query = "SELECT DISTINCT * FROM wp_resources as w, wp_term_relationships, wp_term_relationships as wtr as wtr WHERE w.status = 'publish' AND w.type = '$dvalue' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND  w.wp_post_id = wtr.object_id AND `wtr.term_taxonomy_id` IN ('$advancedkeywords') limit $start, $limit";
  $queryt = "SELECT DISTINCT * FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$dvalue' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND  w.wp_post_id = wtr.object_id AND `wtr.term_taxonomy_id` IN ('$advancedkeywords')";
  }
// if resourcetype has value ends
}

$result = $db->prepare($query);
$result->execute();

$result1 = $db->prepare($queryt);
$result1->execute();
//row count
$rcount = $result1->rowCount();
$limit = '9';
$totalpages = ceil( $rcount / $limit );

$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
$resources = 'https://'.$_SERVER['HTTP_HOST'].'/resources/';

while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $title = $row['post_title'];
    $dvaluen = $row['type'];
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
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
		    <div class="col-sm-6 col-md-4">
		     <!-- resource block starts -->
		     <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
		   <div class="text-holder"><p class="dot-holder">'.$titlen.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT DISTINCT  * FROM $table_name WHERE object_id='$postid' limit 3");
   	$resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
      	 $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT DISTINCT  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tags="'.$tagid.'" tagid="'.$tagid.'">';
               $output .= $resultnn['name'];
               $output .='</a>';
           }
        }
   	//$output .= 'test';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div></div></div>
                    <!-- resource block ends -->
                    </div>
                    <!-- resource resource in resources starts -->';
        //$output .= '</div>';
        $return_arr[] = array("message" => $output);
}
// Encoding array in JSON format
echo json_encode($return_arr);
?>
