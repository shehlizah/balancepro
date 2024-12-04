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
    $lifestage = $personData->lifestage;
    $search = $personData->search;
    $sort = $personData->sort;
    $pager = $personData->pager;
    $searchQuery = str_replace('\\', "", $search);
    $unquotedQuery = str_replace('"', "", $search);
	
$searchQuery = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $searchQuery);
$unquotedQuery = str_replace("'", "", $unquotedQuery);
$unquotedQuery = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $unquotedQuery);
	
    $return_arr = array();
    $list = 'true';
    $level = '100';
    $pgorder = '1';

    //$return_arr['search'] = $searchQuery;
    $url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    $table_name = "wp_term_relationships";
    $table_name2 = "wp_terms";
    $resources = '/resources/';
    $tags = $personData->tags;
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);
if($lifestage == 1 || $lifestage == '1'){
    $lifestage = 1; 
}
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
    $limit = ' limit 9';
    
    if($lifestage == '0' AND $search == '0'){
    // life stage is 0 and search is 0 starts
        if($sort == "0"){
            if($tags !='0'){ 
                $stmt = $db->prepare("SELECT DISTINCT * FROM wp_resources as w, wp_term_relationships as wtr  WHERE w.status = 'publish' AND w.type = '$rtypes' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') $limit");
            } else {
                $stmt = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE status = 'publish' AND type = '$rtypes' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' $limit"); 
            }
            }else if($sort == "relevance"){
                if($tags !='0'){
                    $stmt = $db->prepare("SELECT DISTINCT * FROM wp_resources as w , wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$rtypes' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') $limit");
                } else {
                    $stmt = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE status = 'publish' AND type = '$rtypes' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' $limit");
                }
            }else if($sort == "views"){
                if($tags !='0'){
                    $query = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.type = '$rtypes' AND  wc.wp_post_id = w.wp_post_id AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY wc.view_count DESC  $limit";
                    $stmt = $db->prepare($query);
                } else {
                    $query = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc WHERE w.status = 'publish' AND w.type = '$rtypes' AND  wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ORDER BY wc.view_count DESC  $limit";
                    $stmt = $db->prepare($query);
                }
                //  echo $query;
            }else if($sort == "date"){
                if($tags !='0'){
                    $query = "SELECT DISTINCT * FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as w.status = 'publish' AND wc, wp_term_relationships as wtr WHERE w.type = '$rtypes' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' AND  wc.wp_post_id = w.wp_post_id AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY P.post_date_gmt DESC  $limit";
                    $stmt = $db->prepare($query);
                } else {
                    $query = "SELECT DISTINCT * FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc WHERE w.status = 'publish' AND w.type = '$rtypes' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' AND  wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ORDER BY P.post_date_gmt DESC  $limit";
                    $stmt = $db->prepare($query);
                }
            }
// if search is 0 and life stage is 0 ends
        }else if($lifestage != '0' AND $search == '0'){
            // life stage is 'some value' and search is 0 starts
            //echo'ccccc';
         if($sort == "0"){
            if($tags !='0'){
                $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
                FROM life_stage_type AS l, wp_resources AS w , wp_term_relationships as wtr
                WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') limit 9"); 
              
            } else {
                $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
                FROM life_stage_type AS l, wp_resources AS w
                WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND w.page_order = '$pgorder' limit 9"); 
              
            }
        }else if($sort == "relevance"){
            $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
            WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' limit 9");
        }else if($sort == "views"){

            if($tags !='0'){
                $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
                FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_term_relationships as wtr
                WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')  order by  wc.view_count desc limit 9"); 
             
            } else {
                $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count 
                FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc
                WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'  order by  wc.view_count desc limit 9"); 
             
            }
        }else if($sort == "date"){
            if($tags !='0'){
            $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc, wp_term_relationships as wtr,wp_posts as P
            WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id  AND  w.wp_post_id = wtr.object_id AND P.ID=w.wp_post_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords')  order by P.post_date_gmt DESC  limit 9"); 
            
            } else {
				
                $stmt = $db->prepare("SELECT DISTINCT  l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt 
            FROM life_stage_type AS l, wp_resources AS w, wp_resources_view_count as wc,wp_posts as P
            WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' AND wc.wp_post_id = w.wp_post_id AND P.ID=w.wp_post_id AND w.page_order = '$pgorder' order by P.post_date_gmt DESC  limit 9"); 
            
            }
        }
            // life stage is 'some value' and search is 0 ends
        }else if($lifestage == '0' AND $search != '0'){
            // life stage is 0 and search is 'some value' starts
            if($sort == "0"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }else
            if($rtypes != '0'){
                $where .= " and P.post_type = '$rtypes'";
            }
            if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
            $order = " order by title_match desc, title_rough_match desc, relevancy desc";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }else if($sort == "relevance"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }else
            if($rtypes != '0'){
                $where .= " and P.post_type = '$rtypes'";
            }
            if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
            $order = " order by title_match desc, title_rough_match desc, relevancy desc";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }else if($sort == "views"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }else
            if($rtypes != '0'){
                $where .= " and P.post_type = '$rtypes'";
            }
            if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
            $order = " order by wc.view_count desc";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }else if($sort == "date"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
            $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }else
            if($rtypes != '0'){
                $where .= " and P.post_type = '$rtypes'";
            }
            if($tags !='0'){
				$from .= " , wp_term_relationships as wtr ";
				$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
			 }
            $order = " order by P.post_date_gmt desc";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }
// if search has value and resourcetype is 0 ends
        }else if($lifestage !== '0' AND $search !== '0'){
            // life stage is 'some value' and search is 'some value' starts
            if($sort == "0"){
            
        $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
        $from = " FROM wp_resources as w";
        $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
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
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order . $limit;
        $stmt = $db->prepare($query);
        }else if($sort == "relevance"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
            $from = " FROM wp_resources as w, wp_resources_view_count as wc";
            $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
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
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }else if($sort == "views"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            $from = " FROM wp_resources as w, wp_resources_view_count as wc";
            $where = " WHERE wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder' ";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
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
            $order = " order by wc.view_count desc";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }else if($sort == "date"){
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
            $from = " FROM wp_resources as w, wp_resources_view_count as wc";
            $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'";
            if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
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
            $order = " order by P.post_date_gmt desc";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . $limit;
            $stmt = $db->prepare($query);
        }
            // life stage is 0 and search is 'some value' ends
        }else{}
		//echo $query;
        $stmt->execute();

        $limit = '9';
        // $return_arr['query'] = $check; 
        $totalpages = ceil( $rcount / $limit );
        $count = $stmt->rowCount();
        $resources = 'https://'.$_SERVER['HTTP_HOST'].'/resources/';
        //$return_arr['query'] = $stmt;

        while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
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
            
            $output = '';
                $output .= '<!-- resource resource in resources starts -->
                    <div class="col-sm-6 col-md-4">
                     <!-- resource block starts -->
                     <div class="resource-block">';
                $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
                   <div class="text-holder"><p class="dot-holder">'.$titlesmall.'</p>';
                $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" test="resource_life_stages">';
                $resultss = $db->prepare("SELECT DISTINCT  * FROM $table_name WHERE object_id='$postidn' limit 3");
                $resultss->execute();
                while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
                 $tagidn = $resultn['term_taxonomy_id'];
                   $output .= $tagidn;
                   $gettagname = $db->prepare("SELECT DISTINCT  * FROM $table_name2 WHERE term_id='$tagidn'");
                   $gettagname->execute();
                   while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
                       $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$dvaluen.'" dvalue="'.$tagidn.'" tagid="'.$tagidn.'" tags="'.$tagidn.'">';
                       $output .= $resultnn['name'];
                       $output .='</a>';
                   }
                }
            //$output .= 'test';
                $output .='</div>';
                $output .='<a role="button" href="'.$seo_dvalue. $slug.'" target="_self" class="but btn btn-primary">VIEW</a>';
                $output .='</div>';
                $output .= '<span class="icon-lock" style="display: none;"></span>';
                $output .= '</div>
                            <!-- resource block ends -->
                            </div>
                            <!-- resource resource in resources starts -->';
            $return_arr[] = array("message" => $output);
        }

        // Encoding array in JSON format
        echo json_encode($return_arr);
        ?>
