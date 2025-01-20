<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{
if($_GET['delid']){
$sid=$_GET['delid'];
mysqli_query($con,"delete from tblservices where ID ='$sid'");
echo "<script>alert('Data Deleted');</script>";
echo "<script>window.location.href='manage-services.php'</script>";
          }

// Fetch and process BookService data for best sellers
$serviceCount = [];
$result = mysqli_query($con, "
    SELECT BookService, COUNT(*) as service_count 
    FROM tblbook 
    WHERE Status = 'Selected' 
    GROUP BY BookService 
    ORDER BY service_count DESC 
	LIMIT 5
");

while ($row = mysqli_fetch_assoc($result)) {
    $services = explode(',', $row['BookService']);
    foreach ($services as $service) {
        $service = trim($service); // Trim whitespace
        if (!empty($service)) {
            if (isset($serviceCount[$service])) {
                $serviceCount[$service] += $row['service_count']; // Add the count for the service
            } else {
                $serviceCount[$service] = $row['service_count'];
            }
        }
    }
}

// Prepare data for ApexCharts
$serviceNames = json_encode(array_keys($serviceCount));
$serviceValues = json_encode(array_values($serviceCount));
	

	// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Fetch count of selected services for the current month
$doughnutData = [];
$resultDoughnut = mysqli_query($con, " SELECT 
COUNT(*) as count, 
Status
FROM 
tblbook 
WHERE (Status = 'Selected' OR Status = 'Rejected')
AND MONTH(RemarkDate) = '$currentMonth' 
AND YEAR(RemarkDate) = '$currentYear' 
GROUP BY Status 
");

while ($row = mysqli_fetch_assoc($resultDoughnut)) {
    $service = $row['Status'];
    $count = $row['count'];
	$remind += $count;
    $doughnutData[$service] = $remind; // Store the count of each service
}

// Prepare data for Doughnut Chart
$doughnutLabels = json_encode(array_keys($doughnutData));
$doughnutValues = json_encode(array_values($doughnutData));



 ?>
<!DOCTYPE HTML>
<html>
<head>
<title>BPMS || Manage Services</title>

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
<link href="css/newcustom.css" rel="stylesheet">
<link href="css/newstyle.css" rel="stylesheet">
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- APEX CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
				<div class="tables">
					<h3 class="title1">Manage Services</h3>
					<div class="table-responsive bs-example widget-shadow">
					<section class="best-service">
					<div id="chart" style="max-width: 600px; margin: 20px auto;"></div>
					</section>

					<div class="row" style="display: flex; margin-top:0px;">
				<div class="col-left" style="flex: 1 0 50%; max-width: 100%; margin-right: 15px;">
				<div class="tables" style="width:100%;">
						<h4>Update Services:</h4>
						<table  class="table table-bordered" id="leaveTable"  style="width:100%;"> <thead> <tr> <th>#</th> <th>Service Name</th> <th>Service Price</th> <th>Creation Date</th><th>Action</th> </tr> </thead> <tbody>
							<?php
							$ret=mysqli_query($con,"select * from  tblservices WHERE Cost != 0");
							$cnt=1;
							while ($row=mysqli_fetch_array($ret)) {

							?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> <td><?php  echo $row['ServiceName'];?></td> <td><?php  echo $row['Cost'];?></td><td><?php  echo $row['CreationDate'];?></td> <td>
						 	<a href="edit-services.php?editid=<?php echo $row['ID'];?>" class="btn btn-primary fa fa-pencil-square-o"></a>

						 	</td> </tr>   
							<?php 
							$cnt=$cnt+1;
							}?></tbody> </table> 
	</div>
	</div>
	<div class="col-right" style="flex: 2; ">
    <div class="tables" style="width: 100%;">
        <div class="table-responsive bs-example widget-shadow">
		<div id="DoughnutSELECTEDChart" style="width: 100%;"></div>
	</div>
		</div>
		</div>


</div>
					</div>
				</div>
			</div>
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

		/////////////FOR DATATABLE SETTINGS
		$(document).ready(function() {
    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false,
        "pageLength": 4, // Set the number of entries to show to 3
		"lengthChange": false
		
    });
});

			var options = {
                            chart: {
								type: 'bar',
								height: 350,
								toolbar: {
								show: true,
								},
                            },
                            series: [{
                                name: 'BEST SELLER',
                                data: <?php echo $serviceValues; ?>
                            }],
                            xaxis: {
                                categories: <?php echo $serviceNames; ?>
                            },
							title: {
									text: 'Best Seller Service Over Months',
									align: 'center'
								},
								colors: ['#246dec', '#cc3c43', '#367952', '#f5b74f', '#4f35a1'],
							plotOptions: {
								bar: {
								distributed: true,
								borderRadius: 4,
								horizontal: false,
								columnWidth: '60%',
								},
							},
							dataLabels: {
								enabled: true,
							},
							legend: {
								show: false,
							},
									};

                        var chart = new ApexCharts(document.querySelector("#chart"), options);
                        chart.render();




						/////////////DOUGHNUT
			var optionsDoughnut = {
				chart: {
					type: 'donut',
					height: 350,

				},
				series: <?php echo $doughnutValues; ?>,
				labels: <?php echo $doughnutLabels; ?>,
				title: {
					text: 'Total Response for Current Month',
					align: 'center'
				},
				plotOptions: {
        pie: {
            donut: {
                size: '60%'
            },
			
        }
    },
    colors: ['#00E396', '#775DD0'],
    legend: {
        position: 'bottom', // Set legend position to bottom
        horizontalAlign: 'center' // Center the legend
    },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: {
                height: 200
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center' // Center the legend for smaller screens too
            }
        }
    }]
			};

			var doughnutChart = new ApexCharts(document.querySelector("#DoughnutSELECTEDChart"), optionsDoughnut);
			doughnutChart.render();
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