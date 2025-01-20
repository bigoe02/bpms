<!-- --------------SEVENDAYS SALE FAKE------------- -->
<div class="col-md-4 widget states-mdl">
						<?php
								//Last Sevendays Sale
								$query8=mysqli_query($con,"select tblinvoice.ServiceId as ServiceId, tblservices.Cost
								from tblinvoice 
								join tblservices  on tblservices.ID=tblinvoice.ServiceId where date(PostingDate)>=(DATE(NOW()) - INTERVAL 7 DAY);");
								while($row8=mysqli_fetch_array($query8))
								{
								$sevendays_sale=$row8['Cost'];
								$tseven+=$sevendays_sale;

								}

							//7 days installment sale
							$queryInstallments = mysqli_query($con, "SELECT first_install, 
							second_install, third_install FROM tblbook WHERE date(InvpostingDate)>=(DATE(NOW()) - INTERVAL 7 DAY);");

							$totalFirstInstall = 0;
							$totalSecondInstall = 0;
							$totalThirdInstall = 0;

							while ($row = mysqli_fetch_array($queryInstallments)) {
								$totalFirstInstall += $row['first_install'];
								$totalSecondInstall += $row['second_install'];
								$totalThirdInstall += $row['third_install'];
							}

							// Calculate total amount of installments
							$sevendaysaleinstall = $totalFirstInstall + $totalSecondInstall + $totalThirdInstall;
							$completesevendaysale =$yesterdaysale + $sevendaysaleinstall;
							?>
 
						<div class="stats-left">
							<h5>Last Sevendays</h5>
							<h4>Sale</h4>
						</div>
						<div class="stats-right">
							<label> <?php 

						if($completesevendaysale ==""):
							echo "0";
else:
	echo $completesevendaysale ;
	// echo "install $sevendaysaleinstall ";
endif;?></label>
						</div>
						<div class="clearfix"> </div>	
					</div>
					<!-- TRASHBUTTON -->
					<a href="new-appointment.php?delid=<?php echo $row['bid'];?>" class="btn btn-danger fa fa-trash-o" onClick="return confirm('Are you sure you want to delete?')"></a>   