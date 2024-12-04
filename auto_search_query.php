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
$dvalue = $personData->keyword;
$rtypes = $personData->rtypes;
$lifestage = $personData->lifestage;
$searchQuery = str_replace('\\', "", $dvalue);
$unquotedQuery = str_replace('"', "", $dvalue);
$list = 'true';
$level = '100';
$pgorder = '1';
$limit = ' limit 9';
//$return_arr['message'] = $dvalue;
//$query = "SELECT * FROM wp_resources WHERE post_title LIKE '%$dvalue%' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $limit";
$output = '';
$srchquery = $db->prepare("SELECT * FROM wp_terms WHERE name LIKE '%$searchQuery%' ORDER BY term_id ASC ");

$srchquery1 = $db->prepare("SELECT DISTINCT name,term_id FROM wp_terms as wt, wp_term_relationships AS wtr WHERE wt.term_id = wtr.term_taxonomy_id AND wt.name LIKE '%$searchQuery%' ORDER BY wt.term_id ASC");
$srchquery->execute();
$rr = $srchquery1->execute();

$return_arr['query'] =  $srchquery;
$return_arr['Nextquery'] =  $srchquery1;
$countSrch = $srchquery->rowCount();
$countSrch1 = $srchquery1->rowCount();
$return_arr['row1'] =  $countSrch;
$return_arr['row2'] =  $countSrch1;
if($countSrch1 > 0){
  while($row = $srchquery1->fetch(PDO::FETCH_ASSOC)){
        $tagname = $row['name'];
        $tagid = $row['term_id'];
        $output .= '<!-- tag search keyword starts -->
                      <div style="padding: 5px 15px 15px;" class="tag-click" tagname="'.$tagname.'" dvalue="'.$tagid.'" tagid="'.$tagid.'" pager="1">'.$tagname.'
                      </div>
                    <!-- tag search keyword ends -->';
     }
   }else{
    $output .= '<!-- tag search keyword starts -->
                      <div style="padding: 5px 15px 15px;">No Result
                      </div>
                    <!-- tag search keyword ends -->';
   }

$return_arr['message'] =  $output;
// Encoding array in JSON format
echo json_encode($return_arr);
?>