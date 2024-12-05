<?php
/*
Template Name: T01: Homepage
Modules: {"m1[0]":{"name":"M1: Hero"},"m34[0]":{"name":"M34: Section"},"m56_m17[0]":{"name":"M17: Four messages"},"m55[0]":{"name":"M55: Life Stages"},"m19[0]":{"name":"M19: Vertical logos"}}
*/
?>
<?php
global $additional_body_class, $data, $post;
$additional_body_class = 'homepage';
get_custom_data();

get_header();

/*echo "<pre>";
print_r($data['slider_duration']);
echo "</pre>";*/

?>
<input type="hidden" id="theOrigin" value="<?=$data['m1_module'][0]['slider_image1']['image']['fullpath'];?>"></input>
<input type="hidden" id="showslider" value="<?=$data['m1_module'][0]['showslider']['loginshow'];?>"></input>
<input type="hidden" id="sliderDuration" value="<?=$data['slider_duration'];?>"></input>
<script type="text/javascript">
	jQuery(document).ready(function() {
		var sessval = document.getElementById('theOrigin').value;
		var showslider = document.getElementById('showslider').value;
		
		if(sessval != "" && showslider != ""){
			jQuery(".banner-curve").hide();
		} else {
			jQuery(".wrapper").hide();
		}
})
</script>
<?php
if(isset($data['m1_module'][0]['slider_image1']['image']['fullpath']) && isset($data['m1_module'][0]['showslider']['loginshow'])){
?>

<style type="text/css">
	.carousel{position:relative}.carousel-inner{position:relative;width:100%;overflow:hidden}.carousel-item{position:relative;display:none;-webkit-box-align:center;-ms-flex-align:center;align-items:center;width:100%;transition:-webkit-transform .6s ease;transition:transform .6s ease;transition:transform .6s ease,-webkit-transform .6s ease;-webkit-backface-visibility:hidden;backface-visibility:hidden;-webkit-perspective:1000px;perspective:1000px}.carousel-item-next,.carousel-item-prev,.carousel-item.active{display:block}.carousel-item-next,.carousel-item-prev{position:absolute;top:0}.carousel-item-next.carousel-item-left,.carousel-item-prev.carousel-item-right{-webkit-transform:translateX(0);transform:translateX(0)}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){.carousel-item-next.carousel-item-left,.carousel-item-prev.carousel-item-right{-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}}.active.carousel-item-right,.carousel-item-next{-webkit-transform:translateX(100%);transform:translateX(100%)}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){.active.carousel-item-right,.carousel-item-next{-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}}.active.carousel-item-left,.carousel-item-prev{-webkit-transform:translateX(-100%);transform:translateX(-100%)}@supports ((-webkit-transform-style:preserve-3d) or (transform-style:preserve-3d)){.active.carousel-item-left,.carousel-item-prev{-webkit-transform:translate3d(-100%,0,0);transform:translate3d(-100%,0,0)}}.carousel-control-next,.carousel-control-prev{position:absolute;top:0;bottom:0;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;width:5%;color:#fff;text-align:center;opacity:.5}.carousel-control-next:focus,.carousel-control-next:hover,.carousel-control-prev:focus,.carousel-control-prev:hover{color:#fff;text-decoration:none;outline:0;opacity:.9}.carousel-control-prev{left:0}.carousel-control-next{right:0}.carousel-control-next-icon,.carousel-control-prev-icon{display:inline-block;width:25px;height:25px;background:transparent no-repeat center center;background-size:100% 100%}.carousel-control-prev-icon{background-image:url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3E%3Cpath d='M5.25 0l-4 4 4 4 1.5-1.5-2.5-2.5 2.5-2.5-1.5-1.5z'/%3E%3C/svg%3E")}.carousel-control-next-icon{background-image:url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23000' viewBox='0 0 8 8'%3E%3Cpath d='M2.75 0l-1.5 1.5 2.5 2.5-2.5 2.5 1.5 1.5 4-4-4-4z'/%3E%3C/svg%3E")}.carousel-indicators{position:absolute;right:0;bottom:10px;left:0;z-index:15;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;padding-left:0;margin-right:15%;margin-left:15%;list-style:none}.carousel-indicators li{position:relative;-webkit-box-flex:0;-ms-flex:0 1 auto;flex:0 1 auto;width:8px;height:1px; padding:5px;margin-right:3px;margin-left:3px;text-indent:-999px;background-color:#5555559c}.carousel-indicators li::before{position:absolute;top:-10px;left:0;display:inline-block;width:100%;height:10px;content:""}.carousel-indicators li::after{position:absolute;bottom:-10px;left:0;display:inline-block;width:100%;height:10px;content:""}.carousel-indicators .active{position:relative;-webkit-box-flex:0;-ms-flex:0 1 auto;flex:0 1 auto;width:8px;height:1px;padding:5px;margin-right:3px;margin-left:3px;text-indent:-999px;background-color:#000}.carousel-caption{position:absolute;right:15%;bottom:20px;left:15%;z-index:10;padding-top:20px;padding-bottom:20px;color:#fff;text-align:center}

		@media screen and (max-width: 5120px) {
  .carousel-item img {
    width: 90%;
  }
}

@media screen and (min-width: 1400px) {
  .carousel-item img {
    width: 1300px;
  }
}
@media screen and (min-width: 1600px) {
  .carousel-item img {
    width: 1500px;
  }
}
@media screen and (min-width: 1900px) {
  .carousel-item img {
    width: 1800px;
  }
}
@media screen and (min-width: 2560px) {
  .carousel-item img {
    width: 2460px;
  }
}

@media screen and (min-width: 3840px) {
  .carousel-item img {
    width: 3840px;
  }
}
@media screen and (min-width: 4096px) {
  .carousel-item img {
    width: 4096px;
  }
}
@media screen and (min-width: 5120px) {
  .carousel-item img {
    width: 5120px;
  }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<div class="wrapper">
	  <div class="banner text-center">
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" <?php if(isset($data['slider_duration']) && $data['slider_duration'] != ""){ ?>data-interval="<?php echo $data['slider_duration'];?>"<?php } else { ?>data-interval="5000"<?php } ?>>
		  <ol class="carousel-indicators">
				<?php if(isset($data['m1_module'][0]['slider_image1']['image']['fullpath']) && $data['m1_module'][0]['slider_image1']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image2']['image']['fullpath']) && $data['m1_module'][0]['slider_image2']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image3']['image']['fullpath']) && $data['m1_module'][0]['slider_image3']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image4']['image']['fullpath']) && $data['m1_module'][0]['slider_image4']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image5']['image']['fullpath']) && $data['m1_module'][0]['slider_image5']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image6']['image']['fullpath']) && $data['m1_module'][0]['slider_image6']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image7']['image']['fullpath']) && $data['m1_module'][0]['slider_image7']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image8']['image']['fullpath']) && $data['m1_module'][0]['slider_image8']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image9']['image']['fullpath']) && $data['m1_module'][0]['slider_image9']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="8"></li>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image10']['image']['fullpath']) && $data['m1_module'][0]['slider_image10']['image']['fullpath'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="9"></li>
				<?php } ?>
		  </ol>
		  <div class="carousel-inner">
				<?php if(isset($data['m1_module'][0]['slider_image1']['image']['fullpath']) && $data['m1_module'][0]['slider_image1']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item active">
				<?php if(isset($data['m1_module'][0]['slider_image1_url']) && $data['m1_module'][0]['slider_image1_url'] != ""){ ?>
					  <a href="<?php echo $data['m1_module'][0]['slider_image1_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image1']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image1']['image']['fullpath']; ?>"></a>
				  <?php } else {?>
					<!-- <a href="#"></a> -->
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image1']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image1']['image']['fullpath']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image2']['image']['fullpath']) && $data['m1_module'][0]['slider_image2']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				<?php if(isset($data['m1_module'][0]['slider_image2_url']) && $data['m1_module'][0]['slider_image2_url'] != ""){ ?>
					  <a href="<?php echo $data['m1_module'][0]['slider_image2_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image2']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image2']['image']['fullpath']; ?>">
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image2']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image2']['image']['fullpath']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image3']['image']['fullpath']) && $data['m1_module'][0]['slider_image3']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				<?php if(isset($data['m1_module'][0]['slider_image3_url']) && $data['m1_module'][0]['slider_image3_url'] != ""){ ?>
					  <a href="<?php echo $data['m1_module'][0]['slider_image3_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image3']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image3']['image']['fullpath']; ?>">
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image3']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image3']['image']['fullpath']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image4']['image']['fullpath']) && $data['m1_module'][0]['slider_image4']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				<?php if(isset($data['m1_module'][0]['slider_image4_url']) && $data['m1_module'][0]['slider_image4_url'] != ""){ ?>
					  <a href="<?php echo $data['m1_module'][0]['slider_image4_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image4']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image4']['image']['fullpath']; ?>">
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image4']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image4']['image']['fullpath']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image5']['image']['fullpath']) && $data['m1_module'][0]['slider_image5']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image5']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image5']['image']['fullpath']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image6']['image']['fullpath']) && $data['m1_module'][0]['slider_image6']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image6']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image6']['image']['fullpath']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image7']['image']['fullpath']) && $data['m1_module'][0]['slider_image7']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image7']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image7']['image']['fullpath']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image8']['image']['fullpath']) && $data['m1_module'][0]['slider_image8']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image8']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image8']['image']['fullpath']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image9']['image']['fullpath']) && $data['m1_module'][0]['slider_image9']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image9']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image9']['image']['fullpath']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($data['m1_module'][0]['slider_image10']['image']['fullpath']) && $data['m1_module'][0]['slider_image10']['image']['fullpath'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $data['m1_module'][0]['slider_image10']['image']['fullpath']; ?>" alt="<?php echo $data['m1_module'][0]['slider_image10']['image']['fullpath']; ?>">
				    </div>
				<?php } ?>
		  </div>
		  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
		    <span class="sr-only">Previous</span>
		  </a>
		  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		    <span class="carousel-control-next-icon" aria-hidden="true"></span>
		    <span class="sr-only">Next</span>
		  </a>
		</div>
	  </div>
	</div>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	
<?php
}

?>



<?php
/* M01 renderer */
if ( !empty( $data['m1_module'] ) && !empty( $data['m1_module'][0] ) ) {
	echo render_m1_hero( $data['m1_module'][0], 'tall' );
}

/* M34 renderer */
if ( !empty( $data['m34_module'] ) && !empty( $data['m34_module'][0] ) ) {
	echo render_m34_section_title_copy( $data['m34_module'][0] );
}

/* M56/M17 renderer */
if ( !empty( $data['m56_module'] ) && !empty( $data['m56_module'][0] ) ) {
	echo render_m56_m17_three_four_messages( $data['m56_module'][0] );
}

/* M55 renderer */
if ( !empty( $data['m55_module'] ) && !empty( $data['m55_module'][0] ) ) {
	echo render_m55_listed_life_stages( $data['m55_module'][0] );
}

/* M19 renderer */
if ( !empty( $data['m19_module'] ) && !empty( $data['m19_module'][0] ) ) {
	echo render_m19_vertical_logos( $data['m19_module'][0] );
}

get_footer();

?>
