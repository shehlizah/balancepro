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
$lifestage = $personData->tagid;
$page = $personData->page;
$rtypes = $personData->rtypes;
$search = $personData->searchquery;

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
$sort =  $personData->sort;
$tags = $personData->tags;
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
if(empty($search) or $search == "" ){
	$search = '0'	;
}
if(empty($sort)){
	$sort = '0'	;
}
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
$list = 'true';
$level = '100';
$pgorder = '1';
$resources = 'resources/';

$check = '0';
if($rtypes == '0' AND $search == '0'){
// if search is 0 and resourcetype is 0 starts

    if($sort == '0'){
        if($tags =='0'){
            $checkn = $db->prepare("SELECT Distinct  Distinct l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w 
            WHERE w.status = 'publish'  AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ");
        } else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.page_order = '$pgorder'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ");
        }
    } else if($sort == 'relevance'){
        if($tags !='0'){
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
            WHERE l.lifestagetype = '$lifestage' AND w.page_order = '$pgorder' AND l.postid = w.ID   AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') AND w.page_order = '$pgorder'");
     } else {
        $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
        FROM life_stage_type AS l, wp_resources AS w
        WHERE l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.page_order = '$pgorder'");
     }
    }else if($sort == 'views'){
        if($tags !='0'){
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY wc.view_count DESC ");
        } else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND wc.wp_post_id = w.wp_post_id   ORDER BY wc.view_count DESC ");
        }
    }  else if($sort == 'date'){
        if($tags !='0'){
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
            FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P  , wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id AND  l.postid = w.ID  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY P.post_date_gmt DESC ");
        } else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
            FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND  l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id AND  l.postid = w.ID  ORDER BY P.post_date_gmt DESC ");
        }
    }
// if search is 0 and resourcetype is 0 ends
}else if($rtypes != '0' AND $search == '0'){
// if search is 0 and resourcetype has value starts
    if($sort == '0'){
        if($tags !='0'){
          //  if($rtypes != "Worksheet"){
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND  l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ");
        /*} else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ");
        }*/

        } else {
          //  if($rtypes != "Worksheet"){            
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND  l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes'");
        /*} else {            
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  l.lifestagetype = '$lifestage' AND l.postid = w.wp_post_id AND w.type = '$rtypes'");
        }*/
        }
    } else if($sort == 'relevance'){
        if($tags !='0'){
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w, wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  AND w.page_order = '$pgorder'");
        } else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.page_order = '$pgorder'");
        }
    }else if($sort == 'views'){
        if($tags !='0'){
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND  wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY wc.view_count DESC ");
        } else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND  wc.wp_post_id = w.wp_post_id ORDER BY wc.view_count DESC ");
        }

    } else if($sort == 'date'){
        if($tags !='0'){
			
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
            FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P , wp_term_relationships as wtr 
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id AND l.postid = w.ID AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')   ORDER BY P.post_date_gmt DESC ");

        } else {
            $checkn = $db->prepare("SELECT Distinct  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
            FROM life_stage_type AS l, wp_resources AS w, wp_posts AS P
            WHERE w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' AND l.lifestagetype = '$lifestage' AND P.ID = w.wp_post_id AND l.postid = w.ID AND w.type = '$rtypes'  ORDER BY P.post_date_gmt DESC ");

        }
    }
// if search is 0 and resourcetype has value ends
}else if($rtypes == '0' AND $search != '0'){
// if search has value and resourcetype is 0 starts
    if($sort == '0'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w";
		$where = " WHERE  w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		if ($searchQuery != '') {
		    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and w.type = '$rtypes'";
			$where .= " and P.post_type = '$rtypes'";
		}
		if($rtypes != '0'){
			$from .=", wp_posts AS P";
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
		$order = " order by title_match desc, title_rough_match desc, relevancy desc";
        $query = $select . $from . $where . $order;
        $checkn = $db->prepare($query);
    } else if($sort == 'relevance'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
		$from = " FROM wp_resources as w";
		$where = " WHERE  w.status = 'publish'  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		if ($searchQuery != '') {
		    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
		}
		if($rtypes != '0'){
			$where .= " and w.type = '$rtypes'";
			$where .= " and P.post_type = '$rtypes'";
		}
		if($rtypes != '0'){
			$from .=", wp_posts AS P";
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
		$order = "";
        $query = $select . $from . $where . $order;
        $checkn = $db->prepare($query);
    
    }else if($sort == 'views'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        $from = " FROM wp_resources as w, wp_resources_view_count as wc";
        $where = " WHERE  w.status = 'publish' AND  wc.wp_post_id = w.wp_post_id  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
        }
        if($rtypes != '0'){
            $where .= " and w.type = '$rtypes'";
            $where .= " and P.post_type = '$rtypes'";
        }
        if($rtypes != '0'){
            $from .=", wp_posts AS P";
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
        $order = " ORDER BY wc.view_count DESC ";
        $query = $select . $from . $where . $order ;
        $checkn = $db->prepare($query);
    }  else if($sort == 'date'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id. P.post_date_gmt";
		$from = " FROM wp_resources as w, wp_posts AS P";
		$where = " WHERE  w.status = 'publish'  AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		if ($searchQuery != '') {
		    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
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
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
		$order = " ORDER BY P.post_date_gmt DESC ";
        $query = $select . $from . $where . $order;
        $checkn = $db->prepare($query); 
    }
// if search has value and resourcetype is 0 starts
}else if($rtypes != '0' AND $search != '0'){
// if search has value and resourcetype has value starts
    if($sort == '0'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
    
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
        if($rtypes != '0'){
            $where .= " and w.type = '$rtypes'";
            $where .= " and P.post_type = '$rtypes'";
        }
        if($lifestage != '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
    $order = " order by title_match desc, title_rough_match desc, relevancy desc";
    //$limitnn = 'limit '.$start.','. $limit;
    $query = $select . $from . $where . $order;
    $checkn = $db->prepare($query);
        } else if($sort == 'relevance'){
            $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($rtypes != '0'){
                $where .= " and w.type = '$rtypes'";
                $where .= " and P.post_type = '$rtypes'";
            }
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($tags !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
        $order = "";
        //$limitnn = 'limit '.$start.','. $limit;
        $query = $select . $from . $where . $order;
        $checkn = $db->prepare($query);
        }else if($sort == 'views'){
            $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $where = " WHERE w.status = 'publish' AND  wc.wp_post_id = w.wp_post_id AND  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($rtypes != '0'){
                $where .= " and w.type = '$rtypes'";
                $where .= " and P.post_type = '$rtypes'";
            }
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($tags !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
        $order = "  ORDER BY wc.view_count DESC ";
        //$limitnn = 'limit '.$start.','. $limit;
        $query = $select . $from . $where . $order;
        $checkn = $db->prepare($query);
        }  else if($sort == 'date'){
            $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
            $from = " FROM wp_resources as w, wp_posts AS P";
            $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
            if($rtypes != '0'){
                $where .= " and w.type = '$rtypes'";
                $where .= " and P.post_type = '$rtypes'";
            }
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($tags !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
        $order = "  ORDER BY P.post_date_gmt DESC ";
        //$limitnn = 'limit '.$start.','. $limit;
        $query = $select . $from . $where . $order;
        $checkn = $db->prepare($query);
        }
// if search has value and resourcetype has value ends
}

else{}
    //check 
    $checkn->execute();
    //now count row 
    $checkcountn = $checkn->rowCount();
    //$return_arr['countnow'] = $checkcountn;
    $totalpages = ceil( $checkcountn / $limit );
    $output = '';
    $output .= '<nav aria-label="balance pager m14-m15" balance-pager="" class="paging-holder clear">
    <ul class="pagination">';

    if (empty($page) || $page == '' || $page == 0 || $page == '0') {
        $page = 1;
    }
    
    // Display "previous" button if not on the first page
    if ($page > 1) {
        $output .= '<li>
        <div class="prv-btn" lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page-1).'"  search="'.$searchvalue.'"  sort="'.$sort.'">
            <div style="float: left; margin-top:4px; margin-right:4px;  cursor: pointer;">
            <span class="btn-prev"></span>
        </div>
        <div style="float: left;  cursor: pointer; ">
            <span class="hidden-xs"></span>
        </div>  
            </div>
        </li>';
    }
    
    // Display page numbers with ellipsis logic
    $maxPagesToShow = 2; // Total number of pages to show at once, including ellipsis
    
    if ($totalpages > $maxPagesToShow) {
        // Display the first page
        $output .= '<li class="pg-btn '.($page == 1 ? 'active' : '').'" style="padding:5px 6px; font-size: 16px ; cursor: pointer;" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="1" search="'.$searchvalue.'" sort="'.$sort.'">1</li>';
    
        if ($page > 2) {
            $output .= '<li class="pg-btn disabled" style="cursor: default; color: #6BD9DE; font-size:22px; position:relative; bottom :5px;">..</li>';
        }
    
        // Display the range of pages around the current page
        $start = max(2, $page - 1);
        $end = min($totalpages - 1, $page + 1);
        for ($i = $start; $i <= $end; $i++) {
            $output .= '<li class="pg-btn '.($page == $i ? 'active' : '').'" style="padding:5px 6px; font-size: 16px ; cursor: pointer;" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="'.$i.'" search="'.$searchvalue.'" sort="'.$sort.'">'.$i.'</li>';
        }
    
        if ($page < $totalpages - 2) {
            $output .= '<li class="pg-btn disabled" style="cursor: default; color: #6BD9DE; font-size:22px; position:relative; bottom :5px;">..</li>';
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
            <div class="next-btn" search="'.$searchvalue.'" sort="'.$sort.'" lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page + 1).'">
                <div style="float: left;   cursor: pointer;  align-items: center;">
            <span class="hidden-xs"></span>
        </div>
        <div style="float: left;  cursor: pointer; align-items: center; margin-top:4px; margin-left:4px;">
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