
<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
error_reporting(0);

// reCAPTCHA Verification

$recaptcha_secret = '6LcBWIQqAAAAANVA4ss9o4fDhwnCmFy-FCJVdqBv';
$recaptcha_response = $_POST['g-recaptcha-response'];

// Verify the reCAPTCHA response
$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) == 1) {

        if(isset($_POST['submit']))
          {
            $fname=$_POST['fname'];
            $lname=$_POST['lname'];
            $phone=$_POST['phone'];
            $email=$_POST['email'];
            $message=$_POST['message'];
            
            $query=mysqli_query($con, "insert into tblcontact(FirstName,LastName,Phone,Email,Message) value('$fname','$lname','$phone','$email','$message')");
            if ($query) {
            $alertMessage .= "<script>showAlert('Success', 'Submit successful!..', 'success'); 
              setTimeout(function(){ window.location.href='contact.php'; }, 2000);</script>";
          }
          else
            {
              $alertMessage = "<script>showAlert('Error', 'Invalid Details Please Retry Again..', 'error');</script>";

            }

          
        }
    }
?>
<!doctype html>
<html lang="en">
  <head>
 

    <title>La Julieta Beauty Center | Contact us Page</title>

    <!-- Template CSS -->
    <link rel="stylesheet" href="assets/css/style-starter.css" >
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
                
 Contact Us
            </h3>
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        Contact</li>
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
               <?php } ?> 
              </div>


                <div class="map-content-9 mt-lg-0 mt-4">
                <div class="loginform-design" style="height: 100%;">
                <div class="container-login" style="width: 100%; height: 100%;">
                    <div class="brand-logo"style="background:url('assets/images/messagelogo.png');  background-size: cover;"></div>
                    <div class="brand-title">LA JULIETA BEAUTY CENTER</div>  
                
                    <form method="post" onsubmit="return validateForm()">
                        <div class="twice-two">
                            <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name" required="">
                            <input type="text" class="form-control" name="lname" id="lname" placeholder="Last Name" required="">
                        </div>
                        <div class="twice-two">
                           <input type="text" class="form-control" placeholder="Phone" required="" name="phone" pattern="[0-9]+" maxlength="10">
                           <input type="email" class="form-control" class="form-control" placeholder="Email" required="" name="email">
                        </div>
                        <textarea class="form-control" id="message" name="message" placeholder="Message" required=""></textarea><br>
                        <div class="twice-two">
                        <div class="g-recaptcha" data-sitekey="6LcBWIQqAAAAABiBHcUMVqDw1wvK4TJGLfOW-Bol" data-callback="enableSubmit"></div>
                        <button type="submit" class="btn btn-contact" name="submit" id="submitBtn"  style="width: 100%;" disabled>Send Message</button>
                      </div>
                      </form>
                    <?php if (isset($alertMessage)) echo $alertMessage; ?>
                    
                </div>
                </div>
    </div>
   
    </div></div>
</section>
<?php if (strlen($_SESSION['bpmsuid']>0)) {?>
<div class="button-container" style="text-align: center;">
<a type="button" href="feedback.php" class="btn btn--purple">
<span class="btn__txt ">SUBMIT A FEEDBACK<i class='far fa-calendar-alt' style=' margin-left: 10px; font-size:24px;'></i></span>
<i class="btn__bg" aria-hidden="true"></i>
<i class="btn__bg" aria-hidden="true"></i>
<i class="btn__bg" aria-hidden="true"></i>
<i class="btn__bg" aria-hidden="true"></i>
</a>
</div>
<?php }?>

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
        if(response.length == 0) {
            showAlert('Please complete the reCAPTCHA', '', 'warning'); 
            return false;
        }
        return true;
    }
</script>
<!-- /move top -->
</body>

</html>

<style>

/*button rainbow*/

.button-container .btn {
  background: hsl(var(--hue), 90%, 80%);
  border: none;
  border-radius: 7px;
  cursor: pointer;
  color: black;
  font: 600 1.05rem/1 "Nunito", sans-serif;
  letter-spacing: 0.05em;
  overflow: hidden;
  padding: 1.15em 1.5em;
  min-height: 1.3em;
  position: relative;
  text-transform: uppercase;
}

.button-container .btn--purple {
  --hue: 244;
}
.button-container .btn--yellow {
  --hue: 30;
}
.button-container .btn--green {
  --hue: 163;
}
.button-container .btn--purple {
  --hue: 244;
}
.button-container .btn--red {
  --hue: 0;
}
.button-container .btn--blue {
  --hue: 210;
}

.button-container .btn:active,
.button-container .btn:focus {
  outline: 3px solid hsl(calc(var(--hue) + 90), 98%, 80%);
}

.button-container .btn + .btn {
  margin-top: 2.5em;
}

.button-container .btn__txt {
  position: relative;
  z-index: 2;
  font-family:"Segoe UI Symbol";
  font-size: 20px;
}

.button-container .btn__bg {
  background: hsl(var(--hueBg), 98%, 80%);
  border-radius: 50%;
  display: block;
  height: 0;
  left: 50%;
  margin: -50% 0 0 -50%;
  padding-top: 100%;
  position: absolute;
  top: 50%;
  width: 100%;
  transform: scale(0);
  transform-origin: 50% 50%;
  transition: transform 0.175s cubic-bezier(0.5, 1, 0.89, 1);
  z-index: 1;
}

.button-container .btn__bg:nth-of-type(1) {
  --hueBg: calc(var(--hue) - 90);
  transition-delay: 0.1725s;
}

.button-container .btn__bg:nth-of-type(2) {
  --hueBg: calc(var(--hue) - 180);
  transition-delay: 0.115s;
}

.button-container .btn__bg:nth-of-type(3) {
  --hueBg: calc(var(--hue) - 270);
  transition-delay: 0.0575s;
}

.button-container .btn__bg:nth-of-type(4) {
  --hueBg: calc(var(--hue) - 360);
  transition-delay: 0s;
}

.button-container .btn:hover .btn__bg,
.button-container .btn:focus .btn__bg,
.button-container .btn:active .btn__bg {
  transform: scale(1.5);
  transition: transform 0.35s cubic-bezier(0.11, 0, 0.5, 0);
}

.button-container .btn:hover .btn__bg:nth-of-type(1),
.button-container .btn:focus .btn__bg:nth-of-type(1),
.button-container .btn:active .btn__bg:nth-of-type(1) {
  transition-delay: 0.115s;
}

.button-container .btn:hover .btn__bg:nth-of-type(2),
.button-container .btn:focus .btn__bg:nth-of-type(2),
.button-container .btn:active .btn__bg:nth-of-type(2) {
  transition-delay: 0.23s;
}

.button-container .btn:hover .btn__bg:nth-of-type(3),
.button-container .btn:focus .btn__bg:nth-of-type(3),
.button-container .btn:active .btn__bg:nth-of-type(3) {
  transition-delay: 0.345s;
}

.button-container .btn:hover .btn__bg:nth-of-type(4),
.button-container .btn:focus .btn__bg:nth-of-type(4),
.button-container .btn:active .btn__bg:nth-of-type(4) {
  transition-delay: 0.46s;
}</style>