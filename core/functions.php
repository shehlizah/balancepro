<?php

function resourcesUrl($redirectLocation){
	global $base_url;
	//$new = str_replace('/resources/','',$redirectLocation);
	//$newx = explode('/',$new);
	//print_r($newx);
	//$id = str_replace('/','',$newx[1]);
	//$type='articles';//$newx[0];
	//$type=$newx[0];
	///resources/articles/credit-union-programs-debt-and-budget-coaching/
	//$newsite=$base_url.'index.php?action=resources1&type='.$type.'&id='.$id;
	//return $newsite;
       return $base_url.'resources/'.$redirectLocation;
}
?>
