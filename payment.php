<?php
session_start(); // Start the PHP session

// Check if the user is logged in
if ($_SESSION["Login"] != "YES") {
    header("Location: index.php");
    exit();
}

// Check if USER_ID is set
if (!isset($_SESSION["ID"])) {
    echo "User ID is not set in the session.";
    exit();
}
$user_id = $_SESSION["ID"];

require("config.php");

$query = "
    SELECT 
        u.first_name, 
        u.last_name, 
        u.gender, 
        u.phone
    FROM 
        user u 
    WHERE 
        u.id = '$user_id'
";

$result = mysqli_query($conn, $query);
$rows = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link rel="stylesheet" href="fonts.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="payment.css">
    
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div>
                <div>
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <h2 class="heading text-center">Payments</h2>
                        </div>
                    </div>
                    <form onsubmit="event.preventDefault()" class="form-card">
                        <div class="row justify-content-center mb-4 radio-group">
                            <div class="col-sm-3 col-5">
                                <div class='radio selected mx-auto' data-value="dk">
                                    <img class="fit-image" src="IMAGE/card2.jpg" width="105px" height="55px">
                                </div>
                            </div>
                            <div class="col-sm-3 col-5">
                                <div class='radio mx-auto' data-value="visa">
                                    <img class="fit-image" src="IMAGE/card1.jpg" width="105px" height="55px">
                                </div>
                            </div>
                            <div class="col-sm-3 col-5">
                                <div class='radio mx-auto' data-value="master">
                                    <img class="fit-image" src="IMAGE/card3.jpg" width="105px" height="55px">
                                </div>
                            </div>
                            <div class="col-sm-3 col-5">
                                <div class='radio mx-auto' data-value="paypal">
                                    <img class="fit-image" src="IMAGE/card4.png" width="105px" height="55px">
                                </div>
                            </div>
                        </div>
              
                        <div class="row justify-content-center">
                            <div >
                                <div class="input-group">
                                    <input type="text" name="Name" placeholder="John Doe">
                                    <label>Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div>
                                <div class="input-group">
                                    <input type="text" id="cr_no" name="card-no" placeholder="0000 0000 0000 0000" minlength="19" maxlength="19">
                                    <label>Card Number</label>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div >
                                <div class="row">
                                    <div>
                                        <div class="input-group">
                                            <input type="text" id="exp" name="expdate" placeholder="MM/YY" minlength="5" maxlength="5">
                                            <label>Expiry Date</label>
                                        </div>
                                    </div>
                                    <div >
                                        <div class="input-group">
                                            <input type="password" name="cvv" placeholder="&#9679;&#9679;&#9679;" minlength="3" maxlength="3">
                                            <label>CVV</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div >
                                <input type="submit" value="Pay Now" class="btn btn-pay placeicon">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>  
    <script type='text/javascript'>$(document).ready(function () {



// Radio button
$('.radio-group .radio').click(function () {
    $(this).parent().parent().find('.radio').removeClass('selected');
    $(this).addClass('selected');
});
})</script>
<script type='text/javascript'>var myLink = document.querySelector('a[href="#"]');
myLink.addEventListener('click', function (e) {
e.preventDefault();
});</script>


<?php mysqli_close($conn); ?>

</body>

<!-- footer-->
<footer>
    <div class="footer-container">
        <div class="footer-links">
            <a href="#" onclick="openModal('terms')">Terms of Service</a> |
            <a href="#" onclick="openModal('privacy')">Privacy Policy</a>
        </div>
        <div class="footer-info">
            <p>&copy; 2024 JomTeam. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Modal for Terms of Service -->
<div id="termsModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('terms')">&times;</span>
        <h2>Terms of Service</h2>
        <p>
            Welcome to JomTeam! By using our platform, you agree to these Terms of Service. Please read them carefully.
            If you do not agree with any part of these terms, you may not use our services.
        </p>
        <h3>1. User Accounts</h3>
        <p>Users must provide accurate and up-to-date information during registration.</p>
        <h3>2. Privacy</h3>
        <p>Your privacy is important to us. We are committed to protecting your personal information.</p>
        <h3>3. Acceptable Use</h3>
        <p>You agree not to use the platform for illegal, harmful, or disruptive purposes.
            Harassment, hate speech, or inappropriate content is strictly prohibited.</p>
        <h3>4. Match Creation and Participation</h3>
        <p>Users creating matches must ensure the information provided (e.g., location, time) is accurate.
            Users participating in matches must adhere to the agreed-upon rules and schedules.</p>
        <h3>5. Payment and Premium Services</h3>
        <p>Premium features may be offered with a subscription. Fees are non-refundable unless specified otherwise.</p>

    </div>
</div>

<!-- Modal for Privacy Policy -->
<div id="privacyModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('privacy')">&times;</span>
        <h2>Privacy Policy</h2>
        <p>At JomTeam, we respect your privacy. This policy outlines how we handle your personal data when you use our
            platform.</p>

        <h3>1. Information Collection</h3>
        <p>We collect information you provide when you register, interact with our platform, and use our services.</p>

        <h3>2. Data Usage</h3>
        <p>Your data is used to improve our services and provide a personalized experience.</p>

        <h3>3. How We Use Your Information<br></h3>
        <ul>
            <li>To provide and improve our services.</li>
            <li>To personalize your experience and match recommendations.</li>
            <li>To communicate updates, promotions, or changes to the platform.</li>
        </ul>

        <h3>4. Data Sharing</h3>
        <ul>
            <li>We do not sell your personal information.</li>
            <li>Data may be shared with third-party providers (e.g., payment processors) necessary to deliver our
                services.</li>
        </ul>

        <h3>5. Security</h3>
        <p>We use advanced encryption and security measures to protect your data. However, no system is completely
            secure.</p>

        <h3>6. Your Rights</h3>
        <ul>
            <li>You can access, modify, or delete your personal information by contacting support.</li>
            <li>You can opt out of promotional communications at any time.</li>
        </ul>

        <h3>7. Cookies</h3>
        <p>Our platform uses cookies to enhance your browsing experience. You can manage cookie preferences in your
            browser settings.</p>

        <h3>8. Changes to Privacy Policy</h3>
        <p>We may update this Privacy Policy periodically. Changes will be posted on this page with the revised date.
        </p>
    </div>
</div>


<script src="footer.js"></script>
