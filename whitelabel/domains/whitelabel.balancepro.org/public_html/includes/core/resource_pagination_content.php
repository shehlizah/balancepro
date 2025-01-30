<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// SET HEADER
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include('database.php');include('functions.php');
// MAKE SQL QUERY
$personData = json_decode($_REQUEST['data']);
$dtype = $personData->type;  // Resource type
$type = $personData->type;  // Resource type
if(isset($personData->pager)){
$dpager = $personData->pager;  // Page number
$pager = $personData->pager;  // Page number
}
else {
$pager=1;	
$dpager=1;
}
$lifestage = $personData->lifestage;
$search = $personData->search;

$newsearch='';
if(strpos($search ,'(' )!='' &&  strpos($search ,')') ==''){
	$searchx = explode('(',$search);
	$newsearch = trim($searchx[0]);	
}
else if(strpos($search ,'(' )!='' &&  strpos($search ,')') !=''){
$search = trim(preg_replace('/\s*\([^)]*\)/', '', $search));
}
if($newsearch!=''){
	$search=$newsearch;
}



$search = str_replace('\\', "", $search);
$search = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $search);

//29/10/21 Sorting by Swati
$sort = $personData->sort;

if(empty($search)){
    $search = "0";
}
if(empty($lifestage)){
    $lifestage = "0";
}
if(empty($personData->pager)){
    //$lifestage = "0";
} else {
    
}

if(empty($dtype)){
    $dtype = "0";
}

if($dtype == 'tags'){
    $dtype = "0";
}

if(empty($sort)){
    $sort = "0";
}
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);

$searchQuery = preg_replace('/[#\@\.\*\%\;\$\&\^\-]+/', '', $searchQuery);
$unquotedQuery = str_replace("'", "", $unquotedQuery);
$unquotedQuery = preg_replace('/[#\@\.\*\%\;\$\&\^\-]+/', '', $unquotedQuery);


$return_arr = array();
$limit = '9';
// $limitn = ' limit 9';
if ($dtype != "0") {
$resourcelists = "SELECT Distinct * FROM wp_resources as w WHERE w.status = 'publish' AND w.type = '$dtype' AND w.list_in_search = 'true'";
$resourcelist = "SELECT Distinct * FROM wp_resources as w WHERE w.status = 'publish' AND w.type = '$dtype' AND w.list_in_search = 'true'";
} else {
$resourcelists = "SELECT Distinct * FROM wp_resources as w WHERE w.status = 'publish' AND w.list_in_search = 'true'";
$resourcelist = "SELECT Distinct * FROM wp_resources as w WHERE w.status = 'publish' AND w.list_in_search = 'true'";
}

$resourcelist = $db->query($resourcelist);
$num_rows = $resourcelist->rowCount();

// $limitn = ' limit 9';
if($dpager){
    $starts = ($dpager - 1) * $limit; 

    if($num_rows <= $starts) {
        $start = 0;
    } else {
        $start = $starts;
    }
}else{
    $start = 0; 
} 

$return_arr[] = array("rows" => $num_rows,"starts" => $starts,"start" => $start,"que" => $resourcelists);

$tags = $personData->tags;
if(empty($tags) or $tags == "" ){
    $tags = '0' ;
}
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);
if(empty($tags) or $tags == "" ){
    $tags = '0' ;
}

$list = 'true';
$level = '100';
$pgorder = '1';
//echo "HI";die;
$return_arr[] = array("lifestage" => $lifestage, "dtype" => $dtype, "search" => $search);
if($lifestage == '0' AND $dtype == '0' AND $search == '0'){
    $return_arr[] = array("near" => "1");
    //echo'11';
// if search is 0 and life stage is 0 and resourcetype is 0 starts
    if($sort == "0"){
        //echo '--line 50';
        if($tags !='0'){
            $query = "SELECT  Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') limit $start, $limit";
            $result = $db->prepare($query);
         } else {
            $query = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' AND status='publish' limit $start, $limit";
            $result = $db->prepare($query);
         }
        
}else if($sort == "relevance"){
    if($tags !='0'){
        $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit";
        $result = $db->prepare($query);
    } else {
        $query = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' AND status='publish' limit $start, $limit";
        $result = $db->prepare($query);
    }
    
}else if($sort == "views"){
    if($tags !='0'){
        $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count FROM wp_resources as w, wp_resources_view_count as wc, wp_term_relationships as wtr WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY wc.view_count DESC limit $start, $limit";
        $result = $db->prepare($query);
        
    } else {
        $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count FROM wp_resources as w, wp_resources_view_count as wc WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ORDER BY wc.view_count DESC limit $start, $limit";
        $result = $db->prepare($query);
    }
}else if($sort == "date"){
    if($tags !='0'){
        $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt FROM wp_resources as w, wp_posts AS P, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' AND w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')   ORDER BY P.post_date_gmt DESC limit $start, $limit";
         $result = $db->prepare($query);
    } else {
        $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt FROM wp_resources as w, wp_posts AS P WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.page_order = '$pgorder' AND w.list_in_search = '$list' AND status='publish'  ORDER BY P.post_date_gmt DESC limit $start, $limit";
         $result = $db->prepare($query);
    }
}   
// if search is 0 and life stage is 0 and resourcetype is 0 ends
}
else if($lifestage == '0' AND $search == '0' AND $dtype != '0'){
    $return_arr[] = array("near" => "2");
    // life stage is 0 and search is 0 starts
    if($sort == '0'){
        if($tags !='0'){
        $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit";
        $result = $db->prepare($query);
    } else {
        $query = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND type = '$dtype' AND level_of_access = '$level' AND list_in_search = '$list' AND status='publish' AND page_order = '$pgorder' limit $start, $limit";
        $result = $db->prepare($query);
    }
    } else if($sort=='relevance'){
        if($tags !== '0'){
            $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND status='publish' AND wtr.term_taxonomy_id IN ('$advancedkeywords') limit $start, $limit";
            $result = $db->prepare($query);
        } else {
            $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' limit $start, $limit";
            $result = $db->prepare($query);
        }
        
    } else if($sort=='views'){
        if($tags !== '0'){
            $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count FROM wp_resources as w, wp_resources_view_count as wc, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.list_in_search = '$list'  AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY wc.view_count DESC limit $start, $limit";
            $result = $db->prepare($query);
        } else {
            $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count FROM wp_resources as w, wp_resources_view_count as wc WHERE w.status = 'publish' AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.list_in_search = '$list'  AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'  ORDER BY wc.view_count DESC limit $start, $limit";
        $result = $db->prepare($query); 
        }
       
    } else if($sort=='date'){
        if($tags !== '0'){
            
            $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt FROM wp_resources as w, wp_posts AS P, wp_term_relationships as wtr WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.list_in_search = '$list'  AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY P.post_date_gmt DESC  limit $start, $limit";
            $result = $db->prepare($query);
        } else {
            $query = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt FROM wp_resources as w, wp_posts AS P WHERE w.status = 'publish' AND  P.ID = w.wp_post_id AND w.type = '$dtype' AND w.level_of_access = '$level' AND w.page_order = '$pgorder' AND w.list_in_search = '$list' ORDER BY P.post_date_gmt DESC  limit $start, $limit";
            $result = $db->prepare($query);
        }
    } 
// if search is 0 and life stage is 0 ends
}
else if($lifestage != '0' AND $search == '0' AND $dtype != '0'){
    $return_arr[] = array("near" => "3");
//echo "1";
// if search is 0 and life stage has value and resourcetype has value starts
    if($sort == '0'){
        if($tags !== '0'){
            //if($dtype != "Worksheet"){
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$dtype' AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit"; 
            $result = $db->prepare($query);
        /*} else {

            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id AND w.type = '$dtype' AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit"; 
            $result = $db->prepare($query);
        }*/
        
        } else {
          //  if($dtype != "Worksheet"){
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w WHERE w.status = 'publish' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$dtype' limit $start, $limit"; 
            $result = $db->prepare($query);
        /*} else {
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w WHERE w.status = 'publish' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id AND w.type = '$dtype' limit $start, $limit"; 
            $result = $db->prepare($query);
        }*/
    
        }
    } else if($sort=='relevance'){
        if($tags !== '0'){
          //  if($dtype != "Worksheet"){
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$dtype' AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit"; 
            $result = $db->prepare($query);
        /*} else {
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id AND w.type = '$dtype' AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit"; 
            $result = $db->prepare($query);            
        }*/
        
        } else {
          //  if($dtype != "Worksheet"){
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$dtype' AND w.page_order = '$pgorder'  limit $start, $limit"; 
            $result = $db->prepare($query);
        /*} else {
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM life_stage_type AS l, wp_resources AS w WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id AND w.type = '$dtype' AND w.page_order = '$pgorder'  limit $start, $limit"; 
            $result = $db->prepare($query);
        }*/
    
        }
    } else if($sort=='views'){
        if($tags !== '0'){
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_term_relationships as wtr WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$dtype' AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY wc.view_count DESC limit $start, $limit"; 
            $result = $db->prepare($query);
          
        } else {
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id , wc.view_count FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$dtype' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ORDER BY wc.view_count DESC limit $start, $limit"; 
            $result = $db->prepare($query);
          
        }
    } else if($sort=='date'){

        if($tags !== '0'){
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P, wp_term_relationships as wtr WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id  AND l.postid = w.ID AND w.type = '$dtype' AND w.page_order = '$pgorder'   AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') order by P.post_date_gmt DESC limit $start, $limit"; 
            $result = $db->prepare($query);
        } else {
            $query = "SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id  AND l.postid = w.ID AND w.type = '$dtype' AND w.page_order = '$pgorder' order by P.post_date_gmt DESC limit $start, $limit"; 
            $result = $db->prepare($query);
        }
    } 
// if search is 0 and life stage has value and resourcetype has value ends
}
else if($lifestage == '0' AND $search != '0' AND $dtype != '0'){
    $return_arr[] = array("near" => "4");
// if search has value and life stage is 0 and resourcetype has value starts
    if($sort == '0'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " order by title_match desc, title_rough_match desc, relevancy desc";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='relevance'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " AND w.page_order = '$pgorder' ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='views'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
       // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
       
        $from = " FROM wp_posts AS P ";
        $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
       $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
       //$where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  ORDER BY wc.view_count DESC ";
        $query = $select . $from . $ljoin. $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='date'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " order by P.post_date_gmt DESC ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } 
// if search has value and life stage is 0 and resourcetype has value ends
}else if($lifestage != '0' AND $search != '0' AND $dtype != '0'){
    $return_arr[] = array("near" => "5");
//echo "2";
// if search has value and life stage has value and resourcetype has value starts
    if($sort == '0'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " order by title_match desc, title_rough_match desc, relevancy desc";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='relevance'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " AND w.page_order = '$pgorder' ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='views'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
       // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $from = " FROM wp_posts AS P ";
        $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
        //$where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  ORDER BY wc.view_count DESC ";
        $query = $select . $from . $ljoin . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='date'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$dtype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($dtype != '0'){
            $where .= " and P.post_type = '$dtype'";
        }
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  order by P.post_date_gmt DESC ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } 
// if search has value and life stage has value and resourcetype has value ends
} else if($lifestage == '0' AND $search != '0' AND $dtype == '0'){
    $return_arr[] = array("near" => "6");
// if search has value and life stage is 0 and resourcetype is 0 starts
    if($sort == '0'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " order by title_match desc, title_rough_match desc, relevancy desc";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
//echo $query;exit;
        $result = $db->prepare($query);
    } else if($sort=='relevance'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " order by title_match desc, title_rough_match desc, relevancy desc ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='views'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
       // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $from = " FROM wp_posts AS P ";
        $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";

       $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
       //$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  ORDER BY wc.view_count DESC ";
        $query = $select . $from .$ljoin. $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='date'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
        $order = " order by P.post_date_gmt DESC ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } 
// if search has value and life stage is 0 and resourcetype is 0 ends
} else if($lifestage != '0' AND $search == '0' AND $dtype == '0'){
    $return_arr[] = array("near" => "7");
//echo "3";
// if search is 0 and life stage has value and resourcetype is 0 starts
    if($sort == '0'){
        $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " AND w.page_order = '$pgorder' ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='relevance'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
        
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " AND w.page_order = '$pgorder' ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='views'){
        $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $from = " FROM wp_posts AS P ";
        $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
        
        $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
        //$where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
        
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  ORDER BY wc.view_count DESC ";
        $query = $select . $from . $ljoin . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='date'){
        $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  order by P.post_date_gmt DESC  ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } 
// if search is 0 and life stage has value and resourcetype is 0 ends
} else if($lifestage != '0' AND $search != '0' AND $dtype == '0'){
    $return_arr[] = array("near" => "8");
//echo "4";
// if search has value and life stage has value and resourcetype is 0 starts
    if($sort == '0'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
       
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " order by title_match desc, title_rough_match desc, relevancy desc";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='relevance'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
       
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " AND w.page_order = '$pgorder' ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='views'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $from = " FROM wp_posts AS P ";
        $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";    

       $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
       //$where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
       
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  ORDER BY wc.view_count DESC ";
        $query = $select . $from . $ljoin . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } else if($sort=='date'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
       
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        
if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = "  order by P.post_date_gmt DESC ";
        $query = $select . $from . $where . $order. ' limit ' . $start . ',' . $limit;
        $result = $db->prepare($query);
    } 
// if search has value and life stage has value and resourcetype is 0 starts
}

 $return_arr[] = array("query" => $query);

$result->execute();$countthem = $result->rowCount();

$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
$resources = '/resources/';

while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $titlen = $row['post_title'];
        $titlesmall = substr_replace($titlen, "...", 150);
    $dvaluen = $row['type'];
    $slug = $row['slug'];
        $postid = $row['wp_post_id'];
    if($dvaluen == 'article'){
    $seo_dvalue = 'articles/';
    }else if($dvaluen == 'calculator'){
     $seo_dvalue = 'calculators/';
    }else if($dvaluen == 'video'){
     $seo_dvalue = 'videos/';
    }else if($dvaluen == 'newsletter'){
     $seo_dvalue = 'newsletters/';
    }else if($dvaluen == 'podcast'){
     $seo_dvalue = 'podcasts/';
    }else if($dvaluen == 'toolkit'){
     $seo_dvalue = 'toolkits/';
    }else if($dvaluen == 'booklet'){
     $seo_dvalue = 'booklets/';
    }else if($dvaluen == 'worksheet'){
             $seo_dvalue = 'worksheets/';
            }else if($dvaluen == 'checklist'){
             $seo_dvalue = 'checklists/';
            }
	  $viewUrl = $seo_dvalue.$slug;
            $variable = resourcesUrl($viewUrl);

        $output = '';
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
            <div class="col-sm-6 col-md-4">	 <a href="'.$variable.'" target="_self">
             <!-- resource block starts -->
             <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
           <div class="text-holder"><p class="dot-holder">'.$titlesmall.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT Distinct  * FROM $table_name WHERE object_id='$postid' limit 3");
    $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
         $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT Distinct  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<span class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'"  tags="'.$tagid.'"  tagid="'.$tagid.'" sort="'.$sort.'">';
               $output .= $resultnn['name'];
               $output .='</span>';
           }
        }
    $viewUrl = $seo_dvalue.$slug;$variable = resourcesUrl($viewUrl);
        $output .='</div>';
       //$output .='<a role="button" href="'.$variable.'" target="_self" class="but btn btn-primary">VIEW</a>';
        $output .='</div>';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div>
                    <!-- resource block ends -->
                    </div></a>
                    <!-- resource resource in resources starts -->';
        //$output .= '</div>';
        $return_arr[] = array("message" => $output);
        
        //echo $output;
        //return $output;
}
// Encoding array in JSON format
echo json_encode($return_arr);
?>
