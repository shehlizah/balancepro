<?php


	function render_search_main_design($type, $pager){
		if($pager !=''){
			$pagernew = $pager;
		} else {
			$pagernew = 1;
		}
		
		$ltype = '100';
		$pgorder = '1';
		$litype = 'true';
        if($type == '0'){
			$ltypen = "WHERE level_of_access = '$ltype'";
			$litypen = "AND list_in_search = '$litype'";
			$pgorder = "AND page_order = '$pgorder'";
	   }else if($type == ''){
        	$ltypen = "WHERE level_of_access = '$ltype'";
			$litypen = "AND list_in_search = '$litype'";
			$pgorder = "AND page_order = '$pgorder'";
        }else{
			$ntype = "WHERE type = '$type'";
			$litypen = "AND list_in_search = '$litype'";
			$ltypen = "AND level_of_access = '$ltype'";
			$pgorder = "AND page_order = '$pgorder'";
        }
		
		//echo $ntype,':',$litypen,':',$ltypen,':',$pgorder;
            //echo $pagernew.'--this is pager in render-search-main-design--'.$type;
?>

	<?php 
	        // INCLUDING DATABASE AND MAKING OBJECT
	        //include('../db/database.php');
			include('../db/database.php');
			
		//	print_r($_GET);
			
//get data from url			
	 $url = $_SERVER['REQUEST_URI'];
	 $url = str_replace('?sortBy=-views&pager=1', '', $url);
	$uri = trim(strtok($url, '?'));
	$uri1 = explode('?', $url);
	$uri2 = explode('=', $uri1[1]);
	    $resourcetypes1 = explode('&', $uri2[1]);
	    $lifestage1 = explode('&', $uri2[2]);
	    $lifestage1 = explode('&', $uri2[2]);
	    $sortid1 = explode('&', $uri2[3]);
	    $search1 = explode('&', $uri2[4]);
	    $tags1 = explode('&', $uri2[5]);
	    $page1 = explode('&', $uri2[6]);
	    $tagname1 = explode('&', $uri2[7]);
		$pagernew = $page1[0];
$extractedlifestage = explode('/', $url);

if($extractedlifestage){
	if($extractedlifestage[2] == "getting-started"){
		$lifestage = "50";
	} else if($extractedlifestage[2] == "new-beginnings"){
		$lifestage = "183";
	} else if($extractedlifestage[2] == "lifes-curveballs"){
		$lifestage = "184";
	} else if($extractedlifestage[2] == "ongoing-concerns"){
		$lifestage = "185";
	}  else if($extractedlifestage[2] == "retirement-costs"){
		$lifestage = "186";
	} 
}
	if(empty($resourcetypes1[0]) or $resourcetypes1[0] == "" ){
		$resourcetypes = '0';
	} else {
		$resourcetypes = $resourcetypes1[0];
	}
	if(empty($lifestage1[0]) or $lifestage1[0] == "" ){
		$lifestage = '0';
	}else {
		$lifestage = $lifestage1[0];
	}

	if(empty($sortid1[0]) or $sortid1[0] == "" ){
		$sort = '0';
	}else {
		$sort = $sortid1[0];
	}

	if(empty($search1[0]) or $search1[0] == "" ){
		$search = '0';
	} else {
		$search =str_replace('%20',' ',$search1[0]);
	}

	if(empty($tags1[0]) or $tags1[0] == "" ){
		$tags = '0';
	}else {
		$tags = $tags1[0];
	}

	if(empty($tagname1[0]) or $tagname1[0] == "" ){
		$tagname = '0';
	}else {
		$tagname = $tagname1[0];
	}
	if(empty($page1[0]) or $page1[0] == "" ){
		$page = '0';
	}else {
		$page = $page1[0];
	}
	$tagnames = $tagname;
	
	//echo "resourcetypes1 ->". $resourcetypes . " lifestage->".$lifestage . " sort->". $sort . " tags->". $tags;
	
	        $list = 'true';
	        $level = '100';
	        $pgorder = '1';
	        $rtypes = $resourcetypes;
	        $searchQuery = str_replace('\\', "", $search);
	        $unquotedQuery = str_replace('"', "", $search);
			$keywords= explode(',', $tags);
			
			$advancedkeywords = implode("', '", $keywords);
	        
			
			
			$limitQuery = ' limit 9';
	        $dvalue = $search;
			
		if($page != '0'){
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
		if($pagernew==''||$pagernew==0)$page = 1;
		else $page=$pagernew;
	       // $pagernew = $_GET['page'];

		global $wpdb;
		$table_name = "wp_resources";
		$column_name = "type";
		$pagenum == $pagernew;
	    $limit = 9;
	    $offset = ($pagenum-1) * $limit;
		if($page){
			$pvalue = $page;
		}else{
			$pvalue = '0';
		}
		if($page){
			$start = ($pvalue - 1) * $limit; 
		}else{
			$start = 0; 
		}
		 $limitQuery = " limit $start, $limit ";
		 //echo $pagernew.'--this is pager in render-search-main-design--'.$type;
	    
		 if($dvalue == "0" and $resourcetypes == '0' and $lifestage == '0'){
			 //echo 'sort:',$sort;
// if search is 0 and life stage is 0 and resourcetype is 0 starts
	    if($sort == '0'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish' ";
	        
	           
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
	            }
	            if($lifestage != '0'){
	                $from .=", life_stage_type AS l";
	                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID ";
	            }
	            if($tags !='0'){
	                $from .= " , wp_term_relationships as wtr ";
	                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
	             }
	        $order = " AND w.page_order = '$pgorder' ";
	        //$limit = 'limt 0, 9';
	        $queryp = $select . $from . $where . $order;
	        $query = $select . $from . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);

			
			$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			//$select1 = "SELECT COUNT(DISTINCT w.post_title) ";
	      
	        $query1 = $select1 . $from . $where . $order;
	        //$total = $wpdb->get_var($query1);
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
		
	    }else if($sort == 'relevance'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish'";
	        
	           
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
	            }
	            if($lifestage != '0'){
	                $from .=", life_stage_type AS l";
	                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	            }
	            if($tags !='0'){
	                $from .= " , wp_term_relationships as wtr ";
	                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
	             }
	        $order = " AND w.page_order = '$pgorder' ";
	        //$limit = 'limt 0, 9';
	        $queryp = $select . $from . $where . $order ;
	        $query = $select . $from . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);
			
			/*$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			//$select1 = "SELECT COUNT(DISTINCT w.post_title) ";
	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
			
		
	    }else if($sort == 'views'){			
	       $select = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	       // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
		    $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		   $where = " WHERE P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish' AND w.page_order = '$pgorder' ";
		  // $where = " WHERE  wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish' AND w.page_order = '$pgorder' ";
	        
	          
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $queryp = $select . $from . $ljoin . $where . $order;
	        $query = $select . $from . $ljoin . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);

			$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			//$select1 = "SELECT COUNT(DISTINCT w.post_title) ";	
	        /*$query1 = $select1 . $from . $ljoin. $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			
			$checkn = $wpdb->get_results($queryp);
			
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
		
	    }else if($sort == 'date'){
	        $select = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish' AND w.page_order = '$pgorder'";
	        
	          
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);

			/*$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			//$select1 = "SELECT COUNT(DISTINCT w.post_title) ";
	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
			
		}
// if search is 0 and life stage is 0 and resourcetype is 0 ends
	}else 
	     if($dvalue != "0"){
// if search is 0 and life stage is 0 and resourcetype has value starts
	    if($sort == '0'){
	        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
	        
	            if ($searchQuery != '') {
	            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	            }
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);
			
			/*$select1 = "SELECT DISTINCT COUNT(*), (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	       
	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
			
		
	    }else if($sort == 'relevance'){
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
	            if($lifestage != '0'){
	                $from .=", life_stage_type AS l";
	                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	            }
	            if($tags !='0'){
	                $from .= " , wp_term_relationships as wtr ";
	                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
	             }
	        $order = " AND w.page_order = '$pgorder' ";
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);
			
			/*$select1 = "SELECT DISTINCT COUNT(*), (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";

	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
			
		
	    }else if($sort == 'views'){
	        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	       // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	        $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";

			
		   $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
		  // $where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
	        
	            if ($searchQuery != '') {
	            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	            }
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $ljoin . $where . $order . $limitQuery;
	        $queryp = $select . $from . $ljoin . $where . $order;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);
			
			/*$select1 = "SELECT DISTINCT COUNT(*), (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";

	        $query1 = $select1 . $from . $ljoin. $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
			
		
	    }else if($sort == 'date'){
	        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
	        
	            if ($searchQuery != '') {
	            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
	            }
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order ;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);
			
			/*$select1 = "SELECT DISTINCT COUNT(*), (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";

	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
		
	    }
// if search is 0 and life stage is 0 and resourcetype has value ends
	} else {
// if search is 0 and life stage is 0 and resourcetype is 0 starts
	    if($sort == '0'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' ";
	        
	           
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
	            }
	            if($lifestage != '0'){
	                $from .=", life_stage_type AS l";
	                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID ";
	            }
	            if($tags !='0'){
	                $from .= " , wp_term_relationships as wtr ";
	                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
	             }
	        $order = " AND w.page_order = '$pgorder' ";
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order ;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);

			
			/*$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
	      $select1 = "SELECT COUNT(DISTINCT w.post_title) ";
	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
		
	    }else if($sort == 'relevance'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
	        
	           
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
	            }
	            if($lifestage != '0'){
	                $from .=", life_stage_type AS l";
	                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	            }
	            if($tags !='0'){
	                $from .= " , wp_term_relationships as wtr ";
	                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
	             }
	        $order = " AND w.page_order = '$pgorder' ";
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order ;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);
			
			/*$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			
	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
			
		
	    }else if($sort == 'views'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	       // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
			$from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";

		   $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
		  // $where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
	        
	          
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $ljoin.$where . $order . $limitQuery;
	        $queryp = $select . $from . $ljoin.$where . $order;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);

			/*$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			
	        $query1 = $select1 . $from . $ljoin.$where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
		
	    }else if($sort == 'date'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
	        
	          
	            if($resourcetypes != '0'){
	                $where .= " and w.type = '$resourcetypes'";
	                $where .= " and P.post_type = '$resourcetypes'";
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
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        $queryp = $select . $from . $where . $order;
	        //$result = $db->prepare($query);
	        $results=$wpdb->get_results($query, object);

			/*$select1 = "SELECT DISTINCT COUNT(*), w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
			
	        $query1 = $select1 . $from . $where . $order;
	        $total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );*/
			$checkn = $wpdb->get_results($queryp);	
			$total = count($checkn);	
	        $num_of_pages = ceil( $total / $limit );
		
	    }
	} 
		//echo  $query;
		
		$life_stage_type = 'life_stage';
		$sqllifestages = "SELECT DISTINCT P.ID as id, P.post_title as tag from wp_posts as P join wp_postmeta as PM on P.ID = post_id  where P.post_type = 'life_stage' and PM.`meta_key` = '_wp_page_template' and PM.meta_value = '$life_stage_type' AND P.post_status='publish'";
		$sqlresults=$wpdb->get_results($sqllifestages, object);
		
		if($search =='0'){
			$input_search_value = "";
		}else {
			$input_search_value = $search;
		}
		
		
		//print_r($_REQUEST);

	$output = '';
	$output .='<section aria-label="search container">';
	    $output .='<div class="searchContainer">';
		    $output .='<div class="container background-white">';
			    $output .='<div class="row">';
			    $output .='<div class="inner-row">';
			    $output .='<aside id="sidebar" class="col-sm-4">';
			    $output .='<!-- Mobile view filter code starts from megha -->';

			    $output .='	<div class="opener-block text-center">';
			    $output .='	<a href="#" class="filter-drop-opener text-warning">VIEW FILTERS</a>';
			     $output .='</div>';
			     $output .='<!-- Mobile view filter code Enddd from megha -->';
			    $output .='<!-- sidebar code starts -->';
			    $output .='<div class="aside-drop"><div class="ovh-holder">';
				$output .= '<div class="aside-holder ">';
				 $output .='<!-- Mobile view filter code starts from megha -->';

				  $output .='<div class="apply-block visible-xs">';
                   $output .='	<a href="#" ng-click="resetFilters($event)" class="filter-drop-close">CANCEL</a>';
                     $output .='  	<a href="#" ng-click="apply($event)" class="filter-drop-close">APPLY</a>';
                       $output .='   </div>';

                   $output .='<!-- Mobile view filter code Enddd from megha -->';


				$output .='<h2 class="aside-title hidden-xs text-center h3">' . __('FILTERS', 'balance' ) . '</h2>';
                $output .='<h3 class="aside-title-xs visible-xs text-center">' . __('Select Filters', 'balance' ) . '</h3>';
				$output .= '<div class="aside-form ng-valid ng-dirty ng-valid-parse">';
				$output .='<fieldset>';
				$output .= '<ul class="aside-filter same-height-holder" style="border-top:1px solid #a7bab9; ">';
				$output .='<!-- filter type panel starts -->';
				$output .='<li>';
				// $output .='<a href="#" class="filter-opener visible-xs">' . __('RESOURCE TYPES', 'balance' ) . '<span class="selected ng-binding">1 selected</span> <span class="custom-caret visible-xs">All Types</span>';
				$output .='<a href="#" class="filter-opener visible-xs">' . __('RESOURCE TYPES', 'balance' ) . '<span class="selected ng-binding">1 selected</span>';
				$output .='<span class="custom-caret visible-xs">';
    			$output .='<img width="19" height="11" alt="image description" src="' . get_stylesheet_directory_uri() . '/images/drop-arrow.svg">';
  				$output .='</span>';
				$output .='</a>';
				$output .='<span class="filter-opener hidden-xs">' . __('RESOURCE TYPES', 'Types').'</span>';
				$output .='<ul class="filter-drop" style="position: absolute; top: -9999px; left: -9999px; width: 277px;">';
			    if($resourcetypes == '0'){
					$output .='<li>
					<div class="col-sm-12">
						<div class="input-group">
							<label for="select-all-types">
								<div id="select-all-types" lifestage="'.$lifestage.'" active="1" dvalue="0" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" class="ng-pristine ng-valid">
								<span class="fake-input"></span>
								<span class="fake-label text-center selectalltypes">
								<span class="text">' . __('All Types', 'balance').'</span>
								</span>
							</label>
						</div>
					</div>
				</li>';
				} else {
					$output .='<li>
					<div class="col-sm-12">
						<div class="input-group">
							<label for="select-all-types">
								<div id="select-all-types" lifestage="'.$lifestage.'" active="0" dvalue="0" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" class="ng-pristine ng-valid">
								<span class="fake-input"></span>
								<span class="fake-label text-center selectalltypes">
								<span class="text">' . __('All Types', 'balance').'</span>
								</span>
							</label>
						</div>
					</div>
				</li>';
				}
				


				$output .='<li>';
				$output .='<!--icons starts -->';
					$output .='<div class="col-xs-6">';
						$output .='<!-- sidebar small menu starts -->';

						if($resourcetypes == 'article'){
						$output .='<div class="input-group ng-scope">
							<label for="cb-article">
								<div id="cb-article" class="siderbar-small-category" lifestage="'.$lifestage.'" active="1" dvalue="article" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'" inform="rsearchbtn">
									<span class="resourcetype fake-label same-height same-height-left same-height-right active-article" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
										<span class="icon-article tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
										<span class="text">Articles </span>
									</span>
								</div>
							</label>
						</div>';
						} else {
							$output .='<div class="input-group ng-scope">
							<label for="cb-article">
								<div id="cb-article" class="siderbar-small-category" lifestage="'.$lifestage.'" active="0" dvalue="article" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'" inform="rsearchbtn">
									<span class="resourcetype fake-label same-height same-height-left same-height-right active-article" style="height: 28px;">
										<span class="icon-article tagicon"></span>
										<span class="text">Articles </span>
									</span>
								</div>
							</label>
						</div>';
						}

					$output .='<!-- sidebar small menu ends -->';
					$output .='</div>';
					$output .='<!-- icons ends -->';
					$output .='<!--icons starts -->';
					$output .='<div class="col-xs-6">';
						$output .='<!-- sidebar small menu starts -->';
						if($resourcetypes == 'calculator'){
						$output .='<div class="input-group ng-scope">
							<label for="cb-calculator">
								<div id="cb-calculator" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="calculator" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'" >
									<span class="resourcetype fake-label same-height same-height-left same-height-right  active-calculator" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
										<span class="icon-calculator tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
										<span class="text">Calculators </span>
									</span>
								</div>
								</label>
								</div>';
							} else {
						$output .='<div class="input-group ng-scope">
							<label for="cb-calculator">
								<div id="cb-calculator" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="0" dvalue="calculator" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'" >
									<span class="resourcetype fake-label same-height same-height-left same-height-right  active-calculator" style="height: 28px;">
										<span class="icon-calculator tagicon"></span>
										<span class="text">Calculators </span>
									</span>
								</div>
								</label>
								</div>';
							}
								$output .='<!-- sidebar small menu ends -->';
								$output .='</div>';
								$output .='<!-- icons ends -->';
								$output .='<!--icons starts -->';
								$output .='<div class="col-xs-6">';
								$output .='<!-- sidebar small menu starts -->';
						if($resourcetypes == 'video'){
								$output .='<div class="input-group ng-scope">
									<label for="cb-video">
										<div id="cb-video" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="video" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											<span class="resourcetype fake-label same-height same-height-left same-height-right  active-video" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
												<span class="icon-video tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
												<span class="text">Videos </span>
											</span>
										</div>
										</label>
								</div>';
							} else {

								$output .='<div class="input-group ng-scope">
									<label for="cb-video">
										<div id="cb-video" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="video" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											<span class="resourcetype fake-label same-height same-height-left same-height-right  active-video" style="height: 28px;">
												<span class="icon-video tagicon"></span>
												<span class="text">Videos </span>
											</span>
										</div>
										</label>
								</div>';
							}
								$output .='<!-- sidebar small menu ends -->';
								$output .='</div>';
								$output .='<!-- icons ends -->';
								/*$output .='<!--icons starts -->';
								$output .='<div class="col-xs-6">';
								    $output .='<!-- sidebar small menu starts -->';
						if($resourcetypes == 'newsletter'){
									$output .='<div class="input-group ng-scope">
										<label for="cb-newsletter">
											<div id="cb-newsletter" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="newsletter" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-newsletter" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											        <span class="icon-newsletter tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
											        <span class="text">Newsletters </span>
											    </span>
											</div>
										</label>
									</div>';
								} else {

									$output .='<div class="input-group ng-scope">
										<label for="cb-newsletter">
											<div id="cb-newsletter" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="newsletter" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-newsletter" style="height: 28px;">
											        <span class="icon-newsletter tagicon"></span>
											        <span class="text">Newsletters </span>
											    </span>
											</div>
										</label>
									</div>';
								}
								    $output .='<!-- sidebar small menu ends -->';
								
								$output .='</div>';
								$output .='<!-- icons ends -->';*/
								$output .='<!--icons starts -->';
								$output .='<div class="col-xs-6">';
								$output .='<!-- sidebar small menu starts -->';
						if($resourcetypes == 'podcast'){
									$output .='<div class="input-group ng-scope">
										<label for="cb-podcast">
											<div id="cb-podcast" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="podcast" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-podcast" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											        <span class="icon-podcast tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
											        <span class="text">Podcasts </span>
											    </span>
											</div>
										</label>
									</div>';
								} else {

									$output .='<div class="input-group ng-scope">
										<label for="cb-podcast">
											<div id="cb-podcast" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="podcast" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-podcast" style="height: 28px;">
											        <span class="icon-podcast tagicon"></span>
											        <span class="text">Podcasts </span>
											    </span>
											</div>
										</label>
									</div>';
								}
								$output .='<!-- sidebar small menu ends -->';
								$output .='</div>';
								$output .='<!-- icons ends -->';
								$output .='<!--icons starts -->';
								    $output .='<div class="col-xs-6">';
								    $output .='<!-- sidebar small menu starts -->';
						if($resourcetypes == 'toolkit'){
									    $output .='<div class="input-group ng-scope">
											<label for="cb-toolkit">
											    <div id="cb-toolkit" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="toolkit" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-toolkit" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-toolkit tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
											            <span class="text">Toolkits </span>
											        </span>
											    </div>
											</label>
										</div>';
									} else {
									    $output .='<div class="input-group ng-scope">
											<label for="cb-toolkit">
											    <div id="cb-toolkit" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="toolkit" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-toolkit" style="height: 28px;">
											            <span class="icon-toolkit tagicon"></span>
											            <span class="text">Toolkits </span>
											        </span>
											    </div>
											</label>
										</div>';

									}
								   	$output .='<!-- sidebar small menu ends -->';
									$output .='</div>';
								    $output .='<!-- icons ends -->';
								    $output .='<!--icons starts -->';
								    $output .='<div class="col-xs-6">';
								    	$output .='<!-- sidebar small menu starts -->';

						if($resourcetypes == 'booklet'){
									    $output .='<div class="input-group ng-scope">
											<label for="cb-booklet">
											    <div id="cb-booklet" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="booklet" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-booklet" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-booklet tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
											            <span class="text">Booklets </span>
											        </span>
											    </div>
											</label>
										</div>';
									} else {

									    $output .='<div class="input-group ng-scope">
											<label for="cb-booklet">
											    <div id="cb-booklet" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="booklet" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-booklet" style="height: 28px;">
											            <span class="icon-booklet tagicon"></span>
											            <span class="text">Booklets </span>
											        </span>
											    </div>
											</label>
										</div>';
									}
								        $output .='<!-- sidebar small menu ends -->';
									
								    $output .='</div>';
								    $output .='<!-- icons ends -->';
								    $output .='<!--icons starts -->';
								    $output .='<div class="col-xs-6">';
								    	$output .='<!-- sidebar small menu starts -->';

						if($resourcetypes == 'Worksheet' || $resourcetypes == 'worksheet'){
									    $output .='<div class="input-group ng-scope">
											<label for="cb-Worksheet">
											    <div id="cb-Worksheet" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="Worksheet" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-Worksheet" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-worksheet tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
											            <span class="text">Worksheets </span>
											        </span>
											    </div>
											</label>
										</div>';
									} else {

									    $output .='<div class="input-group ng-scope">
											<label for="cb-Worksheet">
											    <div id="cb-Worksheet" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="Worksheet" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-Worksheet" style="height: 28px;">
											            <span class="icon-worksheet tagicon"></span>
											            <span class="text">Worksheets </span>
											        </span>
											    </div>
											</label>
										</div>';
									}
								        $output .='<!-- sidebar small menu ends -->';
									
								    $output .='</div>';
								    $output .='<!-- icons ends -->';
								    $output .='<!--icons starts -->';
								    $output .='<div class="col-xs-6">';
								    	$output .='<!-- sidebar small menu starts -->';

						if($resourcetypes == 'checklist'){
									    $output .='<div class="input-group ng-scope">
											<label for="cb-checklist">
											    <div id="cb-checklist" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" active="1" dvalue="checklist" pager="'.$pagernew.'" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-checklist" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-checklist tagicon" style="background: rgb(2, 166, 156);color: rgb(255, 255, 255);cursor: pointer;"></span>
											            <span class="text">Checklists </span>
											        </span>
											    </div>
											</label>
										</div>';
									} else {

									    $output .='<div class="input-group ng-scope">
											<label for="cb-checklist">
											    <div id="cb-checklist" class="siderbar-small-category" inform="rsearchbtn" lifestage="'.$lifestage.'" dvalue="checklist" pager="0" tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" tagnames="'.$tagnames.'">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-checklist" style="height: 28px;">
											            <span class="icon-checklist tagicon"></span>
											            <span class="text">Checklists </span>
											        </span>
											    </div>
											</label>
										</div>';
									}
								        $output .='<!-- sidebar small menu ends -->';
									
								    $output .='</div>';
								    $output .='<!-- icons ends -->';
								       	$output .='</li>';
								                            $output .='</ul>';
								                        $output .='</li>';
								              		$output .='<!-- filter type panel ends -->';
								              		$output .='<!-- autosuggest panel starts -->';
								              			$output .='<li class="autocomplete-tag-holder"><a href="#" class="filter-opener visible-xs">TAGS<span class="selected ng-binding tagcount">0 selected</span><span class="custom-caret visible-xs"><img width="19" height="11" alt="image description" src="' . get_stylesheet_directory_uri() . '/images/drop-arrow.svg"></span></a><span class="filter-opener hidden-xs">Tags</span>
								                                <ul class="filter-drop" style="position: absolute; top: -9999px; left: -9999px; width: 277px;">
								                                  <li>
								                                    <div class="col-xs-12">
								                                      <div class="input-group">
								                                        <input type="search"  placeholder="Enter Keywords"class="autocomplete-tag-input ng-pristine ng-untouched ng-valid ui-autocomplete-input tagsbox" autocomplete="off" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  pager="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">
								                                        <button class="search-btn"><span class="icon-search"></span></button>
								                                      </div>
								                                      <div class="autocomplete-tag-list"
								                                       resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  pager="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">
								                                      	<div class="tag-search-keyword"  resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  pager="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">
								                                      	</div>
								                                      </div>
								                                    </div>
								                                  </li>
								                                </ul>
								                              </li>';
								              		$output .='<!-- autosuggest panel ends -->';
											$output .='<!-- life stages panel starts -->';
                                                                                        $output .='<li>';
											$output .='<a href="#" class="filter-opener visible-xs">';
  											$output .='LIFE STAGES';
  											$output .='<span class="selected ng-binding">1 selected</span>';
  											$output .='<span class="custom-caret visible-xs">';
    											$output .='<img width="19" height="11" alt="image description" src="' . get_stylesheet_directory_uri() . '/images/drop-arrow.svg">';
  											$output .='</span>';
											$output .='</a>';
                                                                                        $output .='<span class="filter-opener hidden-xs">LIFE STAGES</span>';
  											$output .='<ul class="filter-drop same-height-holder" style="position: absolute; top: -9999px; left: -9999px; width: 277px;">';
    											$output .='<li>';
											$output .='<div class="col-sm-12">';
											$output .='<div class="input-group">';
											$output .='<label for="select-stage-all">';
											$output .='<span class="fake-input"></span>';

						if($lifestage == '0'){
											$output .='<span class="fake-label text-center" id="select-stage-all" active="1" dvalue="0" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  pager="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">';
 											$output .='<span class="text">All Stages</span>';
											$output .='</span>';
										} else {
											$output .='<span class="fake-label text-center" id="select-stage-all" active="0" dvalue="0" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  pager="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">';
 											$output .='<span class="text">All Stages</span>';
											$output .='</span>';

										}
 											$output .='</label>';
											$output .='</div>';
											$output .='</div>';
											$output .='</li>';
											$output .='<li>';
											foreach( $sqlresults as $sqlresult )
							                		{
							                    		$lifestageid = $sqlresult->id;
							                   		$lifestagetagname = $sqlresult->tag;

												$output .='<div class="col-xs-6">';
        												$output .='<div class="input-group ng-scope lifestagec checkattr'.$lifestageid.'" dvalue="'.$lifestageid.'" page="'.$pagernew.'" active="0"
        												 resourcetypes="'.$resourcetypes.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">';
          												$output .='<label for="stage-50" class="text-center label-text">';
            											        $output .='<div id="stage-50" class="ng-pristine ng-untouched ng-valid"></div>';
            												$output .='<span class="fake-input"></span>';
            												if($lifestage == $lifestageid) {
            												$output .='<span class="fake-label lifestage'.$lifestageid.' lfstyle" style="background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">';
              												$output .='<span class="center text ng-binding">'.$lifestagetagname.'</span>';
            												$output .='</span>';
            												} else {
            												$output .='<span class="fake-label lifestage'.$lifestageid.' lfstyle">';
              												$output .='<span class="center text ng-binding">'.$lifestagetagname.'</span>';
            												$output .='</span>';
            												}
          												$output .='</label>';
        												$output .='</div>';		
        												$output .='</div>';
											}
											$output .='</li>';
                                                                                        $output .='<!-- life stages panel ends -->';
								              	$output .= '</ul>';
								              $output .='</fieldset>';            
								        $output .='</div>';
								        $output .='</div></div></div>';
			        			$output .='<!-- sidebar code ends -->';
			        		$output .='</aside>';
			        	$output .='</div>';
			        	$output .= '<div id="content" class="content-section col-sm-8">';
			        			 $output .= '<div class="resource-column same-height-holder">';
				         			$output .='<!-- search input box starts -->';
				         				$output .='<form class="sort-form">
									                  <fieldset>';
									        if($search == "0"){
									                    $output .='<div class="col-sm-8">
									                      <div class="input-group">
									                        <input type="search" aria-label="Enter Keywords field." placeholder="Enter Keywords" ng-model="filters.query" class="form-control" id="resource-search-input" active="0" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  page="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" value="">
									                      </div>
									                    </div>';
				         			} else {
				         				$output .='<div class="col-sm-8">
									                      <div class="input-group">
									                        <input type="search" aria-label="Enter Keywords field." placeholder="Enter Keywords" ng-model="filters.query" class="form-control" id="resource-search-input" active="1" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  page="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'" value="'.$search.'">
									                      </div>
									                    </div>';
				         			}
				         			if($sort == "0"){
				         				$output .='<div class="col-sm-4">
														<div class="input-group">
														<select class="form-control sort-form-select" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  page="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">
														<!--  <option value="nothing">Sort By</option>  -->
															<option value="0">Relevance</option>
															<option value="views">Most Popular</option>
															<option value="date">Most Recent</option>
														</select>
									                      </div>
									                    </div>';
				         			} else if($sort =="views"){
				         				$output .='<div class="col-sm-4">
														<div class="input-group">
														<select class="form-control sort-form-select" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  page="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">
														<!--  <option value="nothing">Sort By</option>  -->
															<option value="views">Most Popular</option>
															<option value="0">Relevance</option>
															<option value="date">Most Recent</option>
														</select>
									                      </div>
									                    </div>';
				         			} else {
				         				$output .='<div class="col-sm-4">
														<div class="input-group">
														<select class="form-control sort-form-select" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  page="'.$pagernew.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">
														<!--  <option value="nothing">Sort By</option>  -->
															<option value="date">Most Recent</option>
															<option value="0">Relevance</option>
															<option value="views">Most Popular</option>
														</select>
									                      </div>
									                    </div>';
				         			}
														
									                  $output .='</fieldset>
									                </form>';
				         			$output .='<!-- search input box ends -->';
				         			$output .='<!-- main content starts -->';
				         			$output .= '<div class="resource-column-new same-height-holder hidden">';
				         			$output .= '</div>';
				         				$output .= '<div class="resource-column same-height-holder content-inner-page">';
							                foreach( $results as $result )
							                {
							                    $titlen = $result->post_title;
							                    $dvaluen = $result->type;
							                    $slug = $result->slug;
                                                $postid = $result->wp_post_id;
							                    if($dvaluen == 'all types' ){
													$seo_dvalue = 'all types/';
												}else if($dvaluen == 'article'){
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
                                                $url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

									   $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
                                        $table_name = "wp_term_relationships";
									     $table_name2 = "wp_terms";
							                    $resources = '/resources/';
							                    $output .= '<!-- resource resource in resources starts -->
							                              <div class="col-sm-6 col-md-4" >
							                              <!-- resource block starts -->
							                                <div class="resource-block">
							                                  <div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
							                                  <div class="text-holder">
							                                    <p class="dot-holder">'.$titlen.'</p>
							                                    <div class="btn-tag same-height  same-height-left same-height-right life'.$dvaluen.'" style="height: 122px;" dvalue="'.$countthem.'">';
                                                                                             
                                                    $resultss=$wpdb->get_results("SELECT DISTINCT * FROM $table_name WHERE object_id='$postid' limit 3");
    											    foreach($resultss as $printss) {
      													$tagid = $printss->term_taxonomy_id;
                                                        $gettagname = $wpdb->get_results("SELECT DISTINCT * FROM $table_name2 WHERE term_id='$tagid'");
 						
                                                        foreach ($gettagname as $printsq) {
															$output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$printsq->name.'" pager="'.$pagernew.'" type="'.$type.'" dvalue="'.$tagid.'" tagid="'.$tagid.'" resourcetypes="'.$resourcetypes.'" lifestage="'.$lifestage.'"  tags="'.$tags.'" search="'.$search.'" sort="'.$sort.'">';
                                                        	$output .= $printsq->name;
															$output .='</a>';
                                                        }
 													
                                                        //$output .= $tagid;
    												}
                                                         $output .='</div>
							                                    <a href="'. site_url().$resources.$seo_dvalue. $slug.'" target="_self" class="but btn btn-primary">' . __('VIEW', 'balance').'</a>
							                                  </div>
							                                  <span class="icon-lock" style="display: none;"></span>
							                                </div>
							                                <!-- resource block ends -->
							                              </div>
							                              <!-- resource resource in resources ends -->';
							                }
							              $output .= '      </div>';
				         			$output .='<!-- main content ends -->';
				         		$output .='</div>';
				         		$output .='<!-- pagination box starts -->';
				         		$output .='<div style="padding:10px 5px; clear: both;" id="pagination-box-n" class="hidden">';
				         		$output .='</div>';
				         		$output .='<div style="padding:10px 5px; clear: both;" id="pagination-box" type="'.$type.'" dvalue="'.$dvaluen.'" pagination-box="render-search-main-design" type="'.$ttvalue.'" pager="'.$pagernew.'" numpages="'.$num_of_pages.'">';
				         		$output .='<nav aria-label="balance pager m14-m15" balance-pager="" class="paging-holder clear">
            								<ul class="pagination">';
							if($type == ''){
								$ttvalue = '0';
                            }else if($type == '0'){
								$ttvalue = '0';
                            }else{
								$ttvalue = $dvaluen;
							}
								if ($pagernew > 1) {
                                    //ech' here';
                                    $output .='<li test="sdfds ---this is type'.$type.'">
                                 	  	<div class="prv-btn" lifestage="0" type="'.$ttvalue.'" pager="'.($pagernew-1).'" search="0">
                                 		   <div style="float:left;margin-right: 5px;margin-left: 10px;margin-top: 11px; cursor: pointer;">
												<span class="btn-prev"></span>
											</div>
							              	<div style="float:left;margin-top: 7px;  cursor: pointer; margin-right: 22px;">
							              		<span class="hidden-xs">Prev</span>
											</div>
								        </div>
								    </li>';
								}
								
								/** added by dhiraj **/
								if($pagernew=='')$pagernew=1;
								$offs = $num_of_pages-$pagernew;
								if($offs<=5) $pagernews=$pagernew-(5-$offs);
								else $pagernews=$pagernew;
								
								if($pagernew>5){
									//$output .='<li class="pg-btn" style="padding:5px 6px; cursor: pointer" lifestage="0" typevalue="'.$dvaluen.'" pagerv="1" search="0">1</li>...';
								}
								/** added by dhiraj **/
								
								for ($i=max(1, $pagernews - 0); $i <= min($pagernews + 5, $num_of_pages); $i++) {
									//if(isset($_GET['page']) && $_GET['page']==$i) $bold='active';
									//else $bold='';
									
									$output .='<li class="pg-btn '.($pagernew == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer '.$bold.'" lifestage="0" typevalue="'.$dvaluen.'" pagerv="'.$i.'" search="0">'.$i.'</li>';
								}
								if ($pagernew < $num_of_pages) {
                                                                        $output .='<li test="sdfdsdf">
								              	<div class="next-btn" lifestage="0" type="'.$ttvalue.'" pager="'.($pagernew+1).'" search="0">
								              		<div style="float:left;margin-right: 5px;margin-left: 10px;margin-top: 4px; cursor: pointer;"><span class="hidden-xs">Next</span></div>
								              		<div style="float:left;margin-top: 10px;  cursor: pointer;"><span class="btn-next"></span></div>
								              	</div>
								              </li>';
								}
								if($num_of_pages > 1){
								$output .='</ul>
								            <p>
								            	<span>of&nbsp;</span>
								            	<span class="ng-binding" dvalue="sdfsd">'.$num_of_pages.'</span>
								            	<span>&nbsp;pages</span>
								            </p>
								          </nav>';
								} else {
								$output .='</ul>
								            <p>
								            	<span>of&nbsp;</span>
								            	<span class="ng-binding" dvalue="sdfsd">'.$num_of_pages.'</span>
								            	<span>&nbsp;page</span>
								            </p>
								          </nav>';
								}
				         		$output .='</div>';
				         		$output .='<!-- pagination box ends -->';
			         	$output .='</div>';
					$output .='</div>';
				$output .='</div>';
			$output .='</div>';
		$output .='</section>';
		return stripslashes( $output );
	} 



	 $url = $_SERVER['REQUEST_URI'];
	 $url = str_replace('?sortBy=-views&pager=1', '', $url);
if(strpos($url,"resources") !== false){
	$uri = trim(strtok($url, '?'));
	$uri1 = explode('?', $url);
	$uri2 = explode('=', $uri1[1]);
	    $tags1 = explode('&', $uri2[5]);
	    $tagname1 = explode('&', $uri2[7]);

	if(empty($tags1[0]) or $tags1[0] == "" ){
		$tags = '0';
	}else {
		$tags = $tags1[0];
	}

	if(empty($tagname1[0]) or $tagname1[0] == "" ){
		$tagname = '0';
	}else {
		$tagname = $tagname1[0];
	}


	

	$val = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
$url2 = substr( $val, 0, strrpos( $val, "?"));
$resources = 'https://'.$_SERVER['HTTP_HOST'].'/resources/';
if($url2 == $resources){
	/*echo "its working";
	echo $url2;*/
?>
  <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script type="text/javascript">
	var flag = true;
	if(flag){  
	$(document).ready(function(){
		var tagVal = "<?php echo $tags?>";
		//alert(tagVal);
		if(tagVal == "0" || tagVal == "") {
			$(".autocomplete-tag-list").hide();
		}

		var TagArray = "<?php echo $tags?>";
		const tgArray = "<?php echo $tags?>";
		var TagNameArray = "<?php echo $tagname?>";
		/*var tgNameArray = TagNameArray.reverse();*/
		var array = TagArray.split(",");
		var namearray = TagNameArray.split(",").reverse();

		var idArray = array.reverse();
		var tgnameArray = namearray.reverse();



	var tagsValue = (new URL(location.href)).searchParams.get('tags').split(",");

	var tagArrayLength = tagsValue.length;
	$('.tagcount').html(tagArrayLength+ " selected");
		
console.log(array, array.reverse());
		console.log(TagNameArray);
		$.each(array,function(i, keyword){
			$.each(namearray,function(j, keywordName){
				if(i == j && keyword != "0" && keywordName != "0" && keywordName != ""){
					keywordName = keywordName.replace('%20', ' ');
				   $(".autocomplete-tag-list").prepend('<span class="tag ng-scope tgbtn'+keyword+'"><span tagid="'+keyword+'" tagname="'+keywordName+'" class="tagbtn">'+keywordName+'</span><div class="close closetag" tagid="'+keyword+'" url="http://www.devxekera.com/resources/" tagname="'+keywordName+'" tags="'+TagArray+'"></div></span>').before();
				   //console.log(i+"%%"+j);
				}
			});
		});
	});
	flag=false;  
}
</script>


<?php
} else {
	/*echo "its not working";
	echo $url2;*/
}
}
