<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
error_reporting(0);

$recaptcha_secret = '6LcBWIQqAAAAANVA4ss9o4fDhwnCmFy-FCJVdqBv';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $responseKeys = json_decode($response, true);


if(intval($responseKeys["success"]) == 1) {

if(isset($_POST['submit']))
  {
        $fname=$_POST['firstname'];
        $lname=$_POST['lastname'];
        $contno=$_POST['mobilenumber'];
        $email=$_POST['email'];
        $password=md5($_POST['password']);

        $image=$_POST['image'];
        $image=$_FILES["image"]["name"];
            // get the image extension
            $extension = substr($image,strlen($image)-4,strlen($image));
            // allowed extensions
            $allowed_extensions = array(".jpg","jpeg",".png");
            // Validation for allowed extensions .in_array() function searches an array for a specific value.
            if(!in_array($extension,$allowed_extensions))
            {
                $alertMessage .= "<script>showAlert('Error', 'Invalid format. Only jpg / jpeg/ png /gif format allowed', 'error');</script>";

            }
            else
            {
                                //rename the image file
                $newimage=md5($image).time().$extension;
                // Code for move image into directory
                move_uploaded_file($_FILES["image"]["tmp_name"],"assets/images/imageuser/".$newimage);

                $ret=mysqli_query($con, "select Email from tbluser where Email='$email' || MobileNumber='$contno'");
                $result=mysqli_fetch_array($ret);
                if($result>0){

                    $alertMessage .= "<script>showAlert('Warning', 'This email or Contact Number already associated with another account!.' , 'warning');</script>";
                            }
                            else{
                                    $query=mysqli_query($con, "insert into tbluser(FirstName, LastName, MobileNumber, imageUser, Email, Password) value('$fname', '$lname','$contno', '$newimage','$email', '$password' )");
                                    if ($query) 
                                        {
                                            $alertMessage .= "<script>showAlert('Success','You have successfully registered.','success');</script>";
                                        }
                                        else
                                        {
                                        
                                        $alertMessage ="<script>showAlert('Error', 'Something Went Wrong. Please try again.' , 'error');</script>";
                                        }
                                }
            }
    }
}

?>
<!doctype html>
<html lang="en">
  <head>
 

    <title>Beauty Parlour Management System | Signup Page</title>

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css">
    <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
<!-- SWEETALERT -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/alert.js"></script>
        <!-- RECAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
    <div class="about-inner contact ">
        <div class="container">   
            <div class="main-titles-head text-center">
            <h3 class="header-name ">
                
 Signup
            </h3>
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        Signup</li>
</ul>
</div>
</div>
    </div>
</section>
<!-- breadcrumbs //-->
<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec	">
        <div class="container">

            <div class="d-grid contact-view">
                <div class="cont-details">
                    <?php

$ret=mysqli_query($con,"select * from tblpage where PageType='contactus' ");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>
                    <div class="cont-top">
                        <div class="cont-left text-center">
                            <span class="fa fa-phone text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Call Us</h6>
                            <p class="para"><a href="tel:+44 99 555 42">+<?php  echo $row['MobileNumber'];?></a></p>
                        </div>
                    </div>
                    <div class="cont-top margin-up">
                        <div class="cont-left text-center">
                            <span class="fa fa-envelope-o text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Email Us</h6>
                            <p class="para"><a href="mailto:example@mail.com" class="mail"><?php  echo $row['Email'];?></a></p>
                        </div>
                    </div>
                    <div class="cont-top margin-up">
                        <div class="cont-left text-center">
                            <span class="fa fa-map-marker text-primary"></span>
                        </div>
                        <div class="cont-right">
                            <h6>Address</h6>
                            <p class="para"> <?php  echo $row['PageDescription'];?></p>
                        </div>
                    </div>
                    <div class="cont-top margin-up">
                        <div class="cont-left text-center">
                            <span class="fa fa-map-marker text-primary"></span>
                        </div>
                        <div class="cont-right">
                        <h6>Time / Day</h6>
                            <p class="para"> <?php echo $row['Timing']; ?> - Tuesday To Sunday</p>
                            <p class="para"> CLOSED every Monday</p>
                        </div>
                    </div>
               <?php } ?> </div>
                <div class="map-content-9 mt-lg-0 mt-4">
                <div class="loginform-design" style="height: 100%;">
                                <div class="container-login" style="width: 100%; height: 100%;">
                <div class="brand-logo" style="background:url('assets/images/signuplogo.png');  background-size: cover;  border-radius: 10%;"></div>
                    <div class="brand-title">LA JULIETA BEAUTY CENTER</div>  
                    <form method="post" name="signup" onsubmit="return checkpass();" enctype="multipart/form-data">

                        <div class="twice-two">
                            <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name" required="true" autocomplete="off" pattern="[A-Za-z]+" title="Please enter letters only.">
                            <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name" required="true" autocomplete="off" pattern="[A-Za-z]+" title="Please enter letters only.">
                        </div>
                        <div class="twice-two">
                           <input type="text" class="form-control" placeholder="Mobile Number" required="true" name="mobilenumber" pattern="[0-9]+" maxlength="10"  autocomplete="off">
                            <input type="email" class="form-control" class="form-control" placeholder="Email address" required="true" name="email"  autocomplete="off">
                        </div>
                        <div style="display: flex; align-items: center;">
                       <input type="file" class="form-control" id="image" name="image" value="Image" required="true">
                        </div><br>

                        <div class="twice-two"> 
                            <div style="display: flex; align-items: center;">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="true" autocomplete="off">
                                <button type="button" id="togglePassword" onclick="togglePasswords('password','togglePassword')">Show</button>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <input type="password" class="form-control" id="repeatpassword" name="repeatpassword" placeholder="Repeat password" required="true" autocomplete="off">
                                <button type="button" id="newrepeatpassword" onclick="togglePasswords('repeatpassword', 'newrepeatpassword')">Show</button>
                            </div>
                        </div>
                        <div class="twice-two"> 
                        <div class="g-recaptcha" data-sitekey="6LcBWIQqAAAAABiBHcUMVqDw1wvK4TJGLfOW-Bol" data-callback="enableSubmit"></div>

                        <button type="submit" class="btn btn-contact" name="submit" id="SbmtBtn"  style="width: 100%;"disabled>Signup</button>
                   </div>
                    </form>
                    <!-- ShowAlert DISPLAY -->
                    <?php if (isset($alertMessage)) echo $alertMessage; ?>
                </div>
            </div>
        </div>
    </div>
   
    </div></div>
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

      // RECAPTCHA
      function enableSubmit() {
        document.getElementById('SbmtBtn').disabled = false;
    }

    function checkpass()
    {
        var response = grecaptcha.getResponse();
            if(response.length == 0) {
                showAlert('Please complete the reCAPTCHA', '', 'warning'); 
                return false;
            }
            return true;
            
            if(document.signup.password.value!=document.signup.repeatpassword.value)
            {
            alert('Password and Repeat Password field does not match');
            document.signup.repeatpassword.focus();
            return false;
            }
            return true;
    } 

    function togglePasswords(inputId, buttonId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = document.getElementById(buttonId);

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text'; // Change to text to show the password
        toggleButton.textContent = 'Hide'; // Change button text to 'Hide'
    } else {
        passwordInput.type = 'password'; // Change back to password to hide it
        toggleButton.textContent = 'Show'; // Change button text to 'Show'
    }
}
	// When the user scrolls down 20px from the top of the document, show the button
	window.onscroll = function () {
		scrollFunction()
	};
</script>
<!-- /move top -->
</body>

</html>