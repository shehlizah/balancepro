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
$dvaluen = $personData->dvalue;
$page = $personData->page;
$dvalue = $personData->tagid;
$tagids = $personData->tagid;
$keywords= explode(',', $dvalue);
$advancedkeywords = implode("', '", $keywords);
$limit = 9;
if($page){
    $start = ($page - 1) * $limit; 
    $newpage = $page;
}else{
    $start = 0; 
    $newpage = 1;
}

if(empty($page) or $page == ''){
    $page = 1; 
}

if(empty($dvalue)){
    $dvalue = '0' ;  
}
$search = $personData->search;
$searchvalue = $personData->search;
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);
$lifestage = $personData->lifestage;

if(!empty($personData->rtypes)){
    $resourcetypes = $personData->rtypes;
    $rtypes = $personData->rtypes;
    $rtype = $personData->rtypes;
    $rt = $personData->rtypes;
} 

if(!empty($personData->rtype)){
    $rt = $personData->rtype;
    $resourcetypes = $personData->rtype;
    $rtypes = $personData->rtype;
    $rtype = $personData->rtype; 
} 

if(isset($personData->sort)){
    $sort = $personData->sort; 
} else {
   $sort = $personData->sortid;  
}

if(empty($search)){
    $search = '0' ;  
}
if(empty($resourcetypes) or  $resourcetypes == '' ){
    $resourcetypes = '0' ;  
}
if(empty($dvalue)){
    $dvalue = '0' ;  
}
$list = 'true';
$level = '100';
$pgorder = '1';
$resources = 'resources/';

if($search == '0'){
// if search is 0 starts
         if($sort == '0'){
            $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
            
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue != '0'){
                 $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " AND w.page_order = '$pgorder'";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
   
          //  echo $query; 
            $checkn = $db->prepare($query);
         } else if ($sort == 'relevance'){
            $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
            
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue != '0'){
                $from .= " , wp_term_relationships as wtr";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " AND w.page_order = '$pgorder'";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
   
            //echo $query; 
            $checkn = $db->prepare($query);
         }else if ($sort == 'views'){
            $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            $from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id AND w.page_order = '1' ";
            
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue != '0'){
                $from .= " , wp_term_relationships as wtr";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = "  ORDER BY wc.view_count DESC ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
   
            //echo $query; 
            $checkn = $db->prepare($query);
         }else if ($sort == 'date'){
            $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt ";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '1'";
            
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue != '0'){
                $from .= " , wp_term_relationships as wtr";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " ORDER BY P.post_date_gmt DESC ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
            //echo $query; 
            $checkn = $db->prepare($query);
         }
// if search is 0 ends
     } else {
// if search has value starts
        
        if($sort == '0'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '1'";
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
            $order = " order by title_match desc, title_rough_match desc, relevancy desc ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
            $checkn = $db->prepare($query);
        } else if ($sort == 'relevance'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '1'";
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
            $order = " order by title_match desc, title_rough_match desc, relevancy desc ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
            $checkn = $db->prepare($query);
        }else if ($sort == 'views'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            $from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc ";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id AND w.page_order = '1' ";
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
            $order = "  ORDER BY wc.view_count DESC  ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
            $checkn = $db->prepare($query);
        }else if ($sort == 'date'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt ";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '1'";
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($dvalue !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " ORDER BY P.post_date_gmt DESC  ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order;
            $checkn = $db->prepare($query);
        }
// if search has value ends
     }
	$return_arr["query"] = $query; 
//echo $query;	 
$checkn->execute();$countthem = $checkn->rowCount();
//now count row 
$checkcountn = $checkn->rowCount();

$totalpages = ceil( $checkcountn / $limit );


$ttvalue = 'tags';
$output = '';
//$output = $newpage.'--this is new page--this is total page'.$totalpages;  
$output .='<nav aria-label="balance pager m14-m15" balance-pager="" class="paging-holder clear">
    <ul class="pagination">';
	if ($page > 1) {
		if($totalpages != 1){
		$output .='<li style="padding-top: 4px; padding-left: 17px;">
	<div class="search-prv-click" query="'.$searchvalue.'" pager="'.($pvalue-1).'" aria-label="Next"  resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"   sort="'.$sort.'">
		<div style="float: left; margin-top:4px; margin-right:4px;   cursor: pointer;">
        <span class="btn-prev"></span>
    </div>
    <div style="float: left;  cursor: pointer; ">
        <span class="hidden-xs"></span>
    </div>
	</div>
	</li>';
	}
}
								
	if($page == $totalpages){

        if($totalpages == 1){
                $output .='<li class="active" style="padding:5px 6px; cursor: pointer"  totalpages="'.$totalpages.'"  resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'" queryvalue="'.$searchvalue.'" pagerv="1"  sort="'.$sort.'">1</li>';
        } else if($totalpages<= 6) {
            for ($i= 1 ; $i <= $totalpages; $i++) {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
            }
        } else if((6 + $page -1)<$totalpages){
            for ($i= (1 + $page -1) ; $i <= (6 + $page -1) ; $i++) {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
            }
        } else{
            for ($i= ($totalpages-5); $i <=  $totalpages ; $i++)
            {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
            }
        }
        
		// for ($i= max(1, $page ); $i <= min($page + 5, $totalpages); $i++) 
        // {
		// 	$output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
		// }

		if ($page < $totalpages) {
			$output .='<li style="padding-top: 4px; padding-left: 17px;">
		<div class="search-nxt-click" style="cursor: pointer" query="'.$searchvalue.'" pager="'.($pvalue+1).'" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'" sort="'.$sort.'">
			<div style="float:left;margin-left: 4px; cursor: pointer;"><span class="hidden-xs"></span></div>
                    <div style="float:left;margin-top: 4px;  cursor: pointer;"><span class="btn-next"></span></div>
		</div>
		</li>';
		}
		
		$output .='</ul>
			
	</nav>';
		}
	else{

        if($totalpages == 1){
                $output .='<li class="active" style="padding:5px 6px; cursor: pointer"  totalpages="'.$totalpages.'"  resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'" queryvalue="'.$searchvalue.'" pagerv="1"  sort="'.$sort.'">1</li>';
        } else if($totalpages<= 6) {
            for ($i= 1 ; $i <= $totalpages; $i++) {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
            }
        } else if((6 + $page -1)<$totalpages){
            for ($i= (1 + $page -1) ; $i <= (6 + $page -1) ; $i++) {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
            }
        } else{
            for ($i= ($totalpages-5); $i <=  $totalpages ; $i++)
            {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
            }
        }
        
		// for ($i= max(1, $page ); $i <= min($page + 5, $totalpages); $i++) {
		// 	$output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$tagids.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'" search="0" sort="0">'.$i.'</li>';
		// }

		if ($page < $totalpages) {
			$output .='<li style="padding-top: 4px; padding-left: 17px;">
	<div class="search-nxt-click" style="cursor: pointer" query="'.$searchvalue.'" pager="'.($pvalue+1).'" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'" sort="'.$sort.'">
		<div style="float:left;margin-left: 4px; cursor: pointer;"><span class="hidden-xs"></span></div>
                    <div style="float:left;margin-top: 4px;  cursor: pointer;"><span class="btn-next"></span></div>
	</div>
	</li>';
		}
		
		$output .='</ul>
			
	</nav>';
		}

$return_arr['message'] = $output;
echo json_encode($return_arr);
?>