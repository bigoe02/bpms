<?php

    session_start();
    error_reporting(E_ALL);
    include('includes/dbconnection.php');
    error_reporting(0);


   
    if(isset($_POST['login'])) {
 
        // Get the email or mobile number and password
        $emailcon = $_POST['emailcont'];
        $password = md5($_POST['password']);

        // reCAPTCHA Verification
        $recaptcha_secret = '6LcBWIQqAAAAANVA4ss9o4fDhwnCmFy-FCJVdqBv';
        $recaptcha_response = $_POST['g-recaptcha-response'];

        // Verify the reCAPTCHA response
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            $alertMessage = "<script>showAlert('Error', 'Please complete the reCAPTCHA', 'error');</script>";
        } else {
            ////////////////////// CHECK USER LOGIN
            // Check user login using prepared statements
            $stmt = $con->prepare("SELECT ID FROM tbluser WHERE (Email=? OR MobileNumber=?) AND Password=?");
            $stmt->bind_param("sss", $emailcon, $emailcon, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $ret = $result->fetch_assoc();

            if ($ret > 0) {
                $_SESSION['bpmsuid'] = $ret['ID'];
                $alertnewMessage = "<script>showAlert('Success', 'Client Login successful! Redirecting...', 'success'); 
                setTimeout(function(){ window.location.href='book-appointment.php'; }, 2000);</script>";
            } else {
                $alertMessage = "<script>showAlert('Error', 'Invalid Details Please Retry Again..', 'error');</script>";
            }




            ////////////////////// CHECK ADMIN LOGIN
            // Check admin login using prepared statements
            $stmtAdmin = $con->prepare("SELECT ID, employeeID FROM tbladmin WHERE UserName=? AND Password=?");
            $stmtAdmin->bind_param("ss", $emailcon, $password);
            $stmtAdmin->execute();
            $resultAdmin = $stmtAdmin->get_result();
            $retAdmin = $resultAdmin->fetch_assoc();

            if ($retAdmin !== null ) {

 ///////API ipLocation for Access Control for admin and staff: WAG BUBURAHIN              
// Get the user's IP address
// $user_ip = '122.52.129.220'; 
// //  $_SERVER['REMOTE_ADDR'];

// // IP2Location API settings
// $api_key = '7D80BC1D2DB3EABF3D90CCC212EC5A44';
// $api_url = 'https://api.ip2location.io/?key=' . $api_key . '&ip=' . $user_ip;

// // Make API request to IP2Location
// $ch = curl_init($api_url);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $response = curl_exec($ch);
// curl_close($ch);

// $ip_data = json_decode($response, true);

// // Allowed IP addresses
// $allowed_ips = array('122.52.129.220');
//             // Check if the user's IP address is allowed
//             if (in_array($user_ip, $allowed_ips)) {
                // Allow access

                $_SESSION['bpmsaid'] = $retAdmin['ID'];
                $_SESSION['bpmsid'] = $retAdmin['employeeID'];

                // Show success alert for admin login and redirect after a delay
                $alertMessage .= "<script>showAlert('Success', 'Login successful! Redirecting to admin dashboard...', 'success'); 
                setTimeout(function(){ window.location.href='admin/dashboard.php'; }, 2000);</script>";
            // } else {
            //     // Deny access
            //     $alertMessage = "<script>showAlert('Error', 'Access denied. Your IP address is not allowed.', 'error');</script>";
            // }
            
            } else {
                $alertMessage = "<script>showAlert('Error', 'Invalid Details Please Retry Again.', 'error');</script>";
            }

       


            // Close the statements
            $stmt->close();
            $stmtAdmin->close();
        }


    }

?>
<!doctype html>
<html lang="en">
  <head>
 

    <title>La Julieta Beauty Center | Login</title>

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
                

            </h3>
        </div>
</div>
</div>
<div class="breadcrumbs-sub">
<div class="container">   
<ul class="breadcrumbs-custom-path">
    <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right" aria-hidden="true"></span></a> <p></li>
    <li class="active ">
        Login</li>
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
                    <div class="brand-logo"></div>
                    <div class="brand-title">LA JULIETA BEAUTY CENTER</div>  
                    <form method="post" id="loginForm" onsubmit="return validateForm()">
                    <div class="inputs">    
                    <div>
                            <label>EMAIL OR USERNAME</label>
                            <input type="text" class="form-control" name="emailcont" required="true" placeholder="Registered Email or Username" autocomplete="off" >
                           
                        </div><br>
                        <label>PASSWORD</label>
                        <div style="display: flex; align-items: center;">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="true" autocomplete="off" style="flex: 1; margin-right: 10px;">
                        <button type="button" id="togglePassword" onclick="togglePasswords()">Show</button>
                        </div>

                        
                         <br>
                          <div class="twice-two">
                          <div class="g-recaptcha" data-sitekey="6LcBWIQqAAAAABiBHcUMVqDw1wvK4TJGLfOW-Bol" data-callback="enableSubmit"></div>
                          <a class="link--gray" style="color: blue;" href="forgot-password.php">Forgot Password?</a>
                        <button type="submit" class="btn btn-contact" name="login" id="submitBtn" style="width: 100%;"disabled>Login</button>
                        </div>     
                    </div>
                    </form>
                    <?php if (isset($alertMessage)) echo $alertMessage; ?>
                    <?php if (isset($alertnewMessage)) echo $alertnewMessage; ?>
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

    function togglePasswords() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');

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
</body>

</html>