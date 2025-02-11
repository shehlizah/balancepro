<?php
/**** Function for resources ***/
/**** Author: Dhiraj Uphat ***/


/** function to get resource data **/
function getResource( $type, $id, $pageNum = 1 ){
	global $base_url,$parent_url,$conn;
	$pluralType = $type;
	
	$resource   = getResourceData($type, $id, $pageNum);
	$checkAuth  = checkAuth($resource[0]);

/*	echo "<pre>";
//	print_r($resource)['m34_module'];
	print_r($resource);
	echo "</pre>";
*/

	if ( $checkAuth ) {
		return $checkAuth;
	}
	
	// Resource is a quiz
	if ( strpos($resource[0]['html'], 'form id="quiz-form') || strpos($resource[0]['html'], 'quiz-header') ) {
		$relatedResources ='';// getTopRelatedResources($id,	$_SESSION['white_label_website_id']);

		$icons   = getIconTypes();

		// Get the post so we can use the post_name in iframe URL
		$ptitle = $resource[0]['post_title'];
		$sqlp = "select * from wp_posts where post_title='$ptitle' and post_status='publish' and post_type='quiz' limit 1";
		$resp= mysqli_query($conn,$sqlp);;
                $rowp = mysqli_fetch_array($resp);
		$_SESSION['quiz_id'] = $rowp['ID'];
		if(mysqli_num_rows($resp)==0){
			$uns = unserialize($resource[0]['meta_value']);
			$wp_post_id = $uns['multipage_module_module'][0]['last_page_quiz'];
//			$wp_post_id = $rowp['ID'];
			$_SESSION['quiz_id'] = $uns['multipage_module_module'][0]['last_page_quiz'];

			$sql = "select * from wp_posts where ID='$wp_post_id' limit 1";
			$res= mysqli_query($conn,$sql);
			$row = mysqli_fetch_array($res);
			
		}
		else {
			//$row = mysqli_fetch_all($res,MYSQLI_ASSOC);
		}
		
		$data =  ['template'=>'resource',
			'resource'         => $resource[0],
			'relatedResources' => $relatedResources,
			'icons'            => $icons,
			'wp_post_id'       => $_SESSION['quiz_id'],
			'posttype'         => 'quiz'
		];
		return $data;
		
	}
	else {
		$calc=0;$calcUrl='';

		$resource   = setResourceModuleData($resource[0]);
		$checkAuth  = checkAuth($resource);
//		echo 'pageNum:'.$pageNum;
		if ( $checkAuth ) {
			return $checkAuth;
		}

		if ( $type === 'calculator' ) {
			$calcUrl  = $parent_url.'/resources/calculators/'.$id.'?plainhtml=true';
			$calc=1;
			$data = file_get_contents($calcUrl);
			$datax=explode('<!-- M34/M31: SECTION -->',$data);
			$datax1=explode('<!-- end M02: SECTION -->',$datax[1]);
			$resource['html'] = $datax1[0];
			
		}
		else if ( checkMultiPageArticle($resource['type'], $id, $pageNum) ) {
			
			if (trim($resource['html']) != ''){
			$resource['html']= $resource['chapter_select_html'].$resource['html'].$resource['pager_html'];
			} else {
				$resource['html'] = $resource['meta']['m34_module'][0]['copy'];
			}
		}
//echo $resource['html'];

		if ( $pageNum === 1 ) {
			$resource['html']= $resource['hero_html'].$resource['html'];
		}
//print_r($resource);
		if($resource['type']=='article' && trim($resource['html'])==''){
		//	echo'<pre>',print_r($resource);
			$html = "<article  class='text-block default-content-style article default-content-style'><div class='container'><div class='row'><div>";
			$copy = str_replace('<strong>','<p><strong>',$resource['meta']['m34_module'][0]['copy']);
			$copy = str_replace('</strong>','</strong><br>',$copy);
			//$copy = str_replace('&lt;/strong$gt;','&lt;/strong$gt;&lt;/p$gt;',$$copy);
			$html .= '<h1 class="text-info text-center">'.$resource['meta']['m34_module'][0]['title'].'</h1><p>'.$copy.'</p>';
			$html .='</div>';
			$html .='</div>';
			$html .='</div>';
			$html .='</article>';
			$resource['html']=$html;
		}

		$relatedResources = getTopRelatedResources(
			$id,
			$_SESSION['white_label_website_id']
		);

		
		$icons = getIconTypes();

		$data =  ['template'=>'resource',
			'resource'         => $resource,
			'relatedResources' => $relatedResources,
			'calcUrl'=>$calcUrl,
			'calc'=>$calc,
			'icons'            => $icons
		];
		return $data;
	}

}

/** function to check multiple page article **/
function checkMultiPageArticle($postType, $id, $pageNum){
	if ($postType === 'article') {
		if ($pageNum > 1) {
			return true;
		} else {
			if (getResourceCount($id) >1) {
				return true;
			}
		}
	}
	return false;
}

/** function to count resource data **/
function getResourceCount($id) {
	global $conn;
	$sql="select ID from wp_resources as R where R.slug='$id' and status='publish' ";
	$res=mysqli_query($conn,$sql);
	return mysqli_num_rows($res);	
}

/** function to set resource module data **/
function setResourceModuleData($resource)  {
	$meta = unserialize($resource['meta_value']);
	$resource['meta']= $meta;
	if (isset($meta['m1_module'])) {
		$resource['m1Module'] = $meta['m1_module'][0];
	}
	unset($resource['meta_key'], $resource['meta_value']);

	return $resource;
}


/** function to get top related resource data **/
function getTopRelatedResources($postName, $siteId, $relatedResourcesMax = 4)  {
	$topRelatedResources = [];
	$tags = array(getTags($postName));
//logging(print_r($tags,true));	
//if(!empty($tags)){
$tagInArray = getFilterTags($tags);
//logging(print_r($tagInArray,true));	
//}
$topRelatedResources = [];
	if (is_array($tagInArray)) {
		$relatedResources = getRelatedResources($postName, $tagInArray, $siteId);

		$tagCount = count($tagInArray);
		if (count($relatedResources) > $relatedResourcesMax) {
			$relatedStrength = createRelationsStrength($relatedResources, $tagInArray);
			$topRelatedResources = createTopRelatedResourcesArray($relatedResourcesMax, $tagCount, $relatedStrength, $topRelatedResources);
		} else {
			$topRelatedResources = $relatedResources;
		}
	}
	
	return $topRelatedResources;
}

/** function to get related resource data **/
function getRelatedResources($postName, $tagInArray, $siteId){	
	global $conn;	
	if(empty($tagInArray)){
		$tagInArraynew = 0;
	
	} else {
	       	$tagInArraynew = implode(',',$tagInArray);
	}
		$sql="select AG.post__in,AG.term__in from wp_access_groups as AG left join wp_white_label_website_access_group as WAG on AG.term__in = WAG.access_group_id where white_label_website_id='$siteId'";
		$res = mysqli_query($conn,$sql);
		$postin=array();		
		$termin=array();		
		while($row=mysqli_fetch_array($res)){
			$postin[]=$row['post__in'];
			$termin[]=$row['term__in'];
		}	
		$postinv = implode(',',$postin);
		$terminv = implode(',',$termin);
		$sql1="";
	if(!empty($tagInArray[0])){
                $sql1="AND T.term_id IN($tagInArraynew)";
        }

		$sql="SELECT P.post_title,P.post_excerpt,P.post_name,P.post_type,GROUP_CONCAT(DISTINCT T.term_id) AS term_ids 
FROM wp_resources AS R LEFT JOIN wp_posts AS P ON P.ID = R.wp_post_id 
LEFT JOIN wp_term_relationships AS TR ON TR.object_id = P.ID 
LEFT JOIN wp_term_taxonomy AS TT ON TT.term_taxonomy_id = TR.term_taxonomy_id 
LEFT JOIN wp_terms AS T ON T.term_id = TT.term_id 
LEFT JOIN wp_postmeta AS PM ON PM.post_id = P.ID 
LEFT JOIN wp_white_label_website_access_group AS WAG ON WAG.white_label_website_id = $siteId 
LEFT JOIN wp_access_groups AS AG ON AG.wp_post_id = WAG.access_group_id 
LEFT JOIN wp_term_relationships AS aTR ON aTR.object_id = P.ID 
LEFT JOIN wp_term_taxonomy AS aTT ON aTT.term_taxonomy_id = aTR.term_taxonomy_id 
WHERE P.post_name != '$postName' AND PM.meta_key = 'list_in_search' AND PM.meta_value = 'true' $sql1
GROUP BY R.wp_post_id ORDER BY term_ids ASC, RAND() LIMIT 4";
//var_dump($tagInArray);
//	var_dump($sql); var_dump($sql1);
	   $res = mysqli_query($conn,$sql);	
	   if(mysqli_num_rows($res)>0)		return mysqli_fetch_all($res,MYSQLI_ASSOC);
		
}


/** function to create relation **/
function createRelationsStrength($relatedResources, $tagInArray) {
	$relatedStrength = [];
	foreach ($relatedResources as $relatedResource) {
		$termArray = str_getcsv($relatedResource['term_ids']);
		$n = 0;
		foreach ($termArray as $term) {
			if (in_array($term, $tagInArray, false)) {
				$n++;
			}
		}
		$relatedStrength[$n][] = $relatedResource;
	}
	return $relatedStrength;
}

/** function to generate related resource arrays **/
function createTopRelatedResourcesArray($relatedResourcesMax, $tagCount, $relatedStrength, $topRelatedResources){
	$remainingRelations = $relatedResourcesMax;
	for ($n = $tagCount; $n > 0; $n--) {
		if (array_key_exists($n, $relatedStrength)) {
			$relatedStrengthCount = count($relatedStrength[$n]);
			if ($relatedStrengthCount === $remainingRelations) {
				$topRelatedResources = $relatedStrength[$n];
				return $topRelatedResources;
			} elseif ($relatedStrengthCount > $remainingRelations) {
				$randomSelections = array_rand($relatedStrength[$n], $remainingRelations);
				if (is_array($randomSelections)) {
					foreach ($randomSelections as $el) {
						$topRelatedResources[] = $relatedStrength[$n][$el];
					}
				} else {
					$topRelatedResources[] = $relatedStrength[$n][$randomSelections];
				}
				return $topRelatedResources;
			} else {
				foreach ($relatedStrength[$n] as $el) {
					$topRelatedResources[] = $el;
				}
				$remainingRelations -= $relatedStrengthCount;
			}
		}
	}
	return $topRelatedResources;
}

/** function to get resource data **/
function getResourceData( $type, $id, $pageNum = 1 )   {
	global $conn;
	if ( is_numeric($id) ) {
		$idc = 'R.wp_post_id='.$id;
	}
	else {
		$idc = "R.slug='$id' and R.type='$type'";
	}
	$sql="select * from wp_resources as R join wp_postmeta as PM on PM.post_id=R.wp_post_id where status='publish' and PM.meta_key='_page_edit_data' and page_order='$pageNum' and R.type!='program' and $idc"; 
	$res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		return mysqli_fetch_all($res,MYSQLI_ASSOC);
	}	
}

/** function to get term tags **/
function getTags($postName)  {
	global $conn;
	$sql = "select TT.term_id from wp_resources R left join wp_term_relationships as TR on TR.object_id=R.wp_post_id left join wp_term_taxonomy as TT on TT.term_taxonomy_id=TR.term_taxonomy_id left join wp_terms as T on T.term_id=TT.term_id where R.slug='$postName'";
    $res = mysqli_query($conn,$sql);
	if(mysqli_num_rows($res)>0){
		return mysqli_fetch_all($res,MYSQLI_ASSOC);
	}    
}

/** function to make tag array **/
function getFilterTags($tags) {
        $tagInArray = [];
        foreach ($tags[0] as $tag) {
            $tagInArray[] = $tag['term_id'];
        }
//var_dump($tagInArray);
        return $tagInArray;
}

/** function to check user auth **/
function checkAuth($resource){
	global $base_url;
	if (!isset($_SESSION['loggedIn']) && $resource['list_in_search'] === 'false' && $resource['level_of_access'] > 100) {
		$_SESSION['login_error']='You must login to continue.';
		header("location:$base_url".'login');die();
	}

	return false;
}


/** function to check program auth **/
function checkAuthProgram($program){
	global $base_url;
	if (!isset($_SESSION['loggedIn']) && $program['level_of_access'] > 100) {		
		$_SESSION['login_error']='You must login to continue.';
		header("location:$base_url".'login');die();
	}
	return false;
}

/** function to get Icon types **/
function getIconTypes(){
	$icons = [];
	$icons['article'] = 'icon-article';
	$icons['calculator'] = 'icon-calculator';
	$icons['video'] = 'icon-video';
	$icons['newsletter'] = 'icon-newsletter';
	$icons['checklist'] = 'icon-checklist';
	$icons['podcast'] = 'icon-podcast';
	$icons['pdf'] = 'icon-pdf';
	$icons['calendar'] = 'icon-calendar';
	$icons['quiz'] = 'icon-quiz';
	$icons['toolkit'] = 'icon-toolkit';
	$icons['worksheet'] = 'icon-worksheet';
	$icons['booklet'] = 'icon-booklet';

	return $icons;
}

/** function to check urls from data **/
function getValidUrlsFrompage($content) {
	global $base_url;
	$htmlDom = new DOMDocument;

	//Load the HTML string into our DOMDocument object.
	@$htmlDom->loadHTML($content);

	//Extract all anchor elements / tags from the HTML.
	$anchorTags = $htmlDom->getElementsByTagName('a');

	//Create an array to add extracted images to.
	$extractedAnchors = array();

	
	foreach($anchorTags as $anchorTag){
		//Get the href attribute of the anchor.
		$aHref = $anchorTag->getAttribute('href');
			
			if(strstr($aHref,'/resources/')){
				$new = str_replace('/resources/','',$aHref);
				$newx = explode('/',$new);
				//print_r($newx);
				$id = str_replace('/','',$newx[1]);
				$type='articles';//$newx[0];
				$type=trim($newx[0]);
				if(isset($newx[2]) && $newx[2]=='page'){
					$page = '&page='.$newx[3];
				}
				else $page ='';
				$newsite=$base_url.'index.php?action=resources1&type='.$type.'&id='.$id.$page;
				if(strstr('https://www.balancepro.org/resources/articles/dmp-disclosures/',$aHref)){}
				else $content = str_replace($aHref,$newsite,$content);
			}
			else if(strstr($aHref,'/programs/')){
				
				$new = str_replace('https://','',$aHref);
				//echo '<br>',$new;
				$newx = explode('/',$new);
				if(isset($newx[2]) && $newx[2]!='') {
					$newsite=$base_url.'index.php?action=programs1&amp;article='.$newx[2];
				} 
				else $newsite=$base_url.'index.php?action=programs1';
				$content = str_replace('https://penair.balancepro.org/programs/balance-track',$base_url.'index.php?action=programs1&article=balance-track',$content);
				$content = str_replace($aHref,$newsite,$content);
				
				
			}
			
		
	}	
	return $content;
}


/** function to get check resource url **/
function removeResourseUrl($content) {
    global $base_url, $subdomain, $parent_url;
    $htmlDom = new DOMDocument;
    @$htmlDom->loadHTML($content);
    $anchorTags = $htmlDom->getElementsByTagName('a');

    foreach ($anchorTags as $anchorTag) {
        $aHref = $anchorTag->getAttribute('href');

        // Skip links that point to chatmessage.php (do not modify them)
        if (strpos($aHref, 'chatmessage.php') !== false) {
            continue;
        }

        // Replacement for 'sortBy=-views&pager=1'
        if (strpos($aHref, 'sortBy=-views&pager=1') !== false) {
            $newurl = 'index.php?action=resources';
            $content = str_replace($aHref, $newurl, $content);
        }

        // Handle different 'programs/' paths
        if ($aHref == 'programs/' || $aHref == '/programs/') {
            $newurl = 'programs';
            $content = str_replace($aHref, $newurl, $content);
        }

        if ($aHref == 'programs/balancetrack-insight/') {
            $newurl = 'programs/balancetrack-insight';
            $content = str_replace($aHref, $newurl, $content);
        }

        if ($aHref == '/programs/balance-track') {
            $newurl = 'programs/balance-track';
            $content = str_replace($aHref, $newurl, $content);
        }

        // Rewrite URL if it contains '/programs/'
        if (strpos($aHref, '/programs/') !== false) {
            $new = str_replace('https://', '', $aHref);
            $newx = explode('/', $new);
            if (isset($newx[2]) && $newx[2] != '') {
                $newsite = $base_url . 'index.php?action=programs1&amp;article=' . $newx[2];
            } else {
                $newsite = $base_url . 'index.php?action=programs1';
            }
            $content = str_replace($aHref, $newsite, $content);
        }

        // Remove trailing slashes from URLs
        if (substr($aHref, -1) == '/') {
            $newurl = substr($aHref, 0, -1);
            $content = str_replace($aHref, $newurl, $content);
        }
    }

    // Additional global replacements
    $content = str_replace('programsbalance-track', 'programs/balance-track', $content);
    $content = str_replace('https://www.balancepro.org', $parent_url, $content);
    $oldurl = 'https://' . $subdomain . '.balancepro.org/';
    $content = str_replace($oldurl, $base_url, $content);
    $content = str_replace('resources?sortBy=-views&pager=1', 'index.php?action=resources', $content);
    $content = str_replace('toolkits/balance-bites/', 'toolkits/balance-bites', $content);

    return $content;
}



/** function to modify urls in data **/
function modifyUrl($content){
	global $base_url,$subdomain,$parent_url;
	$htmlDom = new DOMDocument;
	@$htmlDom->loadHTML($content);	
	$anchorTags = $htmlDom->getElementsByTagName('a');	
	foreach($anchorTags as $anchorTag){
		$aHref = $anchorTag->getAttribute('href');
		$last = substr($aHref,-1);	
		if($last=='/'){
			$newurl = substr($aHref,0,-1);
			$content = str_replace($aHref,$newurl,$content);
		} 
	}
	$content = str_replace('https://www.balancepro.org',$parent_url,$content);
	$oldurl = 'https://'.$subdomain.'.balancepro.org/';
	$content = str_replace($oldurl,$base_url,$content);
	return $content;	
}

?>
