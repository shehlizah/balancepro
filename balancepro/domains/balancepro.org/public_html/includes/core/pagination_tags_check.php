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
$tagid = $personData->tagid;
$resourcetypes = $personData->rtypes;
//$dvalue = $personData->dvalue;
$keywords= explode(',', $tagid);
$advancedkeywords = implode("', '", $keywords);
$lifestage = $personData->lifestage;
$search = $personData->search;
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);
$sort =  $personData->sort;
if(empty($sort)){
    $sort = '0' ;  
}
if(empty($search)){
    $search = '0' ;  
}
if(empty($resourcetypes)  or $resourcetypes == '' ){
    $resourcetypes = '0' ;  
}
if(empty($tagid)){
    $tagid = '0' ;  
}

$limit = 9;
if($page){
    $start = ($page - 1) * $limit; 
    $newpage = $page;
}else{
    $start = 0; 
    $newpage = 1;
}
if(empty($page)  or $page == '' ){
    $page = '0' ;  
}
$list = 'true';
$level = '100';
$pgorder = '1';
$resources = 'resources/';

if($search == '0'){
// if search is 0 starts
    if($sort == '0'){
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
        if($resourcetypes != '0'){
            $where .= " and w.type = '$resourcetypes'";
            $where .= " and P.post_type = '$resourcetypes'";
        }
        if($lifestage !== '0'){
            //if($resourcetypes != "Worksheet"){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        /*} else {
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id";            
        }*/
        }
        if($tagid != '0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
        $order = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ;
        //echo $query; 
        $checkn = $db->prepare($query);
    } else if ($sort == 'relevance'){
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
        if($resourcetypes != '0'){
            $where .= " and w.type = '$resourcetypes'";
            $where .= " and P.post_type = '$resourcetypes'";
        }
        if($lifestage !== '0'){
          //  if($resourcetypes != "Worksheet"){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        /*} else {
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id";
        }*/
        }
        if($tagid != '0'){
            $from .= " , wp_term_relationships as wtr  ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
        $order = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ;
        //echo $query; 
        $checkn = $db->prepare($query);
    }else if ($sort == 'views'){
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        $from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
        if($resourcetypes != '0'){
            $where .= " and w.type = '$resourcetypes'";
            $where .= " and P.post_type = '$resourcetypes'";
        }
        if($lifestage !== '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tagid != '0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
        $order = "  ORDER BY wc.view_count DESC ";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ;
        //echo $query; 
        $checkn = $db->prepare($query);
    }else if ($sort == 'date'){
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
        if($tagid != '0'){
            $from .= " , wp_term_relationships as wtr ";
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
        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
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
        if($tagid !='0'){
            $from .= " , wp_term_relationships as wtr  ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
        $order = " AND w.page_order = '$pgorder' ";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ;
        //echo $query;
        $checkn = $db->prepare($query);
    } else if ($sort == 'relevance'){
        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
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
        if($tagid !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
            $order = " AND w.page_order = '$pgorder' ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order ;
            $checkn = $db->prepare($query);
    }else if ($sort == 'views'){
        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        $from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc ";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
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
        if($tagid !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
        }
        $order = "  ORDER BY wc.view_count DESC  ";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ;
        $checkn = $db->prepare($query);
    }else if ($sort == 'date'){
        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
        if($tagid !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " ORDER BY P.post_date_gmt DESC  ";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ;
        $checkn = $db->prepare($query);
    }
// if search has value ends
}


$checkn->execute();

//$checkcount = $check->rowCount();
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
			<div class="prv-btn" type="'.$ttvalue.'" pager="'.($page-1).'">
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
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
            }
        } else if((6 + $page -1)<$totalpages){
            for ($i= (1 + $page -1) ; $i <= (6 + $page -1) ; $i++) {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
            }
        } else{
            for ($i= ($totalpages-5); $i <=  $totalpages ; $i++)
            {
                $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
            }
        }
		// for ($i= max(1, $page ); $i <= min($page + 5, $totalpages); $i++) {
		// 	$output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
		// }

	if ($page < $totalpages) {
		$output .='<li><div class="next-btn" type="'.$ttvalue.'" pager="'.($page+1).'">
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
            $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
        }
    } else if((6 + $page -1)<$totalpages){
        for ($i= (1 + $page -1) ; $i <= (6 + $page -1) ; $i++) {
            $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
        }
    } else{
        for ($i= ($totalpages-5); $i <=  $totalpages ; $i++)
        {
            $output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
        }
    }
		// for ($i= max(1, $page ); $i <= min($page + 5, $totalpages); $i++) {
		// 	$output .='<li class="pg-btn-tags '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" tagid="'.$dvaluen.'" typevalue="'.$ttvalue.'" pagerv="'.$i.'">'.$i.'</li>';
		// }

	if ($page < $totalpages) {
		$output .='<li><div class="next-btn" type="'.$ttvalue.'" pager="'.($page+1).'">
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
$return_arr['message'] = $output;
echo json_encode($return_arr);
?>
