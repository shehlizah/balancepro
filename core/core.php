<?php
	error_reporting(E_ALL);
	include("../db/database.php");
	$personData = json_decode($_REQUEST['data']);
    $action = $personData->action;
    if($action == 'rsearchbtn'){
    	$dvalue = $personData->dvalue;
    	$table_name = "wp_resources";
    	$json['output'] = '';
    	//$column_name = "type";
    	$result1 = $db->prepare("SELECT * FROM wp_resources WHERE type = '$dvalue' limit 9");
    	$result1->execute();$countthem = $result1>rowCount();
    	//fetch
    	$i = '1';
    	while($results = $result1->fetch(PDO::FETCH_ASSOC)){
    		$titlen = $results['title'];
    		$json['output'] .= '<div class="resource-column same-height-holder">
                				<!-- resource resource in resources starts -->
                              	<div class="col-sm-6 col-md-4">
	                              <!-- resource block starts -->
	                                <div class="resource-block">
	                                  <div class="img-holder same-height"><span class="icon-article"></span></div>
	                                  <div class="text-holder">
	                                    <p class="dot-holder">'.$titlen.'</p>
	                                    <div class="btn-tag same-height" style="display: none;">
	                                      <a class="tag"></a>
	                                    </div>
	                                    <a target="_self" class="text-view">' . __('VIEW', 'balance').'</a>
	                                  </div>
	                                  <span class="icon-lock"></span>
	                                </div>
	                                <!-- resource block ends -->
                              	</div>
                              	<!-- resource resource in resources ends -->
            					</div>';
	    	<?php 
	    	$i++;
	    	}
    		 $json['success'] = 'working';
    		 $json['message'] = 'working serach result';
    }
	//encode JSON
	echo json_encode($json);
?>