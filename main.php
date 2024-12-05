<?php 
include '../includes/func_resource.php';
//$style_pop='display:none';
//$_SESSION['firstlogin']=1;
if(isset($_SESSION['firstlogin']) && $_SESSION['firstlogin']==1){
	 //$style_pop='';
?>
<script>
$(document).ready(function(){
 $("#modalThank").modal();   
});
</script>
<?php	 
}
?>

<script>
$(document).ready(function(){
	var testVal = $("#tlsVersion").val();
	if(testVal == "2"){
	 $("#modalTLS").modal();   
	}
});
</script>


<main id="main" role="main">
<?php
if(isset($_SESSION['firstlogin_err']) && $_SESSION['firstlogin_err']!=''){
?>
<div class="alert alert-warning">
  <span class="closebtn">&times;</span>  
  <?php echo $_SESSION['firstlogin_err']?>
</div>

<?php
}
if(isset($_SESSION['message']) && $_SESSION['message']!=''){
?>
	<div class="alert alert-success">
  <span class="closebtn">&times;</span>  
  <?php echo $_SESSION['message']?>
	</div>
<?php
}	
unset($_SESSION['message']);
unset($_SESSION['firstlogin']);
unset($_SESSION['firstlogin_err']);
?>
<div id="modalThank" tabindex="-1" role="dialog" class="modal fade">
	<div  class="modal-dialog">
		<div class="modal-content">
			 <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">X</span></button>    <!-- &#215; -->
			<div class="alert " role="alert">  
				<?php 
				if(isset($_SESSION['thankYou_text']) && $_SESSION['thankYou_text'] !=''){
					echo str_replace(array('%site_url%','%site_title%'),array($base_url,$_SESSION['title']),$_SESSION['thankYou_text']);
				} else {
				?>
				<h1 class="text-info text-center">Thank you for verifying your email address</h1>
				<p>Thank you for completing your registration with BALANCE, in partnership with <span class="text-info"><b><?=$_SESSION['title']?></b></span>. You’ve taken a step to financial fitness by being an active participant in your own financial wellness.</p>
				<p>Your registration gives you access to more BALANCE online education programs available on this website. Use your email address and BALANCE password to log in to a program when prompted.</p>
				<p>We hope you enjoy your experience with BALANCE, in partnership with <span class="text-info"><b><?=$_SESSION['title']?></b></span>. We are here to help you achieve your financial success.</p>
				<p>Best,</p>
				<p>BALANCE in partnership with <span class="text-info"><b><?=$_SESSION['title']?></b></span></p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>




<div id="modalTLS" tabindex="-1" role="dialog" class="modal fade">
	<div  class="modal-dialog">
		<div class="modal-content">
			 <!-- <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">X</span></button> -->    <!-- &#215; -->
			<div class="alert " role="alert">   
        <h1 class="text-info text-center">Warning: Incompatibility Issue</h1>
	<p>For the best viewing experience, please upgrade your Transport Layers Security to version 1.2 or greater.</p>
				<p style="text-align: center;"><button type="button" data-dismiss="modal" aria-label="Close">OK</button></p>
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="tlsVersion"></input>

<input type="hidden" id="theOrigin" value="<?=$_SESSION['slider_image1'];?>"></input>
<input type="hidden" id="bannershow" value="<?=$_SESSION['bannershow'];?>"></input>
<input type="hidden" id="showslider" value="<?=$_SESSION['showslider'];?>"></input>
<script type="text/javascript">
	$(document).ready(function() {
		var sessval = document.getElementById('theOrigin').value;
		var bannershow = document.getElementById('bannershow').value;
		var showslider = document.getElementById('showslider').value;
		$("#login-wrap").hide();
		
//alert(showslider+"%%"+bannershow);
		if(sessval != ""){
			if ($("div.banner").length == 2){
				//alert("CLASS EXISTS");
    			// Do something if class exists
				$(".banner").removeAttr("style");
				$(".banner").html($(".wrapper").html());
				$(".wrapper").html('');
			} else {
				//alert("CLASS NOT EXISTS");
				$("#text").hide();
				if($("article").length == 0) {

				} else {
					$("article").first().prepend('<div class="banner"></div>');
					$(".banner").html($(".wrapper").html());
					$(".wrapper").html('');
				}
			}


//alert($(".slide-nav").find("span").is("class"));
/*alert($(".slide").length);
alert($(".slide-nav").find("span").length);*/
			/*if($(".slide").length < $(".slide-nav").find("span").length){
				var slidelength11 = ($(".slide").length)-1;
				var slidelength = ($(".slide").length);
				var slidelength1 = ($(".slide").length)+1;
				var slidelength2 = ($(".slide").length)+2;
				var slidelength3 = ($(".slide").length)+3;
				var slidelength4 = ($(".slide").length)+4;
				var slidelength5 = ($(".slide").length)+5;
/*alert(slidelength1);*

				$(".slide-nav span:gt("+slidelength11+")").hide();
				$(".slide-nav span:gt("+slidelength+")").hide();
				$(".slide-nav span:gt("+slidelength1+")").hide();
				$(".slide-nav span:gt("+slidelength2+")").hide();
				$(".slide-nav span:gt("+slidelength3+")").hide();
				$(".slide-nav span:gt("+slidelength4+")").hide();
				$(".slide-nav span:gt("+slidelength5+")").hide();
			}
		        $(".slide-nav").find("span").removeAttribute("hidden");
			var displaySpan = $(".slide-nav").find("span").is("class");
			if (displaySpan == true) {
		        $(".slide-nav").find("span").removeAttribute("hidden");
		     } 
		     if (displaySpan == false) {
		        $(".slide-nav").find("span").attr("hidden", "hidden");
		     }*/
		}
})
</script>
<?php
//echo removeResourseUrl($mainhtml);
echo str_replace("/resources/?sortBy=-views&pager=1","/index.php?action=resources",$mainhtml);
//echo removeProgramUrl($mainhtml);


if(isset($_SESSION['slider_image1']) && isset($_SESSION['showslider'])){
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
	  <div class="newbanner banner text-center">
		<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
		  <ol class="carousel-indicators">
				<?php if(isset($_SESSION['slider_image1']) && $_SESSION['slider_image1'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image2']) && $_SESSION['slider_image2'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image3']) && $_SESSION['slider_image3'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image4']) && $_SESSION['slider_image4'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image5']) && $_SESSION['slider_image5'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image6']) && $_SESSION['slider_image6'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="5"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image7']) && $_SESSION['slider_image7'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="6"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image8']) && $_SESSION['slider_image8'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="7"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image9']) && $_SESSION['slider_image9'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="8"></li>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image10']) && $_SESSION['slider_image10'] != ""){ ?>
					<li data-target="#carouselExampleIndicators" data-slide-to="9"></li>
				<?php } ?>
		  </ol>
		  <div class="carousel-inner">
				<?php if(isset($_SESSION['slider_image1']) && $_SESSION['slider_image1'] != ""){ ?>
					<div class="carousel-item active">
				<?php if(isset($_SESSION['slider_image1_url']) && $_SESSION['slider_image1_url'] != ""){ ?>
					  <a href="<?php echo $_SESSION['slider_image1_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image1']; ?>" alt="<?php echo $_SESSION['slider_image1']; ?>"></a>
					  
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image1']; ?>" alt="<?php echo $_SESSION['slider_image1']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image2']) && $_SESSION['slider_image2'] != ""){ ?>
					<div class="carousel-item">
				<?php if(isset($_SESSION['slider_image2_url']) && $_SESSION['slider_image2_url'] != ""){ ?>
					  <a href="<?php echo $_SESSION['slider_image2_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image2']; ?>" alt="<?php echo $_SESSION['slider_image2']; ?>"></a>
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image2']; ?>" alt="<?php echo $_SESSION['slider_image2']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image3']) && $_SESSION['slider_image3'] != ""){ ?>
					<div class="carousel-item">
				<?php if(isset($_SESSION['slider_image3_url']) && $_SESSION['slider_image3_url'] != ""){ ?>
					  <a href="<?php echo $_SESSION['slider_image3_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image3']; ?>" alt="<?php echo $_SESSION['slider_image3']; ?>"></a>
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image3']; ?>" alt="<?php echo $_SESSION['slider_image3']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image4']) && $_SESSION['slider_image4'] != ""){ ?>
					<div class="carousel-item">
				<?php if(isset($_SESSION['slider_image4_url']) && $_SESSION['slider_image4_url'] != ""){ ?>
					  <a href="<?php echo $_SESSION['slider_image4_url']; ?>">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image4']; ?>" alt="<?php echo $_SESSION['slider_image4']; ?>"></a>
				  <?php } else {?>
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image4']; ?>" alt="<?php echo $_SESSION['slider_image4']; ?>">
				  <?php } ?>
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image5']) && $_SESSION['slider_image5'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image5']; ?>" alt="<?php echo $_SESSION['slider_image5']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image6']) && $_SESSION['slider_image6'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image6']; ?>" alt="<?php echo $_SESSION['slider_image6']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image7']) && $_SESSION['slider_image7'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image7']; ?>" alt="<?php echo $_SESSION['slider_image7']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image8']) && $_SESSION['slider_image8'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image8']; ?>" alt="<?php echo $_SESSION['slider_image8']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image9']) && $_SESSION['slider_image9'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image9']; ?>" alt="<?php echo $_SESSION['slider_image9']; ?>">
				    </div>
				<?php } ?>
				<?php if(isset($_SESSION['slider_image10']) && $_SESSION['slider_image10'] != ""){ ?>
					<div class="carousel-item">
				      <img class="d-block w-100 banner" src="<?php echo $_SESSION['slider_image10']; ?>" alt="<?php echo $_SESSION['slider_image10']; ?>">
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
	<script src="/assets/js/slider.js"></script>
	<?php
	 if(isset($_SESSION['slider_duration']) && $_SESSION['slider_duration'] != ""){ ?>
		<script>
		$('.carousel').carousel({
	  		interval: <?php echo $_SESSION['slider_duration'];?>
        });
	</script>
	<?php } else {?>
		<script>
		$('.carousel').carousel({
	  		interval: 5000
        });
	</script>
	<?php }?>
<?php
}

?>
</main>



<script type="text/javascript">

	document.addEventListener('keydown', function() {
  if (event.keyCode == 123) {
    //alert("This function has been disabled to prevent you from stealing my code!");
    return false;
  } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) {
    //alert("This function has been disabled to prevent you from stealing my code!");
    return false;
  } else if (event.ctrlKey && event.keyCode == 85) {
    //alert("This function has been disabled to prevent you from stealing my code!");
    return false;
  }
}, false);

window.parseTLSinfo = function(data) {
  var version = data.tls_version.split(' ');
//alert(version);
  /*alert(
    version[0] != 'TLS' || version[1] < 1.2
    ? 'So bad! Your browser only supports ' + data.tls_version + '. Please upgrade to a browser with TLS 1.2 support.'
    : 'All OK. Your browser supports ' + data.tls_version + '.'
  );
  console.log(data);*/
  //jQuery("#modalTLS").modal();
   if(version[0] != 'TLS' || version[1] < 1.2){
          $("#tlsVersion").val("2");
    } else {
					$("#tlsVersion").val("1");
    }
};
//});

</script>
<script src="https://www.howsmyssl.com/a/check?callback=parseTLSinfo"></script>
