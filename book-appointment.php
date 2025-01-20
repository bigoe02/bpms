<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
require_once('vendor/autoload.php');



if (strlen($_SESSION['bpmsuid'] == 0)) {
    header('location:logout.php');
} else {
    $loginID = $_SESSION['bpmsuid'];
    // Fetch user email from the tbluser table
    $userQuery = mysqli_query($con, "SELECT Email FROM tbluser WHERE ID = $loginID");
    $userData = mysqli_fetch_array($userQuery);
    $userEmail = $userData['Email'];

    //     reCAPTCHA Verification

    $recaptcha_secret = '6LcBWIQqAAAAANVA4ss9o4fDhwnCmFy-FCJVdqBv';
    $recaptcha_response = $_POST['g-recaptcha-response'];

    // Verify the reCAPTCHA response
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}");
    $responseKeys = json_decode($response, true);


    if (intval($responseKeys["success"]) == 1) {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {



            ////////////GCASH GATEWAY INTEGRATION
            $totalCost = $_POST['totalCost'];

            $client = new \GuzzleHttp\Client();
            error_log("Preparing to send request to PayMongo with totalCost: " . $totalCost);
            try {
                // Create a payment link with PayMongo
                $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
                    'body' => json_encode([
                        'data' => [
                            'attributes' => [
                                'amount' => $totalCost * 100, // Convert to the correct unit (e.g., cents)
                                'description' => 'Appointment Payment',
                                'remarks' => 'Payment for services booked',
                                'payment_method' => [
                                    'type' => 'GCash' // Ensure that this is set correctly for GCash
                                ]
                            ]
                        ]
                    ]),
                    'headers' => [
                        'accept' => 'application/json',
                        'authorization' => 'Basic ' . base64_encode('sk_test_c4mXzsphn4V1TcNG8qANB1MH:'),
                        'content-type' => 'application/json',
                    ],
                ]);

                $paymentLink = json_decode($response->getBody(), true);

                // Redirect the user to the checkout URL
                if (isset($paymentLink['data']['attributes']['checkout_url'])) {
                    $checkoutUrl = $paymentLink['data']['attributes']['checkout_url'];

                    // Output JavaScript to open the checkout URL in a new window
                    echo "<script>
            
                           window.onload = function() {
                            window.open('$checkoutUrl', '_blank');
                            
                            // Display loading section immediately
                            document.getElementById('loading-section').style.display = 'flex';
                            document.body.style.overflow = 'hidden'; // Prevent scrolling of the body

                            // Redirect to thank-you.php after 10 seconds
                            setTimeout(function() {
                                window.location.href = 'thank-you.php';
                            }, 20000); // Adjust the time as needed
                        };
                        </script>";


                    ////////////INSERT APPOINTMENT 
                    $uid = $_SESSION['bpmsuid'];
                    $adate = $_POST['adate'];
                    $atime = $_POST['atime'];
                    $bookService = $_POST['attributes'];
                    $msg = $_POST['message'];
                    $aptnumber = mt_rand(100000000, 999999999);

                    // Join all selected services into a single string
                    $bookServiceStr = implode(', ', $bookService);


                    $query = mysqli_query($con, "insert into tblbook(UserID,AptNumber,AptDate,AptTime,BookService,Message) value
                    ('$uid','$aptnumber','$adate','$atime','$bookServiceStr','$msg')");

                    if ($query) {
                        $ret = mysqli_query($con, "select AptNumber from tblbook where tblbook.UserID='$uid' order by ID desc limit 1;");
                        $result = mysqli_fetch_array($ret);
                        $_SESSION['aptno'] = $result['AptNumber'];

                    } else {
                        echo '<script>alert("Something Went Wrong. Please try again")</script>';
                    }

                } else {
                    throw new Exception("Checkout URL not found in the response.");
                }

            } catch (Exception $e) {
                echo '<script>alert("Payment processing error: ' . $e->getMessage() . '")</script>';
            }


        }
    }



    ?>
    <!doctype html>
    <html lang="en">

    <head>


        <title>Beauty Parlour Management System | Appointment Page</title>

        <!-- Template CSS -->
        <link rel="stylesheet" href="assets/css/style-starter.css">
        <link rel="stylesheet" href="assets/css/style-newstarter.css">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Slab:400,700,700i&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <!-- MODAL -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
        <!-- SWEETALERT -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="assets/js/alert.js"></script>
        <!-- RECAPTCHA -->
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <!-- EMAILJS WAG BURAHIN -->
        <script type="text/javascript"
                    src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
            </script>
            <script type="text/javascript">
            (function(){
                emailjs.init({
                    publicKey: "1ZW6y7tgNLWPKmcP1", 
                    // Pp90UCRf5XKeLWJRu
                });
            })();
            </script>
    </head>

    <body id="home">
        <?php include_once('includes/header.php'); ?>

        <script src="assets/js/jquery-3.3.1.min.js"></script> <!-- Common jquery plugin -->
        <!--bootstrap working-->
        <script src="assets/js/bootstrap.min.js"></script>
        <!-- //bootstrap working-->
        <!-- disable body scroll which navbar is in active -->
        <script>
            $(document).ready(function () {
                $('.navbar-toggler').click(function () {
                    $('body').toggleClass('noscroll');
                });
            });
        </script>
        <!-- disable body scroll which navbar is in active -->

        <!-- breadcrumbs -->
        <section class="w3l-inner-banner-main">
            <div class="about-inner contact ">
                <div class="container">
                    <div class="main-titles-head text-center">
                        <h3 class="header-name ">

    
                    </div>
                </div>
            </div>
            <div class="breadcrumbs-sub">
                <div class="container">
                    <ul class="breadcrumbs-custom-path">
                        <li class="right-side propClone"><a href="index.php" class="">Home <span class="fa fa-angle-right"
                                    aria-hidden="true"></span></a>
                            <p>
                        </li>
                        <li class="active page-content">
                            Book Appointment</li>
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

                            $ret = mysqli_query($con, "select * from tblpage where PageType='contactus' ");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {

                                ?>
                                <div class="cont-top">
                                    <div class="cont-left text-center">
                                        <span class="fa fa-phone text-primary"></span>
                                    </div>
                                    <div class="cont-right">
                                        <h6>Call Us</h6>
                                        <p class="para"><a href="tel:+44 99 555 42">+<?php echo $row['MobileNumber']; ?></a></p>
                                    </div>
                                </div>
                                <div class="cont-top margin-up">
                                    <div class="cont-left text-center">
                                        <span class="fa fa-envelope-o text-primary"></span>
                                    </div>
                                    <div class="cont-right">
                                        <h6>Email Us</h6>
                                        <p class="para"><a href="mailto:example@mail.com"
                                                class="mail"><?php echo $row['Email']; ?></a></p>
                                    </div>
                                </div>
                                <div class="cont-top margin-up">
                                    <div class="cont-left text-center">
                                        <span class="fa fa-map-marker text-primary"></span>
                                    </div>
                                    <div class="cont-right">
                                        <h6>Address</h6>
                                        <p class="para"> <?php echo $row['PageDescription']; ?></p>
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
                            <div class="button-container" style="text-align: center;">
                                <a type="button" class="btn btn--purple" data-toggle="modal" data-target="#calendarModal">
                                    <span class="btn__txt ">CHECK AVAILABILITY<i class='fas fa-calendar-alt'
                                            style=' margin-left: 10px; font-size:15px;'></i></span>
                                    <i class="btn__bg" aria-hidden="true"></i>
                                    <i class="btn__bg" aria-hidden="true"></i>
                                    <i class="btn__bg" aria-hidden="true"></i>
                                    <i class="btn__bg" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="loginform-design" style="height: 100%;">
                                <div class="container-login" style="width: 100%; height: 100%;">
                                    <div class="brand-logo"
                                        style="background:url('assets/images/bookinglogo.png');  background-size: cover;">
                                    </div>
                                    <div class="brand-title">LA JULIETA BEAUTY CENTER</div>
                                    <form method="post" id="bookform">
                                        <!--  onsubmit="return validateForm()" -->

                                        <input type="hidden" name="subject" id="subject" value="book_appointment">
                                        <input type="hidden" name="username" id="username"
                                            value="<?php echo htmlspecialchars($userEmail); ?>">
                                        <div class="twice-two">
                                            <label style="margin-top: 10px;">Appointment Date</label> <label>Appointment
                                                Time</label>
                                            <input type="date" class="form-control appointment_date" placeholder="Date"
                                                name="adate" id='adate' required="true" oninput="fetchTimeSlots()">
                                            <input oninput="validateTime()" list="time-option" type="time"
                                                class="form-control appointment_time" placeholder="Time" name="atime"
                                                id='atime' required="true" min="11:00" max="18:00">
                                            <!-- THE DATA HERE IN DATALIST  ARE USING AJAX METHOD in fetchTimeSlots -->
                                                <datalist id="time-option"></datalist> 

                                        </div>

                                        <script>

                                            function validateTime() {
                                                const input = document.getElementById('atime');
                                                const value = input.value;

                                                // Check if the input value is equal to 08:00 or 19:00 or 14:00
                                                if (value !== '11:00' && value !== '13:00' && value !== '15:00' && value !== '17:00') {
                                                    // If not, set the input value to an empty string to make it invalid
                                                    input.value = '11:00';
                                                }
                                            }


                                            function fetchTimeSlots() {
                                                const dateInputs = document.getElementById('adate');
                                                const selectedDate = dateInputs.value;

                                                if (!selectedDate) {
                                                    return; // Exit if no date is selected
                                                }

                                                // Send an AJAX request to get-time-slots.php
                                                fetch('get-time-slots.php', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                    },
                                                    body: `date=${selectedDate}`,
                                                })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        const datalist = document.getElementById('time-option');
                                                        datalist.innerHTML = ''; // Clear existing options

                                                        // Populate the datalist with new options
                                                        data.forEach(slot => {
                                                            const option = document.createElement('option');
                                                            option.value = slot.value;
                                                            option.textContent = slot.label;
                                                            datalist.appendChild(option);
                                                        });
                                                    })
                                                    .catch(error => console.error('Error:', error));
                                            }
                                        </script>

                                        <div class="form-group">
                                            <label for="SELECTServiceName">CATEGORY</label>
                                            <?php
                                            // Assuming you have a connection to your database already established
                                            // Query to get only specific serviceIDs for the dropdown
                                            $query1 = mysqli_query($con, "SELECT ServiceName, Cost FROM tblservices WHERE serviceID BETWEEN 201 AND 204");

                                            // Query to get all services for cost retrieval
                                            $query2 = mysqli_query($con, "SELECT ServiceName, Cost FROM tblservices WHERE serviceID > 204");
                                            ?>
                                            <input type="hidden" id="serviceCosts" value='<?php
                                            $costs = array();
                                            while ($row = mysqli_fetch_array($query2)) {
                                                $costs[$row['ServiceName']] = $row['Cost'];
                                            }
                                            echo json_encode($costs);
                                            ?>'>
                                            <select class="form-control" id="SELECTServiceName" name="SELECTServiceName"
                                                required="true">
                                                <option value=""><-CHOOSE HERE-></option>
                                                <?php
                                                // Reset the query1 pointer since we used it above
                                                mysqli_data_seek($query1, 0);
                                                while ($row = mysqli_fetch_array($query1)) {
                                                    $selected = (isset($_SESSION['selectedService']) && $_SESSION['selectedService'] == $row['ServiceName']) ? 'selected' : '';
                                                    echo "<option value='" . $row['ServiceName'] . "' " . $selected . ">" . $row['ServiceName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="twice-two">

                                            <div id="attributes-container" class="dropdown-container"
                                                style="display: none;">
                                                <label for="attributes">Select Service:</label>
                                                <select id="attributes" class="form-control" name="attributes[]"
                                                    required="true">

                                                </select>
                                                <button type="button" id="add-attribute"
                                                    style="margin-left: 10px;">+</button>
                                            </div>

                                            <div id="additional-attributes-container">
                                                <!-- New select containers will be added here -->
                                            </div>
                                        </div>
                                        <div id="totalCostDisplay" style="padding-top: 10px;">
                                            <strong>Total Cost: ₱<span id="totalCost">0.00</span></strong>
                                            <input type="hidden" name="totalCost" id="totalCostInput" value="0">
                                            <input type="hidden" id="secondPayment" name="secondPayment" value="">
                                        </div>



                                        <div>
                                            <textarea class="form-control" id="message" name="message"
                                                placeholder="Note(Optional)"></textarea>
                                        </div><br>
                                        <div class="twice-two">
                                            <div class="g-recaptcha" data-sitekey="6LcBWIQqAAAAABiBHcUMVqDw1wvK4TJGLfOW-Bol"
                                                data-callback="enableSubmit"></div>

                                            <button type="submit" class="btn btn-contact" name="submitBtn" id="submitBtn"
                                                style="width:100%;" disabled>Make an
                                                Appointment</button>
                                        </div>
                                    </form>
                                    <?php if (isset($alertnewMessage))
                                        echo $alertnewMessage; ?>

                                    <section class="sec-loading" id="loading-section" style=" display: none;">
                                        <div class="one">
                                        </div>
                                        <div class="loadingAnimation">
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Structure -->
                    <section class="calendarprev">
                        <div class="modal fade" id="calendarModal" tabindex="-1" role="dialog"
                            aria-labelledby="calendarModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="calendarModalLabel">Calendar Availability</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Load the content of calendarpreview.php here -->
                                        <iframe src="calendarpreview.php"
                                            style="width: 100%; height: 600px; border: none;"></iframe>
                                    </div>
                                    <style>
                                        @media (max-width: 768px) {
                                            .modal-dialog {
                                                max-width: 90%;
                                            }

                                            .modal-content {
                                                height: 80vh;
                                                overflow-y: auto;
                                            }

                                            iframe {
                                                height: 100%;
                                            }
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
        <?php include_once('includes/footer.php'); ?>
        <!-- move top -->
        <button onclick="topFunction()" id="movetop" title="Go to top">
            <span class="fa fa-long-arrow-up"></span>
        </button>
        <script>
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
            $(function () {
                var dtToday = new Date();

                var month = dtToday.getMonth() + 1;
                var day = dtToday.getDate();
                var year = dtToday.getFullYear();
                if (month < 10)
                    month = '0' + month.toString();
                if (day < 10)
                    day = '0' + day.toString();

                var maxDate = year + '-' + month + '-' + day;
                $('#adate').attr('min', maxDate);
            });




            function disableDropdown(dropdown) {
                dropdown.disabled = true;
            }

            document.addEventListener('DOMContentLoaded', function () {
                const serviceSelect = document.getElementById('SELECTServiceName');
                const attributesContainer = document.getElementById('attributes-container');
                const attributesSelect = document.getElementById('attributes');
                const additionalAttributesContainer = document.getElementById('additional-attributes-container');
                const totalCostDisplay = document.getElementById('totalCost');
                const serviceCosts = JSON.parse(document.getElementById('serviceCosts').value);


                const laserTreatmentOptions = ['CHOOSE SERVICE HERE', 'OPT Upper/Lower Lip', 'OPT Face', 'OPT Face & Neck', 'OPT Underarms HR', 'OPT Underarms SR', 'OPT Half Arms HR',
                    'OPT Half Arms SR', 'Full Arms HR', 'Full Arms SR', 'Half Legs HR', 'Half Legs SR', 'Full Legs HR', 'Full Legs SR', 'Picosecond Carbon Laser Peel Facial',
                    'Picosecond Intensive Underarm Whitening', 'Picosecond Skin Rejuvenation', 'CO2 Fractional Laser', 'CO2 Pigmentation Removal', 'CO2 PRP Treatment'];

                const skinfusionTreatmentOptions = ['CHOOSE SERVICE HERE', 'Skin Repair Stem Cell MTS', 'Acne Clear MTS', 'PRP Treatment', 'Korean BB Glow Nano', 'Korean BB Glow Micro'
                    , 'BB Blush Nano', 'BB Blush Micro', 'AC Stem Cell Gold Serum', 'Whitening Stem Cell Serum', 'EGF Peptide Gold Serum', 'Salmon DNA Glod Serum',
                    'Hyaluronic Acid Serum', 'Vitamin C Serum', 'Basic Facial', 'Diamond Peel', 'Hydraglow Facial', 'Oxygen Facial', 'Korean Sheet Mask'
                ];
                const multivitaminsOptions = ['CHOOSE SERVICE HERE', 'Bella Drip', 'Basic Facial', 'Diamond Peel'];
                let selectedServices = [];
                let firstPayment = 0;
                document.getElementById('attributes').addEventListener('change', function () {
                    const selectedService = this.value;
                    const price = serviceCosts['Bella Drip'];

                    if (selectedService === "Bella Drip") {
                        // Calculate the installment payments
                        firstPayment = (price * 0.20).toFixed(0);
                        const secondPayment = (price * 0.70).toFixed(0);
                        const lastPayment = (price * 0.10).toFixed(0); // 10% interest on the last payment
                        const totalpayment = (parseFloat(firstPayment) + parseFloat(secondPayment) + parseFloat(lastPayment)).toFixed(0);


                        // Get today's date
                        const today = new Date();

                        // Calculate the payment dates
                        const firstPaymentDate = today; // Current date
                        const secondPaymentDate = new Date(today); // Copy today
                        secondPaymentDate.setDate(today.getDate() + 35); // 1 month and 5 days later

                        const lastPaymentDate = new Date(secondPaymentDate); // Start from second payment date
                        lastPaymentDate.setMonth(secondPaymentDate.getMonth() + 1); // 1 month after second payment

                        // Format dates to a readable format (e.g., MM/DD/YYYY)
                        const formatDate = (date) => {
                            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
                            const day = String(date.getDate()).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${month}/${day}/${year}`;
                        };

                        Swal.fire({
                            title: 'Payment Reminder',
                            html: `
                                                    The price is: ₱${price} <br>
                                                    This service is available for pre-installment in 3 terms:<br>
                                                    1st term total: ₱${firstPayment} due on ${formatDate(firstPaymentDate)} <br>(20% of total price)<br>
                                                    2nd term total: ₱${secondPayment} due on ${formatDate(secondPaymentDate)} <br>(70% of total price)<br>
                                                    3rd term total: ₱${lastPayment} due on ${formatDate(lastPaymentDate)} <br>(10% interest on the last payment)<br>
                                                    (PLEASE TAKE NOTE OF THIS. THANKYOU!)
                                                `,
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });


                        updateTotalCost();
                        document.getElementById('secondPayment').value = secondPayment;

                    }
                });
                function updateTotalCost() {
                    let total = 0;
                    const selectedServices = document.querySelectorAll('select[name="attributes[]"]');
                    // console.log("Selected Services:", selectedServices);
                    // console.log("Service Costs:", serviceCosts);
                    selectedServices.forEach(select => {
                        const serviceName = select.value;
                        if (serviceName === "Bella Drip") {

                            total = parseFloat(firstPayment); // Use the global firstPayment


                        } else if (serviceCosts[serviceName]) {
                            total += parseFloat(serviceCosts[serviceName]);
                        }
                    });
                    totalCostDisplay.textContent = total.toFixed(2);
                    totalCostInput.value = total;
                    //document.getElementById('totalCostInput').value = total;
                }

                attributesSelect.addEventListener('change', updateTotalCost);
                additionalAttributesContainer.addEventListener('change', updateTotalCost);

                serviceSelect.addEventListener('change', function () {


                    if (serviceSelect.value === 'LASER TREATMENT') {
                        // Show the attributes container  
                        updateTotalCost();
                        attributesContainer.style.marginTop = '10px';
                        attributesContainer.style.display = 'flex';
                        attributesContainer.style.alignItems = 'center';

                        // Populate the initial attributes select with options
                        populateSelectOptions(attributesSelect, laserTreatmentOptions);
                    }
                    else if (serviceSelect.value === 'FACIAL & SKINFUSION TREATMENT') {
                        // Show the attributes container

                        attributesContainer.style.marginTop = '10px';
                        attributesContainer.style.display = 'flex';
                        attributesContainer.style.alignItems = 'center';


                        // Populate the initial attributes select with options
                        populateSelectOptions(attributesSelect, skinfusionTreatmentOptions,);

                    }
                    else if (serviceSelect.value === 'MULTIVATAMINS IV DRIP') {
                        // Show the attributes container

                        attributesContainer.style.marginTop = '10px';
                        attributesContainer.style.display = 'flex';
                        attributesContainer.style.alignItems = 'center';


                        // Populate the initial attributes select with options
                        populateSelectOptions(attributesSelect, multivitaminsOptions);

                    }

                    else {
                        // Hide the attributes container
                        attributesContainer.style.display = 'none';

                        // Optionally clear the options
                        attributesSelect.innerHTML = '';
                        additionalAttributesContainer.innerHTML = ''; // Clear additional attributes
                    }
                });


                document.getElementById('add-attribute').addEventListener('click', function () {
                    let currentOptions;
                    if (serviceSelect.value === 'LASER TREATMENT') {
                        currentOptions = laserTreatmentOptions;
                    } else if (serviceSelect.value === 'FACIAL & SKINFUSION TREATMENT') {
                        currentOptions = skinfusionTreatmentOptions;
                    }
                    createNewSelectContainer(additionalAttributesContainer, currentOptions);
                    updateTotalCost();
                });

                // Initialize a counter for attributes
                function createNewSelectContainer(container, options) {
                    // Create a new select container
                    const newSelectContainer = document.createElement('div');
                    newSelectContainer.style.marginTop = '10px';
                    newSelectContainer.style.display = 'flex';
                    newSelectContainer.style.alignItems = 'center';

                    // Create a new label
                    const newLabel = document.createElement('label');
                    newLabel.textContent = 'Select Another Service:';
                    newLabel.style.marginRight = '10px';

                    // Create a new select element
                    const newSelect = document.createElement('select');
                    newSelect.className = 'form-control';
                    newSelect.required = true;
                    // Set the name to 'attributes[]' so it can be processed by PHP as an array
                    newSelect.name = 'attributes[]';

                    // Populate the new select with options
                    populateSelectOptions(newSelect, options);
                    // Add the event listener here, after creating and populating the select
                    // newSelect.addEventListener('change', updateTotalCost);
                    newSelect.addEventListener('change', function () {
                        const selectedValue = this.value;
                        if (selectedValue) {
                            // Add the selected service to the list
                            selectedServices.push(selectedValue);
                            // Remove the selected value from other dropdowns
                            removeSelectedFromDropdowns(selectedValue);
                        }
                        updateTotalCost(); // Update total cost whenever a service is selected
                    });
                    // Create a new plus button
                    const newPlusButton = document.createElement('button');
                    newPlusButton.type = 'button';
                    newPlusButton.textContent = '+';
                    newPlusButton.style.marginLeft = '10px';

                    // Add event listener to the new plus button
                    newPlusButton.addEventListener('click', function () {
                        if (attributeCount < 3) {
                            attributeCount++; // Increment the count
                            createNewSelectContainer(container, options);
                            updatePlusButtons(); // Update button states
                        } else {
                            showAlert('You can only add up to 3 Services.', '', 'warning');
                        }

                    });
                    // Call updatePlusButtons initially to set the correct state of buttons when the script loads
                    updatePlusButtons();
                    // Create a new delete button
                    const newDeleteButton = document.createElement('button');
                    newDeleteButton.type = 'button';
                    newDeleteButton.textContent = '-';
                    newDeleteButton.style.marginLeft = '10px';

                    // Add event listener to the delete button
                    newDeleteButton.addEventListener('click', function () {
                        container.removeChild(newSelectContainer);
                        attributeCount--; // Decrement the counter
                        updatePlusButtons(); // Update button states buttons
                        updateTotalCost(); // Recalculate total cost after removal


                        // Remove the deleted service from the selected services array
                        selectedServices = selectedServices.filter(service => service !== newSelect.value);
                        // Re-populate the dropdowns to include the removed service
                        populateSelectOptions(newSelect, options);

                    });

                    // Append the new elements to the container
                    newSelectContainer.appendChild(newLabel);
                    newSelectContainer.appendChild(newSelect);
                    newSelectContainer.appendChild(newPlusButton);
                    newSelectContainer.appendChild(newDeleteButton);

                    // Append the new container to the parent container
                    container.appendChild(newSelectContainer);
                    attributeCount++; // Increment the counter
                    updatePlusButtons();// Check if we need to disable buttons
                    //  updateTotalCost();

                }

                // Function to remove selected value from all dropdowns
                function removeSelectedFromDropdowns(selectedValue) {
                    const allSelects = document.querySelectorAll('select[name="attributes[]"]');
                    allSelects.forEach(select => {
                        if (select.value !== selectedValue) {
                            const options = Array.from(select.options);
                            options.forEach(option => {
                                if (option.value === selectedValue) {
                                    option.style.display = 'none'; // Hide the option
                                } else {
                                    option.style.display = 'block'; // Show other options
                                }
                            });
                        }
                    });
                }
                let attributeCount = 0;
                function updatePlusButtons() {
                    const plusButtons = document.querySelectorAll('#add-attribute'); // Adjust the selector as needed
                    console.log(attributeCount);
                    if (attributeCount >= 3) {
                        plusButtons.forEach(button => {
                            button.disabled = true;
                        });
                    } else {
                        plusButtons.forEach(button => {
                            button.disabled = false;
                        });
                    }
                }
                function populateSelectOptions(selectElement, options) {
                    // Clear existing options
                    selectElement.innerHTML = '';

                    // Add new options
                    options.forEach(option => {
                        const opt = document.createElement('option');
                        opt.value = option;
                        opt.textContent = option;
                        selectElement.appendChild(opt);
                    });
                }
            });

            function checkForDuplicateSelections() {
                const selectedServices = [];
                const allSelects = document.querySelectorAll('select[name="attributes[]"]');

                allSelects.forEach(select => {
                    if (select.value) { // Check if a service is selected
                        selectedServices.push(select.value);
                    }
                });

                // Check for duplicates
                const hasDuplicates = selectedServices.some((item, index) => selectedServices.indexOf(item) !== index);

                if (hasDuplicates) {
                    showAlert('Error', 'Please try again. You have selected the same service in multiple dropdowns.', 'error');
                    event.preventDefault();
                    return true; // Return true if duplicates exist
                }
                return false; // Return false if no duplicates
            }



            document.addEventListener("DOMContentLoaded", function () {

        Swal.fire({
            title: 'Important Reminder',
            html: `
                <p> Once you confirm your booking, There will be no booking cancellation.
                 If you fail to show up on the scheduled date, 
                 you will need to reschedule with the admin.</p>
            `,
            icon: 'info',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                // Do nothing
            }
        });

    
                document.getElementById('bookform').addEventListener('submit', function (event) {
                    event.preventDefault();

                    // Check for duplicate selections
                    if (checkForDuplicateSelections()) {
                        return;
                    }

                    // Get form elements
                    var formElements = document.querySelectorAll('input[required], select[required], textarea[required]');
                    var isValid = true;

                    // Check if all required fields are filled
                    formElements.forEach(function (element) {
                        if (!element.value) {
                            isValid = false;
                            element.style.borderColor = 'red'; // Highlight empty fields
                        } else {
                            element.style.borderColor = ''; // Reset border color if filled
                        }
                    });

                    if (isValid) {
                        // Get the total cost and selected services
                        const totalCost = document.getElementById('totalCostInput').value;
                        const selectedServices = Array.from(document.querySelectorAll('select[name="attributes[]"]'))
                            .map(select => select.value)
                            .filter(value => value); // Filter out empty values

                        // Show SweetAlert2 confirmation dialog
                        Swal.fire({
                            title: 'Confirm Appointment',
                            html: `
                                    <p>Total Amount: ₱${totalCost}</p>
                                    <p>Selected Services: ${selectedServices.join(', ')}</p>
                                    `,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                console.log("Confirmed! Submitting form..."); // Debugging line
                                document.getElementById('bookform').submit();
                                        ///EMAILJS SENDING
                                        
                        //         const selectedServices = Array.from(document.querySelectorAll('select[name="attributes[]"]'))
                        //             .map(select => select.value)
                        //             .filter(value => value); // Filter out empty values

                        // // Check if "Bella Drip" is among the selected services
                        //     const isBellaDripSelected = selectedServices.includes("Bella Drip");
                        //     let params = {
                        //                     username: document.getElementById("username").value,
                        //                     subject: document.getElementById("subject").value,
                        //                     adate: document.getElementById("adate").value,
                        //                     atime: document.getElementById("atime").value,
                        //                     selectedServices: selectedServices.join(', ') // Include selected services here
                        //                 };

                        //                 if (isBellaDripSelected) {
                        //                     params.secondPayment = document.getElementById("secondPayment").value;
                        //                     emailjs.send("service_qoanxfx", "template_zajgqnv", params);
                        //                 } else {
                        //                     params.atime = document.getElementById("atime").value;
                        //                     emailjs.send("service_qoanxfx", "template_t8cfirg", params);
                                        // }

                                document.getElementById('loading-section').style.display = 'flex'; // Show loading section
                                document.body.style.overflow = 'hidden'; // Prevent scrolling of the body



                            } else {
                                console.log("Not confirmed."); // Debugging lin
                                // User clicked "No"
                                Swal.fire({
                                    title: 'No Appointment Made',
                                    text: 'You have chosen not to make an appointment.',
                                    icon: 'info',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });

                    }

                });

                const loadingText = document.querySelector('.sec-loading .loadingAnimation');
                let dots = 0;

                setInterval(() => {
                    dots = (dots + 1) % 4; // Cycle through 0 to 3
                    loadingText.textContent = 'Loading' + '.'.repeat(dots);
                }, 500); // Change the text every 500 milliseconds
            });

            // RECAPTCHA
            function enableSubmit() {
                document.getElementById('submitBtn').disabled = false;
            }

            function validateForm() {
                var response = grecaptcha.getResponse();
                if (response.length == 0) {
                    showAlert('Please complete the reCAPTCHA', '', 'warning');
                    return false;
                }
                return true;
            }



            ////// FOR THE INPUT FIELD TO DISABLE MONDAY SELECTION
            // Get the date input field
            const dateInput = document.getElementById('adate');

            // Add an event listener to the input field
            dateInput.addEventListener('input', function () {
                // Get the selected date
                const selectedDate = new Date(this.value);

                // Check if the selected date is a Monday
                if (selectedDate.getDay() === 1) {
                    // If it's a Monday, set the input field to an empty string
                    Swal.fire({
                        title: 'Monday is not available for selection.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    this.value = '';

                }
            });
        </script>
        <!-- /move top -->
    </body>

    </html><?php } ?>