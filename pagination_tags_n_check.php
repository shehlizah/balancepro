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
$dvaluen = $personData->dvalue;
$page = $personData->page;
$dvalue = $personData->tagid;
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
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);
$sort =  $personData->sort;
$lifestage = $personData->lifestage;
$resourcetypes = $personData->rtypes;

$rt = $personData->rtype;

if(!empty($rt)){
    $resourcetypes = $personData->rtype;
}
if(empty($personData->sort) && empty($personData->sortid)){
    $sort = '0';
} else if(empty($sort)){
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
              //  if($resourcetypes != 'Worksheet'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            /*} else {
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id";
            }*/
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
              //  if($resourcetypes != 'Worksheet'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            /*} else {
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id";
            }*/
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
           // $from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";	
			
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id ";
            
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
            $query = $select . $from .$ljoin. $where . $order ;
   
            //echo $query; 
            $checkn = $db->prepare($query);
         }else if ($sort == 'date'){
            $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt ";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
            
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
       // echo'ccc';
        if($sort == '0'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
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
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
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
            //$from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc ";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id ";
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
            $query = $select . $from . $ljoin.$where . $order ;
            $checkn = $db->prepare($query);
        }else if ($sort == 'date'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt ";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
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
	 
//echo $query;	 
$checkn->execute();
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
		$output .='<li>
			<div class="prv-btn-tags" search="0" sort="0" tagid="'.$tagids.'" type="'.$ttvalue.'" pager="'.($page-1).'" search="0" sort="0">
				<div style="float:left;margin-right: 5px;margin-left: 10px;margin-top: 11px; cursor: pointer;">
					<span class="btn-prev"></span>
				</div>
				<div style="float:left;margin-top: 7px;  cursor: pointer; margin-right: 22px;">
					<span class="hidden-xs">Prev</span>
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
			$output .='<li><div class="next-btn-tags" tagid="'.$tagids.'" type="'.$ttvalue.'" pager="'.($page+1).'" search="0" sort="0">
				<div style="float:left;margin-right: 5px;margin-left: 22px;margin-top: 4px; cursor: pointer;"><span class="hidden-xs">Next</span></div>
				<div style="float:left;margin-top: 10px;  cursor: pointer;"><span class="btn-next"></span></div>
			</div></li>';
		}
		if ($totalpages > 1) {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;pages</span>
			</p>
	</nav>';
		} else {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;page</span>
			</p>
	</nav>';
		}
	}else{

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
			$output .='<li><div class="next-btn-tags" tagid="'.$tagids.'" type="'.$ttvalue.'" pager="'.($page+1).'" search="0" sort="0">
				<div style="float:left;margin-right: 5px;margin-left: 22px;margin-top: 4px; cursor: pointer;"><span class="hidden-xs">Next</span></div>
				<div style="float:left;margin-top: 10px;  cursor: pointer;"><span class="btn-next"></span></div>
			</div></li>';
		}
		if ($totalpages > 1) {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;pages</span>
			</p>
	</nav>';
		} else {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;page</span>
			</p>
	</nav>';
		}
}
$return_arr['query'] = $query;
$return_arr['message'] = $output;
echo json_encode($return_arr);
?>