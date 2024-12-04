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
$lifestage = $personData->tagid;
$dpager = $personData->page;
// $searchquery = $personData->search;


$searchQuery = str_replace('\\', "", $dvaluen);
$unquotedQuery = str_replace('"', "", $dvaluen);
$return_arr = array();
	if($page){
		$pvalue = $page;
	}else{
		$pvalue = '0';
	}
//echo $pvalue;
$limit = 9;

$rtypes = $personData->rtype;
$return_arr = array();
$limit = '9';

if($dpager){
    $start = ($dpager - 1) * $limit; 
}else{
    $start = 0; 
}
$check ='0';

if($rtypes == '0'){
// if resourcetype is 0 starts
    $check = $db->prepare("SELECT * FROM life_stage_type WHERE lifestagetype = '$lifestage' limit $start, $limit");
    $check->execute();$countthem = $check->rowCount();
// if resourcetype is 0 ends
}else if ($rtypes != '0' AND $search == '0')
{
// if search is 0 and resourcetype has value starts
    $check = $db->prepare("SELECT l.postid, l.lifestagetype, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id 
    FROM life_stage_type AS l, wp_resources AS w
    WHERE w.status = 'publish' AND l.lifestagetype = '$lifestage' AND l.postid = w.ID AND w.type = '$rtypes' limit $start, $limit");
// if search is 0 and resourcetype has value ends
}else if($rtypes != '0' AND $search != '0'){
// if search has value and resourcetype has value starts
    $select = "SELECT (w.title = '{$unquotedQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P, life_stage_type AS l";
    $where = " WHERE w.type = '$rtypes' AND w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
    if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
    }else
    if($rtypes != '0'){
        $where .= " and P.post_type = '$rtypes'";
    }
    if($lifestage != '0'){
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
    $order = " order by title_match desc, title_rough_match desc, relevancy desc";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order . $limit;
    $check = $db->prepare($query);
// if search has value and resourcetype has value ends
}else if($rtypes == '0' AND $search != '0'){
// if search has value and resourcetype is 0 starts
    $select = "SELECT (w.title = '{$searchQuery}') AS title_match, match (w.html, w.title) against ('{$searchQuery}') AS relevancy, (w.post_title LIKE '%{$unquotedQuery}%') AS title_rough_match, w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
    $from = " FROM wp_resources as w, wp_posts AS P, life_stage_type AS l";
    $where = " WHERE w.status = 'publish' AND P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
    if ($searchQuery != '') {
        $where .= " and (match (w.html, w.title) against ('{$searchQuery}' in boolean mode) or match (P.post_excerpt) against ('{$searchQuery}' in boolean mode))";
    }else if ($lifestage != '0'){
        $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
    }
    $order = " order by title_match desc, title_rough_match desc, relevancy desc";
    //$limit = 'limt 0, 9';
    $query = $select . $from . $where . $order . $limit;
    $check = $db->prepare($query);
// if search has value and resourcetype is 0 ends
}
$check->execute();$countthem = $check->rowCount();

$table_name = "wp_term_relationships";
$table_name2 = "wp_terms";
//now get post id
while($pid = $check->fetch(PDO::FETCH_ASSOC)){
$postid = $pid['postid'];
    $getwppost = $db->prepare("SELECT * FROM wp_resources WHERE ID = '$postid'");
    $getwppost->execute();
    //now count here
    while($row = $getwppost->fetch(PDO::FETCH_ASSOC)){
    $title = $row['title'];
    $titlesmall = substr_replace($title, "...", 150);
    $postidn = $row['wp_post_id'];
    $postname = $row['title'];
    $postslug = $row['slug'];
    $posttype = $row['type'];
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
        $output .= '<div class="img-holder same-height"><span class="icon-'.$posttype.'"></span></div>
           <div class="text-holder"><p class="dot-holder">'.$titlesmall.'</p>';
        $output .= '<div class="btn-tag same-height  same-height-left same-height-right" style="height: 103px;" dvalue="'.$countthem.'" test="resource_life_stages">';
        $resultss = $db->prepare("SELECT * FROM $table_name WHERE object_id='$postidn' limit 3");
        $resultss->execute();
        while($resultn = $resultss->fetch(PDO::FETCH_ASSOC)) {
         $tagidn = $resultn['term_taxonomy_id'];
           $output .= $tagidn;
           $gettagname = $db->prepare("SELECT * FROM $table_name2 WHERE term_id='$tagidn'");
           $gettagname->execute();
           while($resultnn = $gettagname->fetch(PDO::FETCH_ASSOC)){
               $output .='<a class="tag ng-binding ng-scope tag-click" url="'.$escaped_url.'" tagname="'.$resultnn['name'].'" pager="'.$dpager.'" type="'.$posttype.'" dvalue="'.$postid.'"tagid="'.$lifestage.'"tags="'.$lifestage.'">';
               $output .= $resultnn['name'];
               $output .='</a>';
           }
        }
    $output .='</div>';
        $output .='<a role="button" href="'.$seo_dvalue. $postslug.'" target="_self" class="but btn btn-primary">VIEW</a>';
        $output .='</div>';
        $output .= '<span class="icon-lock" style="display: none;"></span>';
        $output .= '</div>
                    <!-- resource block ends -->
                    </div>
                    <!-- resource resource in resources starts -->';
        //$output .= '</div>';
        $return_arr[] = array("message" => $output);
    //$return_arr[] = array("title" => $title, "postid" => $postid, "postname" => $postname, "posttype" => $posttype);
    }
}

// Encoding array in JSON format
    echo json_encode($return_arr);
?>
