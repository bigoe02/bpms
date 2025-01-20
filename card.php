<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
  ?>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<div class="accordian">
<?php
// Assuming you have already established a connection to the database in $con

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

<style>

.accordian {
	width: 805px; height: 320px;
	overflow: hidden;
	
	/*Time for some styling*/
	margin: 100px auto;
	box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.35);
	-webkit-box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.35);
	-moz-box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.35);
}

/*A small hack to prevent flickering on some browsers*/
.accordian ul {
	width: 1200px;
	/*This will give ample space to the last item to move
	instead of falling down/flickering during hovers.*/
}

.accordian li {
	position: relative;
	display: block;
	width: 160px;
	float: left;
	
	border-left: 1px solid #888;
	
	box-shadow: 
        0 0 10px 1px rgba(255, 192, 203, 0.5), /* Light pink */
        0 0 20px 5px rgba(255, 105, 180, 0.5), /* Hot pink */
        0 0 30px 10px rgba(255, 20, 147, 0.5); /* Deep pink */
    
    /*Transitions to give animation effect*/
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
    -moz-transition: all 0.5s;
	/*If you hover on the images now you should be able to 
	see the basic accordian*/
}

/*Reduce with of un-hovered elements*/
.accordian ul:hover li {width: 40px;}
/*Lets apply hover effects now*/
/*The LI hover style should override the UL hover style*/
.accordian ul li:hover {width: 640px;}


.accordian li img {
	display: block;
	width: 500px;
	height: 100%;
}

/*Image title styles*/
.accordian .image_title {
	background: linear-gradient(to right, #FFE548, pink);
	position: absolute;
	left: 0; bottom: 0;	
width: 500px;	

}
.accordian .image_title a {
	display: block;
	color: #fff;
	text-decoration: none;
	font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
	padding: 20px;
	font-size: 30px;

}


.accordian  .count {
    position: absolute;
    top: 50px; /* Adjust as needed */
    right: 10px; /* Adjust as needed */
    color: black; /* Text color */
	font-size: 150px;
    padding: 5px; /* Padding around the count */
    border-radius: 5px; /* Rounded corners */
	opacity: 1; /* Fully visible by default */
    transition: opacity 0.5s ease; /* Smooth transition for opacity */
}

.accordian .hide {
    opacity: 0; /* Hidden state */
}


/* KEYBOARD */
.keyboard {
    display: flex; /* Use flexbox */
    justify-content: center; /* Center horizontally */
    margin: 0 auto; /* Center the keyboard in the middle */
}

.keyboard .key {
  font-size: 9vw;
  display: inline-block;
  letter-spacing: -1vw;
  transition: transform 0.2s;
  /* background-color: #101013; */
  color: black;
  justify-content: center;
  align-items: center;
  height: 22vh;
  
  font-family: "Poppins", sans-serif;
  font-weight: 900;
}



@keyframes pressDown1 {
  30%,
  40%,
  100% {
    transform: translateY(0);
  }
  35% {
    transform: translateY(10px);
  }
}

@keyframes pressDown2 {
  70%,
  80%,
  100% {
    transform: translateY(0);
  }
  75% {
    transform: translateY(10px);
  }
}

@keyframes pressDown3 {
  30%,
  40%,
  100% {
    transform: translateY(0);
  }
  35% {
    transform: translateY(10px);
  }
}

@keyframes pressDown4 {
  40%,
  50%,
  100% {
    transform: translateY(0);
  }
  45% {
    transform: translateY(10px);
  }
}

@keyframes pressDown5 {
  20%,
  30%,
  100% {
    transform: translateY(0);
  }
  25% {
    transform: translateY(10px);
  }
}

@keyframes pressDown6 {
  60%,
  70%,
  100% {
    transform: translateY(0);
  }
  65% {
    transform: translateY(10px);
  }
}

@keyframes pressDown7 {
  10%,
  20%,
  100% {
    transform: translateY(0);
  }
  15% {
    transform: translateY(10px);
  }
}

@keyframes pressDown8 {
  35%,
  45%,
  100% {
    transform: translateY(0);
  }
  40% {
    transform: translateY(10px);
  }
}

@keyframes pressDown9 {
  60%,
  70%,
  100% {
    transform: translateY(0);
  }
  65% {
    transform: translateY(10px);
  }
}

@keyframes pressDown10 {
  30%,
  40%,
  100% {
    transform: translateY(0);
  }
  35% {
    transform: translateY(10px);
  }
}

@keyframes pressDown01 {
  30%, 40%, 100% {
    transform: translateY(0);
  }
  35% {
    transform: translateY(10px);
  }
}

@keyframes pressDown02 {
  20%, 30%, 100% {
    transform: translateY(0);
  }
  25% {
    transform: translateY(10px);
  }
}


.keyboard .key:nth-child(1) {
  animation: pressDown1 2s infinite;
}

.keyboard .key:nth-child(2) {
  animation: pressDown2 3s infinite;
}

.keyboard .key:nth-child(3) {
  animation: pressDown3 4s infinite;
}

.keyboard .key:nth-child(4) {
  animation: pressDown4 2.5s infinite;
}

.keyboard .key:nth-child(5) {
  animation: pressDown5 2.5s infinite;
}

.keyboard .key:nth-child(6) {
  animation: pressDown6 3.5s infinite;
}

.keyboard .key:nth-child(7) {
  animation: pressDown7 2.2s infinite;
}

.keyboard .key:nth-child(8) {
  animation: pressDown8 3.2s infinite;
}
.keyboard .key:nth-child(9) {
  animation: pressDown9 1.2s infinite;
}
.keyboard .key:nth-child(10) {
  animation: pressDown10 2.0s infinite;
}
.keyboard .key:nth-child(13) { /* This is the 'C' key */
  animation: pressDown01 0.9s infinite;
}

.keyboard .key:nth-child(14) { /* This is the 'E' key */
  animation: pressDown02 2.5s infinite;
}




/* extra stuff */
.keyboard .jux-linx {
  display: flex;
  flex-direction: row;
  align-items: center;
  flex-wrap: wrap;
  justify-content: flex-start;
  gap: 10px;
  position: absolute;
  left: 20px;
  bottom: 20px;
}

.keyboard a {
  text-decoration: none;
  font-family: "IBM Plex Sans", sans-serif;
  font-weight: 400;
  font-size: 16px;
  color: white;
  background-color: black;
  border: 1px solid rgba(255, 255, 255, 0.3);
  border-radius: 2px;
  padding: 5px 10px;
  transition: 0.1s all ease-in;
}

.keyboard a:nth-child(1):hover {
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0px 2px 0 #349eff;
}

.keyboard a:nth-child(2):hover {
  border: 1px solid rgba(255, 255, 255, 0.4);
  box-shadow: 0px 2px 0 #ff5757;
}
</style>
<script>
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

<meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>LA JULIETA BEAUTY CENTER</title>

    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@500&amp;display=swap" rel="stylesheet"> 

   <!-- <script src='https://kit.fontawesome.com/c8e4d183c2.js'></script> -->
   
    

   <script src="assets/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
<!--bootstrap working-->
<script src="assets/js/bootstrap.min.js"></script>
<!-- //bootstrap working-->
<!-- disable body scroll which navbar is in active -->