
<!DOCTYPE html>
<html>
<head>
<title>La Julieta Beauty Center | Feedback</title>
<!-- custom-theme -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Elegant Feedback Form  Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //custom-theme -->
<link href="assets\css\feedbackstyle.css" rel="stylesheet" type="text/css" media="all" />
<link href="//fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
<!-- FAFASTAR -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<style>
	.star {
    font-size: 30px;
    color: lightgray;
    cursor: pointer;
}

.star.filled {
    color: gold;
}
</style>
<body class="agileits_w3layouts">
    <h1 class="agile_head text-center">Feedback Form</h1>
    <div class="w3layouts_main wrap">
	  <h3>Please help us to serve you better by taking a couple of minutes. </h3>
	    <form action="insertfeedback.php" method="post" class="agile_form">
		  <h2>How satisfied were you with our Service?</h2>
			 <ul class="agile_info_select">
				 <li><input type="radio" name="view" value="excellent" id="excellent" required> 
				 	  <label for="excellent">excellent</label>
				      <div class="check w3"></div>
				 </li>
				 <li><input type="radio" name="view" value="good" id="good"> 
					  <label for="good"> good</label>
				      <div class="check w3ls"></div>
				 </li>
				 <li><input type="radio" name="view" value="neutral" id="neutral">
					 <label for="neutral">neutral</label>
				     <div class="check wthree"></div>
				 </li>
				 <li><input type="radio" name="view" value="poor" id="poor"> 
					  <label for="poor">poor</label>
				      <div class="check w3_agileits"></div>
				 </li>

				 
			 </ul>	  
			<h2>If you have specific feedback, please write to us...</h2>
			<textarea placeholder="Additional comments" class="w3l_summary" name="comments" required=""></textarea>
			<input type="text" placeholder="Your Name (optional)" name="name"  />
			<input type="email" placeholder="Your Email (optional)" name="email"/>
			<input type="text" placeholder="Your Number (optional)" name="num"  /><br>
			<center>
			<h2>Rate our Employee:</h2>
				<select name="employee" required>
					<?php
					include('includes/dbconnection.php');
					// Query to retrieve AdminNames from tbladmin
					$ratingquery = "SELECT AdminName FROM tbladmin";
					$result = mysqli_query($con, $ratingquery);

					while ($row = mysqli_fetch_assoc($result)) {
						echo "<option value='" . $row['AdminName'] . "'>" . $row['AdminName'] . "</option>";
					}

					?>
				</select><br>
<div class="star-rating"><br>
	<span class="star" data-value="1"><i class="fas fa-star"></i></span>
    <span class="star" data-value="2"><i class="fas fa-star"></i></span>
    <span class="star" data-value="3"><i class="fas fa-star"></i></span>
    <span class="star" data-value="4"><i class="fas fa-star"></i></span>
    <span class="star" data-value="5"><i class="fas fa-star"></i></span>
</div><br>
<input type="hidden" name="rating" id="rating" required>
<p>Your rating: <span id="rating-value">0</span></p><br>
			<input type="submit" value="submit Feedback" class="agileinfo" style="width:35%;"/>
			</center>
<button onclick="history.back()" class="agileinfo">Return</button>
			
	  </form>
	</div>
	<div class="agileits_copyright text-center">
			<p>© 2024 </p>
	</div>
</body>
<script>
	const stars = document.querySelectorAll('.star');
const ratingInput = document.getElementById('rating');
const ratingValueDisplay = document.getElementById('rating-value');

stars.forEach(star => {
    star.addEventListener('click', () => {
        const value = star.getAttribute('data-value');
        ratingInput.value = value;
        ratingValueDisplay.textContent = value;

        // Remove filled class from all stars
        stars.forEach(s => s.classList.remove('filled'));
        
        // Fill the stars up to the clicked one
        for (let i = 0; i < value; i++) {
            stars[i].classList.add('filled');
        }
    });
});
</script>
</html>


