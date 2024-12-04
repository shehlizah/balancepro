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
$dvaluen = $personData->dvalue;
$page = $personData->page;
$lifestage = $personData->lifestage;
if(empty($lifestage)){
	$lifestage = '0';   
}
//$tags = $personData->tags;
$search = $personData->search;
//$sort = $personData->sort;
$rtypes = $personData->rtype;
$search = $personData->search;
if(empty($lifestage)){
	$lifestage = '0';   
}
$search = $personData->search;
if(empty($search)){
	$search = '0';   
}
if(empty($rtypes)){
	$rtypes = '0';   
}
if(empty($sort)){
	$sort = '0';   
}
$searchQuery = str_replace('\\', "", $search);
$unquotedQuery = str_replace('"', "", $search);
$limit = 9;
if($page){
    $start = ($page - 1) * $limit; 
}else{
    $start = 0; 
}  
$list = 'true';
$level = '100';
$pgorder = '1';
$resources = 'resources/';
$limitn = ' limit 9';

if($search == '0' AND $lifestage == '0' AND $dvaluen == '0' AND $rtypes == '0'){
// if search is 0 and life stage is 0 and resourcetype is 0 starts
	$check = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $start, $limit");
	$checkn = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE status = 'publish' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'");
// if search is 0 and life stage is 0 and resourcetype is 0 ends
}else
if ($search == '0'){
// if search is 0 starts
	 $select = "SELECT DISTINCT   w.post_title, w.level_of_access, w.list_in_search, w.page_order, w.title, w.type, w.slug, w.wp_post_id";
	 $from = " FROM wp_resources as w, wp_posts AS P";
	 $where = " WHERE  P.ID = w.wp_post_id AND w.list_in_search = 'true' AND w.level_of_access = '100'";
	 
	 if($rtypes != '0'){
		
		 $where .= " and w.type = '$rtypes'";
		 $where .= " and P.post_type = '$rtypes'";
		 
	 }
	
	 if($lifestage !== '0'){
				 $from .=", life_stage_type AS l";
		 $where .= " AND l.lifestagetype = '$lifestage' AND l.postid = w.ID";
	 }
	 $order = " AND w.page_order = '$pgorder'";
	 //$limit = 'limt 0, 9';
	 $ln   =" limit $start, $limit";
	 $query = $select . $from . $where . $order . $ln;
	 
 $check = $db->prepare($query);
 $query1 = $select . $from . $where . $order ;
 $checkn = $db->prepare($query1);
// if search is 0 ends
}else
if($dvaluen != '0'){
// if resourcetype is 0 starts
	$check = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE status = 'publish' AND type = '$dvaluen' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder' limit $start, $limit");
	$checkn = $db->prepare("SELECT DISTINCT * FROM wp_resources WHERE status = 'publish' AND type = '$dvaluen' AND level_of_access = '$level' AND list_in_search = '$list' AND page_order = '$pgorder'");
// if resourcetype is 0 ends
}

$check->execute();$countthem = $check->rowCount();
//now count row 
$checkcount = $check->rowCount();

$checkn->execute();$countthem = $checkn->rowCount();
//now count row 
$checkcountn = $checkn->rowCount();
$totalpages = ceil( $checkcountn / $limit );
$output = '';
$output .='<nav aria-label="balance pager m14-m15" balance-pager="" class="paging-holder clear">
    <ul class="pagination">';
								
if ($page > 1) {
	if($totalpages != 1){
	$output .='<li>
		<div class="prv-btn" lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page-1).'">
			<div style="float:left;margin-right: 5px;margin-left: 10px;margin-top: 11px; cursor: pointer;">
				<span class="btn-prev"></span>
			</div>
			<div style="float:left;margin-top: 7px;  cursor: pointer; margin-right: 22px;">
				<span class="hidden-xs">Prev</span>
			</div>
		</div>
	</li>';
	}
}
								
if($page == $totalpages){
	
		for ($i= max(1, $page ); $i <= min($page + 5, $totalpages); $i++) {
			$output .='<li class="pg-btn '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="'.$i.'">'.$i.'</li>';
		}
	
	if ($page < $totalpages) {
		$output .='<li><div class="next-btn" lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page+1).'" tab="sdff'.$page.'">
			<div style="float:left;margin-right: 5px;margin-left: 22px;margin-top: 4px; cursor: pointer;"><span class="hidden-xs">Next</span></div>
			<div style="float:left;margin-top: 10px;  cursor: pointer;"><span class="btn-next"></span></div>
		</div></li>';
	}
		if ($totalpages > 1) {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;pages</span>
			</p>
	</nav>';
		} else {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;page</span>
			</p>
	</nav>';
		}
}else{
	for ($i= max(1, $page ); $i <= min($page + 5, $totalpages); $i++) {
		$output .='<li class="pg-btn '.($page == $i ? 'active' : '').'" style="padding:5px 6px; cursor: pointer" lifestage="'.$lifestage.'" typevalue="'.$rtypes.'" pagerv="'.$i.'">'.$i.'</li>';
	}

if ($page < $totalpages) {
	$output .='<li><div class="next-btn" lifestage="'.$lifestage.'" type="'.$rtypes.'" pager="'.($page+1).'" tab="sdff'.$page.'">
		<div style="float:left;margin-right: 5px;margin-left: 22px;margin-top: 4px; cursor: pointer;"><span class="hidden-xs">Next</span></div>
		<div style="float:left;margin-top: 10px;  cursor: pointer;"><span class="btn-next"></span></div>
	</div></li>';
}
		if ($totalpages > 1) {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;pages</span>
			</p>
	</nav>';
		} else {
		$output .='</ul>
			<p>
				<span>of&nbsp;</span>
				<span class="ng-binding">'.$totalpages.'</span>
				<span>&nbsp;page</span>
			</p>
	</nav>';
		}
}
								
	$return_arr['message'] = $output;
	echo json_encode($return_arr);
?>