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

$rtypes = $personData->dvalue;
$pages = $personData->pager;
$lifestage = $personData->lifestage;
$search = $personData->search;
$sort = $personData->sort;
$tags = $personData->tags;
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
if($lifestage == 1 || $lifestage == '1'){
    $lifestage = 1; 
}
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
if(empty($sort)){
	$sort = "0";
}
if($rtypes == 'tags'){
	$rtypes = "0";
}
if(empty($search)){
	$search = "0";
}
if(empty($lifestage)){
	$lifestage = "0";
}
if(empty($rtypes)){
	$rtypes = "0";
}

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


$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);

$searchQuery = preg_replace('/[#\@\.\*\%\;\$\&\^\-]+/', '', $searchQuery);
$unquotedQuery = str_replace("'", "", $unquotedQuery);
$unquotedQuery = preg_replace('/[#\@\.\*\%\;\$\&\^\-]+/', '', $unquotedQuery);


$limit = 9;

if($rtypes != "0"){
$resourcelist = "SELECT Distinct * FROM wp_resources as w WHERE w.status = 'publish' AND w.type = '$rtypes' AND w.list_in_search = 'true'";
} else {
$resourcelist = "SELECT Distinct * FROM wp_resources as w WHERE w.status = 'publish' AND w.list_in_search = 'true'";

}
$resourcelist = $db->query($resourcelist);
$num_rows = $resourcelist->rowCount();

// $limitn = ' limit 9';
if($pages){
    $starts = ($pages - 1) * $limit;

        if($num_rows <= $starts) {
                $start = 0;
		$page = 0;
        } else {
                $start = $starts;
		$page = $pages;
        }
}else{
	$start = 0; 
	$page = 0;
}  

//$return_arr[] = array("rows" => $num_rows,"pages" => $page, "start" => $start);

$list = 'true';
$level = '100';
$pgorder = '1';
$resources = 'resources/';
$check = '0';
if($search == ''){
	$searchvalue = '0';
}else{
	$searchvalue = $search;
}

if($lifestage == '0' AND $searchvalue != '0' AND $rtypes == '0'){
	
// if search has value and life stage is 0 and resourcetype is 0 starts
	if($sort == "0"){
		$select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w, wp_posts AS P";
		$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
		if ($searchQuery != '') {
		    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
		$order = " order by title_match desc, title_rough_match desc, relevancy desc";
		$query = $select . $from . $where . $order;
		$checkn = $db->prepare($query);
	}else if($sort == "relevance"){
		$select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w, wp_posts AS P";
		$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
		if ($searchQuery != '') {
		    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
		$order = " order by title_match desc, title_rough_match desc, relevancy desc";
		$query = $select . $from . $where . $order;
		$checkn = $db->prepare($query);
	}else if($sort == "views"){
		$select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
		//$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
		$from = " FROM wp_posts AS P ";
		$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
		//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
		if ($searchQuery != '') {
		    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
		$order = " order by wc.view_count desc";
		$query = $select . $from . $ljoin. $where . $order;
		    $checkn = $db->prepare($query);
		}else if($sort == "date"){
		    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
		    //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
		    $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
			//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
		    if ($searchQuery != '') {
		    	$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		    }
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
		    $order = " order by P.post_date_gmt desc";
		    $query = $select . $from . $ljoin.$where . $order;
		    $checkn = $db->prepare($query);
		}
// if search has value and life stage is 0 and resourcetype is 0 ends
	} else if($lifestage != '0' AND $searchvalue == '0' AND $rtypes == '0'){
//echo "1";		
// if search is 0 and life stage has value and resourcetype is 0 starts
		if($sort == "0"){
		    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		    $from = " FROM wp_resources as w, wp_posts AS P ";
		    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'  ";
		    if($lifestage !== '0'){
				$from .=", life_stage_type AS l";
				$where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
			}
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
		    $order = "  AND w.page_order = '$pgorder' ";
		    $query = $select . $from . $where . $order;
		    $checkn = $db->prepare($query);
		}else if($sort == "relevance"){
		    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		    $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
		    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
		   
			if($lifestage !== '0'){
				$from .=", life_stage_type AS l";
				$where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
			}
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
		    $order = " AND w.page_order = '$pgorder' ";
		    $query = $select . $from . $where . $order;
		    $checkn = $db->prepare($query);
		}else if($sort == "views"){
		    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
		    $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
		    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
		   
		    if($lifestage !== '0'){
				$from .=", life_stage_type AS l";
				$where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
			}
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
		    $order = " order by wc.view_count desc";
		    $query = $select . $from . $where . $order;
		    $checkn = $db->prepare($query);
		}else if($sort == "date"){
		    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
		    $from = " FROM wp_resources as w, wp_posts AS P ";
		    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
			if($lifestage !== '0'){
				$from .=", life_stage_type AS l";
				$where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
			}
		    $order = " order by P.post_date_gmt desc";
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
		    $query = $select . $from . $where . $order;
		    $checkn = $db->prepare($query);
		}
// if search is 0 and life stage has value and resourcetype is 0 ends
	} else if($searchvalue !== '0'){
		
// if search has value starts
		 if($sort == "0"){
	     $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	     $from = " FROM wp_resources as w, wp_posts AS P";
	     $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
	     if ($searchQuery != '') {
	     	$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	     }
	     if($rtypes != '0'){
	         $where .= " and w.type = '$rtypes'";
	         $where .= " and P.post_type = '$rtypes'";
	     }
	     if($lifestage !== '0'){
	         $from .=", life_stage_type AS l";
	         $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	     }
		 if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
	     $order = " order by title_match desc, title_rough_match desc, relevancy desc";
	     //$limit = 'limt 0, 9';
	     $query = $select . $from . $where . $order ;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $where . $order ;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){

		$select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	     $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	     $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
	     if ($searchQuery != '') {
	     	$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	     }
	     if($rtypes != '0'){
	         $where .= " and w.type = '$rtypes'";
	         $where .= " and P.post_type = '$rtypes'";
	     }
	     if($lifestage !== '0'){
	         $from .=", life_stage_type AS l";
	         $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	     }
		 if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
	     $order = " order by title_match desc, title_rough_match desc, relevancy desc";
	     //$limit = 'limt 0, 9';
	     $query = $select . $from . $where . $order ;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $where . $order ;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
	     //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	     $from = " FROM wp_posts AS P ";
		 $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";	
		 
		 $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
		 //$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
	     if ($searchQuery != '') {
	     	$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	     }
	     if($rtypes != '0'){
	         $where .= " and w.type = '$rtypes'";
	         $where .= " and P.post_type = '$rtypes'";
	     }
	     if($lifestage !== '0'){
	         $from .=", life_stage_type AS l";
	         $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	     }
		 if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
	     $order = " order by wc.view_count desc";
	     //$limit = 'limt 0, 9';
	     $query = $select . $from . $ljoin. $where . $order ;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $ljoin. $where . $order ;
	     $checkn = $db->prepare($query1);
	}else if($sort == "date"){
	    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
	     //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	     $from = " FROM wp_posts AS P ";
		 $ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";	
		 
		 $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
		 //$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
	     if ($searchQuery != '') {
	     $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	     }
	     if($rtypes != '0'){
	         $where .= " and w.type = '$rtypes'";
	         $where .= " and P.post_type = '$rtypes'";
	     }
	     if($lifestage !== '0'){
	         $from .=", life_stage_type AS l";
	         $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	     }
		 if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
	     $order = " order by P.post_date_gmt desc";
	     //$limit = 'limt 0, 9';
	     $query = $select . $from . $ljoin. $where . $order;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $ljoin. $where . $order ;
	     $checkn = $db->prepare($query1);
	}
// if search has value ends
} else if ($searchvalue == '0'){
//	echo '4';
// if search is 0 starts
	if($sort == "0"){
//echo "1";
	    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	    $from = " FROM wp_resources as w, wp_posts AS P";
	    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
	    if($rtypes != '0'){
	        $where .= " and w.type = '$rtypes'";
	        $where .= " and P.post_type = '$rtypes'";
	    }
	    if($lifestage !== '0'){
            //if($rtypes != "Worksheet"){
	        $from .=", life_stage_type AS l";
	        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	    /*} else {
	        $from .=", life_stage_type AS l";
	        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id";
	    }*/
	    }
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
	    $order = " AND w.page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	     $query = $select . $from . $where . $order;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $where . $order;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
//echo "2";
	    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	    $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
	    if($rtypes != '0'){
	        $where .= " and w.type = '$rtypes'";
	        $where .= " and P.post_type = '$rtypes'";
	    }
	    if($lifestage !== '0'){
          //  if($rtypes != "Worksheet"){
	        $from .=", life_stage_type AS l";
	        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	    /*} else{
	        $from .=", life_stage_type AS l";
	        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id";
	    }*/
	    }
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
	    $order = " AND w.page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select . $from . $where . $order;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $where . $order;
	     $checkn = $db->prepare($query1);	    
	}else if($sort == "views"){
//echo "3";
	    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
	   // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
		$from = " FROM wp_posts AS P ";
		$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";	

	   $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
	    if($rtypes != '0'){
	        $where .= " and w.type = '$rtypes'";
	        $where .= " and P.post_type = '$rtypes'";
	    }
	    if($lifestage !== '0'){
	        $from .=", life_stage_type AS l";
	        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	    }
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
	    $order = " order by wc.view_count desc";
	    //$limit = 'limt 0, 9';
	    $query = $select . $from . $ljoin. $where . $order;
	    $result = $db->prepare($query);
	    $query1 = $select . $from . $ljoin. $where . $order;
	    $checkn = $db->prepare($query1);
	}else if($sort == "date"){
//echo "4";
	    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
	   // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	    $from = " FROM wp_posts AS P ";
		$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";	
		
		$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='1'";
		//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
	    if($rtypes != '0'){
	        $where .= " and w.type = '$rtypes'";
	        $where .= " and P.post_type = '$rtypes'";
	    }
	    if($lifestage !== '0'){
	        $from .=", life_stage_type AS l";
	        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	    }
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		}
	    $order = " order by P.post_date_gmt desc";
	    //$limit = 'limt 0, 9';
	   $query = $select . $from . $ljoin. $where . $order;
	     $result = $db->prepare($query);
	     $query1 = $select . $from . $ljoin. $where . $order;
	     $checkn = $db->prepare($query1);
	}
// if search is 0 ends
} else if($lifestage == '0' AND $searchvalue == '0' AND $rtypes == '0'){
//	echo '5';
// if search is 0 and life stage is 0 and resourcetype is 0 starts
	if($sort == "0"){
		//echo '--line 233';
	    $select = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
	    $select = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct * FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' AND wc.wp_post_id = w.wp_post_id ORDER BY wc.view_count DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "date"){
	    $select = "SELECT Distinct * FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' AND wc.wp_post_id = w.wp_post_id ORDER BY P.post_date_gmt DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}
// if search is 0 and life stage is 0 and resourcetype is 0 ends
} else if($lifestage == '0' AND $searchvalue == '0' AND $rtypes == '0'){
//	echo '6';
// if search is 0 and life stage is 0 and resourcetype is 0 starts
	if($sort == "0"){
	    $select = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
	    $select = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc where w.status = 'publish' AND wc.wp_post_id = w.wp_post_id ORDER BY wc.view_count DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "date"){
	    $select = "SELECT Distinct * FROM wp_resources as w, wp_posts AS P WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' ORDER BY  P.post_date_gmt DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}
// if search is 0 and life stage is 0 and resourcetype is 0 ends
}else if($lifestage == '0' AND $searchvalue == '0'AND $rtypes != '0'){
//	echo '7';
	// life stage is 0 and search is 0 starts
	if($sort == "0"){
	    $select = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND type = '$rtypes' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
		WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND wc.wp_post_id = w.wp_post_id";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
		WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.level_of_access = '100' AND w.list_in_search = 'true' AND w.page_order = '$pgorder' AND wc.wp_post_id = w.wp_post_id ORDER BY wc.view_count DESC";
	    //$limit = 'limt 0, 9';
		
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "date"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count, P.post_date_gmt 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_posts AS P
		WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.level_of_access = '100' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.page_order = '$pgorder' AND wc.wp_post_id = w.wp_post_id ORDER BY P.post_date_gmt DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	} 
	// life stage is 0 and search is 0 ends
}else if($lifestage != '0' AND $searchvalue == '0'){
//	echo '8';
	// life stage is 'some value' and search is 0 starts
	if($sort == "0"){
	    $select = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND type = '$rtypes' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
		WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND wc.wp_post_id = w.wp_post_id";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
		WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND wc.wp_post_id = w.wp_post_id ORDER BY wc.view_count DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "date"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count, P.post_date_gmt 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_posts AS P
		WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND P.ID = w.wp_post_id AND w.page_order = '$pgorder' AND wc.wp_post_id = w.wp_post_id ORDER BY P.post_date_gmt DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	} 
	// life stage is 'some value' and search is 0 ends
}else if($lifestage == '0' AND $searchvalue != '0'){
//	echo '9';
	// life stage is 0 and search is 'some value' starts
    if($sort == "0"){
	    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_posts AS P WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode)) order by title_match DESC, title_rough_match desc, relevancy desc";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
	    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode)) order by title_match DESC, title_rough_match desc, relevancy desc";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode)) order by title_match DESC, title_rough_match desc, relevancy desc, wc.view_count desc";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "date"){
	    $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode)) order by title_match DESC, title_rough_match desc, relevancy desc, P.post_date_gmt DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}
	// life stage is 0 and search is 'some value' ends
}else if($lifestage != '0' AND $searchvalue != '0'){
//	echo '11';
	// life stage is 'some value' and search is 'some value' starts
	if($sort == "0"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, (w.title = '{$unquotedQuery}') AS title_match 
		FROM life_stage_type AS l, wp_resources AS w
		WHERE w.status = 'publish' AND title LIKE '%{$unquotedQuery}%' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND list_in_search = 'true' AND level_of_access = '100' order by title_match DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "relevance"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, (w.title = '{$unquotedQuery}') AS title_match 
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
		WHERE w.status = 'publish' AND title LIKE '%{$unquotedQuery}%' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND list_in_search = 'true' AND level_of_access = '100' AND wc.wp_post_id = w.wp_post_id order by title_match DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}else if($sort == "views"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, (w.title = '{$unquotedQuery}') AS title_match , wc.view_count
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
		WHERE w.status = 'publish' AND title LIKE '%{$unquotedQuery}%' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND  list_in_search = 'true' AND level_of_access = '100' AND wc.wp_post_id = w.wp_post_id order by wc.view_count desc";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);  
	}else if($sort == "date"){
	    $select = "SELECT Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, (w.title = '{$unquotedQuery}') AS title_match,  P.post_date_gmt
		FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_posts AS P
		WHERE w.status = 'publish' AND title LIKE '%{$unquotedQuery}%' AND l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id AND l.postid = w.ID AND w.type = '$rtypes' AND list_in_search = 'true' AND level_of_access = '100' AND wc.wp_post_id = w.wp_post_id order by  P.post_date_gmt DESC";
	    //$limit = 'limt 0, 9';
	    $query = $select;
	     $result = $db->prepare($query);
	     $query1 = $select;
	     $checkn = $db->prepare($query1);
	}
	// life stage is 0 and search is 'some value' ends
}else{
	
	//echo '14';
}

			$return_arr['query'] = $query; 
	$checkn->execute();
	//now count row 
	$checkcountn = $checkn->rowCount();
	//$return_arr['countnow'] = $checkcountn;
	$totalpages = ceil( $checkcountn / $limit );
	$output = '';
	$output .= '<nav aria-label="balance pager m14-m15" balance-pager="" class="paging-holder clear" totalcheckcount="'.$checkcountn.'" totalpages="'.$totalpages.'">
    <ul class="pagination">';

if (empty($page) || $page == '' || $page == 0 || $page == '0') {
    $page = 1;
}

// Display "previous" button if not on the first page
if ($page > 1) {
    $output .= '<li>
	<div class="prv-btn"  lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page-1).'"  search="'.$searchvalue.'"  sort="'.$sort.'">
        <div style="float: left;   cursor: pointer; margin-top: 4px; margin-right: 4px;">
        <span class="btn-prev"></span>
    </div>
    <div style="float: left;  cursor: pointer; ">
        <span class="hidden-xs"></span>
    </div>  
        </div>
    </li>';
}

// Display page numbers with ellipsis logic
$maxPagesToShow = 5; // Total number of pages to show at once, including ellipsis

if ($totalpages > $maxPagesToShow) {
    // Display the first page
    $output .= '<li class="pg-btn '.($page == 1 ? 'active' : '').'" style="padding:5px 6px; font-size: 16px ; cursor: pointer;" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="1" search="'.$searchvalue.'" sort="'.$sort.'">1</li>';

    if ($page > 4) {
        $output .= '<li class="pg-btn disabled" style="cursor: default; color: #6BD9DE">...</li>';
    }

    // Display the range of pages around the current page
    $start = max(2, $page - 2);
    $end = min($totalpages - 1, $page + 2);
    for ($i = $start; $i <= $end; $i++) {
        $output .= '<li class="pg-btn '.($page == $i ? 'active' : '').'" style="padding:5px 6px; font-size: 16px ; cursor: pointer;" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="'.$i.'" search="'.$searchvalue.'" sort="'.$sort.'">'.$i.'</li>';
    }

    if ($page < $totalpages - 3) {
        $output .= '<li class="pg-btn disabled" style="cursor: default; color: #6BD9DE">...</li>';
    }

    // Display the last page
    $output .= '<li class="pg-btn '.($page == $totalpages ? 'active' : '').'" style="padding:5px 6px; font-size: 16px ; cursor: pointer;" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="'.$totalpages.'" search="'.$searchvalue.'" sort="'.$sort.'">'.$totalpages.'</li>';
} else {
    // Display all pages if the total number is less than or equal to the max to show
    for ($i = 1; $i <= $totalpages; $i++) {
        $output .= '<li class="pg-btn '.($page == $i ? 'active' : '').'" style="padding:5px 6px; font-size: 16px ; cursor: pointer;" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="'.$i.'" search="'.$searchvalue.'" sort="'.$sort.'">'.$i.'</li>';
    }
}

// Display "next" button if not on the last page
if ($page < $totalpages) {
    $output .= '<li>
        <div class="next-btn"  search="'.$searchvalue.'" sort="'.$sort.'" lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page + 1).'">
            <div style="float: left;   cursor: pointer;  align-items: center;">
        <span class="hidden-xs"></span>
    </div>
    <div style="float: left;  cursor: pointer; align-items: center; margin-top: 4px; margin-left: 4px;">
        <span class="btn-next"></span>
    </div>
        </div>
    </li>';
}

$output .= '</ul>';
$output .= '</nav>';
										
			$return_arr['message'] = $output;
			echo json_encode($return_arr);
		?>
