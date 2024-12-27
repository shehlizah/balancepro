
<?php
$db_encoding    = 'utf8';
//$user = 'balancepro';
/* End config */



$dsn = 'mysql:host='.$host.';dbname='.$database.";charset=UTF8";

$db = new PDO($dsn, $dbuser, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


$getlifeData = "SELECT * FROM wp_postmeta WHERE post_id = '1205' AND meta_key = '_page_edit_data'";

                $lifeResults = $db->prepare($getlifeData);

                        $lifeResults->execute();
                        $lifeResult[]=$lifeResults->fetchAll();
$lifepostmetaData = unserialize($lifeResult[0][0]['meta_value']);

$getstageData = "SELECT * FROM wp_postmeta WHERE post_id IN(50,183,184,185,186) AND meta_key = '_page_edit_data'";

                $stageResults = $db->prepare($getstageData);

                        $stageResults->execute();
                        $stageResult=$stageResults->fetchAll();
/*$lifeStageData = unserialize($stageResult[0][1]['meta_value']);

echo "<br><br><br><br><br><br>";
echo "<pre>";
//print_r($stageResult);
print_r($lifeStageData['life_stage_homepage_info_module']);
//print_r($postmetaData['m1_module'][0]);
echo "</pre>";
*/
$lifebannerImage = $lifepostmetaData['m1_module'][0]['bgimage']['image']['fullpath'];
$lifebannerTitle = $lifepostmetaData['m1_module'][0]['title'];
$lifebannerCopy = $lifepostmetaData['m1_module'][0]['copy'];
$lifeContent = $lifepostmetaData['m55_module'][0]['copy'];
/*
$lifestageTypeData = $lifeStageData['life_stage_homepage_info_module'];
$lsTitle = $lifestageTypeData[0]['title'];
$lsImage = $lifestageTypeData[0]['image']['image']['fullpath'];
$lsBullet1 = $lifestageTypeData[0]['bullets'][0]['copy'];
$lsBullet2 = $lifestageTypeData[0]['bullets'][1]['copy'];
$lsBullet3 = $lifestageTypeData[0]['bullets'][2]['copy'];
$lsButton = $lifestageTypeData[0]['buttontext'];

*/

$getWlwData = "SELECT * FROM wp_postmeta WHERE post_id = '50' AND meta_key = '_page_edit_data'";

                $wlwResults = $db->prepare($getWlwData);

                        $wlwResults->execute();
                        $wlwResult[]=$wlwResults->fetchAll();
$postmetaData = unserialize($wlwResult[0][0]['meta_value']);
/*echo "<br><br><br><br><br><br>";
echo "<pre>";
print_r($postmetaData);
print_r($postmetaData['m1_module'][0]);
echo "</pre>";*/
?>



<main id="main" role="main">
    <style>
    .report-block img {
        height: 315px;
        width: auto;
    }

    .report-block .image-holder,
    .report-block .copy-holder {
        width: 50%;
    }

    .report-block .copy-holder {
        position: relative;
        padding: 0 38px;
        text-align: left;
    }

    @media  screen and (max-width: 1024px) {
        .report-block .image-holder {
            width: 100%;
        }

        .report-block .image-holder img {
            width: 100%;
            height: auto !important;
        }

        .report-block .copy-holder {
            width: 100%;
            padding-top: 25px;
            padding-bottom: 25px;
            top: 0;

            -webkit-transform: translateY(0%);
            -ms-transform: translateY(0%);
            transform: translateY(0%);
        }
    }
</style>

<?php/*
foreach($stageResult as $lifeStageData){
$lifepostmetaData = unserialize($lifeStageData[0][0]['meta_value']);

$lifebannerImage = $lifepostmetaData['m1_module'][0]['bgimage']['image']['fullpath'];
$lifebannerTitle = $lifepostmetaData['m1_module'][0]['title'];
$lifebannerCopy = $lifepostmetaData['m1_module'][0]['copy'];
$lifeContent = $lifepostmetaData['m55_module'][0]['copy'];

$lifestageTypeData = $lifeStageData['life_stage_homepage_info_module'];
$lsTitle = $lifestageTypeData[0]['title'];
$lsImage = $lifestageTypeData[0]['image']['image']['fullpath'];
$lsBullet1 = $lifestageTypeData[0]['bullets'][0]['copy'];
$lsBullet2 = $lifestageTypeData[0]['bullets'][1]['copy'];
$lsBullet3 = $lifestageTypeData[0]['bullets'][2]['copy'];
$lsButton = $lifestageTypeData[0]['buttontext'];
*/
?>
<div style="background-image: url(<?php echo $lifebannerImage;?>);" class="newbanner banner text-center">
	<div class="container">
		<div class="row">
			<div class="banner-block short">
				<div class="banner-text">
					<h1><?php echo $lifebannerTitle;?></h1>
					<p><?php echo $lifebannerCopy;?></p>
				</div>
			</div>
		</div>
	</div>
</div>
<section aria-label="stages" class="block">
    <div class="container">
        <div class="row">

<article  class="text-block article default-content-style" aria-label="module for article <?php echo $contentTitle;?>">

	    	<?php echo $lifeContent;?>

</article>


<div class="thumbnail-holder">


<?php
/*
//print_r($stageResults->fetchAll());
foreach($stageResult as $lifeStageData){
//echo "<pre>";
//print_r($lifeStageData[0]['meta_value']);echo "</pre>";
$lifepostmetaDatas = unserialize($lifeStageData[0]['meta_value']);

//print_r($lifepostmetaDatas);echo "</pre>";
}*/
//echo "%%".count($stageResult);
//print_r($stageResult);

$val = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//        echo "$$".$val."$$";
$newurl = explode("?", $val);
//echo $newurl[0];


foreach($stageResult as $key => $lifeStageData){
$lifepostmetaData = unserialize($lifeStageData['meta_value']);
$thumbnaildata = $lifepostmetaData['life_stage_homepage_info_module'];
$lifebannerImage = $lifepostmetaData['m1_module'][0]['bgimage']['image']['fullpath'];
$lifebannerTitle = $lifepostmetaData['m1_module'][0]['title'];
$lifebannerCopy = $lifepostmetaData['m1_module'][0]['copy'];
$lifeContent = $lifepostmetaData['m55_module'][0]['copy'];

$lifestageTypeData = $lifepostmetaData['life_stage_homepage_info_module'];
$lsTitle = $lifestageTypeData[0]['title'];
$lsImage = $lifestageTypeData[0]['image']['image']['fullpath'];
$lsBullet1 = $lifestageTypeData[0]['bullets'][0]['copy'];
$lsBullet2 = $lifestageTypeData[0]['bullets'][1]['copy'];
$lsBullet3 = $lifestageTypeData[0]['bullets'][2]['copy'];
$lsButton = $lifestageTypeData[0]['buttontext'];
//$lsurl = $newurl[0]."?action=lifestages&lifestagetype=".str_replace("'","",str_replace(' ', '-', strtolower($lsTitle)));

$lsurl = $newurl[0]."?action=".str_replace("'","",str_replace(' ', '', $lsTitle));
?>

<div class="col">
		<div class="thumbnail" style="border: 1px solid #446c68;">
			<div class="img-holder">
				<a href="<?php echo $lsurl; ?>"><img alt="<?php echo $lsTitle; ?>" src="<?php echo $lsImage;?>" width="207" height="200"></a>
			</div>
			<a href="<?php echo $lsurl; ?>"></a>
			<div class="backcolor caption">
				<a href="<?php echo $lsurl; ?>"></a>
				<h2 class="h3">
				<a href="<?php echo $lsurl; ?>"></a>
				<a href="<?php echo $lsurl; ?>"><?php echo $lsTitle; ?></a>
				</h2><a href="<?php echo $lsurl; ?>">
					<ul class="list same-height-left" style="height: 197.906px;font-weight: 500; ">
						<li>
							<p><?php echo $lsBullet1; ?></p>
						</li>
						<li>
							<p><?php echo $lsBullet2; ?></p>
						</li>
						<li>
							<p><?php echo $lsBullet3; ?></p>
						</li>
					</ul>
					</a>
					<div class="btn-holder text-center">
						<a role="button" aria-label="<?php echo $lsTitle; ?>" href="<?php echo $lsurl; ?>" class=" but btn btn-primary"><?php echo $lsButton; ?></a>
					</div>
				</div>
			</div>
		</div>



<?php
 } 

?>
<!--<h1 class="text-info text-center"><?php echo $_SESSION['successtitle'];?></h1>-->
<h1 class="text-info text-center"><?php //echo $postmetaData['post_title'];?></h1>
<?php 
//print_r($postmetaData['m34_module'][0]['copy']);
?>

<?php 
//echo $data = getValidUrlsFrompage($data);
//echo $_SESSION['successstory'] ;//= getValidUrlsFrompage($data);


?>
</div>
</div>
</div>
</section>
            </main>
