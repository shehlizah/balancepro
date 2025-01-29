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
$dtype = $personData->type;  					//Life Stages type
$page = $personData->page;
//$searchquery = $personData->searchquery;
$search = $personData->searchquery;  			//Search Keyword

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
$rtypes = $personData->rtype;  					//Resource type
$tags = $personData->tags;
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
$sort =  $personData->sort;
$pgorder = '1';

if(empty($sort)){
	$sort = '0'	;
}

$return_arr = array();
$limit = '9';
if($page){
    $start = ($page - 1) * $limit; 
}else{
    $start = 0; 
}  
//$return_arr['line'] = '26';
if($rtypes != '0' AND $search != '0'){
// if search has value and resourcetype has value starts
	if($sort == '0'){
		$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w, wp_posts AS P, life_stage_type AS l";
		$where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
		if ($searchQuery != '') {
			$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and P.post_type = '$rtypes'";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = " order by title_match desc, title_rough_match desc, relevancy desc";
		// $limit = 'limit ' ;
		$query = $select . $from . $where . $order.' limit '.$start.','. $limit;
		$check = $db->prepare($query);
	} else if($sort == 'relevance'){
		$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w, wp_posts AS P, life_stage_type AS l";
		$where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
		if ($searchQuery != '') {
			$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and P.post_type = '$rtypes'";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = " AND w.page_order = '$pgorder' ";
		// $limit = 'limit ' ;
		$query = $select . $from . $where . $order.' limit '.$start.','. $limit;
		$check = $db->prepare($query);
	}else if($sort == 'views'){
		$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
		$from = " FROM wp_resources as w, wp_posts AS P, life_stage_type AS l, wp_resources_view_count as wc";
		$where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND  wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
		if ($searchQuery != '') {
			$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and P.post_type = '$rtypes'";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = "  ORDER BY wc.view_count DESC ";
		// $limit = 'limit ' ;
		$query = $select . $from . $where . $order.' limit '.$start.','. $limit;
		$check = $db->prepare($query);

	} else if($sort == 'date'){
		$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
		$from = " FROM wp_resources as w, wp_posts AS P, life_stage_type AS l";
		$where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
		if ($searchQuery != '') {
			$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and P.post_type = '$rtypes'";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = " ORDER BY P.post_date_gmt DESC ";
		// $limit = 'limit ' ;
		$query = $select . $from . $where . $order.' limit '.$start.','. $limit;
		$check = $db->prepare($query);
	}
// if search has value and resourcetype has value starts
}
else if($rtypes == '0' AND $search != '0'){
// if search has value and resourcetype is 0 starts
		if($sort == '0'){
			$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
			$from = " FROM wp_resources as w";
			$where = " WHERE  w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100'";
			if ($searchQuery != '') {
				$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
			}
			if($rtypes != '0'){
				$where .= " and w.type = '$rtypes'";
				$where .= " and P.post_type = '$rtypes'";
			}
			if($rtypes != '0'){
				$from .=", wp_posts AS P";
				//$where .= " and P.post_type = '$rtypes'";
			}
			if($dtype !== '0'){
				$from .=", life_stage_type AS l";
				$where .= " AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
			}
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
			$order = " order by title_match desc, title_rough_match desc, relevancy desc";
			//$limit = 'limt 0, 9';
			$query = $select . $from . $where . $order .' limit '.$start.','. $limit;
			$check = $db->prepare($query);
		}
		else if($sort == 'relevance'){
			
		$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w";
		$where = " WHERE  w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100'";
		if ($searchQuery != '') {
			$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and w.type = '$rtypes'";
			$where .= " and P.post_type = '$rtypes'";
		}
		if($rtypes != '0'){
			$from .=", wp_posts AS P";
			//$where .= " and P.post_type = '$rtypes'";
		}
		if($dtype !== '0'){
			$from .=", life_stage_type AS l";
			$where .= " AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = " AND w.page_order = '$pgorder' ";
		//$limit = 'limt 0, 9';
		$query = $select . $from . $where . $order .' limit '.$start.','. $limit;
		$check = $db->prepare($query);

		}else if($sort == 'views'){
	
			$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
			$from = " FROM wp_resources as w, wp_resources_view_count as wc";
			$where = " WHERE  w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
			if ($searchQuery != '') {
				$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
			}
			if($rtypes != '0'){
				$where .= " and w.type = '$rtypes'";
				$where .= " and P.post_type = '$rtypes'";
			}
			if($rtypes != '0'){
				$from .=", wp_posts AS P";
				//$where .= " and P.post_type = '$rtypes'";
			}
			if($dtype !== '0'){
				$from .=", life_stage_type AS l";
				$where .= " AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
			}
			if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
			$order = " ORDER BY wc.view_count DESC ";
			//$limit = 'limt 0, 9';
			$query = $select . $from . $where . $order .' limit '.$start.','. $limit;
			$check = $db->prepare($query);
		} else if($sort == 'date'){
	
		$select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
		$from = " FROM wp_resources as w, wp_posts AS P";
		$where = " WHERE  w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
		if ($searchQuery != '') {
		$where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and w.type = '$rtypes'";
			$where .= " and P.post_type = '$rtypes'";
		}
		if($rtypes != '0'){
			//$from .=", wp_posts AS P";
			//$where .= " and P.post_type = '$rtypes'";
		}
		if($dtype !== '0'){
			$from .=", life_stage_type AS l";
			$where .= " AND l.lifestagetype = '$dtype' AND l.postid = w.ID";
		}
		if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = " ORDER BY P.post_date_gmt DESC ";
		//$limit = 'limt 0, 9';
		$query = $select . $from . $where . $order .' limit '.$start.','. $limit;
		$check = $db->prepare($query);
		}
// if search has value and resourcetype is 0 ends
	}
	else if($rtypes != '0' AND $search == '0'){
// if search is 0 and resourcetype has value starts
		if($sort == '0'){
			if($tags !='0'){
				//if ($rtypes != "Worksheet") {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit");
			/*} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.wp_post_id AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit");
			}*/

			} else {
			//	if ($rtypes != "Worksheet") {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w  
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.type = '$rtypes'    limit $start, $limit");
			/*} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w  
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  l.lifestagetype = '$dtype' AND l.postid = w.wp_post_id AND w.type = '$rtypes'    limit $start, $limit");				
			}*/
			}
		}
		else if($sort == 'relevance'){

			if($tags =='0'){
			$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
			FROM life_stage_type AS l, wp_resources AS w
			WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.type = '$rtypes'  AND w.page_order = '$pgorder' limit $start, $limit");
			} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.type = '$rtypes'  AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') limit $start, $limit");
			}
		}else if($sort == 'views'){
			if($tags !='0'){
			$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
			FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc , wp_term_relationships as wtr 
			WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY wc.view_count DESC limit $start, $limit");
			} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
				FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id  ORDER BY wc.view_count DESC limit $start, $limit");	

			}
		} else if($sort == 'date'){
			if($tags !='0'){
				
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
				FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P, wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  l.lifestagetype = '$dtype' AND P.ID = w.wp_post_id AND l.postid = w.ID AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY P.post_date_gmt DESC limit $start, $limit");
			} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
				FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND P.ID = w.wp_post_id AND l.postid = w.ID AND w.type = '$rtypes'  ORDER BY P.post_date_gmt DESC limit $start, $limit");
			}
		}
// if search is 0 and resourcetype has value ends
	}
	else if($rtypes == '0' AND $search == '0'){
// if search is 0 and resourcetype is 0 starts
		if($sort == '0'){
			
             if($tags !='0'){
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr 
				WHERE  w.status = 'publish' AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') limit $start, $limit"); 
	
			 } else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w
				WHERE  w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID  limit $start, $limit"); 
	
			 }
		}
		else if($sort == 'relevance'){
			if($tags !='0'){
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
				FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  limit $start, $limit"); 
				
			} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
			FROM life_stage_type AS l, wp_resources AS w
			WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND w.page_order = '$pgorder' limit $start, $limit"); 
	
			}
		}else if($sort == 'views'){
			if($tags !='0'){
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
				FROM life_stage_type AS l, wp_resources AS w,  wp_resources_view_count as wc, wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY wc.view_count DESC limit $start, $limit"); 
			
			} else {
				$check = $db->prepare("SELECT Distinct w.post_title, l.postid, l.lifestagetype, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
				FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND wc.wp_post_id = w.wp_post_id  ORDER BY wc.view_count DESC limit $start, $limit"); 
			}
		}else if($sort == 'date'){
			if($tags !='0'){
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
				FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P, wp_term_relationships as wtr 
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND P.ID = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY P.post_date_gmt DESC limit $start, $limit"); 
			} else {
				$check = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
				FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P
				WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$dtype' AND l.postid = w.ID AND P.ID = w.wp_post_id  ORDER BY P.post_date_gmt DESC limit $start, $limit"); 
			}
	}
// if search is 0 and resourcetype is 0 starts
	}else{}

	$check->execute();

    //$return_arr['query'] = $check;

	$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
	$table_name = "wp_term_relationships";
	$table_name2 = "wp_terms";
	$resources = 'https://'.$_SERVER['HTTP_HOST'].'/resources/';
	while($rows = $check->fetch(PDO::FETCH_ASSOC)){
	    //$output = '<pre>';
	    //$output .= $rows['post_title'];
	    $postname = $rows['post_title'];
	    $title = $rows['title'];
	    $titlesmall = substr_replace($postname, "...", 150);
	    $dvaluen = $rows['type'];
	    $slug = $rows['slug'];
	        $postidn = $rows['wp_post_id'];
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
	$viewUrl =$seo_dvalue.$slug;
	    $output = '';
	        $output .= '<!-- resource resource in resources starts -->
	            <div class="col-sm-6 col-md-4">  
				<a href="'.$viewUrl.'" target="_self">
	             <!-- resource block starts -->
	             <div class="resource-block">';
	        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
	           <div class="text-holder"><p class="dot-holder">'.$titlesmall.'</p>';
	        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" test="resource_life_stages">';
	        $resultss = $db->prepare("SELECT Distinct  * FROM $table_name WHERE object_id='$postidn' limit 3");
	        $resultss->execute();
	        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
	         $tagidn = $resultn['term_taxonomy_id'];
	           $output .= $tagidn;
	           $gettagname = $db->prepare("SELECT Distinct  * FROM $table_name2 WHERE term_id='$tagidn'");
	           $gettagname->execute();
	           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
	               $output .='<span class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$dvaluen.'" dvalue="'.$tagidn.'" sort="'.$sort.'" tagid="'.$tagid.'" tags="'.$tagid.'">';
	               $output .= $resultnn['name'];
	               $output .='</span>';
	           }
	        }
	    //$output .= 'test';
	        $output .='</div>';
	        // $output .='<a role="button" href="'.$seo_dvalue. $slug.'" target="_self" class="but btn btn-primary">VIEW</a>';
	        $output .='</div>';
	        $output .= '<span class="icon-lock" style="display: none;"></span>';
	        $output .= '</div></a>
	                    <!-- resource block ends -->
	                    </div>
	                    <!-- resource resource in resources starts -->';
	    $return_arr[] = array("message" => $output);
	}
// Encoding array in JSON format
echo json_encode($return_arr);
?>
