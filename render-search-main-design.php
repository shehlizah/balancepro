


<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
/* Database config */
$db_encoding    = 'utf8'; 
$user = 'balancepro';
/* End config */


$dsn = 'mysql:host='.$host.';dbname='.$database.";charset=UTF8";

$db = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$wlwId = $_SESSION['white_label_website_id'];

$getWlwData = "SELECT * FROM wp_white_label_websites WHERE white_label_website_id = '$wlwId'";			
			
	        $wlwResults = $db->prepare($getWlwData);
			
			$wlwResults->execute();
			$wlwResult[]=$wlwResults->fetchAll();

			if(empty($wlwResult[0][0]['resources_page_html']) || $wlwResult[0][0]['resources_page_html'] == ''){


?>
<p style="margin-top: 139px;"></p>
		<!--article name="" class="text-block article default-content-style" aria-label="module for article Resources"> 
			<div class="container">    
				<div class="row">
					<h1 class="text-info text-center">Resources</h1>
					<p>
						<strong>Everything you need to master your money.</strong>
					</p>
					<p>We’ve made it easy to access the financial education resources that matter to you. You can search by keyword, 
						resource type, topic, or any combination of the&nbsp;three. You can learn more about the search features by 
						<a href="<?=$parent_url?>/resources/search-help/">visiting&nbsp;the search help page</a>.
					</p>
    			</div>  
			</div>
		</article-->
<?php
			} else {
				?>
				<p style="margin-top: 139px;"></p>
				<?php
				echo str_replace('#b3481b','',removeResourseUrl($wlwResult[0][0]['resources_page_html']));
			}
?>

<?php
	 $url = $_SERVER['REQUEST_URI'];
	 
		//if($pager !=''){
		if(isset($_GET['page']) && $_GET['page'] !=''){
			$pagernew = $_GET['page'];
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
?>
	<?php 
	 $url = str_replace('&sortBy=-views&pager=1', '', $url);
	$uri = trim(strtok($url, '?'));
	$uri1 = explode('?', $url);
	$uri2 = explode('=', $uri1[1]);

	
	    $resourcetypes1 = $_GET['resourcetypes'];
	    $lifestage1 = $_GET['lifestage'];
	    $sortid1 = $_GET['sortid'];
	    $search1 = $_GET['search'];
	    $tags1 = $_GET['tags'];
	    $page1 = $_GET['page'];
	    $tagname1 = $_GET['tagnames'];
		
		/*
	echo "<pre>";
	print_r($resourcetypes1[0]);
	echo "</pre>";*/

	if(empty($_GET['resourcetypes']) || $_GET['resourcetypes'] == ""  || $_GET['resourcetypes'] == "resources" ){
		$resourcetypes = '0';
	} else {
		$resourcetypes = $_GET['resourcetypes'];
	}
	if(empty($_GET['lifestage']) || $_GET['lifestage'] == "" ){
		$lifestage = '0';
	}else {
		$lifestage = $_GET['lifestage'];
	}

	if(empty($_GET['sortid']) || $_GET['sortid'] == "" ){
		$sort = '0';
	}else {
		$sort = $_GET['sortid'];
	}

	if(empty($_GET['search']) || $_GET['search'] == "" ){
		$search = '0';
	} else {
		$search = $_GET['search'];
	}

	if(empty($_GET['tags']) || $_GET['tags'] == "" ){
		$tags = '0';
	}else {
		$tags = $_GET['tags'];
	}

	if(empty($_GET['tagnames']) || $_GET['tagnames'] == "" ){
		$tagname = '0';
	}else {
		$tagname = $_GET['tagnames'];
	}
	if(empty($_GET['page']) || $_GET['page'] == "" ){
		$page = '0';
	}else {
		$page = $_GET['page'];
	}
	$tagnames = $tagname;

	
		if($page != '0'){
			$page = $_GET['page'];
		} else {
			$page = 1;
		}
	
	        $list = 'true';
	        $level = '100';
	        $pgorder = '1';
	        $rtypes = $resourcetypes;
			
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
			$keywords= explode(',', $tags);
			$advancedkeywords = implode("', '", $keywords);
	        $limitQuery = ' limit 9';
	        $dvalue = $search;
	        $pagernew = $page;
			
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
		 if($dvalue == "0" and $resourcetypes == '0' and $lifestage == '0'){
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
	        $query = $select . $from . $where . $order . $limitQuery;
			
			
	        $results = $db->prepare($query);
			
			$results->execute();
			$resourceResult[]=$results->fetchAll();
			
			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
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
	      
	        $query12 = $select1 . $from . $where . $order;
			
			$query1 = $db->prepare($query12);
			$query1->execute();
			$resultsT = $results->rowCount();
			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
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
	        $query = $select . $from . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);
			
			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'views'){		
			
			
	        $select = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,wc.view_count";
	        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
			$from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		   $where = " WHERE  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish' AND w.page_order = '$pgorder'";
	        
	          
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
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);

			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,wc.view_count ";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from .$ljoin. $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'date'){
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt";
	        $from = " FROM wp_resources as w, wp_posts AS P";
	        $where = " WHERE  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.status = 'publish' AND w.page_order = '$pgorder'";
	        
	          
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
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);

			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt ";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }
            // if search is 0 and life stage is 0 and resourcetype is 0 ends
	}else 
	     if($dvalue != "0"){

            // if search has value starts
	    if($sort == '0'){
			//echo "DS10";
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
	        $order = " order by title_match desc, title_rough_match desc, relevancy desc";
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);
			
			$select1 = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	       
	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'relevance'){
			//echo "DS1R";
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
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);
			
			$select1 = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'views'){
			//echo "DS1v";
	        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,wc.view_count";
	        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
			$from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		    $where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		    //$where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
	        
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
	        $query = $select . $from .$ljoin. $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);
			
			$select1 = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,wc.view_count";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $ljoin.$where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'date'){
			//echo "DS1d";
	        $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt";
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
	        $order = "  ORDER BY P.post_date_gmt DESC ";
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);
			
			$select1 = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }
            // if search has value ends
	} else {
            // if search is 0 starts
	    if($sort == '0'){
			//echo "SORT0";
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
	        //$result = $db->prepare($query);
			
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);

			
			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
	      
	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'relevance'){
			//echo "SORTR";
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
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);
			
			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'views'){
			//echo "SORTV";
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,wc.view_count ";
	        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
	        $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
						
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
	        
	          
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
	        $query = $select . $from .$ljoin. $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);

			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ,wc.view_count";

	        //$query1 = $select1 . $from . $where . $order;
	         $query12 = $select1 . $from .$ljoin. $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }else if($sort == 'date'){
			//echo "SORTD";
	        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt";
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
	        $order = "  ORDER BY P.post_date_gmt DESC ";
	        //$limit = 'limt 0, 9';
	        $query = $select . $from . $where . $order . $limitQuery;
	        //$result = $db->prepare($query);
	        $results = $db->prepare($query);
			$results->execute();
			$resourceResult[]=$results->fetchAll();
	        //$results=$wpdb->get_results($query, object);

			$select1 = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,P.post_date_gmt ";

	        //$query1 = $select1 . $from . $where . $order;
	        $query12 = $select1 . $from . $where . $order;
			$query1 = $db->prepare($query12);
			$query1->execute();

			$total = $query1->rowCount();
	        //$total = $wpdb->get_var($query1);
	        $num_of_pages = ceil( $total / $limit );
	    }
	}
            // if search is 0 ends
		
            // get all life stage type starts
	$life_stage_type = 'life_stage';
	
	$sqllifestages = "SELECT DISTINCT P.ID as id, P.post_title as tag from wp_posts as P join wp_postmeta as PM on P.ID = post_id  where P.post_type = 'life_stage' and PM.`meta_key` = '_wp_page_template' and PM.meta_value = '$life_stage_type' AND P.post_status='publish'";
	//$sqlresults=$wpdb->get_results($sqllifestages, object);    
	        $sqlresults = $db->prepare($sqllifestages);
			$sqlresults->execute(); 
			$lifestageResult[]=$sqlresults->fetchAll(); 
			
            // get all life stage type ends
		if($search =='0'){
			$input_search_value = "";
		}else {
			$input_search_value = $search;
		}

?>
	<section aria-label="search container filter">
	    <div class="searchContainer">
		    <div class="container background-white">
			    <div class="row">
			    <div class="inner-row">
			    <aside id="sidebar" class="col-sm-4">
			    <!-- sidebar code starts --
			    <div class="aside-drop"><div class="ovh-holder">
				<div class="aside-holder ">-->
			   <!-- Mobile view filter code starts from megha -->

			    <div class="opener-block text-center">
			    <a href="#" class="filter-drop-opener text-warning">VIEW FILTERS</a>
			    </div>
			    <!-- Mobile view filter code Enddd from megha -->
			   <!-- sidebar code starts -->
			   <div class="aside-drop"><div class="ovh-holder">
				<div class="aside-holder ">
				<!-- Mobile view filter code starts from megha -->

				  <div class="apply-block visible-xs">
                   	<a href="#" ng-click="resetFilters($event)" class="filter-drop-close">CANCEL</a>
                       	<a href="#" ng-click="apply($event)" class="filter-drop-close">APPLY</a>
                          </div>

                   <!-- Mobile view filter code Enddd from megha -->
				<h2 class="aside-title hidden-xs text-center h3">FILTERS</h2>
                <h3 class="aside-title-xs visible-xs text-center">Select Filters</h3>
				<div class="aside-form ng-valid ng-dirty ng-valid-parse">
				<fieldset>
				<ul class="aside-filter same-height-holder" style="border-top:1px solid #a7bab9; ">
				<!-- filter type panel starts -->
				<li>
				<a href="#" class="filter-opener visible-xs">RESOURCE TYPES<span class="selected ng-binding">1 selected</span><span class="custom-caret visible-xs"><img width="19" height="11" alt="image description" src="assets/img/drop-arrow.svg"></span> <!-- <span class="custom-caret visible-xs">All Types</span> -->
				</a>
				<span class="filter-opener hidden-xs">RESOURCE TYPES</span>
				<ul class="filter-drop" style="position: absolute; top: -9999px; left: -9999px; width: 277px;">
			    <?php if($resourcetypes == '0'){ ?>
					<li>
					<div class="col-sm-12">
						<div class="input-group">
							<label for="select-all-types">
								<div id="select-all-types" lifestage="<?php echo $lifestage; ?>" active="1" dvalue="0" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" class="ng-pristine ng-valid">
								<span class="fake-input"></span>
								<span class="fake-label text-center selectalltypes">
								<span class="text">All Types</span>
								</span>
							</label>
						</div>
					</div>
				</li>
				<?php } else { ?>
					<li>
					<div class="col-sm-12">
						<div class="input-group">
							<label for="select-all-types">
								<div id="select-all-types" lifestage="<?php echo $lifestage; ?>" active="0" dvalue="0" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" class="ng-pristine ng-valid">
								<span class="fake-input"></span>
								<span class="fake-label text-center selectalltypes">
								<span class="text">All Types</span>
								</span>
							</label>
						</div>
					</div>
				</li>
<?php 				} ?>
				


				<li>
				<!--icons starts -->
					<div class="col-xs-6">
						<!-- sidebar small menu starts -->

						<?php if($resourcetypes == 'article'){ ?>
						<div class="input-group ng-scope">
							<label for="cb-article">
								<div id="cb-article" class="siderbar-small-category" lifestage="<?php echo $lifestage; ?>" active="1" dvalue="article" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>" inform="rsearchbtn">
									<span class="resourcetype fake-label same-height same-height-left same-height-right active-article" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
										<span class="icon-article tagicon" style="color: #fff;"></span>
										<span class="text">Articles </span>
									</span>
								</div>
							</label>
						</div>
						<?php } else { ?>
							<div class="input-group ng-scope">
							<label for="cb-article">
								<div id="cb-article" class="siderbar-small-category" lifestage="<?php echo $lifestage; ?>" active="0" dvalue="article" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>" inform="rsearchbtn">
									<span class="resourcetype fake-label same-height same-height-left same-height-right active-article" style="height: 28px;">
										<span class="icon-article tagicon"></span>
										<span class="text">Articles </span>
									</span>
								</div>
							</label>
						</div>
						<?php 				} ?>

					<!-- sidebar small menu ends -->
					</div>
					<!-- icons ends -->
					<!--icons starts -->
					<div class="col-xs-6">
						<!-- sidebar small menu starts -->
						<?php if($resourcetypes == 'calculator'){ ?>
						<div class="input-group ng-scope">
							<label for="cb-calculator">
								<div id="cb-calculator" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" active="1" dvalue="calculator" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>" >
									<span class="resourcetype fake-label same-height same-height-left same-height-right  active-calculator" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
										<span class="icon-calculator tagicon" style="color: #fff;"></span>
										<span class="text">Calculators </span>
									</span>
								</div>
								</label>
								</div>
							<?php } else { ?>
						<div class="input-group ng-scope">
							<label for="cb-calculator">
								<div id="cb-calculator" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" active="0" dvalue="calculator" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>" >
									<span class="resourcetype fake-label same-height same-height-left same-height-right  active-calculator" style="height: 28px;">
										<span class="icon-calculator tagicon"></span>
										<span class="text">Calculators </span>
									</span>
								</div>
								</label>
								</div>
							<?php 				} ?>
								<!-- sidebar small menu ends -->
								</div>
								<!-- icons ends -->
								<!--icons starts -->
								<div class="col-xs-6">
								<!-- sidebar small menu starts -->
						<?php if($resourcetypes == 'video'){ ?>
								<div class="input-group ng-scope">
									<label for="cb-video">
										<div id="cb-video" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="video" active="1" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											<span class="resourcetype fake-label same-height same-height-left same-height-right  active-video" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
												<span class="icon-video tagicon" style="color: #fff;"></span>
												<span class="text">Videos </span>
											</span>
										</div>
										</label>
								</div>
							<?php } else { ?>

								<div class="input-group ng-scope">
									<label for="cb-video">
										<div id="cb-video" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="video" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											<span class="resourcetype fake-label same-height same-height-left same-height-right  active-video" style="height: 28px;">
												<span class="icon-video tagicon"></span>
												<span class="text">Videos </span>
											</span>
										</div>
										</label>
								</div>
							<?php } ?>
								<!-- sidebar small menu ends -->
								</div>
								<!-- icons ends -->
								<!--icons starts --
								<div class="col-xs-6">
								   sidebar small menu starts -->
						<?php if($resourcetypes == 'video'){ ?>
									<div class="input-group ng-scope">
										<label for="cb-newsletter">
											<div id="cb-newsletter" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="newsletter" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-newsletter" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											        <span class="icon-newsletter tagicon"></span>
											        <span class="text">Newsletters </span>
											    </span>
											</div>
										</label>
									</div>
								<?php } else { ?>

									<div class="input-group ng-scope">
										<label for="cb-newsletter">
											<div id="cb-newsletter" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="newsletter" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-newsletter" style="height: 28px;">
											        <span class="icon-newsletter tagicon"></span>
											        <span class="text">Newsletters </span>
											    </span>
											</div>
										</label>
									</div>
							<?php 				} ?>
								    <!-- sidebar small menu ends --
								
								</div>
								<!-- icons ends -->
								<!--icons starts -->
								<div class="col-xs-6">
								<!-- sidebar small menu starts -->
						<?php if($resourcetypes == 'podcast'){ ?>
									<div class="input-group ng-scope">
										<label for="cb-podcast">
											<div id="cb-podcast" class="siderbar-small-category" inform="rsearchbtn" active="1" lifestage="<?php echo $lifestage; ?>" dvalue="podcast" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-podcast" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											        <span class="icon-podcast tagicon" style="color: #fff;"></span>
											        <span class="text">Podcasts </span>
											    </span>
											</div>
										</label>
									</div>
								<?php } else { ?>

									<div class="input-group ng-scope">
										<label for="cb-podcast">
											<div id="cb-podcast" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="podcast" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											    <span class="resourcetype fake-label same-height same-height-left same-height-right  active-podcast" style="height: 28px;">
											        <span class="icon-podcast tagicon"></span>
											        <span class="text">Podcasts </span>
											    </span>
											</div>
										</label>
									</div>
								<?php 				} ?>
								<!-- sidebar small menu ends -->
								</div>
								<!-- icons ends -->
								<!--icons starts -->
								    <div class="col-xs-6">
								    <!-- sidebar small menu starts -->
						<?php if($resourcetypes == 'toolkit'){ ?>
									    <div class="input-group ng-scope">
											<label for="cb-toolkit">
											    <div id="cb-toolkit" class="siderbar-small-category" inform="rsearchbtn" active="1" lifestage="<?php echo $lifestage; ?>" dvalue="toolkit" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-toolkit" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-toolkit tagicon" style="color: #fff;"></span>
											            <span class="text">Toolkits </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php } else { ?>
									    <div class="input-group ng-scope">
											<label for="cb-toolkit">
											    <div id="cb-toolkit" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="toolkit" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-toolkit" style="height: 28px;">
											            <span class="icon-toolkit tagicon"></span>
											            <span class="text">Toolkits </span>
											        </span>
											    </div>
											</label>
										</div>

									<?php 				} ?>
								   	<!-- sidebar small menu ends -->
									</div>
								    <!-- icons ends -->
								    <!--icons starts -->
								    <div class="col-xs-6">
								    	<!-- sidebar small menu starts -->

						<?php if($resourcetypes == 'booklet'){ ?>
									    <div class="input-group ng-scope">
											<label for="cb-booklet">
											    <div id="cb-booklet" class="siderbar-small-category" inform="rsearchbtn" active="1" lifestage="<?php echo $lifestage; ?>" dvalue="booklet" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-booklet" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-booklet tagicon" style="color: #fff;"></span>
											            <span class="text">Booklets </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php } else { ?>

									    <div class="input-group ng-scope">
											<label for="cb-booklet">
											    <div id="cb-booklet" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="booklet" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-booklet" style="height: 28px;">
											            <span class="icon-booklet tagicon"></span>
											            <span class="text">Booklets </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php 				} ?>
								        <!-- sidebar small menu ends -->
									
								    </div>
								    <!-- icons ends -->
								    <!--icons starts -->
								    <div class="col-xs-6">
								    	<!-- sidebar small menu starts -->

						<?php if($resourcetypes == 'worksheet' || $resourcetypes == 'Worksheet'){ ?>
									    <div class="input-group ng-scope">
											<label for="cb-worksheet">
											    <div id="cb-worksheet" class="siderbar-small-category" inform="rsearchbtn" active="1" lifestage="<?php echo $lifestage; ?>" dvalue="worksheet" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-worksheet" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-worksheet tagicon" style="color: #fff;"></span>
											            <span class="text">Worksheets </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php } else { ?>

									    <div class="input-group ng-scope">
											<label for="cb-worksheet">
											    <div id="cb-worksheet" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="worksheet" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-worksheet" style="height: 28px;">
											            <span class="icon-worksheet tagicon"></span>
											            <span class="text">Worksheets </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php 				} ?>
								        <!-- sidebar small menu ends -->
									
								    </div>
								    <!-- icons ends -->
								    <!--icons starts -->
								    <div class="col-xs-6">
								    	<!-- sidebar small menu starts -->

						<?php if($resourcetypes == 'checklist'){ ?>
									    <div class="input-group ng-scope">
											<label for="cb-checklist">
											    <div id="cb-checklist" class="siderbar-small-category" inform="rsearchbtn" active="1" lifestage="<?php echo $lifestage; ?>" dvalue="checklist" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-checklist" style="height: 28px;background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
											            <span class="icon-checklist tagicon" style="color: #fff;"></span>
											            <span class="text">Checklists </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php } else { ?>

									    <div class="input-group ng-scope">
											<label for="cb-checklist">
											    <div id="cb-checklist" class="siderbar-small-category" inform="rsearchbtn" lifestage="<?php echo $lifestage; ?>" dvalue="checklist" pager="<?php echo $pagernew; ?>" tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" tagnames="<?php echo $tagnames; ?>">
											        <span class="resourcetype fake-label same-height same-height-left same-height-right  active-checklist" style="height: 28px;">
											            <span class="icon-checklist tagicon"></span>
											            <span class="text">Checklists </span>
											        </span>
											    </div>
											</label>
										</div>
									<?php 				} ?>
								        <!-- sidebar small menu ends -->
									
								    </div>
								    <!-- icons ends -->
								       	</li>
								                            </ul>
								                        </li>
								              		<!-- filter type panel ends -->
								              		<!-- autosuggest panel starts -->
								              			<li class="autocomplete-tag-holder"><a href="#" class="filter-opener visible-xs">TAGS<span class="selected ng-binding tagcount">0 selected</span><span class="custom-caret visible-xs"><img width="19" height="11" alt="image description" src="assets/img/drop-arrow.svg"></span></a><span class="filter-opener hidden-xs">Tags</span>
								                                <ul class="filter-drop" style="position: absolute; top: -9999px; left: -9999px; width: 277px;">
								                                  <li>
								                                    <div class="col-xs-12">
								                                      <div class="input-group">
								                                        <input type="search"  placeholder="Enter Keywords"class="autocomplete-tag-input ng-pristine ng-untouched ng-valid ui-autocomplete-input tagsbox" autocomplete="off" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  pager="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
								                                        <button class="search-btn"><span class="icon-search"></span></button>
								                                      </div>
								                                      <div class="autocomplete-tag-list"
								                                       resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  pager="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
								                                      	<div class="tag-search-keyword"  resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  pager="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
								                                      	</div>
								                                      </div>
								                                    </div>
								                                  </li>
								                                </ul>
								                              </li>
								              		<!-- autosuggest panel ends -->
													
											<!-- life stages panel starts -->
                                                                                        <li>
											<a href="#" class="filter-opener visible-xs">
  											LIFE STAGES
  											<span class="selected ng-binding">1 selected</span>
  											<span class="custom-caret visible-xs">
    											<img width="19" height="11" alt="image description" src="assets/img/drop-arrow.svg">
  											</span>
											</a>
                                                                                        <span class="filter-opener hidden-xs">LIFE STAGES</span>
  											<ul class="filter-drop same-height-holder" style="position: absolute; top: -9999px; left: -9999px; width: 277px;">
    											<li>
											<div class="col-sm-12">
											<div class="input-group">
											<label for="select-stage-all">
											<span class="fake-input"></span>

						<?php if($lifestage == '0'){ ?>
											<span class="fake-label text-center" id="select-stage-all" active="1" dvalue="0" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  pager="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
 											<span class="text">All Stages</span>
											</span>
										<?php } else { ?>
											<span class="fake-label text-center" id="select-stage-all" active="0" dvalue="0" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  pager="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
 											<span class="text">All Stages</span>
											</span>

										<?php 				} ?>
 											</label>
											</div>
											</div>
											</li>
											<li>
											<?php //foreach( $lifestageResult as $sqlresult => $value )
												for($l=0;$l<count($lifestageResult[0]);$l++)	
							                	{
												
							                    	//$lifestageid = $value[$sqlresult]['id'];
							                   		//$lifestagetagname = $value[$sqlresult]['tag'];
													$lifestageid = $lifestageResult[0][$l]['id'];
													$lifestagetagname = $lifestageResult[0][$l]['tag'];
													
													
?>
												<div class="col-xs-6">
        												<div class="input-group ng-scope lifestagec checkattr<?php echo $lifestageid; ?>" dvalue="<?php echo $lifestageid; ?>" page="<?php echo $pagernew; ?>" active="0" resourcetypes="<?php echo $resourcetypes; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
          												<label for="stage-<?php echo $lifestageid; ?>" class="text-center label-text">
            											        <div id="stage-<?php echo $lifestageid; ?>" class="ng-pristine ng-untouched ng-valid"></div>
            												<span class="fake-input"></span>
            												<?php if($lifestage == $lifestageid) { ?>
            												<span class="fake-label text-center lifestage<?php echo $lifestageid; ?> lfstyle" style="background: rgb(2, 166, 156); color: rgb(255, 255, 255); cursor: pointer;">
              												<span class="center1  ng-binding" ><?php echo $lifestagetagname; ?></span>
            												</span>
            												<?php } else { ?>
            												<span class="fake-label text-center lifestage<?php echo $lifestageid; ?> lfstyle">
              												<span class="center1  ng-binding" ><?php echo $lifestagetagname; ?></span>
            												</span>
            												<?php 				} ?>
          												</label>
        												</div>		
        												</div>
											<?php 				} ?>
											</li>
                                            <!-- life stages panel ends -->
								              	</ul>
								              </fieldset>            
								        </div>
								        </div></div></div>
			        			<!-- sidebar code ends -->
			        		</aside>
			        	</div>
			        	<div id="content" class="content-section col-sm-8">
			        			 <div class="resource-column same-height-holder">
				         			<!-- search input box starts -->
				         				<form class="sort-form">
									                  <fieldset>
									        <?php if($search == "0"){ ?>
									                    <div class="col-sm-8">
									                      <div class="input-group">
									                        <input type="search" aria-label="Enter Keywords field." placeholder="Enter Keywords" ng-model="filters.query" class="form-control" id="resource-search-input" active="0" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  page="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" value="">
									                      </div>
									                    </div>
				         			<?php } else { ?>
				         				<div class="col-sm-8">
									                      <div class="input-group">
									                        <input type="search" aria-label="Enter Keywords field." placeholder="Enter Keywords" ng-model="filters.query" class="form-control" id="resource-search-input" active="1" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  page="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>" value="<?php echo $search; ?>">
									                      </div>
									                    </div>
				         			<?php 				} ?>
				         			<?php if($sort == "0"){ ?>
				         				<div class="col-sm-4">
														<div class="input-group">
														<select class="form-control sort-form-select" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  page="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
														<!--  <option value="nothing">Sort By</option>  -->
															<option value="0">Relevance</option>
															<option value="views">Most Popular</option>
															<option value="date">Most Recent</option>
														</select>
									                      </div>
									                    </div>
				         			<?php } else if($sort =="views"){ ?>
				         				<div class="col-sm-4">
														<div class="input-group">
														<select class="form-control sort-form-select" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  page="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
														<!--  <option value="nothing">Sort By</option>  -->
															<option value="views">Most Popular</option>
															<option value="0">Relevance</option>
															<option value="date">Most Recent</option>
														</select>
									                      </div>
									                    </div>
				         			<?php } else { ?>
				         				<div class="col-sm-4">
														<div class="input-group">
														<select class="form-control sort-form-select" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  page="<?php echo $pagernew; ?>"  tags="<?php echo $tags; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
														<!--  <option value="nothing">Sort By</option>  -->
															<option value="date">Most Recent</option>
															<option value="0">Relevance</option>
															<option value="views">Most Popular</option>
														</select>
									                      </div>
									                    </div>
				         			<?php 				} ?>
														
									                  </fieldset>
									                </form>
				         			<!-- search input box ends -->
				         			<!-- main content starts -->
				         			<div class="resource-column-new same-height-holder hidden">
				         			</div>
				         				<div class="resource-column same-height-holder content-inner-page">
							               <?php
											foreach( $resourceResult[0] as $result )
							                {
							                    $titlen = $result['post_title'];
							                    $dvaluen = $result['type'];
							                    $slug = $result['slug'];
                                                $postid = $result['wp_post_id'];
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
												$viewUrl = $resources.$seo_dvalue.$slug;												?>
							                    <!-- resource in resources starts -->
							                              
<div class="col-sm-6 col-md-4">
<a href="<?php echo $viewUrl; ?>" target="_self">
							                              <!-- resource block starts -->
							                                <div class="resource-block">
	
							                                  <div class="img-holder same-height tiles"><span class="icon-<?php echo $dvaluen; ?>"></span></div>
							                                  <div class="text-holder">
							                                    <p class="dot-holder"><?php echo $titlen; ?></p>
							                                    <div class="btn-tag same-height same-height-left same-height-right tile life<?php echo $dvaluen; ?>" dvalue="<?php echo $postid; ?>">
<?php 
$tagIdName = "SELECT tagWTR.term_taxonomy_id,tagWTR.object_id,tagW.term_id,tagW.name from wp_term_relationships as tagWTR join wp_terms as tagW on tagWTR.term_taxonomy_id = tagW.term_id where tagWTR.object_id = '$postid'";
	//$sqlresults=$wpdb->get_results($sqllifestages, object);    
	        $tagINdata = $db->prepare($tagIdName);
			$tagINdata->execute(); 
			$tagResult[]=$tagINdata->fetchAll(); 

$lastTag = current(array_slice($tagResult, -1));
			
			foreach ($lastTag as $tagKey => $tagVal) {
				if($tagKey <= 2) {
?>
															
																
<span class="tag ng-binding ng-scope tag-click" url="<?php echo $escaped_url; ?>" tagname="<?php echo $tagVal['name']; ?>" pager="<?php echo $pagernew; ?>" type="<?php echo $type; ?>" dvalue="<?php echo $tagVal['term_id']; ?>" tagid="<?php echo $tagVal['term_id']; ?>" resourcetypes="<?php echo $resourcetypes; ?>" lifestage="<?php echo $lifestage; ?>"  tags="<?php echo $tagVal['term_id']; ?>" search="<?php echo $search; ?>" sort="<?php echo $sort; ?>">
                                                        	<?php echo $tagVal['name']; ?><b style="display: none;"><?php echo $tagKey;?></b>
															</span>
<?php
				}
}
?>
                                                         </div>
														 <?php
														 $viewUrl = $resources.$seo_dvalue.$slug;
														 
														// $variable = resourcesUrl($viewUrl);
														 //echo $variable;
														 ?>

							                                  </div>
							                                  <span class="icon-lock" style="display: none;"></span>
							                                </div>
							                                <!-- resource block ends -->
</div>							                              
<!-- resource resource in resources ends -->
							                <?php } ?>
							              </div>
				         			<!-- main content ends -->
				         		</a></div>
													
													
				         <!-- Pagination box starts -->
<div style="padding:10px 5px; clear: both;" id="pagination-box-n" class="hidden"></div>
<div style="padding:10px 5px; clear: both;" id="pagination-box" type="<?php echo $type; ?>" dvalue="<?php echo $dvaluen; ?>" pagination-box="render-search-main-design" type="<?php echo $ttvalue; ?>" pager="<?php echo $pagernew; ?>" numpages="<?php echo $num_of_pages; ?>">
    <nav aria-label="balance pager m14-m15" balance-pager="" class="paging-holder clear">
        <ul class="pagination">
            <?php 
            if ($type == '') { 
                $ttvalue = '0';
            } else if ($type == '0') { 
                $ttvalue = '0';
            } else {
                $ttvalue = $dvaluen;
            }

            // "Previous" button
            if ($pagernew > 1) { ?>
                <li>
                    <div class="prv-btn" lifestage="0" type="<?php echo $ttvalue; ?>" pager="<?php echo ($pagernew-1); ?>" search="0">
					<div style="float: left; margin-top:4px; margin-right:4px;   cursor: pointer;">
        <span class="btn-prev"></span>
    </div>
    <div style="float: left;  cursor: pointer; ">
        <span class="hidden-xs"></span>
    </div>
                    </div>
                </li>
            <?php } ?>

            <?php 
            // Handle dynamic pagination with ellipsis
            if ($pagernew == '') $pagernew = 1;
            $maxVisible = 5; // Maximum number of pages visible around the current page
            $startPage = max(1, $pagernew - 2);
            $endPage = min($num_of_pages, $pagernew + 2);

            // Display the first page and ellipsis if needed
            if ($startPage > 1) { ?>
                <li class="pg-btn" style="padding:5px 6px; font-size: 16px ; cursor: pointer" lifestage="0" typevalue="<?php echo $dvaluen; ?>" pagerv="1" search="0">1</li>
                <?php if ($startPage > 2) { ?>
                    <li class="pg-btn disabled" style="cursor: default; color:#6BD9DE;">...</li>
                <?php }
            }

            // Display the page numbers in range
            for ($i = $startPage; $i <= $endPage; $i++) { ?>
                <li class="pg-btn <?php echo ($pagernew == $i ? 'active' : ''); ?>" style="padding:5px 6px; font-size: 16px ; cursor: pointer" lifestage="0" typevalue="<?php echo $dvaluen; ?>" pagerv="<?php echo $i; ?>" search="0"><?php echo $i; ?></li>
            <?php }

            // Display ellipsis and the last page if needed
            if ($endPage < $num_of_pages) {
                if ($endPage < $num_of_pages - 1) { ?>
                    <li class="pg-btn disabled" style="cursor: default; color:#6BD9DE;">...</li>
                <?php } ?>
                <li class="pg-btn" style="padding:5px 6px; font-size: 16px ; cursor: pointer" lifestage="0" typevalue="<?php echo $dvaluen; ?>" pagerv="<?php echo $num_of_pages; ?>" search="0"><?php echo $num_of_pages; ?></li>
            <?php } 

            // "Next" button
            if ($pagernew < $num_of_pages) { ?>
                <li>
                    <div class="next-btn" lifestage="0" type="<?php echo $ttvalue; ?>" pager="<?php echo ($pagernew+1); ?>" search="0">
					<div style="float: left;   cursor: pointer;  align-items: center;">
        <span class="hidden-xs"></span>
    </div>
    <div style="float: left;  cursor: pointer;   align-items: center; margin-top:4px; margin-left:4px;">
        <span class="btn-next"></span>
    </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </nav>
</div>
<!-- Pagination box ends -->


			         	</div>
					</div>
				</div>
			</div>
		</section>



     </main>
<?php
	 $url = $_SERVER['REQUEST_URI'];
	 $url = str_replace('?sortBy=-views&pager=1', '', $url);
	$uri = trim(strtok($url, '?'));
	$uri1 = explode('?', $url);
	$uri2 = explode('=', $uri1[1]);
		
	
	    $resourcetypes1 = $_GET['resourcetypes'];
	    $lifestage1 = $_GET['lifestage'];
	    $sortid1 = $_GET['sortid'];
	    $search1 = $_GET['search'];
	    $tags1 = $_GET['tags'];
	    $page1 = $_GET['page'];
	    $tagname1 = $_GET['tagnames'];

	if(empty($_GET['tags']) || $_GET['tags'] == "" ){
		$tags = '0';
	}else {
		$tags = $_GET['tags'];
	}

	if(empty($_GET['tagnames']) || $_GET['tagnames'] == "" ){
		$tagname = '0';
	}else {
		$tagname = $_GET['tagnames'];
	}

	$val = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	//echo "$$".$val."$$";
$url2 = substr( $val, 0, strrpos( $val, "?"));
$newurl = explode("?", $val);
//echo $url2;
if($url2 == $newurl[0]){
	/*echo "its working";
	echo $url2;*/
?>
  <!--script src="https://code.jquery.com/jquery-3.5.0.js"></script-->
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

//alert(tagVal);
//if(tagVal != "0" || tagVal != "") {
if(tagVal.length > 1){
                var tagsValue = (new URL(location.href)).searchParams.get('tags').split(",");
        var tagArrayLength = tagsValue.length;
        $('.tagcount').html(tagArrayLength+ " selected");
}
		
console.log(array, array.reverse());
		console.log(TagNameArray);
		$.each(array,function(i, keyword){
			$.each(namearray,function(j, keywordName){
				if(i == j && keyword != "0" && keywordName != "0" && keywordName != ""){
					keywordName = keywordName.replace('%20', ' ');
				   $(".autocomplete-tag-list").append('<span class="tag ng-scope tgbtn'+keyword+'"><span tagid="'+keyword+'" tagname="'+keywordName+'" class="tagbtn">'+keywordName+'</span><div class="close closetag" tagid="'+keyword+'" url="http://www.devxekera.com/resources/" tagname="'+keywordName+'" tags="'+TagArray+'"></div></span>').after();
				   //console.log(keyword);
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
?>
<style>
	.pagination {
    list-style: none !important;
    
    align-items: center !important;
    padding: 0 !important;
	justify-content: center;
}

.pagination li {
    display: inline-block !important;
}

li.pg-btn {
    text-decoration: none !important;
    padding: 0px 10px !important; /* Add padding for a better click area */
    border-radius: 5px !important; /* Rounded corners */
    color: #000  ; /* Default text color */
    
}
li.pg-btn.disabled:hover {
     background-color: transparent !important; 
    
    color: #6BD9DE !important;
}

li.pg-btn:hover {
    background-color: #6BD9DE !important ; /* Hover background color */
    color: #fff !important; /* Change text color on hover */
	
}

li.pg-btn.active {
    background-color: #6BD9DE !important; /* Active page background color */
    color: #fff !important; /* Active page text color */
}

</style>