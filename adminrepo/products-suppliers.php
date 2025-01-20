<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{

    if (isset($_POST['submit'])) {

        // Get the new data from the input fields
        $newINSERTsupplierid = $_POST['INSERTsupplierid'];
        $newInsertSupplierServiceID = $_POST['InsertSupplierServiceID'];
        $newINSERTproductID = $_POST['INSERTSupplierproductID'];
        $newInsertSupplierName= $_POST['InsertSupplierName'];
        $newInsertSupplierPhone = $_POST['InsertSupplierPhone'];
        $newInsertSupplierAddress = $_POST['InsertSupplierAddress'];



        $_SESSION['newServiceID'] = $newInsertSupplierServiceID;
        $_SESSION['newProductID'] = $newINSERTproductID ;
            // Specify the column(s) to insert into
            $insertnewsupplier = "INSERT INTO tblsupplier 
            ( newsupplierID,
                supplierID,
                 productID,
                supplier_name,
                supplier_phone,
                supplier_address) VALUES 
            ('$newINSERTsupplierid',
                '$newInsertSupplierServiceID',
                '$newINSERTproductID',
                '$newInsertSupplierName',
                '$newInsertSupplierPhone',
                '$newInsertSupplierAddress')";
    
            // Check if the query was executed successfully
            if (!mysqli_query($con, $insertnewsupplier)) {
                echo "Error: " . mysqli_error($con);
            } else {
                // Display a success message
                echo "<script>alert('SUPPLIER has been added in the table.');</script>"; 
                        // Redirect to products.php after a short delay
                echo "<script>setTimeout(function() { window.location.href = 'products.php'; }, 2000);</script>";
            }  
 
}
    
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
<!--//Metis Menu -->
<!-- DATATABLES -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
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
	
            <h3 class="title1">Add Supplier</h3>
					<div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
						<div class="form-title">
							<h4>ADD: </h4>
						</div>
						<div class="form-body">
							<form method="post" enctype="multipart/form-data">
								<p style="font-size:16px; color:red" align="center"> <?php if($msg){
    echo $msg;
  }  ?> </p>

                        
                            <div class="form-group"> 
                                <label for="InsertSupplierServiceID">Service ID( galing sa tblservice mga CATEGORY)</label> 
                                <select class="form-control" id="InsertSupplierServiceID" name="InsertSupplierServiceID" required="true">
                                    <?php
                                    $query = mysqli_query($con, "SELECT serviceID FROM tblservices WHERE serviceID");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo "<option value='" . $row['serviceID'] ."'>" . $row['serviceID'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group"> 
                                <label for="INSERTSupplierproductID">ProductID(produkto unique ID)</label> 
                                <input type="text" class="form-control" id="INSERTSupplierproductID" name="INSERTSupplierproductID"  value="" required="true"></input> 
                            </div>




                            <div class="form-group"> 
                                <label for="INSERTsupplierid">Supplier ID (unique na supplierID)</label> 
                                <input type="text" class="form-control" id="INSERTsupplierid" name="INSERTsupplierid"  value="" required="true" > 
                            </div>
							 <div class="form-group"> 
                                <label for="InsertSupplierName">Supplier Name(supplier name)</label> 
                                <input type="text" class="form-control" id="InsertSupplierName" name="InsertSupplierName"  value="" required="true" > 
                            </div>
							 <div class="form-group"> 
                                <label for="InsertSupplierPhone">Phone Number</label> 
                                <input type="text" class="form-control" id="InsertSupplierPhone" name="InsertSupplierPhone"  value="" required="true"></input> 
                            </div>
                            <div class="form-group"> 
                                <label for="InsertSupplierAddress">Address</label> 
                                <input type="text" class="form-control" id="InsertSupplierAddress" name="InsertSupplierAddress"  value="" required="true"> 
                            </div>
							  <button type="submit" name="submit" class="btn btn-primary">Add</button> </form> 
						</div>
						
					</div>

                    <div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
						<div class="form-title">
							<h4>RECENT ADDED SUPPLIER:</h4>
						</div>
                <div class="form-body">
            <table class="table table-bordered" id="leaveTable"  style="width:100%;"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Supplier Name</th> 
                        <th>Supplier ID</th>
                    </tr> 
                </thead> 
                <tbody>
                        <?php
                        $addpet=mysqli_query($con,"SELECT * from tblsupplier GROUP BY newsupplierID;
                            ");
                        $cnt=1;
                        while ($row=mysqli_fetch_array($addpet)) {

                        ?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> 
                         <td><?php  echo $row['supplier_name'];?></td> 
                         <td><?php  echo $row['newsupplierID'];?></td>
                
                         </tr>   
                            <?php $cnt=$cnt+1; }?>
                        </tbody> 
                    </table>
                    <table class="table table-bordered" id="leaveTables"  style="width:100%;"> <thead> <tr> <th>#</th> <th>Supplier Name</th> <th>Supplier ID</th> <th>Address</th> </tr> </thead> <tbody>
                                    <?php
                                    $addpet=mysqli_query($con,"SELECT * from tblsupplier
                                        ");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($addpet)) {

                                    ?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> 
                         <td><?php  echo $row['supplier_name'];?></td> 
                         <td><?php  echo $row['supplier_phone'];?></td>
                         <td><?php  echo $row['supplier_address'];?></td>
                         </tr>   
                            <?php $cnt=$cnt+1; }?>
                        </tbody> 
                    </table>
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

            $(document).ready(function() {
    $('#leaveTable').DataTable({
        "paging": true,
        "searching": true,
        "lengthChange": true,
        "info": true,
        "autoWidth": false
    });
});

$(document).ready(function() {
    $('#leaveTables').DataTable({
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