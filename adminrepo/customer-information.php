<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    $vid = intval($_GET['viewid']);
    ?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <title>BPMS | About Us</title>

        <script
            type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
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
        <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:400,300,300italic,400italic,700,700italic'
            rel='stylesheet' type='text/css'>
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
        <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
        <script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
         <!-- APEX CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    </head>

    <body class="cbp-spmenu-push">
        <div class="main-content">
            <!--left-fixed -navigation-->
            <?php include_once('includes/sidebar.php'); ?>
            <!--left-fixed -navigation-->
            <!-- header-starts -->
            <?php include_once('includes/header.php'); ?>
            <!-- //header-ends -->
            <!-- main content start-->
            <div id="page-wrapper">
                <div class="main-page">
                    <div class="infopage">
                    <div class="row">
                        <div class="profile-nav col-md-3">
                            <?php
                            // Use the employeeName in your SQL query
                            $viewUser = mysqli_query($con, "
                             SELECT * FROM tbluser WHERE ID = '$vid'");

                            while ($row = mysqli_fetch_array($viewUser)) {
                                ?>
                                <div class="panel">
                                    <div class="user-heading round">
                                        <a>
                                        <img src="../assets/images/imageuser/<?php echo htmlspecialchars($row['imageUser']); ?>" class="card-image">
                                        </a>
                                        <h1><?php echo $row['FirstName']; ?><?php echo $row['LastName']; ?></h1>
                                        <p><?php echo $row['Email']; ?></p>
                                    </div>

                                    <ul class="nav nav-pills nav-stacked">
                                        <li class="active"><a> <i class="fa fa-user"></i> Profile</a></li>
                                        <li>
                                    <a  data-toggle="modal" data-target="#chartModal">
                                        <i class="fa fa-calendar"></i> Recent Activity 
                                        <span class="label label-warning pull-right r-activity">9</span>
                                    </a>
                                </li>
                                     
                                    </ul>
                                </div>
                            </div>
                            <div class="profile-info col-md-9">

                                <div class="panel">
                                    <div class="bio-graph-heading">
                                    <h3><?php echo $row['Qoutes']; ?></h3>
                                    </div>
                                    <div class="panel-body bio-graph-info">
                                        <h1>Bio Graph</h1>
                                        <div class="row">
                                            <div class="bio-row">
                                                <p><span>First Name </span>: <?php echo $row['FirstName']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Last Name </span>: <?php echo $row['LastName']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Address: </span>: <?php echo $row['CurAddress']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Age: </span>: <?php echo $row['Age']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Birthday</span>: <?php echo $row['BirthDate']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Occupation </span>: <?php echo $row['Occupation']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Email </span>: <?php echo $row['Email']; ?></p>
                                            </div>
                                            <div class="bio-row">
                                                <p><span>Mobile </span>: <?php echo $row['MobileNumber']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <!-- Modal Structure -->
<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:110%;">
            <div class="modal-header">
                <h4 class="modal-title" id="chartModalLabel">Recent Activity</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="chartContainer"></div> <!-- Container for ApexChart -->

              
						<h4>Booking History:</h4>
						<table class="table table-bordered"  id="leaveTable"  style="width:100%;"> 
							<thead> 
								<tr> 
									<th>#</th> 
									<th>AptNumber</th> 
									<th>BookService</th>
									<th>BookingDate</th> 
									<th>Status</th>
									<th>Invoice Date</th> 
								</tr> 
							</thead> 
							<tbody>
							<?php
							$ret=mysqli_query($con,"select * from  tblbook where UserID = $vid");
							$cnt=1;
							while ($row=mysqli_fetch_array($ret)) {

							?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> 
						 <td><?php  echo $row['AptNumber'];?></td>
						  <td><?php  echo $row['BookService'];?></td> 
						  <td><?php  echo $row['BookingDate'];?></td>
						  <td><?php 
                    // Check if InvpostingDate is empty
                    if (empty($row['Status'])) {
                        echo 'Pending'; // Display 'Not paid' if no record
                    } else {
                        echo $row['Status']; // Display the actual date if it exists
                    }
                ?></td>
						  <td>  <span class="badge badge-primary">
                <?php 
                    // Check if InvpostingDate is empty
                    if (empty($row['InvpostingDate'])) {
                        echo 'Not paid'; // Display 'Not paid' if no record
                    } else {
                        echo $row['InvpostingDate']; // Display the actual date if it exists
                    }
                ?>
            </span></td> 
	                 </tr>   
                     <?php $cnt=$cnt+1; }?>
                     </tbody> 
                </table> 

            </div>
        </div>
    </div>
</div>
<style>

.close {
    position: absolute; /* Position the button absolutely */
    top: 10px; /* Adjust as needed */
    left: 260px; /* Adjust as needed */
    background: transparent; /* Optional: make background transparent */
    border: none; /* Optional: remove border */
    font-size: 40px; /* Adjust size as needed */
    cursor: pointer; /* Change cursor to pointer */
}
</style>
                            <div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="bio-chart">
                                                    <div style="display:inline;width:100px;height:100px;">
                                                        <canvas width="100" height="100px"></canvas>

                                                        <i class="knob fa fa-chevron-circle-left" aria-hidden="true" style="width: 54px; 
                                height: 33px; 
                                position: absolute; 
                                vertical-align: middle; 
                                margin-top: 20px; 
                                margin-left: -77px;
                                font-size: 50px; 
                                text-align: center; 
                                color: rgb(240, 221, 112); 
                                padding: 0px; 
                                background: none;">
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="bio-desk">
                                                    <h4 class="yellow">Previous Booking</h4>
                                                    <?php 
                                                    $viewpreviousbook = mysqli_query($con, "
                                                    SELECT AptDate, BookingDate 
                                                    FROM tblbook 
                                                    WHERE UserID = $vid AND Status ='Selected'
                                                    ORDER BY BookingDate DESC 
                                                    LIMIT 1  OFFSET 1;");

                                                    if ($row = mysqli_fetch_array($viewpreviousbook)) {
                                                        // Fetch the dates from the result
                                                        $aptDate = date("d F", strtotime($row['AptDate']));
                                                        $bookingDate = date("d F", strtotime($row['BookingDate']));
                                                    ?>
                                                        <br><p>Registered: <?php echo $aptDate; ?></p>
                                                        <p>Requested Book : <?php echo $bookingDate; ?></p>
                                                    <?php } else { ?>
                                                        <p>No recent bookings found.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="bio-chart">
                                                    <div style="display:inline;width:100px;height:100px;">
                                                        <canvas width="100" height="100px"></canvas>
                                                        <i class="knob fa fa-chevron-circle-right" aria-hidden="true" style="width: 54px; 
                                height: 33px; 
                                position: absolute; 
                                vertical-align: middle; 
                                margin-top: 20px; 
                                margin-left: -77px;
                                font-size: 50px; 
                                text-align: center; 
                               color: rgb(76, 197, 205);
                                padding: 0px; 
                                background: none;">
                                                        </i>
                                                    </div>
                                                </div>
                                                <div class="bio-desk">
                                                    <h4 class="terques">Recent Booking </h4>
                                                    <?php 
                                                    $viewrecentbook = mysqli_query($con, "
                                                    SELECT AptDate, BookingDate 
                                                    FROM tblbook 
                                                    WHERE UserID = $vid AND Status ='Selected'
                                                    ORDER BY BookingDate DESC 
                                                    LIMIT 1;");

                                                    if ($row = mysqli_fetch_array($viewrecentbook)) {
                                                        // Fetch the dates from the result
                                                        $aptDate = date("d F", strtotime($row['AptDate']));
                                                        $bookingDate = date("d F", strtotime($row['BookingDate']));
                                                    ?>
                                                        <br><p>Registered: <?php echo $aptDate; ?></p>
                                                        <p>Requested Book: <?php echo $bookingDate; ?></p>
                                                    <?php } else { ?>
                                                        <p>No recent bookings found.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="bio-chart">
                                                <?php
                                                    // Use the employeeName in your SQL query
                                                    $viewRejected = mysqli_query($con, "
                                                        SELECT COUNT(*) AS RejectedCount
                                                        FROM tblbook
                                                        WHERE Status = 'Rejected' AND UserID = $vid;");

                                                    // Initialize a variable to hold the rejected count
                                                    $rejectedCount = 0;

                                                    if ($row = mysqli_fetch_array($viewRejected)) {
                                                        $rejectedCount = $row['RejectedCount']; // Fetch the count
                                                    }
                                                    ?>
                                                    <div style="display:inline;width:100px;height:100px;">
                                                        <canvas width="100" height="100px"></canvas>
                                                        <i class="knob" aria-hidden="true" 
                                                        value="<?php echo $rejectedCount; ?>" 
                                                        style="width: 54px; 
                                                                height: 33px; 
                                                                position: absolute; 
                                                                vertical-align: middle; 
                                                            
                                                                margin-left: -77px;
                                                                font-size: 50px; 
                                                                text-align: center; 
                                                                color: rgb(224, 107, 125); 
                                                                padding: 0px; 
                                                                background: none;">
                                                        <?php echo $rejectedCount; ?> <!-- Display the count inside the icon -->
                                                        
                                                    <h5>TOTAL REJECTED</h5>
                                                </i>
                                                    </div>
                                                </div>
                                                <div class="bio-desk">
                                                    <h4 class="red">Recent Rejected Booking</h4>
                                                    <?php 
                                                    $viewrejectedbook = mysqli_query($con, "
                                                    SELECT AptDate, BookingDate 
                                                    FROM tblbook 
                                                    WHERE UserID = $vid AND Status ='Rejected'
                                                    ORDER BY BookingDate DESC 
                                                    LIMIT 1;");

                                                    if ($row = mysqli_fetch_array($viewrejectedbook)) {
                                                        // Fetch the dates from the result
                                                        $aptDate = date("d F", strtotime($row['AptDate']));
                                                        $bookingDate = date("d F", strtotime($row['BookingDate']));
                                                    ?>
                                                        <br><p>Registered: <?php echo $aptDate; ?></p>
                                                        <p>Rejected Book: <?php echo $bookingDate; ?></p>
                                                    <?php } else { ?>
                                                        <p>No recent bookings found.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="panel">
                                            <div class="panel-body">
                                                                     <div class="bio-chart">
                                                <?php
                                                    // Use the employeeName in your SQL query
                                                    $viewApproved = mysqli_query($con, "
                                                        SELECT COUNT(*) AS ApprovedCount
                                                        FROM tblbook
                                                        WHERE Status = 'Selected' AND UserID = $vid;");

                                                    // Initialize a variable to hold the rejected count
                                                    $ApprovedCount = 0;

                                                    if ($row = mysqli_fetch_array($viewApproved)) {
                                                        $ApprovedCount = $row['ApprovedCount']; // Fetch the count
                                                    }
                                                    ?>
                                                    <div style="display:inline;width:100px;height:100px;">
                                                        <canvas width="100" height="100px"></canvas>
                                                        <i class="knob" aria-hidden="true" 
                                                        value="<?php echo $ApprovedCount; ?>" 
                                                        style="width: 54px; 
                                                                height: 33px; 
                                                                position: absolute; 
                                                                vertical-align: middle; 
                                                            
                                                                margin-left: -77px;
                                                                font-size: 50px; 
                                                                text-align: center; 
                                                                color: rgb(112, 231, 122); 
                                                                padding: 0px; 
                                                                background: none;">
                                                        <?php echo $ApprovedCount; ?> <!-- Display the count inside the icon -->
                                                        <h5>TOTAL COMPLETED</h5>    
                                                    </i>
                                                    </div>
                                                </div>
                                                <div class="bio-desk">
                                                    <h4 class="green">Completed Booking</h4>
                                                    <?php 
                                                    $viewcompletedbook = mysqli_query($con, "
                                                    SELECT AptDate, BookingDate 
                                                    FROM tblbook 
                                                    WHERE UserID = $vid AND Status ='Selected'
                                                    ORDER BY BookingDate DESC 
                                                    LIMIT 1;");

                                                    if ($row = mysqli_fetch_array($viewcompletedbook)) {
                                                        // Fetch the dates from the result
                                                        $aptDate = date("d F", strtotime($row['AptDate']));
                                                        $bookingDate = date("d F", strtotime($row['BookingDate']));
                                                    ?>
                                                        <br><p>Registered: <?php echo $aptDate; ?></p>
                                                        <p>Approved Book: <?php echo $bookingDate; ?></p>
                                                    <?php } else { ?>
                                                        <p>No recent bookings found.</p>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tables">
					<div class="table-responsive bs-example widget-shadow">
                            <h4>Recent Payments:</h4>
                            <table class="table table-bordered" id="PaymentTable" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Apt Number</th>
                                        <th>Booking Date</th>
                                        <th>Total Payment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SQL query to join the tables and get the required data
                        $query = "
                        SELECT 
                            tbluser.FirstName,
                            tbluser.LastName,
                            tblinvoice.InvoiceAPTNumber,
                            DATE(tblinvoice.PostingDate) AS invoicedate,
                            SUM(tblservices.Cost) AS TotalCost
                        FROM tblinvoice 
                        JOIN tblservices ON tblservices.ID = tblinvoice.ServiceId 
                        JOIN tbluser ON tbluser.ID = tblinvoice.UserId
                        WHERE tblinvoice.UserId = '$vid'
                        GROUP BY 
                            tblinvoice.InvoiceAPTNumber,
                            tbluser.FirstName, 
                            tbluser.LastName
                        ";

                                    $result = mysqli_query($con, $query);
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                        <tr>
                                            <th scope="row"><?php echo $cnt; ?></th>
                                            <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                                            <td><?php echo $row['InvoiceAPTNumber']; ?></td>
                                            <td><?php echo date(" F d Y", strtotime($row['invoicedate'])); ?></td>  
                                            <td><span class="badge" style="background-color: green;"><?php echo $subtotal = $row['TotalCost']; ?></span></td>
                                        
                                            <?php $cnt++;   $gtotal+=$subtotal;} ?>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
                </div>




                </div>
                <?php include_once('includes/footer.php'); ?>
            </div>
            <!-- Classie -->
            <script src="js/classie.js"></script>
            <script>
                
                var menuLeft = document.getElementById('cbp-spmenu-s1'),
                    showLeftPush = document.getElementById('showLeftPush'),
                    body = document.body;

                showLeftPush.onclick = function () {
                    classie.toggle(this, 'active');
                    classie.toggle(body, 'cbp-spmenu-push-toright');
                    classie.toggle(menuLeft, 'cbp-spmenu-open');
                    disableOther('showLeftPush');
                };

                function disableOther(button) {
                    if (button !== 'showLeftPush') {
                        classie.toggle(showLeftPush, 'disabled');
                    }
                }





                $(document).ready(function() {
    $('#chartModal').on('show.bs.modal', function (event) {
        // Fetch data for the chart
        $.ajax({
                url: 'ajax.php',
                type: 'GET',
                data: { viewid: <?php echo $vid; ?> },
                success: function(data) {
                   
                    const parsedData = JSON.parse(data);
                    renderChart(parsedData);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error); // Log any AJAX errors
                }
            });
            
    });

    function renderChart(data) {
        var options = {
        chart: {
            type: 'bar'
        },
        colors: ['lightcoral', 'lightgreen'], // Set colors for the bars
        series: [{
            name: 'Rejected',
            data: data.rejected // Array of rejected counts
        }, {
            name: 'Approved',
            data: data.selected // Array of selected counts
        }],
        xaxis: {
            categories: data.dates.map(date => new Date(date).toLocaleDateString()) // Array of BookingDates
        }
    };

    var chart = new ApexCharts(document.querySelector("#chartContainer"), options);
    chart.render();
    }

    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 3,
        "lengthChange": false
    });

    $('#PaymentTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 5,
        "lengthChange": false
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
<?php } ?>