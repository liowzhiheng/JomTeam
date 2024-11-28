<!DOCTYPE html>
<html lang="en">

<head>
    <title>Create Account</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="animation.css">

</head>

<body class="transition-container">
    <form action="index.php" id="backButtonForm">
        <button type="submit" style="background: none; border: none; cursor: pointer;">
            <div class="arrow"></div>
        </button>
    </form>
    <div class="container">
        <div class="text_box">



            <h1 class="welcome_text">Create Account</h1>
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="error-message">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>

            <form method="post" action="check_register.php" id="registerForm">
                <div class="key_in">
                    <input type="text" name="first_name" id="first_name" placeholder="First Name"
                        value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required />
                    <span id="firstNameFeedback" class="validation-feedback">First name can only contain letters</span>
                </div>

                <div class="key_in">
                    <input type="text" name="last_name" id="last_name" placeholder="Last Name"
                        value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required />
                    <span id="lastNameFeedback" class="validation-feedback">Last name can only contain letters</span>
                </div>

                <div class="key_in_gender">
                    <select name="gender" id="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <span id="genderFeedback" class="validation-feedback">Please select a gender</span>
                </div>

                <div class="key_in_birthday">
                    <input type="date" name="dob" id="dob" placeholder="Date of Birth"
                        value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>" required />
                    <div class="validation-feedback" id="dobFeedback"></div>
                </div>

                <div class="key_in">
                    <input type="email" name="email" id="email" placeholder="Email"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
                    <span id="emailFeedback" class="validation-feedback">Please enter a valid email address</span>
                </div>

                <div class="key_in">
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <button type="button" id="togglePassword">
                        <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility"
                            id="passwordImage" />
                    </button>
                    <span id="passwordFeedback" class="validation-feedback">Password must be at least 8 characters and
                        contain at least 2 of the following: uppercase, lowercase, number, symbol</span>
                    <div id="revealPassword" onclick="togglePasswordVisibility()">
                    </div>


                </div>



                <div class="key_in">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password"
                        required />
                    <button type="button" id="toggleConfirmPassword">
                        <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility"
                            id="confirmPasswordImage" />
                    </button>
                    <span id="confirmPasswordFeedback" class="validation-feedback">Passwords do not match</span>
                    <div id="revealConfirmPassword" onclick="toggleConfirmPasswordVisibility()">

                    </div>
                </div>




                <div class="key_in_country">
                    <select name="country_code" id="country_code" required>
                        <option value="">Select Country Code</option>
                        <option value="+60">+60 Malaysia</option>
                        <option value="+65">+65 Singapore</option>
                        <option value="+86">+86 China</option>
                        <option value="+1">+1 United States</option>
                        <!-- Add more country codes here -->
                    </select>
                </div>

                <div class="key_in">
                    <input type="tel" name="phone" id="phone" placeholder="Phone Number" required />
                    <span id="phoneFeedback" class="validation-feedback">Please enter a valid phone number</span>

                </div>
                <p><input type="submit" value="Create" class="create_button" /></p>
            </form>

        </div>

        <div class="picture_box">
            <img src="IMAGE/player_2.png" class="picture" alt="Player">
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');
            const firstName = document.getElementById('first_name');
            const lastName = document.getElementById('last_name');
            const gender = document.getElementById('gender');
            const dob = document.getElementById('dob');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const revealPasswordButton = document.getElementById('revealPassword');
            revealPasswordButton.addEventListener('click', togglePasswordVisibility);
            const revealConfirmPasswordButton = document.getElementById('revealConfirmPassword');
            revealConfirmPasswordButton.addEventListener('click', toggleConfirmPasswordVisibility);
            const confirmPassword = document.getElementById('confirm_password');
            const phone = document.getElementById('phone');
            const countryCode = document.getElementById('country_code');

            function validateName(name) {
                return /^[A-Za-z\s]+$/.test(name);  // Now allows spaces
            }

            function validateEmail(email) {
                return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            }

            function validatePassword(password) {
                const minLength = password.length >= 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSymbol = /[\W_]/.test(password);

                const conditions = [hasUpper, hasLower, hasNumber, hasSymbol];
                return minLength && conditions.filter(Boolean).length >= 2;
            }

            function validatePhone(phone) {
                const fullPhone = countryCode.value + phone; // combine country code and phone number
                return /^\+?\d{1,3}?[-\s]?\(?\d{1,3}\)?[-\s]?\d{1,4}[-\s]?\d{1,4}[-\s]?\d{1,9}$/.test(fullPhone);
            }

            function togglePasswordVisibility() {
                const passwordField = document.getElementById('password');

            }

            function toggleConfirmPasswordVisibility() {
                const confirmPasswordField = document.getElementById('confirm_password');
                const passwordImage = document.getElementById('passwordImage');


            }

            firstName.addEventListener('blur', function () {
                const feedback = document.getElementById('firstNameFeedback');
                if (!validateName(this.value) && this.value) {
                    feedback.textContent = 'First name can only contain letters';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            lastName.addEventListener('blur', function () {
                const feedback = document.getElementById('lastNameFeedback');
                if (!validateName(this.value) && this.value) {
                    feedback.textContent = 'Last name can only contain letters';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            email.addEventListener('blur', function () {
                const feedback = document.getElementById('emailFeedback');
                if (!validateEmail(this.value) && this.value) {
                    feedback.textContent = 'Please enter a valid email address';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            password.addEventListener('input', function () {
                const feedback = document.getElementById('passwordFeedback');
                if (!validatePassword(this.value) && this.value) {
                    feedback.textContent = 'Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            confirmPassword.addEventListener('input', function () {
                const feedback = document.getElementById('confirmPasswordFeedback');
                if (this.value !== password.value) {
                    feedback.textContent = 'Passwords do not match';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            phone.addEventListener('blur', function () {
                const feedback = document.getElementById('phoneFeedback');
                const phoneValue = this.value;
                const countryCode = document.getElementById('country_code').value;
                const fullPhone = countryCode + phoneValue; // Combine the country code and phone number

                // Phone validation regex (simple international phone format)
                const phoneRegex = /^\+?\d{1,3}?[-\s]?\(?\d{1,3}\)?[-\s]?\d{1,4}[-\s]?\d{1,4}[-\s]?\d{1,9}$/;

                if (!phoneRegex.test(fullPhone) && phoneValue) {
                    feedback.textContent = 'Please enter a valid phone number';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            form.addEventListener('submit', function (e) {
                if (!validateName(firstName.value) || !validateName(lastName.value) || !validateEmail(email.value) || !validatePassword(password.value) || !validatePhone(phone.value)) {
                    e.preventDefault();
                    alert('Please fill up all the information before submitting.');
                }
            });
        });
    </script>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const passwordImage = document.getElementById('passwordImage');

            // Toggle password visibility
            const isPasswordHidden = passwordField.type === 'password';
            passwordField.type = isPasswordHidden ? 'text' : 'password';

            // Change image source
            passwordImage.src = isPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
            passwordImage.alt = isPasswordHidden ? 'Hide Password' : 'Show Password'; // Update alt for accessibility
        });

    </script>

    <script>
        document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
            const confirmPasswordField = document.getElementById('confirm_password');
            const confirmPasswordImage = document.getElementById('confirmPasswordImage');

            // Toggle confirm password visibility
            const isConfirmPasswordHidden = confirmPasswordField.type === 'password';
            confirmPasswordField.type = isConfirmPasswordHidden ? 'text' : 'password';

            // Change image source
            confirmPasswordImage.src = isConfirmPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
            confirmPasswordImage.alt = isConfirmPasswordHidden ? 'Hide Password' : 'Show Password'; // Update alt for accessibility
        });

    </script>


</body>

</html>

<script src="transition.js" defer></script>
