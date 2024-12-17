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
$sortid = $personData->sortid;
$resourcestype = $personData->rtype;
 $lifestage = $personData->lifestage;
 $search = $personData->search;

$tags = $personData->tags;
$keywords= explode(',', $tags);
$advancedkeywords = implode("', '", $keywords);

if(empty($tags) or $tags == '' ){
    $tags = '0' ; 
}

if(empty($dvalue)){
    $dvalue = '0' ;  
}

// //echo " line 15" . $lifestage
 $searchQuery = str_replace('\\', "", $search);
 $unquotedQuery = str_replace('"', "", $search);

 if(empty($lifestage)){
     $lifestage = '0';
 }
 
//  echo $lifestage 
$return_arr = array();
$list = 'true';
$level = '100';
$pgorder = '1';
$limit = ' limit 9';
if($sortid == '0')
{
if($search != '0' AND $lifestage != '0' AND $resourcestype != '0'){ // 111
// if search has value and life stage has value and resourcetype has value starts
    $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";

    if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
    }
    if($resourcestype != '0'){
        $where .= " and w.type = '$resourcestype'";
        $where .= " and P.post_type = '$resourcestype'";
    }
    if($lifestage != '0'){
        $from .=", life_stage_type AS l";
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }

    $order = " order by title_match desc, title_rough_match desc, relevancy desc limit 9";
    $order1 = " order by title_match desc, title_rough_match desc, relevancy desc";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
    $result1 = $db->prepare($queryt);
// if search has value and life stage has value and resourcetype has value ends
 } else if ($search != '0' AND $lifestage == '0' AND $resourcestype != '0'){ //2
// if search has value and life stage is 0 and resourcetype has value starts

    $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
    
    if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
    if($resourcestype != '0'){
        $where .= " and w.type = '$resourcestype'";
        $where .= " and P.post_type = '$resourcestype'";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }
    $order = " order by title_match desc, title_rough_match desc, relevancy desc limit 9";
    $order1 = " order by title_match desc, title_rough_match desc, relevancy desc ";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
    $result1 = $db->prepare($queryt);
// if search has value and life stage is 0 and resourcetype has value ends
 } else if ($search != '0' AND $lifestage == '0' AND $resourcestype == '0'){ //3
// if search has value and life stage is 0 and resourcetype is 0 starts
    $select = "SELECT DISTINCT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
    
    if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
        }
    if($resourcestype != '0'){
        $where .= " and w.type = '$resourcestype'";
        $where .= " and P.post_type = '$resourcestype'";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }

    $order = " order by title_match desc, title_rough_match desc, relevancy desc limit 9";
    $order1 = " order by title_match desc, title_rough_match desc, relevancy desc";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    //echo $query;
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
    $result1 = $db->prepare($queryt);
// if search has value and life stage is 0 and resourcetype is 0 ends
 } else if ($search == '0' AND $lifestage == '0' AND $resourcestype == '0'){ // 4
// if search is 0 and life stage is 0 and resourcetype is 0 starts
    if($tags == '0') {
    $query = "SELECT DISTINCT * FROM wp_resources as w WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' limit 9";
    $result = $db->prepare($query);
    
    $queryt = "SELECT DISTINCT * FROM wp_resources as w WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list' AND w.page_order = '$pgorder' ";
    $result1 = $db->prepare($queryt);

    } else {
    $query = "SELECT DISTINCT * FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') AND w.page_order = '$pgorder' limit 9";
    $result = $db->prepare($query);
    
    $queryt = "SELECT DISTINCT * FROM wp_resources as w, wp_term_relationships as wtr WHERE w.status = 'publish' AND w.level_of_access = '$level' AND w.list_in_search = '$list'  AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') AND w.page_order = '$pgorder' ";
    $result1 = $db->prepare($queryt);
    }
// if search is 0 and life stage is 0 and resourcetype is 0 ends
 }
 else if ($search != '0' AND $lifestage != '0' AND $resourcestype == '0'){ //5
// if search has value and life stage has value and resourcetype is 0 starts
    $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.status = 'publish' AND w.list_in_search = 'true' AND w.level_of_access = '100'";
    if ($searchQuery != '') {
    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
    }
    if($lifestage != '0'){
        $from .=", life_stage_type AS l";
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }

    $order = " order by title_match desc, title_rough_match desc, relevancy desc limit 9";
    $order1 = " order by title_match desc, title_rough_match desc, relevancy desc ";
//$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
   $result1 = $db->prepare($queryt);
// if search has value and life stage has value and resourcetype is 0 ends
 } else if ($search == '0' AND $lifestage != '0' AND $resourcestype != '0'){ //6
// if search is 0 and life stage has value and resourcetype has value starts
    $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.type = '$resourcestype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
   
    if($lifestage != '0'){
        $from .=", life_stage_type AS l";
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
    if($resourcestype != '0'){
        $where .= " and P.post_type = '$resourcestype'";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }

    $order = " AND w.page_order = '$pgorder'  limit 9";
    $order1 = " AND w.page_order = '$pgorder' ";
//$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
   $result1 = $db->prepare($queryt);
// if search is 0 and life stage has value and resourcetype has value ends
 } else if ($search == '0' AND $lifestage == '0' AND $resourcestype != '0'){ //7
// if search is 0 and life stage is 0 and resourcetype has value starts
    $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.type = '$resourcestype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
   
    if($resourcestype != '0'){
        $where .= " and P.post_type = '$resourcestype'";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }

    $order = " AND w.page_order = '$pgorder' limit 9";
    $order1 = " AND w.page_order = '$pgorder' ";
//$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
   $result1 = $db->prepare($queryt);
// if search is 0 and life stage is 0 and resourcetype has value ends
 } else if ($search == '0' AND $lifestage != '0' AND $resourcestype == '0'){ //8
// if search is 0 and life stage has value and resourcetype is 0 starts

    $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
   
    if($lifestage != '0'){
        $from .=", life_stage_type AS l";
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
    if($tags !='0'){
        $from .= " , wp_term_relationships as wtr ";
        $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
    }

    $order = " AND w.page_order = '$pgorder' limit 9";
    $order1 = " AND w.page_order = '$pgorder' ";
//$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $result = $db->prepare($query);
    $queryt = $select . $from . $where . $order1  ;
   $result1 = $db->prepare($queryt);

// if search is 0 and life stage has value and resourcetype is 0 ends
 } 

$result->execute();$countthem = $result->rowCount();
$result1->execute();$countthem = $result1>rowCount();
$rcount = $result1->rowCount();
$limit = '9';
$totalpages = ceil( $rcount / $limit );

$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
$resources = '/resources/';

while($row = $result->fetch(PDO::FETCH_ASSOC)){
    $titlen = $row['post_title'];
    $dvaluen = $row['type'];
    $slug = $row['slug'];
        $postid = $row['wp_post_id'];
    if($dvaluen == 'article'){
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

            $viewUrl = $seo_dvalue.$slug;$variable = resourcesUrl($viewUrl);
        $output = '';
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
            <div class="col-sm-6 col-md-4"> <a href="'.$variable.'" target="_self">
             <!-- resource block starts -->
             <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
           <div class="text-holder"><p class="dot-holder">'.$titlen.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT DISTINCT  * FROM $table_name WHERE object_id='$postid' limit 3");
    $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
         $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT DISTINCT  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<span class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'">';
               $output .= $resultnn['name'];
               $output .='</span>';
           }
        }
    $viewUrl = $seo_dvalue.$slug;$variable = resourcesUrl($viewUrl);
        $output .='</div>';
      //  $output .='<a role="button" href="'.$variable.'" target="_self" class="but btn btn-primary">VIEW</a>';
        $output .='</div>';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div></a>
                    <!-- resource block ends -->
                    </div>
                    <!-- resource resource in resources starts -->';
       // $output .= '</div>';
        $return_arr[] = array("message" => $output);
        
        //echo $output;
        //return $output;
}
}else if($sortid == 'views'){  // 29/10/2021

     if($search != '0' AND $lifestage != '0' AND $resourcestype != '0'){ // 111
// if search has value and life stage has value and resourcetype has value starts

        $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $from = " FROM wp_posts AS P ";
		$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND wc.wp_post_id = w.wp_post_id  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
        if($resourcestype != '0'){
            $where .= " and w.type = '$resourcestype'";
            $where .= " and P.post_type = '$resourcestype'";
        }
    
        if($lifestage != '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
        $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $ljoin.$where . $order ; 
        $querytnn = $db->prepare($query);
         $queryt = $select . $from .$ljoin. $where . $order1  ;
         $result1 = $db->prepare($queryt);
// if search has value and life stage has value and resourcetype has value starts
         } else if ($search != '0' AND $lifestage == '0' AND $resourcestype != '0'){ //2
// if se DESCarch has value and life stage is 0 and resourcetype has value starts
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND wc.wp_post_id = w.wp_post_id  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
            
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
                }
            if($resourcestype != '0'){
                $where .= " and w.type = '$resourcestype'";
                $where .= " and P.post_type = '$resourcestype'";
            }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        
            $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
            $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $ljoin.$where . $order ; 
        $querytnn = $db->prepare($query);
         $queryt = $select . $from .$ljoin. $where . $order1  ;
         $result1 = $db->prepare($queryt);
// if search has value and life stage is 0 and resourcetype has value ends
         } else if ($search != '0' AND $lifestage == '0' AND $resourcestype == '0'){ //3
// if search has value and life stage is 0 and resourcetype is 0 starts
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
           // $from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
			$from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		   $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		   //$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND wc.wp_post_id = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
            
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
                }
            if($resourcestype != '0'){
                $where .= " and w.type = '$resourcestype'";
                $where .= " and P.post_type = '$resourcestype'";
            }
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
        if($tags !='0'){
            $from .= " , wp_term_relationships as wtr ";
            $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
         }
        
            $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
            $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from .$ljoin. $where . $order ; 
        $querytnn = $db->prepare($query);
         $queryt = $select . $from .$ljoin. $where . $order1  ;
         $result1 = $db->prepare($queryt);

// if search has value and life stage is 0 and resourcetype is 0 ends
         } else if ($search == '0' AND $lifestage == '0' AND $resourcestype == '0'){ // 4
// if search is 0 and life stage is 0 and resourcetype is 0 starts
        if($tags !='0'){
            $query = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc, wp_term_relationships as wtr  where  wc.wp_post_id = w.wp_post_id AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY wc.view_count DESC limit 9";
            $querytnn = $db->prepare($query);
            
            
            $queryt = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc, wp_term_relationships as wtr where  wc.wp_post_id = w.wp_post_id AND  w.wp_post_id = wtr.object_id AND w.page_order = '$pgorder' AND wtr.term_taxonomy_id IN ('$advancedkeywords') ORDER BY wc.view_count DESC";
            $result1 = $db->prepare($queryt);
         } else {
            $query = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc  where  wc.wp_post_id = w.wp_post_id AND  w.page_order = '$pgorder'  ORDER BY wc.view_count DESC limit 9";
            $querytnn = $db->prepare($query);
            
            
            $queryt = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count from wp_resources as w, wp_resources_view_count as wc where wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'  ORDER BY wc.view_count DESC";
            $result1 = $db->prepare($queryt);
         }
// if search is 0 and life stage is 0 and resourcetype is 0 ends
         }
         else if ($search != '0' AND $lifestage != '0' AND $resourcestype == '0'){ //5
// if search has value and life stage has value and resourcetype is 0 starts
            $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND wc.wp_post_id = w.wp_post_id  AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
            
            if ($searchQuery != '') {
                $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
                }
            if($resourcestype != '0'){
                $where .= " and w.type = '$resourcestype'";
                $where .= " and P.post_type = '$resourcestype'";
            }
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }

            $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
            $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
            //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
            $query = $select . $from . $ljoin.$where . $order ; 
            $querytnn = $db->prepare($query);
            $queryt = $select . $from . $ljoin.$where . $order1  ;
           $result1 = $db->prepare($queryt);
// if search has value and life stage has value and resourcetype is 0 ends
         } else if ($search == '0' AND $lifestage != '0' AND $resourcestype != '0'){ //6
// if search is 0 and life stage has value and resourcetype has value starts
            $select = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            //$from = " FROM wp_resources as w, wp_posts AS P,  wp_resources_view_count as wc";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			$where = " WHERE w.type = '$resourcestype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND  w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.type = '$resourcestype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND wc.wp_post_id = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
           
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
            if($resourcestype != '0'){
                $where .= " and P.post_type = '$resourcestype'";
            }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }

        
            $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
            $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
        //$limit = 'limt 0, 9';
            $query = $select . $from . $ljoin. $where . $order ; 
            $querytnn = $db->prepare($query);
            $queryt = $select . $from . $ljoin.$where . $order1  ;
           $result1 = $db->prepare($queryt);
// if search is 0 and life stage has value and resourcetype has value ends
         } else if ($search == '0' AND $lifestage == '0' AND $resourcestype != '0'){ //7
// if search is 0 and life stage is 0 and resourcetype has value starts
            $select = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
            //$from = " FROM wp_resources as w, wp_posts AS P,  wp_resources_view_count as wc";
            $from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
			$where = " WHERE w.type = '$resourcestype' AND w.page_order = '$pgorder' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
			//$where = " WHERE w.type = '$resourcestype' AND w.page_order = '$pgorder' AND w.status = 'publish' AND P.ID = w.wp_post_id AND wc.wp_post_id = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
           
            if($resourcestype != '0'){
                $where .= " and P.post_type = '$resourcestype'";
            }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }

        
            $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
            $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
            //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
            $query = $select . $from . $ljoin.$where . $order ; 
            $querytnn = $db->prepare($query);
            $queryt = $select . $from .$ljoin. $where . $order1  ;
           $result1 = $db->prepare($queryt);
// if search is 0 and life stage is 0 and resourcetype has value ends
         } else if ($search == '0' AND $lifestage != '0' AND $resourcestype == '0'){ //8
// if search is 0 and life stage has value and resourcetype is 0 starts
            $select = "SELECT DISTINCT w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, wc.view_count";
           // $from = " FROM wp_resources as w, wp_posts AS P,  wp_resources_view_count as wc";
			$from = " FROM wp_posts AS P ";
			$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		   $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND  w.level_of_access = '100' AND w.page_order = '$pgorder'";
		   //$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND wc.wp_post_id = w.wp_post_id AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
           
            if($lifestage != '0'){
                $from .=", life_stage_type AS l";
                $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
            }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }
            $order = " ORDER BY wc.view_count DESC, w.page_order DESC limit 9";
            $order1 = " ORDER BY wc.view_count DESC, w.page_order DESC";
            //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
            $query = $select . $from . $ljoin.$where . $order ; 
            $querytnn = $db->prepare($query);
            $queryt = $select . $from .$ljoin. $where . $order1  ;
           $result1 = $db->prepare($queryt);
// if search is 0 and life stage has value and resourcetype is 0 ends
         } 
        
//echo $query;
     $querytnn->execute();
    $result1->execute();$countthem = $result1>rowCount();
    $rcount = $result1->rowCount();
    $limit = '9';
    $totalpages = ceil( $rcount / $limit );

     $url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
        $table_name = "wp_term_relationships";
        $table_name2 = "wp_terms";
        $resources = '/resources/';
     while($frow = $querytnn->fetch(PDO::FETCH_ASSOC)){
        $getid = $frow['wp_post_id']; 
        //$output = $getid;
        //now run post 
        $qpost = $db->prepare("SELECT DISTINCT  * FROM wp_resources WHERE wp_post_id = '$getid'");
        $qpost->execute();
        //fetch
        $fqpost = $qpost->fetch(PDO::FETCH_ASSOC);
        $titlen = $fqpost['post_title'];
    $dvaluen = $fqpost['type'];
    $slug = $fqpost['slug'];
        $postid = $fqpost['wp_post_id'];
    if($dvaluen == 'article'){
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
    }else{}
        $output = '';
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
            <div class="col-sm-6 col-md-4">
             <!-- resource block starts -->
             <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
           <div class="text-holder"><p class="dot-holder">'.$titlen.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT DISTINCT  * FROM $table_name WHERE object_id='$postid' limit 3");
    $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
         $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT DISTINCT  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tags="'.$tagid.'" tagid="'.$tagid.'">';
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
        $return_arr[] = array("message" => $output);
    }
// $output .= '</div>';
        
}else if($sortid == 'date'){
     if($search != '0' AND $lifestage != '0' AND $resourcestype != '0'){ // 111
// if search has value and life stage has value and resourcetype has value starts
    $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
    $from = " FROM wp_resources as w, wp_posts AS P";
    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
    
    if ($searchQuery != '') {
    $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
    }
    if($resourcestype != '0'){
        $where .= " and w.type = '$resourcestype'";
        $where .= " and P.post_type = '$resourcestype'";
    }
    if($lifestage != '0'){
        $from .=", life_stage_type AS l";
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }

    $order = " ORDER BY P.post_date_gmt DESC limit 9";
    $order1 = " ORDER BY P.post_date_gmt DESC";
    //$order1 = " AND w.page_order = '$pgorder'";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order ; 
    $querytnn = $db->prepare($query);
     $queryt = $select . $from . $where . $order1  ;
     $result1 = $db->prepare($queryt);
// if search has value and life stage has value and resourcetype has value ends
     } else if ($search != '0' AND $lifestage == '0' AND $resourcestype != '0'){ //2
// if search has value and life stage is 0 and resourcetype has value starts
        $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
        if($resourcestype != '0'){
            $where .= " and w.type = '$resourcestype' ";
            $where .= " and P.post_type = '$resourcestype' ";
        }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }

    
        $order = "  ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = "  ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ; 
        $querytnn = $db->prepare($query);
         $queryt = $select . $from . $where . $order1  ;
         $result1 = $db->prepare($queryt);
// if search has value and life stage is 0 and resourcetype has value ends
     } else if ($search != '0' AND $lifestage == '0' AND $resourcestype == '0'){ //3
// if search has value and life stage is 0 and resourcetype is 0 starts
    
        $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        
        if ($searchQuery != '') {
            $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
            }
        if($resourcestype != '0'){
            $where .= " and w.type = '$resourcestype'";
            $where .= " and P.post_type = '$resourcestype'";
        }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }
    
        $order = " ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = " ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
        //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ; 
        $querytnn = $db->prepare($query);
        $queryt = $select . $from . $where . $order1  ;
        $result1 = $db->prepare($queryt);
// if search has value and life stage is 0 and resourcetype is 0 ends
     } else if ($search == '0' AND $lifestage == '0' AND $resourcestype == '0'){ // 4
// if search is 0 and life stage is 0 and resourcetype is 0 starts
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        //$from = " FROM wp_resources as w, wp_posts AS P, wp_resources_view_count as wc";
        $from = " FROM wp_posts AS P ";
		$ljoin = ", wp_resources as w left join wp_resources_view_count as wc on wc.wp_post_id = w.wp_post_id ";
		$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
		//$where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND wc.wp_post_id = w.wp_post_id AND w.page_order = '$pgorder'";
       
        if($lifestage != '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
if($tags !='0'){
    $select .= " , wtr.term_taxonomy_id";
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }

        $order = " ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = " ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
    //$limit = 'limt 0, 9';
        $query = $select . $from .$ljoin. $where . $order ; 
        $querytnn = $db->prepare($query);
        $queryt = $select . $from . $ljoin.$where . $order1  ;
        $result1 = $db->prepare($queryt); 
// if search is 0 and life stage is 0 and resourcetype is 0 ends
     }
     else if ($search != '0' AND $lifestage != '0' AND $resourcestype == '0'){ //5
// if search has value and life stage has value and resourcetype is 0 starts
        $select = "SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
        if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode))";
        }
        if($lifestage != '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }
    
        $order = " ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = " ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
    //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ; 
        $querytnn = $db->prepare($query);
        $queryt = $select . $from . $where . $order1  ;
        $result1 = $db->prepare($queryt);
// if search has value and life stage has value and resourcetype is 0 ends
     } else if ($search == '0' AND $lifestage != '0' AND $resourcestype != '0'){ //6
// if search is 0 and life stage has value and resourcetype has value starts
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$resourcestype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
       
        if($lifestage != '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
        if($resourcestype != '0'){
            $where .= " and P.post_type = '$resourcestype'";
        }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }  
        $order = " ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = " ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
    //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ; 
        $querytnn = $db->prepare($query);
        $queryt = $select . $from . $where . $order1  ;
        $result1 = $db->prepare($queryt);
// if search is 0 and life stage has value and resourcetype has value ends
     } else if ($search == '0' AND $lifestage == '0' AND $resourcestype != '0'){ //7
// if search is 0 and life stage is 0 and resourcetype has value starts
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.type = '$resourcestype' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order='$pgorder'";
       
        if($resourcestype != '0'){
            $where .= " and P.post_type = '$resourcestype'";
        }
		if($tags !='0'){
		$from .= " , wp_term_relationships as wtr ";
		$where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
	 }
        $order = " ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = " ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
    //$limit = 'limt 0, 9';
         $query = $select . $from . $where . $order ; 
        $querytnn = $db->prepare($query);
        $queryt = $select . $from . $where . $order1  ;
        $result1 = $db->prepare($queryt);
// if search is 0 and life stage is 0 and resourcetype has value ends
     } else if ($search == '0' AND $lifestage != '0' AND $resourcestype == '0'){ //8
// if search is 0 and life stage has value and resourcetype is 0 starts
        $select = "SELECT DISTINCT  w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id, P.post_date_gmt";
        $from = " FROM wp_resources as w, wp_posts AS P";
        $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND w.page_order = '$pgorder'";
       
        if($lifestage != '0'){
            $from .=", life_stage_type AS l";
            $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
        }
if($tags !='0'){
    $from .= " , wp_term_relationships as wtr ";
    $where .= " AND  w.wp_post_id = wtr.object_id AND wtr.term_taxonomy_id IN ('$advancedkeywords') ";
 }
        $order = " ORDER BY P.post_date_gmt DESC limit 9";
        $order1 = " ORDER BY P.post_date_gmt DESC";
        //$order1 = " AND w.page_order = '$pgorder'";
    //$limit = 'limt 0, 9';
        $query = $select . $from . $where . $order ; 
        $querytnn = $db->prepare($query);
        $queryt = $select . $from . $where . $order1  ;
        $result1 = $db->prepare($queryt);
// if search is 0 and life stage has value and resourcetype is 0 ends
     } 
     
    //echo $queryt;
    $querytnn->execute();
    $result1->execute();$countthem = $result1>rowCount();
    $rcount = $result1->rowCount();
    $limit = '9';
    $totalpages = ceil( $rcount / $limit );

    $url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
        $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
        $table_name = "wp_term_relationships";
        $table_name2 = "wp_terms";
        $resources = '/resources/';
     while($frow = $querytnn->fetch(PDO::FETCH_ASSOC)){
        $getid = $frow['wp_post_id']; 
        //$output = $getid;
        //now run post 
        $qpost = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE wp_post_id = '$getid'");
        $qpost->execute();
        //fetch
        $fqpost = $qpost->fetch(PDO::FETCH_ASSOC);
        $titlen = $fqpost['post_title'];
    $dvaluen = $fqpost['type'];
    $slug = $fqpost['slug'];
        $postid = $fqpost['wp_post_id'];
    if($dvaluen == 'article'){
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
    }else{}
        $output = '';
        //$output .= '<div class="resource-column same-height-holder content-inner-page">';
        $output .= '<!-- resource resource in resources starts -->
            <div class="col-sm-6 col-md-4">
             <!-- resource block starts -->
             <div class="resource-block">';
        $output .= '<div class="img-holder same-height"><span class="icon-'.$dvaluen.'"></span></div>
           <div class="text-holder"><p class="dot-holder">'.$titlen.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'">';
        $resultss = $db->prepare("SELECT DISTINCT  * FROM $table_name WHERE object_id='$postid' limit 3");
    $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
         $tagid = $resultn['term_taxonomy_id'];
           $output .= $tagid;
           $gettagname = $db->prepare("SELECT DISTINCT  * FROM $table_name2 WHERE term_id='$tagid'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$pager.'" type="'.$type.'" dvalue="'.$tagid.'" tags="'.$tagid.'" tagid="'.$tagid.'">';
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
        $return_arr[] = array("message" => $output);
    }
// $output .= '</div>';

     
}else{
    $return_arr['message'] = 'blank';
}
//$query = "SELECT DISTINCT  * FROM wp_resources WHERE post_title LIKE '%$dvalue%' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $limit";

//$limit = 'limt 0, 9';



// Encoding array in JSON format
echo json_encode($return_arr);
?>
