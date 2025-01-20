<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
// Set timezone to Philippines
date_default_timezone_set('Asia/Manila');

if (isset($_SESSION['email_sent_today_date']) && $_SESSION['email_sent_today_date'] == date('Y-m-d')) {
    // Email has already been sent, do nothing
    $_SESSION['email_sent_today'] = true;
} else {
    // Email has not been sent, send it now
    $_SESSION['email_sent_today_date'] = date('Y-m-d');
    $_SESSION['email_sent_today'] = false;
    // Send email logic here
}

if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{

if($_GET['delid']){
$sid=$_GET['delid'];
mysqli_query($con,"delete from tblbook where ID ='$sid'");
echo "<script>alert('Data Deleted');</script>";
echo "<script>window.location.href='all-appointment.php'</script>";
          }


// Get today's date
$todayss = date('Y-m-d');

// Fetch today's appointments
$todayAppointments = mysqli_query($con, "SELECT 
    tbluser.FirstName, 
    tbluser.LastName, 
    tbluser.Email, 
    tbluser.MobileNumber, 
    tblbook.ID as bid, 
    tblbook.AptNumber, 
    tblbook.AptDate, 
    tblbook.AptTime, 
    tblbook.Message, 
    tblbook.BookingDate, 
    tblbook.Status 
FROM tblbook 
JOIN tbluser 
ON tbluser.ID = tblbook.UserID 
WHERE tblbook.AptDate = '$todayss' 
AND tblbook.Status = 'Selected'");

// Convert the result to an array for JavaScript
$appointments = [];
while ($row = mysqli_fetch_assoc($todayAppointments)) {
    $appointments[] = $row;
}
  ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || All Appointment</title>

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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!--//Metis Menu -->

<!-- EMAILJS -->
<script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
 <!-- SWEETALERT2-->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 // Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function() { 
  (function(){
        emailjs.init("1ZW6y7tgNLWPKmcP1"); // Initialize with your Public Key
    })();

    
   // Send emails for all today's appointments
var emailSentToday = <?php echo $_SESSION['email_sent_today'] ? 'true' : 'false'; ?>;
if (!emailSentToday) {
        Swal.fire({
            icon: 'success',
            title: 'Sending An Email Today',
            text: 'All Clients Receive Notification Today.',
            confirmButtonText: 'OK'
        }).then(() => {
            const appointments = <?php echo json_encode($appointments); ?>;
            appointments.forEach(appointment => {
                sendEmail(appointment);
            });
            // Update PHP session state via AJAX or form submission
            fetch('ajax_emailjs.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email_sent_today: true })
            }).then(response => response.json())
              .then(data => {
                  console.log('Email status updated:', data);
              });
        });
    }else {
    Swal.fire({
        icon: 'info',
        title: 'Already Sent An Email',
        text: 'All Clients Booking Today Is Already NOTIFIED.',
        confirmButtonText: 'OK'
    });
}

    // Function to send email
    function sendEmail(appointment) {
        emailjs.send("service_qoanxfx", "template_t8cfirg", {
            to_email: appointment.Email,
            to_name: appointment.FirstName + " " + appointment.LastName,
            appointment_number: appointment.AptNumber,
            appointment_date: appointment.AptDate,
            appointment_time: appointment.AptTime
        }).then(function(response) {
            console.log("Email sent successfully", response);
        }, function(error) {
            console.log("Failed to send email", error);
        });
    }
});
</script>
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
				<div class="tables">
					<h3 class="title1">All Appointment</h3>
					
					
				
					<div class="table-responsive bs-example widget-shadow">
						<h4>All Appointment:</h4>
						<table class="display table table-bordered" id="leaveTable"  style="width:100%;" > <thead> <tr> 
							<th>#</th> 
							<th> Appointment Number</th> 
							<th>Name</th><th>Mobile Number</th> 
							<th>Appointment Date</th>
							<th>Appointment Time</th>
							<th>Status</th>
							<th>Action</th> </tr> </thead> <tbody>
<?php
$ret=mysqli_query($con,"select tbluser.FirstName,tbluser.LastName,tbluser.Email,tbluser.MobileNumber,tblbook.ID as bid,tblbook.AptNumber,tblbook.AptDate,tblbook.AptTime,tblbook.Message,tblbook.BookingDate,tblbook.Status from tblbook join tbluser on tbluser.ID=tblbook.UserID");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {

?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> 
						 	<td><?php  echo $row['AptNumber'];?></td> 
						 	<td><?php  echo $row['FirstName'];?> <?php  echo $row['LastName'];?></td>
						 	<td><?php  echo $row['MobileNumber'];?></td>
						 	<td><?php  echo $row['AptDate'];?></td> 
						 	<td><?php  echo $row['AptTime'];?></td>
						 	<?php if($row['Status']==""){ ?>

                     <td class="font-w600"><?php echo "Not Updated Yet"; ?></td>
                     <?php } else { ?>
                      <td><?php  echo $row['Status'];?></td><?php } ?> 
                                       <td><a href="view-appointment.php?viewid=<?php echo $row['bid'];?>" class="btn btn-primary fa fa-eye"></a>
                                       	</td> </tr>   <?php 
						$cnt=$cnt+1;
						}?></tbody> </table> 
					</div>
				</div>

				<?php
// Get today's date
$todays = date('Y-m-d');
// Fetch today's appointments
$todayAppointments = mysqli_query($con, "SELECT 
    tbluser.FirstName, 
    tbluser.LastName, 
    tbluser.Email, 
    tbluser.MobileNumber, 
    tblbook.ID as bid, 
    tblbook.AptNumber, 
    tblbook.AptDate, 
    tblbook.AptTime, 
    tblbook.Message, 
    tblbook.BookingDate, 
    tblbook.Status 
FROM tblbook 
JOIN tbluser 
ON tbluser.ID = tblbook.UserID 
WHERE tblbook.AptDate = '$todays' 
AND tblbook.Status = 'Selected'");

// Display today's appointments
?>
<div class="tables">
<div class="table-responsive bs-example widget-shadow">
    <h4>Today's Appointments:</h4>
    <table class="display table table-bordered" id="todayAppointmentsTable" style="width:100%;">
        <thead>
            <tr>
                <th>#</th>
                <th>Appointment Number</th>
                <th>Name</th>
                <th>Mobile Number</th>
                <th>Email</th>
                <th>Appointment Date</th>
                <th>Appointment Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $cnt = 1;
            while ($row = mysqli_fetch_array($todayAppointments)) {
                ?>
                <tr>
                    <th scope="row"><?php echo $cnt; ?></th>
                    <td><?php echo $row['AptNumber']; ?></td>
                    <td><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></td>
                    <td><?php echo $row['MobileNumber']; ?></td>
                    <td><?php echo $row['Email']; ?></td>
                    <td><?php echo $row['AptDate']; ?></td>
                    <td><?php echo $row['AptTime']; ?></td>

                    </td>
                </tr>
                <?php
                $cnt = $cnt + 1;
            }
            ?>

        </tbody>
    </table>
</div>
</div>			</div>
		</div>
		<!--footer-->
		 <?php include_once('includes/footer.php');?>
        <!--//footer-->
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

			
			$(document).ready(function() {
    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false
    });
});
    
		</script>
	<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.js"> </script>
</body>
</html>
<?php }  ?>