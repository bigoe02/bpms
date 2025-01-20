<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['bpmsaid']==0)) {
  header('location:logout.php');
  } else{

    if (isset($_GET['addid'])) {
        $prodID = $_GET['addid'];
        $query = mysqli_query($con, "SELECT * FROM tblinventory WHERE prodID = '$prodID'");
        $row = mysqli_fetch_array($query);
    
        // Populate the text areas with the retrieved data
        $productName = $row['product_name'];
        $inStocks = $row['In_stocks'];
        $productPrice = $row['product_price'];
        $serviceID = $row['category_id'];

  // Get the existing data
  $existingInStocks = $row['In_stocks'];
  $existingOutStocks = $row['out_stocks'];

  $existingProductPrice = $row['product_price'];
   // Check if the form is submitted
   if (isset($_POST['submit'])) {

    // Get the new data from the input fields
    $newInStocks = $_POST['inStocks'];
    $newprodID = $_POST['newprodID'];
    $insertedserviceID = $row['category_id'];

    // Update the existing data by adding the new data
    $updatedInStocks = $existingInStocks + $newInStocks;
    $updatedOutStocks = $existingOutStocks + $newInStocks;

    
// Update the database
    $updateQuery = "UPDATE tblinventory SET In_stocks = '$updatedInStocks' , out_stocks =' $updatedOutStocks' WHERE prodID = '$prodID'";
    $result = mysqli_query($con, $updateQuery);

    // Display a success message
    echo "<script>alert('ITEM has been added $productName.');</script>"; 
    echo "<script>window.location.href = 'products.php'</script>";   


// Specify the column(s) to insert into
$addstocks = "INSERT INTO added_stocks (newstockID, stocksID, stocks_input, date_added) VALUES ('$newprodID','$insertedserviceID','$newInStocks' ,NOW())";

// Check if the query was executed successfully
if (!mysqli_query($con, $addstocks)) {
    echo "Error: " . mysqli_error($con);
} else {
    echo "Stock added successfully!";
}
}
}


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
					<h3 class="title1">Add Products</h3>
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
                                <label for="productName">Product Name</label> 
                                <input type="text" class="form-control" id="productName" name="productName"  value="<?php echo $productName; ?>" required="true" readonly> 
                            </div>
							 <div class="form-group"> 
                                <label for="inStocks">In_Stocks</label> 
                                <input type="text" class="form-control" id="inStocks" name="inStocks"  value="" required="true"></input> 
                            </div>
                            <div class="form-group"> 
                                <label for="inStocks">Product_ID</label> 
                                 <select class="form-control" id="newprodID" name="newprodID" required="true">
                                    <?php
                                    $query = mysqli_query($con, "SELECT prodID FROM tblinventory WHERE product_name = '$productName'");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo "<option value='" . $row['prodID'] . "'>" . $row['prodID'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group"> 
                                <label for="ServiceID">ServiceID</label> 
                                <input type="text" class="form-control" id="ServiceID" name="ServiceID"  value="<?php echo $serviceID; ?>" required="true" readonly></input> 
                            </div>
							  <div class="form-group"> 
                                <label for="productPrice">Cost</label>
                                <input type="text" id="productPrice" name="productPrice" class="form-control" value="<?php echo $productPrice; ?>" required="true" readonly> 
                            </div>
							  <button type="submit" name="submit" class="btn btn-default">Add</button> </form> 
						</div>
						
					</div>
			</div>
            
       
					<div class="form-grids row widget-shadow" data-example-id="basic-forms"> 
						<div class="form-title">
							<h4>RECENT ADDED PRODUCTS:</h4>
						</div>
                <div class="form-body">
            <table class="table table-bordered"  id="leaveTable"  style="width:100%;"> 
                <thead> 
                    <tr> 
                        <th>#</th> 
                        <th>Product Name</th> 
                        <th>Added_stocks</th> 
                        <th>Date</th> 
                    </tr> 
                </thead> 
                <tbody>
                                    <?php
                                    $addpet=mysqli_query($con,"SELECT 
                                    tblinventory.product_name,
                                    added_stocks.stocks_input, 
                                    added_stocks.date_added
                                    FROM added_stocks
                                    INNER JOIN tblinventory ON added_stocks.newstockID = tblinventory.prodID
                                    ORDER BY added_stocks.date_added DESC LIMIT 5
                                        ");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($addpet)) {

                                    ?>

						 <tr> <th scope="row"><?php echo $cnt;?></th> 
                         <td><?php  echo $row['product_name'];?></td> 
                         <td><?php  echo $row['stocks_input'];?></td>
                         <td><?php  echo date("Y-m-d H:i:s", strtotime($row['date_added']));?></td>
                         </tr>   
                            <?php $cnt=$cnt+1; }?>
                        </tbody> 
                    </table>
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
<?php } ?>