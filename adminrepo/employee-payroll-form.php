<!-- employee-payroll-form.php -->
<?php
// Use the passed variable instead of $_GET
$formType = isset($formType) ? $formType : 'form1'; // Default to form1 if no parameter is set

if ($formType === 'form1') {
    // Form 1
    ?>
    <form method="POST" action="employee-payroll.php"> <!-- Ensure the method is POST -->
    <div class="form-group">
    <label for="Employee_ID">Employee_ID</label>
    <select class="form-control" id="Employee_ID" name="Employee_ID" required="true" style="height: 40px;" onchange="updateFields()">
        <option value="">Select Employee ID</option>
        <?php
        $query = mysqli_query($con, "SELECT employeeID, AdminName, role FROM tbladmin");
        while ($row = mysqli_fetch_array($query)) {
            echo "<option value='" . $row['employeeID'] . "' data-name='" . $row['AdminName'] . "' data-role='" . $row['role'] . "'>" . $row['employeeID'] . "</option>";
        }
        ?>
    </select>
</div>
<div class="form-group">
    <label for="Employee_Name">Employee_Name</label>
    <select class="form-control" id="Employee_Name" name="Employee_Name" required="true" style="height: 40px;">
        <option value="">Select Employee Name</option>
    </select>
</div>

<div class="form-group">
    <label for="Role">Role</label>
    <select class="form-control" id="Role" name="Role" required="true" style="height: 40px;">
        <option value="">Select Role</option>
    </select>
</div>

<script>
function updateFields() {
    var employeeSelect = document.getElementById("Employee_ID");
    var selectedOption = employeeSelect.options[employeeSelect.selectedIndex];

    // Get the AdminName and Role from the selected option's data attributes
    var adminName = selectedOption.getAttribute('data-name');
    var role = selectedOption.getAttribute('data-role');

    // Update the Employee_Name and Role dropdowns
    document.getElementById("Employee_Name").innerHTML = "<option value='" + adminName + "'>" + adminName + "</option>";
    document.getElementById("Role").innerHTML = "<option value='" + role + "'>" + role + "</option>";
}
</script>
    
    <div class="form-group">
        <label for="Basic_Salary">Basic_Salary</label>
        <input type="text" id="Basic_Salary" name="Basic_Salary" placeholder="Basic_Salary" required>
    </div>

    <div class="form-group">
        <label for="Commission">Commission</label>
        <input type="text" id="Commission" name="Commission" placeholder="Commission" required>
    </div>
    

    <button type="submit" name="submit" class="submit-button">Submit</button> <!-- Name the button -->
    </form>
    <?php
} elseif ($formType === 'form2') {
    // Form 2
    ?>
                            <h2>Incentives & Deductions</h2>
                        <table class="table table-bordered"  id="leaveTable"  style="width:100%;">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>Employee</th>
                                    <th>Commission</th>
                                    <th>Overtime</th>
                                    <th>Deduction</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                    $comdeducs = mysqli_query($con, "SELECT * from tblcomdeducs");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($comdeducs)) {
                                 ?>
                           
                                <tr>
                                    <th scope="row"><?php echo $cnt;?></th>
                                    <td><?php  echo $row['employeeName'];?></td>
                                    <td><span class="taxes">₱<?php  echo $row['pluscommission'];?></span></td>
                                    <td><span class="taxes">₱<?php  echo $row['overtime'];?></span></td>
                                    <td><span class="tax">₱<?php  echo $row['minusdeduction'];?></span></td>
                                    <td><?php  echo $row['comdeducsDate'];?></td>
                                </tr>
                                <?php $cnt=$cnt+1; }?>
                            </tbody>
                        </table>
    <?php
}
elseif ($formType === 'form3') {
    // Form 2
    ?>
    <form method="POST" action="employee-payroll.php"> <!-- Ensure the method is POST -->
    <div class="form-group">
        <label for="Employee_Name">Employee_Name</label>
        <select class="form-control" id="Employee_Name" name="Employee_Name" required="true" style="height: 40px;">
            <?php
            $query = mysqli_query($con, "SELECT AdminName FROM tbladmin");
            while ($row = mysqli_fetch_array($query)) {
                echo "<option value='" . $row['AdminName'] ."'>" . $row['AdminName'] . "</option>";
            }
            ?>
        </select>
    </div>
    
       <div class="form-group">
        <label for="Taxes_Date">Taxes_Date</label>
        <select class="form-control" id="Taxes_Date" name="Taxes_Date" required="true" style="height: 40px;">
            <option value=''>Select existing date</option>
           <?php
            $query = mysqli_query($con, "SELECT MAX(payroll_month) AS latest_month FROM tblpayroll;");
            while ($row = mysqli_fetch_array($query)) {
                
                echo "<option value='" . $row['latest_month'] ."'>" . $row['latest_month'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="Taxes_SSS">SSS</label>
        <input type="text" id="Taxes_SSS" name="Taxes_SSS" placeholder="Taxes_SSS" required>
    </div>

    <div class="form-group">
        <label for="Taxes_Pag-Ibig">Pag-Ibig</label>
        <input type="text" id="Taxes_Pag-Ibig" name="Taxes_Pag-Ibig" placeholder="Taxes_Pag-Ibig" required>
    </div>
    
    <div class="form-group">
        <label for="Taxes_PhilHealth">PhilHealth</label>
        <input type="text" id="Taxes_PhilHealth" name="Taxes_PhilHealth" placeholder="Taxes_PhilHealth" required>
    </div>
        <button type="submit" name="submitTaxes" class="submit-button">Submit</button>
    </form>
    <?php
}
elseif ($formType === 'form4') {

    ?>
    <div class="modal-content">
        <span class="payslipclose-btn">&times;</span>
        
        <div style="display: flex; align-items: center;">
            <h3 style="margin-right: 10px;">Select Month for Salary</h3>
             <select id="yearDropdown" style="margin-right: 5px;"></select>

            <label for="Day"style="margin-right: 5px;">Day</label>
            <select id="dayDropdown" class="dayDropdown">
                <option value="">Select Day</option>
                <?php for ($day = 1; $day <= 31; $day++): ?>
                    <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                <?php endfor; ?>
            </select>
        
        </div>
        <p>Employee Name: <span id="employeeIdDisplay"></span></p>    
  <div class="month-grid">
            </div> 
            <section class="payroll-management">
                               
                               <h2>Payroll Management</h2>
                               <table id="leaveTables"  style="width:100%;">
                                   <thead>
                                       <tr>
                                           <th>Employee Name</th>
                                           <th>Employee ID</th>
                                           <th>Month of Payroll</th>
                                       </tr>
                                   </thead>
                               
                                   <tbody>
                                   <?php     
                                     // Retrieve employeeName from the URL
                                        $employeeName = isset($_GET['employeeName']) ? $_GET['employeeName'] : '';
                                    // Use the employeeName in your SQL query
                                    $payrollList = mysqli_query($con, "
                                        SELECT * FROM tblpayroll WHERE employeeName = '$employeeName'");
                                
                                           while ($row=mysqli_fetch_array($payrollList)) {
                                        ?>
                                       <tr>
                                           <td><?php  echo $row['employeeName'];?></td>
                                           <td><?php  echo $row['employeeID'];?></td>
                                           <td><?php  echo $row['payroll_month'];?></td>
                                           

                                       </tr>
                                       <?php }?>
                                   </tbody>
                               </table>
                              
                       </section>
        
    </div>
    <?php
}
elseif ($formType === 'form5') {
    // Form 2
    ?>
    <form method="POST" action="employee-payroll.php"> <!-- Ensure the method is POST -->

    <div class="form-group">
        <label for="Employee_Name">Employee_Name</label>
        <select class="form-control" id="Employee_Name" name="Employee_Name" required="true" style="height: 40px;">
            <?php
            $query = mysqli_query($con, "SELECT AdminName FROM tbladmin");
            while ($row = mysqli_fetch_array($query)) {
                echo "<option value='" . $row['AdminName'] ."'>" . $row['AdminName'] . "</option>";
            }
            ?>
        </select>
    </div>
       <div class="form-group">
        <label for="Allowance_Date">Allowance_Date</label>
        <select class="form-control" id="Allowance_Date" name="Allowance_Date" required="true" style="height: 40px;">
            <option value=''>Select existing date</option>
           <?php
            $query = mysqli_query($con, "SELECT MAX(payroll_month) AS latest_month FROM tblpayroll;");
            while ($row = mysqli_fetch_array($query)) {
                
                echo "<option value='" . $row['latest_month'] ."'>" . $row['latest_month'] . "</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="Allowance_Food">Food</label>
        <input type="text" id="Allowance_Food" name="Allowance_Food" placeholder="Allowance_Food" required>
    </div>

    <div class="form-group">
        <label for="Allowance_Drinks">Drinks</label>
        <input type="text" id="Allowance_Drinks" name="Allowance_Drinks" placeholder="Allowance_Drinks" required>
    </div>
    
        <button type="submit" name="submitAllowance" class="submit-button">Submit</button>
    </form>
   
    <?php
}
elseif($formType === 'form6'){
    ?>

<h2>Incentives & Deductions</h2>
                        <table class="table table-bordered"  id="leaveTables"  style="width:100%;">
                            <thead>
                                <tr>
                                    <th>#</th> 
                                    <th>Employee</th>
                                    <th>Commission</th>
                                    <th>Overtime</th>
                                    <th>Deduction</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            	if (isset($_GET['viewid'])) {
                                    $viewid = $_GET['viewid'];
                                    $comdeducs = mysqli_query($con, "SELECT * from tblcomdeducs WHERE employeeID = '$viewid' ");
                                    $cnt=1;
                                    while ($row=mysqli_fetch_array($comdeducs)) {
                                 ?>
                           
                                <tr>
                                    <th scope="row"><?php echo $cnt;?></th>
                                    <td><?php  echo $row['employeeName'];?></td>
                                    <td><span class="taxes">₱<?php  echo $row['pluscommission'];?></span></td>
                                    <td><span class="taxes">₱<?php  echo $row['overtime'];?></span></td>
                                    <td><span class="tax">₱<?php  echo $row['minusdeduction'];?></span></td>
                                    <td><?php  echo $row['comdeducsDate'];?></td>
                                </tr>
                                <?php $cnt=$cnt+1; }}?>
                            </tbody>
                        </table>


<?php
}
?>