
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
  ?>
<!doctype html>
<html lang="en">
  <head>
    

    <title>La Julieta Beauty Center | service Page </title>

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link rel="stylesheet" href="assets/css/style-newstarter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
  </head>
  <body id="home">
<?php include_once('includes/header.php');?>

<script src="assets/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
<!--bootstrap working-->
<script src="assets/js/bootstrap.min.js"></script>
<!-- //bootstrap working-->
<!-- disable body scroll which navbar is in active -->
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
    <div class="about-inner services ">
    <div class="keyboard">
  <span class="key">T</span>
  <span class="key">O</span>
  <span class="key">P</span>
  <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> <!-- Space after TOP -->
  <span class="key">5</span>
  <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span> 
  <span class="key">S</span>
  <span class="key">E</span>
  <span class="key">R</span>
  <span class="key">V</span>
  <span class="key">I</span>
  <span class="key">C</span>
  <span class="key">E</span>
  <span class="key">S</span>

</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">Services</li>
</ul>

</div>
</div>
    </div>
</section>
<!-- breadcrumbs //-->
<section class="w3l-recent-work-hobbies" > 
    <div class="recent-work ">
        <div class="container">
            <div class="row about-about">
      
            <div class="accordian">
<?php

// Query to select the 2nd best selling service based on the tblbook table
$ret = mysqli_query($con, 
"SELECT s.ServiceName, s.Image, COUNT(DISTINCT b.ServiceName) as booking_count 
    FROM tblservices s
    JOIN (
        SELECT DISTINCT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(b.BookService, ',', numbers.n), ',', -1)) AS ServiceName
        FROM tblbook b
        INNER JOIN (
            SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5
            UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10
        ) numbers ON CHAR_LENGTH(b.BookService) - CHAR_LENGTH(REPLACE(b.BookService, ',', '')) >= numbers.n - 1
        WHERE b.Status = 'Selected'
    ) AS b ON s.ServiceName = b.ServiceName
    GROUP BY s.ServiceName, s.Image
    ORDER BY booking_count DESC
    LIMIT 5
");

?>
<?php
$cnt = 1; // Initialize count variable
?>
<ul>
<?php
while ($row = mysqli_fetch_array($ret)) {
    ?>
    <li>
        <div class="image_title">
            <a href="#"><?php echo htmlspecialchars($row['ServiceName']); ?></a> <!-- Displaying the Service Name -->
        </div> 

            <a href="#">
                <img src="assets/images/imageservices/<?php echo htmlspecialchars($row['Image']); ?>"/> <!-- Displaying the Image -->
            </a>
            <span class="count"><?php echo $cnt; ?></span> <!-- Displaying the Count -->
        
    </li>
    <?php $cnt++;
}
?>
</ul>
</div>
<!-- ssssssssssssssssssssssssssssssssssssssssssssssss -->
                <?php
                

$ret=mysqli_query($con,"select * from  tblservices limit 4");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
                <div class="col-lg-6 col-md-6 col-sm-6 propClone">
                 <img src="admin/images/<?php echo $row['Image']?>" alt="product" height="700" width="800" class="img-responsive about-me">
                   
                    
                    <div class="about-grids ">
                        <hr>
                        <br> <br>
                           
                    </div>
                </div>
                <br>
<?php $cnt=$cnt+1;}?>
            
            </div>
        </div>
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

  $(document).ready(function() {
    $('.accordian li .count').addClass('hide');

    let currentHovered = null; // Variable to keep track of the currently hovered item

    // When hovering over any image
    $('.accordian li').hover(
        function() {
            // Hide all counts
            $('.count').addClass('hide');
            // Show the count for the hovered image
            $(this).find('.count').removeClass('hide');
            currentHovered = $(this); // Set the current hovered item
        }, 
        function() {
            // Hide all counts when mouse leaves
            $('.count').addClass('hide');
            currentHovered = null; // Reset the current hovered item
        }
    );

    // On page load, hide all counts if there is a current hovered item
    if (currentHovered) {
        $('.count').addClass('hide');
        currentHovered.find('.count').removeClass('hide');
    }
});
</script>
<!-- /move top -->
</body>

</html>