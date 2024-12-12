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
$dtype = $personData->type;
$type = $personData->type;
$dpager = $personData->page;
$pager = $personData->page;
$tagids = $personData->tags;
$keywords= explode(',', $tagids);
$advancedkeywords = implode("', '", $keywords);
//$return_arr = array();
$limit = '9';

if(isset($personData->sort)){
    $sort =  $personData->sort; 
}else{
    $sort =  $personData->sortid;
}

if($dpager){
    $start = ($dpager - 1) * $limit; 
}else{
    $start = 0; 
}

$search = $personData->search;
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);

$lifestage = $personData->lifestage;

if(!empty($personData->rtype)){
    $resourcetypes = $personData->rtype;
}
if(!empty($personData->rtypes)){
    $resourcetypes = $personData->rtypes;
}
if(empty($sort)){
    $sort = '0' ;  
}

if(empty($search)){
    $search = '0' ;  
}
if(empty($resourcetypes) or  $resourcetypes == '' ){
    
    $resourcetypes = '0' ;  
}
if(empty($tagids) or $tagids == '0'){
    $tagids = '0' ;  
}
$list = 'true';
$level = '100';
$pgorder = '1';

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
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($tagids != '0'){
               $from .= " , wp_term_relationships as wtr";
               $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " AND w.page_order = '$pgorder'";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . " limit $start, $limit";
   
            //echo $query; 
            $getwppost = $db->prepare($query);
         } else if ($sort == 'relevance'){
            $select = "SELECT Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
            if($tagids != '0'){
               $from .= " , wp_term_relationships as wtr";
               $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " AND w.page_order = '$pgorder'";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . " limit $start, $limit";
   
            //echo $query; 
            $getwppost = $db->prepare($query);
         }else if ($sort == 'views'){
            $select = "SELECT  Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,wc.view_count";
            //$from = " FROM wp_resources as w, wp_posts AS P , wp_resources_view_count as wc";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder' ";
			//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND  wc.wp_post_id = w.wp_post_id ";
            
            if($resourcetypes != '0'){
                $where .= " and w.type = '$resourcetypes'";
                $where .= " and P.post_type = '$resourcetypes'";
            }
            if($lifestage !== '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($tagids != '0'){
               $from .= " , wp_term_relationships as wtr";
               $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = "  ORDER BY wc.view_count DESC ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $ljoin. $where . $order . " limit $start, $limit";
   
            //echo $query; 
            $getwppost = $db->prepare($query);
         }else if ($sort == 'date'){
            $select = "SELECT  Distinct w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
            if($tagids != '0'){
               $from .= " , wp_term_relationships as wtr";
               $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
            }
            $order = " ORDER BY P.post_date_gmt DESC ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . " limit $start, $limit";
   
            //echo $query; 
            $getwppost = $db->prepare($query);
         }
// if search is 0 ends
     } else {
// if search has value starts
        if($sort == '0'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
            if($tagids !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
            $order = "  order by title_match desc, title_rough_match desc, relevancy desc ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . " limit $start, $limit";
            $getwppost = $db->prepare($query);
        } else if ($sort == 'relevance'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
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
            if($tagids !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
            $order = "  order by title_match desc, title_rough_match desc, relevancy desc ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $where . $order . " limit $start, $limit";
            $getwppost = $db->prepare($query);
        }else if ($sort == 'views'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id,w.view_count";
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
            if($tagids !='0'){
                $from .= " , wp_term_relationships as wtr ";
                $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
             }
            $order = "  ORDER BY wc.view_count DESC  ";
            //$limit = 'limt 0, 9';
            $query = $select . $from . $ljoin. $where . $order . " limit $start, $limit";
            $getwppost = $db->prepare($query);
        }else if ($sort == 'date'){
            $select = "SELECT Distinct (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
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
        if($tagids !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " ORDER BY P.post_date_gmt DESC  ";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order . " limit $start, $limit";
        $getwppost = $db->prepare($query);
        }
// if search has value ends
     }

$getwppost->execute();$countthem = $getwppost->rowCount();

if($resourcetypes == '0'){
    $rtypesquery = '';
}else{
    $rtypesquery = " AND post_type = '$resourcetypes'";
}

$poststatus = " ";

$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
    
    while($row = $getwppost->fetch(PDO::FETCH_ASSOC)){
    $title = $row['post_title'];
    $postid = $row['wp_post_id'];
    $postname = $row['title'];
    $posttype = $row['type'];
    $slug = $row['slug'];
    if($posttype == 'article'){
    $seo_dvalue = 'articles/';
    }else if($posttype == 'calculator'){
     $seo_dvalue = 'calculators/';
    }else if($posttype == 'video'){
     $seo_dvalue = 'videos/';
    }else if($posttype == 'newsletter'){
     $seo_dvalue = 'newsletters/';
    }else if($posttype == 'podcast'){
     $seo_dvalue = 'podcasts/';
    }else if($posttype == 'toolkit'){
     $seo_dvalue = 'toolkits/';
    }else if($posttype == 'booklet'){
     $seo_dvalue = 'booklets/';
    }else if($posttype == 'worksheet'){
             $seo_dvalue = 'worksheets/';
            }else if($posttype == 'checklist'){
             $seo_dvalue = 'checklists/';
            }
  $viewUrl = $seo_dvalue.$slug;$variable = resourcesUrl($viewUrl);
    $output = '';
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->

          <div class="col-sm-6 col-md-4">
<a href="'.$variable.'" target="_self">  
             <!-- resource block starts -->
             <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$posttype.'"></span></div>
           <div class="text-holder"><p class="dot-holder">'.$title.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT * FROM $table_name WHERE object_id='$postid' limit 3");
    $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
         $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<span class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tagid="'.$tagid.'" tags="'.$tagid.'">';
               $output .= $resultnn['name'];
               $output .='</span>';
           }
        }
    $output .='</div>';
        $viewUrl = $seo_dvalue.$slug;$variable = resourcesUrl($viewUrl);
//        $output .='<a role="button" href="'.$variable.'" target="_self" class="but btn btn-primary">VIEW</a>';
        $output .='</div>';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div>
                    <!-- resource block ends -->
                    </a></div>
                    <!-- resource resource in resources starts -->';
        //$output .= '</div>';
        $return_arr[] = array("message" => $output);
    //$return_arr[] = array("title" => $title, "postid" => $postid, "postname" => $postname, "posttype" => $posttype);
    }
//}

// Encoding array in JSON format
echo json_encode($return_arr);
?>
