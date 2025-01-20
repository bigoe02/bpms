<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{     
                  if (isset($_POST['submit'])) {
        
                    // Get the new data from the input fields
                    $newINSERTEmployeename = $_POST['INSERTEmployeename'];
                    $newINSERTPhone = $_POST['INSERTPhone'];
                    $newINSERTEmail = $_POST['INSERTEmail'];
                    $newINSERTUsername = $_POST['INSERTUsername'];
                    $newINSERTPassword = $_POST['INSERTPassword'];
        
                        // Hash the password
                        $hashedPassword = md5($newINSERTPassword); // Use password_hash() for better security
        
                        // Specify the column(s) to insert into
                        $insertnewemployee= "INSERT INTO tbladmin (AdminName, UserName, MobileNumber, Email, Password, AdminRegdate) VALUES (' $newINSERTEmployeename  ','$newINSERTUsername','$newINSERTPhone ' ,'$newINSERTEmail  ','$hashedPassword',  NOW())";
                
                        // Check if the query was executed successfully
                        if (!mysqli_query($con, $insertnewemployee)) {
                            echo "Error: " . mysqli_error($con);
                        } else {
                            // Display a success message
                            echo "<script>alert('EMPLOYEE has been added in the table.');</script>"; 
                            echo "<script>window.location.href='employee-list.php'</script>";
                            
                        }  }
  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS | Add Services</title>

<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- Custom CSS -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- font CSS -->
<!-- font-awesome icons -->
<link href="css/font-awesome.css" rel="stylesheet"> 
<!-- //font-awesome icons -->
 <!-- js-->
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/modernizr.custom.js"></script>
<!--webfonts-->
<link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic' rel='stylesheet' type='text/css'>
<!--//webfonts--> 
<!--animate-->
<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">
<script src="js/wow.min.js"></script>
	<script>
		 new WOW().init();
	</script>
<!--//end-animate-->
<!-- Metis Menu -->
<script src="js/metisMenu.min.js"></script>
<script src="js/custom.js"></script>
<link href="css/custom.css" rel="stylesheet">
<!--//Metis Menu -->
</head> 
<body class="cbp-spmenu-push">
	<div class="main-content">
		<!--left-fixed -navigation-->
		 <?php include_once('includes/sidebar.php');?>
		<!--left-fixed -navigation-->
		<!-- header-starts -->
	 <?php include_once('includes/header.php');?>
		<!-- //header-ends -->
		<!-- main content start-->
		<div id="page-wrapper">
			<div class="main-page">
				<div class="forms">
                <div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
						<div class="form-title">
							<h4>ADD Employee:</h4>
						</div>
						<div class="form-body">
							<form method="post" enctype="multipart/form-data">
                            
							 <div class="form-group"> 
                                <label for="INSERTEmployeename">Employee Name</label> 
                                <input type="text" class="form-control" id="INSERTEmployeename" name="INSERTEmployeename"  value="" required="true"> 
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTPhone">Mobile Number</label> 
                                <input type="text" class="form-control" id="INSERTPhone" name="INSERTPhone"  value="" required="true"></input> 
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTEmail">Email</label> 
                                <input type="text" class="form-control" id="INSERTEmail" name="INSERTEmail"  value="" required="true"></input> 
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTUsername">Username</label> 
                                <input type="text" class="form-control" id="INSERTUsername" name="INSERTUsername"  value="" required="true"></input> 
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTPassword">Password</label> 
                                <input type="password" class="form-control" id="INSERTPassword" name="INSERTPassword"  placeholder="password" required="true"></input> 
                            </div>
							  <button type="submit" name="submit" class="btn btn-default">Insert</button> 
                            </form> 
						</div>
						
					</div>
				
			</div>
		</div>
		 <?php include_once('includes/footer.php');?>
	</div>
	<!-- Classie -->
		<script src="js/classie.js"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
   <script src="js/bootstrap.js"> </script>
</body>
</html>
<?php } ?>