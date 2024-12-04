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
$lifestage = $personData->dvalue;
$rtypes = $personData->rtypes;
$search = $personData->search;
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);
$return_arr = array();

$url =  (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
$resources = '/resources/';

if($rtypes == '0' AND $search == '0'){
    // life stage is 0 and search is 0 starts
    $stmt = $db->prepare("SELECT DISTINCT l.postid, l.lifestagetype, w.post_title, w.title, w.type, w.slug, w.wp_post_id 
    FROM life_stage_type AS l, wp_resources AS w
    WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID limit 9");
     echo 'im at line 32';
    // life stage is 0 and search is 0 ends
}else if($rtypes != '0' AND $search == '0'){
    // life stage is 'some value' and search is 0 starts
    $stmt = $db->prepare("SELECT DISTINCT l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
    FROM life_stage_type AS l, wp_resources AS w
    WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' limit 9"); 
    echo 'im at line 39';
    // life stage is 'some value' and search is 0 ends
}else if($rtypes == '0' AND $search != '0'){
    $stmt = $db->prepare("SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_posts AS P WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode)) order by title_match DESC, title_rough_match desc, relevancy desc limit 9");
    // life stage is 0 and search is 'some value' starts
}else if($rtypes != '0' AND $search != '0'){
    // life stage is 'some value' and search is 'some value' starts
    $stmt = $db->prepare("SELECT DISTINCT  (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id FROM wp_resources as w, wp_posts AS P, life_stage_type AS l WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode)) order by title_match DESC, title_rough_match desc, relevancy desc limit 9"); 
    // life stage is 0 and search is 'some value' ends
}else{}

$stmt->execute();
while($rows = $stmt->fetch(PDO::FETCH_ASSOC)){
    //$output = '<pre>';
    //$output .= $rows['post_title'];
    $postname = $rows['post_title'];
    $title = $rows['title'];
    $titlesmall = substr_replace($title, "...", 150);
    $dvaluen = $rows['type'];
    $slug = $rows['slug'];
        $postidn = $rows['wp_post_id'];
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

// Encoding array in JSON format
echo json_encode($return_arr);
?>
