<?php
session_start();
include('includes/dbconnection.php');


///////////// AJAX FOR CHANGING TOTAL SALES IN DASHBOARD CHART
$selectedmonthsales = isset($_GET ['month']) ? intval ( $_GET['month']) : date( 'm');
if (isset($_GET['month'])) {
    $month = intval($_GET['month']); // Ensure month is an integer
    $year = date("Y"); // Get the current year

    // Query to get total sales for each week of the selected month
    $query = "SELECT 
    DAY(tblinvoice.PostingDate) AS day,
    SUM(COALESCE(tblservices.Cost, 0)) AS totalCost,  -- Use COALESCE to handle NULL values
    SUM(COALESCE(tblbook.first_install,0)) AS FirstInstall,
    SUM(COALESCE(tblbook.second_install,0)) AS SecondInstall,
    SUM(COALESCE(tblbook.third_install,0)) AS ThirdInstall  

FROM 
    tblinvoice
LEFT JOIN 
    tblservices ON tblservices.ID = tblinvoice.ServiceId 
LEFT JOIN 
    tblbook ON tblbook.APTNumber = tblinvoice.InvoiceAPTNumber 
	AND DATE(tblbook.InvpostingDate) = DATE(tblinvoice.PostingDate)
WHERE 
    MONTH(tblinvoice.PostingDate) = $month
    AND YEAR(tblinvoice.PostingDate) = $year
GROUP BY 
    DAY(tblinvoice.PostingDate) 
ORDER BY 
    DAY(tblinvoice.PostingDate);
";

    $result = mysqli_query($con, $query);
    $salesData = array_fill(1, cal_days_in_month(CAL_GREGORIAN, $month, $year), 0); // Initialize all days to 0

    // Fetch results and prepare the sales data
    while ($row = mysqli_fetch_assoc($result)) {
        $day = (int)$row['day'];
        $salesData[$day] = (float)$row['totalCost'] + (float)$row['FirstInstall']
        + (float)$row['ThirdInstall'] + (float)$row['SecondInstall']; 
    }

 // Return the sales data as JSON
 echo json_encode(array_values($salesData)); // Return only the values
}


///////////// AJAX FOR CHANGING TOTAL SALARY FOR EMPLOYEE IN EMPLOYEE PERFORMANCE VIEW
$selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
if (isset($_GET['year']) && isset($_GET['employeeID'])) {
    $year = intval($_GET['year']);
    $employeeID = intval($_GET['employeeID']);

    $employee_netSalary = array_fill(0, 12, 0);
    $barquery = "SELECT net_salary AS salary, MONTH(payroll_month) AS month 
    FROM tblpayroll 
    WHERE employeeID = $employeeID 
    AND YEAR(payroll_month) = $year 
    ORDER BY payroll_month";
    $result = mysqli_query($con, $barquery);
    
    while ($row = $result->fetch_assoc()) {
        $month_index = intval($row['month'] - 1); // MySQL MONTH function returns 1 for January, but array index starts from 0
        $employee_netSalary[$month_index] = intval($row['salary']);
    }

    // Return the data as JSON
    echo json_encode(['salaries' => $employee_netSalary]);
    
}

if (isset($_GET['leave_date_linking'])) {
    $leave_date_linking = $_GET['leave_date_linking'];


    // Prepare an array to hold the data for the chart
    $data = [];

    // Query to get leave requests based on the year and month
 $query = "SELECT employeeName, leave_status FROM tblleave WHERE YEAR(leave_date) = '$leave_date_linking'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
 // Loop through the results and count the statuses for each employee
 while ($row = mysqli_fetch_assoc($result)) {
    $employeeName = $row['employeeName'];
    $status = $row['leave_status'];

    // Initialize the employee data if it doesn't exist
    if (!isset($data[$employeeName])) {
        $data[$employeeName] = [
            'approved' => 0,
            'pending' => 0,
            'rejected' => 0,
        ];
    }

    // Increment the count based on the status
    switch ($status) {
        case 1:
            $data[$employeeName]['pending']++;
            break;
        case 2:
            $data[$employeeName]['approved']++;
            break;
        case 3:
            $data[$employeeName]['rejected']++;
            break;
    }
}
} else {
// Handle query error
echo json_encode(['error' => 'Database query failed: ' . mysqli_error($con)]);
exit;
}

// Return the data as JSON
echo json_encode($data);
}



// FOR RECENT ACTIVITY CLIENT INFORMATION
if (isset($_GET['viewid'])) {
$userId = $_GET['viewid'];

// Query to fetch rejected and selected counts based on BookingDate
$query = "SELECT BookingDate, 
                 SUM(CASE WHEN Status = 'Rejected' THEN 1 ELSE 0 END) AS rejected,
                 SUM(CASE WHEN Status = 'Selected' THEN 1 ELSE 0 END) AS selected
          FROM tblbook
          WHERE UserID = $userId 
        GROUP BY DATE(BookingDate)";

$result = mysqli_query($con, $query);

if (!$result) {
    echo "Error executing query: " . mysqli_error($con);
    exit;
}

$dates = [];
$rejectedCounts = [];
$selectedCounts = [];

while ($row = mysqli_fetch_assoc($result)) {
    $dates[] = $row['BookingDate'];
    $rejectedCounts[] = (int)$row['rejected'];
    $selectedCounts[] = (int)$row['selected'];
}

// Prepare data for JSON response
if (empty($dates) || empty($rejectedCounts) || empty($selectedCounts)) {
    echo json_encode(['error' => 'No data found']);
    exit;
}

$response = [
    'dates' => $dates,
    'rejected' => $rejectedCounts,
    'selected' => $selectedCounts
];

echo json_encode($response);
}
?>