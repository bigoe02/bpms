<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!doctype html>
<html lang="en">
    <?php include('header.php')?>
  <head>
    
    <title>La Julieta Beauty Center | About us</title>
<link rel="stylesheet" href="assets\css\style-newstarter.css">
<link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

  </head>
  <body id="home">
<?php include_once('includes/header.php');?>
<script>
$(function () {
  $('.navbar-toggler').click(function () {
    $('body').toggleClass('noscroll');
  })
});
</script>
<!-- disable body scroll which navbar is in active -->

<!-- breadcrumbs -->
    <section class="w3l-inner-banner-main">

   <div class="breadcrumbs-sub">
   <div class="container">   
    <ul class="breadcrumbs-custom-path">
        <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
        <li class="active ">About</li>
    </ul>
</div>
</div>
        </div>
    </section>
<!-- breadcrumbs //-->
<section class="w3l-content-with-photo-4"  id="about">
    <div class="content-with-photo4-block ">
        <div class="container">
            <div class="cwp4-two row">
            <div class="cwp4-image col-xl-6">
                <img src="assets/images/facialspecial.jpg" alt="product" class="img-responsive about-me">
            </div>
                <div class="cwp4-text col-xl-6 ">
                    <div class="posivtion-grid">
                    <h3 class="">Beauty and success starts here</h3>
                    <div class="hair-two-columns" id="blink">
                        <div class="hair-left">
                            <h5 class="blink-waxing">Waxing</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-facial">Facial</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-hair-makeup">Hair Makeup</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-massage">Massage</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-manicure">Manicure</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-pedicure">Pedicure</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-hair-cut">Hair Cut</h5>
                        </div>
                        <div class="hair-left">
                            <h5 class="blink-body-spa">Body Spa</h5>
                        </div>
                    </div>
            </div>
        </div>
        </div>
    </div>
</div>
</section>

<section class="w3l-recent-work">
	<div class="jst-two-col">
		<div class="container">
<div class="row">
		<div class="my-bio col-lg-6">

	<div class="hair-make">
    <div class="flip-card-container" style="--hue: 220;">
  <div class="flip-card">

    <div class="card-front">

        <img src="assets\images\about.jpg" alt="Brohm Lake">
      


    </div>

    <div class="card-back">
      <figure>
        <div class="img-bg"></div>
        <img src="assets\images\logo.jpg" alt="Brohm Lake">
      </figure>
      <div class="content-container">
     
  <ul>    <?php

$ret=mysqli_query($con,"select * from tblpage where PageType='aboutus' ");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
      <li><?php  echo $row['PageDescription'];?></li>
      
      <?php } ?></ul><br>
  <button>BOOK NOW</button>
      <div class="design-container">
        <span class="design design--1"></span>
        <span class="design design--2"></span>
        <span class="design design--3"></span>
        <span class="design design--4"></span>
        <span class="design design--5"></span>
        <span class="design design--6"></span>
        <span class="design design--7"></span>
        <span class="design design--8"></span>
      </div>
    </div>
    </div>
  </div>
</div>
<!-- /flip-card-container -->

	</div>
	
	
	</div>
	<div class="col-lg-6 ">
		<img src="assets/images/sweatox.jpg" alt="product" class="img-responsive about-me">
	</div>

</div>
		</div>
	</div>
</section>
<div class="testimonial-heading">
        <h3>CLIENTS REVIEW</h3><br>
        <div class="star-rating">
    <span class="fa fa-star yellow" style="  color: yellow;"></span>
    <span class="fa fa-star yellow"style="  color: yellow;"></span>
    <span class="fa fa-star yellow" style="  color: yellow;"></span>
    <span class="fa fa-star yellow"style="  color: yellow;"></span>
    <span class="fa fa-star yellow"style="  color: yellow;"></span>
</div>
    </div>


<section id="testimonials" class="container">
    <div class="row">
        <?php
        $ret = mysqli_query($con, "SELECT 
        poll.*, tbluser.* 
        FROM poll
        JOIN tbluser
        ON poll.email = tbluser.Email
        ORDER BY poll.id DESC LIMIT 4"); // Limit to 4 entries for a 2x2 grid
        $cnt = 1;
        while ($row = mysqli_fetch_array($ret)) {
            // Initialize feedback color variable
            $feedback = $row['feedback'];
            $feedbackColor = '';

            // Set color based on feedback content
            if (stripos($feedback, 'neutral') !== false) {
                $feedbackColor = 'black';
            } elseif (stripos($feedback, 'good') !== false) {
                $feedbackColor = 'yellow';
            } elseif (stripos($feedback, 'poor') !== false) {
                $feedbackColor = 'red';
            } elseif (stripos($feedback, 'excellent') !== false) {
                $feedbackColor = 'green';
            }
        ?>

        
            <!-- Single testimonial box in grid layout -->
            <div class="col-md-6 col-sm-12 mb-4">
                <div class="testimonial-box">
                    <div class="box-top">
                        <div class="profile">
                            <div class="profile-img">
                                <img src="assets/images/imageuser/<?php echo $row['imageUser']; ?>" />
                            </div>
                            <div class="name-user">
                                <strong><?php echo $row['name']; ?></strong>
                                <span><?php echo $row['email']; ?></span>
                            </div>
                        </div>
                        <div class="reviews" style="color: <?php echo $feedbackColor; ?>;">
                            <?php echo $feedback; ?>
                        </div>
                    </div>
                    <div class="client-comment">
                        <p><?php echo $row['suggestions']; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
    

<?php include_once('includes/footer.php');?>
<!-- move top -->
<button onclick="topFunction()" id="movetop" title="Go to top">
	<span class="fa fa-long-arrow-up"></span>
</button>
<script>
	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function () {
		scrollFunction()
	};

	function scrollFunction() {
		if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
			document.getElementById("movetop").style.display = "block";
		} else {
			document.getElementById("movetop").style.display = "none";
		}
	}

	// When the user clicks on the button, scroll to the top of the document
	function topFunction() {
		document.body.scrollTop = 0;
		document.documentElement.scrollTop = 0;
	}


</script>
<!-- /move top -->
</body>

</html>

<style>

</style>