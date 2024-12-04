<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// SET HEADER
header("Content-Type: application/json; charset=UTF-8");

// INCLUDING DATABASE AND MAKING OBJECT
include('database.php');include('functions.php');
// MAKE SQL QUERY
$personData = json_decode($_REQUEST['data']);
$dvaluen = $personData->query;
if(isset($personData->pager)){
$page = $personData->pager;
$pager = $personData->pager;
}
else{
	$page ='0'; 
$pager=1;;
}	
$resourcetypes = $personData->resourcetypes;
$lifestage = $personData->lifestage;
$searchQuery = str_replace('\\', "", $dvaluen);
$searchQuery = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $searchQuery);
$unquotedQuery = str_replace('"', "", $dvaluen);
$unquotedQuery = str_replace("'", "", $unquotedQuery);
$unquotedQuery = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $unquotedQuery);

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
if(empty($sort)  ){
    $sort = '0' ;  
}
$return_arr = array();
if($page){
	$pvalue = $page;
}else{
	$pvalue = '0';
}
//echo $pvalue;
$limit = '9';
if($page){
    $start = ($pvalue - 1) * $limit; 
}else{
    $start = 0; 
}  
$list = 'true';
$level = '100';
$pgorder = '1';

if($dvaluen == '0' AND $lifestage == '0' AND $resourcetypes == '0'){
// if search is 0 and life stage is 0 and resourcetype is 0 starts
    if($sort == '0'){
        if($tags !='0'){
            $query = "SELECT Distinct * FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') AND w.page_order = '$pgorder' limit $start, $limit";
            $result = $db->prepare($query);
		 } else {
            $query = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $start, $limit";
            $result = $db->prepare($query);
         }
       
    } else if($sort == 'relevance'){
        
        if($tags !='0'){
		    $query = "SELECT Distinct * FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') AND w.page_order = '$pgorder' limit $start, $limit";
            $result = $db->prepare($query);
		 } else {
            $query = "SELECT Distinct * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $start, $limit";
            $result = $db->prepare($query);
         }
    } else if($sort == 'views'){
 
        if($tags !='0'){
		            $query = "SELECT Distinct * FROM wp_resources as w, wp_resources_view_count as wc, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND  wc.wp_post_id = w.wp_post_id AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')    ORDER BY wc.view_count DESC  limit $start, $limit";
            $result = $db->prepare($query);
		 } else {
            $query = "SELECT Distinct * FROM wp_resources as w, wp_resources_view_count as wc WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND  wc.wp_post_id = w.wp_post_id   ORDER BY wc.view_count DESC  limit $start, $limit";
            $result = $db->prepare($query);
         }
    } else if($sort == 'date'){
        
        if($tags !='0'){

            $query = "SELECT Distinct * FROM wp_resources as w, wp_posts AS P, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords')  ORDER BY P.post_date_gmt DESC limit $start, $limit";
            $result = $db->prepare($query);
		 } else {
            $query = "SELECT Distinct * FROM wp_resources as w, wp_posts AS P WHERE w.status = 'publish' AND w.level_of_access = '$level' AND P.ID = w.wp_post_id AND w.list_in_search = '$list' ORDER BY P.post_date_gmt DESC limit $start, $limit";
            $result = $db->prepare($query);
         }
    } 
// if search is 0 and life stage is 0 and resourcetype is 0 starts
    
}else if($dvaluen !== '0'){
// if search has value starts
if($sort == '0'){
    $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
     }
    $order = " order by title_match desc, title_rough_match desc, relevancy desc";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order . " limit $start, $limit";
    $result = $db->prepare($query);
} else if($sort == 'relevance'){
    $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
     }
    $order = " order by title_match desc, title_rough_match desc, relevancy desc ";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order . " limit $start, $limit";
    $result = $db->prepare($query);
} else if($sort == 'views'){
    $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
    //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
    $from = " FROM wp_posts AS P ";
	$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";	
	
	$where = " WHERE w.status = 'publish' AND  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
	//$where = " WHERE w.status = 'publish' AND  wc.wp_post_id = w.wp_post_id  AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
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
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
     }
    $order = "  ORDER BY wc.view_count DESC ";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $ljoin . $where . $order . " limit $start, $limit";
    //echo $query;
    $result = $db->prepare($query);
} else if($sort == 'date'){
    $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
     }
    $order = "  ORDER BY P.post_date_gmt DESC ";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order . " limit $start, $limit";
    $result = $db->prepare($query);
} 
// if search has value ends
} else if ($dvaluen == '0'){
// if search is 0 starts
    if($sort == '0'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
        if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
        $order = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order . " limit $start, $limit";
        $result = $db->prepare($query);
    } else if($sort == 'relevance'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
        if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
        $order = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order . " limit $start, $limit";
        $result = $db->prepare($query);
    } else if($sort == 'views'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        $from = " FROM wp_resources as w, wp_posts AS P,  wp_resources_view_count as wc";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND  wc.wp_post_id = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
        
        if($resourcetypes != '0'){
            $where .= " and w.type = '$resourcetypes'";
            $where .= " and P.post_type = '$resourcetypes'";
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
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order . " limit $start, $limit";
        $result = $db->prepare($query);
    } else if($sort == 'date'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
        if($tags !='0'){
			$from .= " , wp_term_relationships as wtr ";
			$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
		 }
        $order = "  ORDER BY P.post_date_gmt DESC ";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order . " limit $start, $limit";
        $result = $db->prepare($query);
    }
// if search is 0 ends
}
$result->execute();$countthem = $result->rowCount();
//$return_arr["Query"] = $result;
$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
$resources = '/resources/';
while($row = $result->fetch(PDO::FETCH_ASSOC)){
	$titlen = $row['title'];
	$postid = $row['wp_post_id'];
	$type = $row['type'];
	$slug = $row['slug'];
    if($type == 'article'){
	$seo_dvalue = 'articles/';
	}else if($type == 'calculator'){
	 $seo_dvalue = 'calculators/';
	}else if($type == 'video'){
	 $seo_dvalue = 'videos/';
	}else if($type == 'newsletter'){
	 $seo_dvalue = 'newsletters/';
	}else if($type == 'podcast'){
	 $seo_dvalue = 'podcasts/';
	}else if($type == 'toolkit'){
	 $seo_dvalue = 'toolkits/';
	}else if($type == 'booklet'){
	 $seo_dvalue = 'booklets/';
	}else if($dvaluen == 'worksheet'){
             $seo_dvalue = 'worksheets/';
            }else if($dvaluen == 'checklist'){
             $seo_dvalue = 'checklists/';
            }

        $output = '';
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
		    <div class="col-sm-6 col-md-4">
		     <!-- resource block starts -->
		     <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$type.'"></span></div>
		   <div class="text-holder"><p class="dot-holder">'.$titlen.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT Distinct  * FROM $table_name WHERE object_id='$postid' limit 3");
   	    $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
      	 $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT Distinct  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tagid="'.$tagid.'" tags="'.$tagid.'" sort="'.$sort.'">';
               $output .= $resultnn['name'];
               $output .='</a>';
           }
        }
   	$viewUrl = $seo_dvalue.$slug;$variable = resourcesUrl($viewUrl);
        $output .='</div>';
        $output .='<a role="button" href="'.$variable.'" target="_self" class="but btn btn-primary">VIEW</a>';
        $output .='</div>';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div>
                    <!-- resource block ends -->
                    </div>
                    <!-- resource resource in resources starts -->';
       // $output .= '</div>';
        $return_arr[] = array("message" => $output);
}
// Encoding array in JSON format
echo json_encode($return_arr);
?>
