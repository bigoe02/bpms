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

        if(isset($_POST['submit']))
        {
            $uid = $_SESSION['bpmsuid'];
            $fname = $_POST['firstname'];
            $lname = $_POST['lastname'];
            $mobilenumber = $_POST['mobilenumber'];
            $age = $_POST['Age'];
            $birthdate = $_POST['BirthDate'];
            $occupation = $_POST['Occupation'];
            $curaddress = $_POST['CurAddress'];
            $Qoute = $_POST['Qoute'];
    
            // Update query to include new fields
            $query = mysqli_query($con, "UPDATE tbluser SET FirstName='$fname', LastName='$lname', MobileNumber='$mobilenumber', Age='$age', BirthDate='$birthdate', Occupation='$occupation', CurAddress='$curaddress', Qoute='$Qoute' WHERE ID='$uid'");


            if ($query) {

        $alertMessage .= "<script>showAlert('Success', 'Profile updated successully.', 'success'); 
        setTimeout(function(){ window.location.href='profile.php'; }, 2000);</script>";
        }
        else
            {
            $alertMessage = "<script>showAlert('Error', 'Something Went Wrong. Please try again.', 'error');</script>";
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
                
 Profile
            </h3>
            <p class="tiltle-para ">EDIT YOUR PROFILE</p>
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        profile</li>
<?php
// reCAPTCHA Verification
$recaptcha_secret = '6LcBWIQqAAAAANVA4ss9o4fDhwnCmFy-FCJVdqBv';
$recaptcha_response = $_POST['g-recaptcha-response'];

// Verify the reCAPTCHA response
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) == 1) {
    $alertMessage = "<script>showAlert('Error', 'Invalid reCAPTCHA response.', 'error');</script>";
} else {
    if (isset($_POST['submit'])) {
        $uid = $_SESSION['bpmsuid'];
        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $mobilenumber = $_POST['mobilenumber'];
        $age = $_POST['Age'];
        $birthdate = $_POST['BirthDate'];
        $occupation = $_POST['Occupation'];
        $curaddress = $_POST['CurAddress'];
        $Qoute = $_POST['Qoute'];

        // Update query to include new fields
        $query = mysqli_query($con, "UPDATE tbluser SET FirstName='$fname', LastName='$lname', MobileNumber='$mobilenumber', Age='$age', BirthDate='$birthdate', Occupation='$occupation', CurAddress='$curaddress', Qoute='$Qoute' WHERE ID='$uid'");

        if ($query) {
            $alertMessage = "<script>showAlert('Success', 'Profile updated successfully.', 'success'); setTimeout(function(){ window.location.href='profile.php'; }, 2000);</script>";
        } else {
            $alertMessage = "<script>showAlert('Error', 'Something Went Wrong. Please try again.', 'error');</script>";
        }
    }
}
?>
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
                                <div class="brand-logo"style="background:url('assets/images/profilelogo.png');  background-size: cover;"></div>
                                <div class="brand-title">LA JULIETA BEAUTY CENTER</div>  
                                <form method="post" name="signup"  onsubmit="return validateForm()">
                                    <?php
                                    $uid=$_SESSION['bpmsuid'];
                                    $ret=mysqli_query($con,"select * from tbluser where ID='$uid'");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($ret)) {

                                    ?>
                                       <div class="twice-two">
                                        <label style="margin-top: 14px;">First Name</label> <label>Last Name</label>
                                        <input type="text" class="form-control" name="firstname" value="<?php  echo $row['FirstName'];?>" required="true">
                                        <input type="text" class="form-control" name="lastname" value="<?php  echo $row['LastName'];?>" required="true">
                                    </div>
                                    <div class="twice-two">
                                        <label style="margin-top: 14px;">Mobile Number</label><label>Email address</label>
            
                                        <input type="text" class="form-control" name="mobilenumber" value="<?php echo $row['MobileNumber'];?>" pattern="\d{10}" title="Please enter a valid 10-digit mobile number" required>
                                        <input type="text" class="form-control" name="email" value="<?php  echo $row['Email'];?>"  readonly="true">
                                    </div>
                                    <div class="twice-two">
                                        <label style="margin-top: 14px;">Age</label><label>Birthdate</label>
            
                                        <input type="text" class="form-control" name="Age" value="<?php echo $row['Age'];?>" pattern="\d*" title="Age must be a number" required="true">
                                        <input type="text" class="form-control" name="BirthDate" value="<?php echo $row['BirthDate'];?>" pattern="\d{4}-\d{2}-\d{2}" title="Please enter a valid birthdate (YYYY-MM-DD)" required="true">
                                    </div>
                                    <div class="twice-two">
                                        <label style="margin-top: 14px;">Occupation</label><label>Current Address</label>
            
                                        <input type="text" class="form-control" name="Occupation" value="<?php  echo $row['Occupation'];?>"  required="true">
                                        <input type="text" class="form-control" name="CurAddress" value="<?php  echo $row['CurAddress'];?>"  required="true">
                                    </div>
                                    <div >
                                    <label>Personal Qoute</label>
                                    <input type="text" class="form-control" name="Qoute" value="<?php  echo $row['Qoute'];?>"  required="true">
                                        <label>Registration Date</label>
                                    <input type="text" class="form-control" name="regdate" value="<?php  echo $row['RegDate'];?>"  readonly="true">
                                </div>
                                
                                <?php }?>
                                    <br>   <div class="twice-two">
                                <div class="g-recaptcha" data-sitekey="6LcBWIQqAAAAABiBHcUMVqDw1wvK4TJGLfOW-Bol" data-callback="enableSubmit"></div>
                                    
                                <button type="submit" class="btn btn-contact" name="submit" id="submitBtn" style="width: 100%;" disabled>Save Change</button>
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

    
      // RECAPTCHA
      function enableSubmit() {
        document.getElementById('submitBtn').disabled = false;
    }

    function validateForm() {
    var response = grecaptcha.getResponse();
    if (response.length == 0) {
        showAlert('Please complete the reCAPTCHA', '', 'warning'); 
        return false;
    }

    // Validate mobile number
    var mobileNumber = document.forms["signup"]["mobilenumber"].value;
    var mobilePattern = /^\d{10}$/; // Pattern for 10-digit mobile number
    if (!mobilePattern.test(mobileNumber)) {
        showAlert('Please enter a valid 10-digit mobile number', '', 'warning');
        return false;
    }

    // Validate age (optional)
    var age = document.forms["signup"]["Age"].value;
    if (age && isNaN(age)) {
        showAlert('Age must be a number', '', 'warning');
        return false;
    }

    // Validate birthdate (optional)
    var birthDate = document.forms["signup"]["BirthDate"].value;
    if (birthDate && !isValidDate(birthDate)) {
        showAlert('Please enter a valid birthdate (YYYY-MM-DD)', '', 'warning');
        return false;
    }

    return true;
}

// Function to check if the date is valid
function isValidDate(dateString) {
    var regEx = /^\d{4}-\d{2}-\d{2}$/; // YYYY-MM-DD format
    return dateString.match(regEx) !== null;
}
</script>
<!-- /move top -->
</body>

</html><?php } ?>