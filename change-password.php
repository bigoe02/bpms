<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsuid']==0)) {
  header('location:logout.php');
  } else{
    // reCAPTCHA Verification

$recaptcha_secret = '6LcBWIQqAAAAANVA4ss9o4fDhwnCmFy-FCJVdqBv';
$recaptcha_response = $_POST['g-recaptcha-response'];

// Verify the reCAPTCHA response
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) == 1) 
{

    if(isset($_POST['change']))
    {
    $userid=$_SESSION['bpmsuid'];
    $cpassword=md5($_POST['currentpassword']);
    $newpassword=md5($_POST['newpassword']);
    $query1=mysqli_query($con,"select ID from tbluser where ID='$userid' and   Password='$cpassword'");
    $row=mysqli_fetch_array($query1);
        if($row>0){
        $ret=mysqli_query($con,"update tbluser set Password='$newpassword' where ID='$userid'");

         $alertMessage .= "<script>showAlert('Success', 'Your password successully changed.', 'success');</script>";
        } else {
        $alertMessage = "<script>showAlert('Error', 'Your current password is wrong.', 'error');</script>";

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
<!-- breadcrumbs -->
<section class="w3l-inner-banner-main">
    <div class="about-inner contact ">
        <div class="container">   
            <div class="main-titles-head text-center">
            <h3 class="header-name ">
                
 Change Password
            </h3>
           
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        Change Password</li>
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
                            <div class="brand-logo"style="background:url('assets/images/changepassword.png');  background-size: cover;"></div>
                            <div class="brand-title">LA JULIETA BEAUTY CENTER</div>

                            <form method="post" name="changepassword" onsubmit="return checkpass();">
                            <!-- CURRENT PASSWORD -->
                            <div style="padding-top: 30px;">
                                <label>Current Password</label>
                                <div style="display: flex; align-items: center;">
                                    <input type="password" class="form-control" placeholder="Current Password" id="currentpassword" name="currentpassword" value="" required="true" style="flex: 1; margin-right: 10px;">
                                    <button type="button" id="toggleCurrentPassword" onclick="togglePasswords('currentpassword', 'toggleCurrentPassword')">Show</button>
                                </div>
                            </div>
                            <!-- NEW PASSWORD -->
                            <div style="padding-top: 30px;">
                                <label>New Password</label>
                                <div style="display: flex; align-items: center;">
                                    <input type="password" class="form-control" placeholder="New Password" id="newpassword" name="newpassword" value="" required="true" style="flex: 1; margin-right: 10px;">
                                    <button type="button" id="toggleNewPassword" onclick="togglePasswords('newpassword', 'toggleNewPassword')">Show</button>
                                </div>
                            </div>
                            <!-- CONFIRM PASSWORD -->
                            <div style="padding-top: 30px;">
                                <label>Confirm Password</label>
                                <div style="display: flex; align-items: center;">
                                    <input type="password" class="form-control" placeholder="Confirm Password" id="confirmpassword" name="confirmpassword" value="" required="true" style="flex: 1; margin-right: 10px;">
                                    <button type="button" id="toggleConfirmPassword" onclick="togglePasswords('confirmpassword', 'toggleConfirmPassword')">Show</button>
                                </div>
                            </div>
                            <br> 
                            <div class="twice-two">
                                <div class="g-recaptcha" data-sitekey="6LcBWIQqAAAAABiBHcUMVqDw1wvK4TJGLfOW-Bol" data-callback="enableSubmit"></div>
                                <button type="submit" class="btn btn-contact" name="change" id="submitBtn" style="width:100%;" disabled>Save Change</button>
                                </div>
                            </form>
                            <?php if (isset($alertMessage)) echo $alertMessage; ?>
                        </div>
                    </div>
                </div>
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

    function checkpass()
{
    var response = grecaptcha.getResponse();
        if(response.length == 0) {
            showAlert('Please complete the reCAPTCHA', '', 'warning'); 
            return false;
        }
        return true;

if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{

showAlert('New Password and Confirm Password field does not match', '', 'warning'); 

document.changepassword.confirmpassword.focus();
return false;
}
return true;
} 

     // RECAPTCHA
     function enableSubmit() {
        document.getElementById('submitBtn').disabled = false;
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

</script>
<!-- /move top -->
</body>

</html><?php } ?>