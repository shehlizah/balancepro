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
$dvalue = $personData->dvalue;
$resourcetypes = $personData->resourcetypes;
$lifestage = $personData->lifestage;
 //echo 'line 15--'.$resourcetypes;
$searchQuery = str_replace('\\', "", $dvalue);
$searchQuery = str_replace("'", "", $searchQuery);
$searchQuery = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $searchQuery);


$unquotedQuery = str_replace('"', "", $dvalue);
$unquotedQuery = str_replace("'", "", $unquotedQuery);
$unquotedQuery = preg_replace('/[#\@\.\*\%\;\$\&\^]+-/', '', $unquotedQuery);

$return_arr = array();
$list = 'true';
$level = '100';
$pgorder = '1';
$sort =  $personData->sort;
if(empty($sort)  ){
    $sort = '0';   
}
$tags = $personData->tags;
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}

if(empty($dvalue) or $dvalue == "" ){
	$dvalue = '0'	;
}
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);
if(empty($tags) or $tags == "" ){
	$tags = '0'	;
}
$limit = ' limit 9';

if($dvalue != "0"){
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }else if($sort == 'relevance'){
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }else if($sort == 'views'){
        $select = "SELECT Distinct  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
        
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }else if($sort == 'date'){
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }
// if search has value ends
} else {
// if search is 0 starts
    if($sort == '0'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id ";
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }else if($sort == 'relevance'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }else if($sort == 'views'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $where = " WHERE w.status = 'publish' AND wc.wp_post_id = w.wp_post_id AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
        
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }else if($sort == 'date'){
        $select = "SELECT Distinct   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
        $query = $select . $from . $where . $order . $limit;
        $result = $db->prepare($query);
    }
// if search is 0 ends
}
//echo $query;
$result->execute();$countthem = $result->rowCount();

//row count
$rcount = $result->rowCount();
// $return_arr['query'] = $result;
$totallimit = '9';
$totalpages = ceil( $rcount / $totallimit );
$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $title = $row['title'];
    $titlesmall = substr_replace($title, "...", 150);
    $type = $row['type'];
    $postid = $row['wp_post_id'];
    //$postid = $row['ID'];
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
    $viewUrl = $seo_dvalue.$slug;
    $output = '';
        $output .= '<!-- resource resource in resources starts -->
		    <div class="col-sm-6 col-md-4"><a href="'.$viewUrl.'" target="_self">
		     <!-- resource block starts -->
		     <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$type.'"></span></div>
		   <div class="text-holder"><p class="dot-holder">'.$titlesmall.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 122px;">';
        $resultss = $db->prepare("SELECT Distinct  * FROM $table_name WHERE object_id='$postid' limit 3");
   	$resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
      	 $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT Distinct  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<span class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tags="'.$tagid.'" tagid="'.$tagid.'" sort="'.$sort.'">';
               $tagname = $resultnn['name'];
               $tagnamesmall = mb_strimwidth($tagname, 0, 25, "...");
               $output .= $tagnamesmall;
               $output .='</span>';
           }
        }
        $output .='</div>';
//        $output .='<a role="button" onClick="return my_function()" href="'.$seo_dvalue. $slug.'" target="_self" class="but btn btn-primary">VIEW</a>';
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
