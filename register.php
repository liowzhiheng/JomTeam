<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Account</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <style>
        .error-message {
            color: #EB1436;
            background-color: rgba(235, 20, 54, 0.1);
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
        }
        .validation-feedback {
            color: #EB1436;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
    </style>
</head>
<body>
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
                <input type="text" name="first_name" id="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required />
                <span id="firstNameFeedback" class="validation-feedback">First name can only contain letters</span>
            </div>

            <div class="key_in">
                <input type="text" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required />
                <span id="lastNameFeedback" class="validation-feedback">Last name can only contain letters</span>
            </div>

            <div class="key_in">
                <select name="gender" id="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
                <span id="genderFeedback" class="validation-feedback">Please select a gender</span>
            </div>

            <div class="key_in">
                    <input type="date" name="dob" id="dob" placeholder="Date of Birth"
                           value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>" required />
                    <div class="validation-feedback" id="dobFeedback"></div>
            </div>

            <div class="key_in">
                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required />
                <span id="emailFeedback" class="validation-feedback">Please enter a valid email address</span>
            </div>

            <div class="key_in">
                <input type="password" name="password" id="password" placeholder="Password" required />
                <span id="passwordFeedback" class="validation-feedback">Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol</span>
                <button type="button" id="revealPassword" onclick="togglePasswordVisibility()">Show</button>
            </div>

            <div class="key_in">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required />
                <span id="confirmPasswordFeedback" class="validation-feedback">Passwords do not match</span>
                <button type="button" id="revealConfirmPassword" onclick="toggleConfirmPasswordVisibility()">Show</button>
            </div>

            <div class="key_in">
                <select name="country_code" id="country_code" required>
                    <option value="">Select Country Code</option>
                    <option value="+60">+60 Malaysia</option>
                    <option value="+65">+65 Singapore</option>
                    <option value="+86">+86 China</option>
                    <option value="+1">+1 United States</option>
                    <!-- Add more country codes here -->
                </select>
                <input type="tel" name="phone" id="phone" placeholder="Phone Number" required />
                <span id="phoneFeedback" class="validation-feedback">Please enter a valid phone number</span>
            </div>

                <p><input type="submit" value="Create" class="button" /></p>
            </form>

        </div>

        <div class="picture_box">
            <img src="IMAGE/player_2.png" class="picture" alt="Player">
        </div>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
            if (passwordField.type === "password") {
                passwordField.type = "text";
                this.textContent = "Hide";
            } else {
                passwordField.type = "password";
                this.textContent = "Show";
            }
            }

            function toggleConfirmPasswordVisibility() {
            const confirmPasswordField = document.getElementById('confirm_password');
            if (confirmPasswordField.type === "password") {
                confirmPasswordField.type = "text";
                this.textContent = "Hide";
            } else {
                confirmPasswordField.type = "password";
                this.textContent = "Show";
            }
            }

            firstName.addEventListener('blur', function() {
                const feedback = document.getElementById('firstNameFeedback');
                if (!validateName(this.value) && this.value) {
                    feedback.textContent = 'First name can only contain letters';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            lastName.addEventListener('blur', function() {
                const feedback = document.getElementById('lastNameFeedback');
                if (!validateName(this.value) && this.value) {
                    feedback.textContent = 'Last name can only contain letters';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            email.addEventListener('blur', function() {
                const feedback = document.getElementById('emailFeedback');
                if (!validateEmail(this.value) && this.value) {
                    feedback.textContent = 'Please enter a valid email address';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            password.addEventListener('input', function() {
                const feedback = document.getElementById('passwordFeedback');
                if (!validatePassword(this.value) && this.value) {
                    feedback.textContent = 'Password must be at least 8 characters and contain at least 2 of the following: uppercase, lowercase, number, symbol';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            confirmPassword.addEventListener('input', function() {
                const feedback = document.getElementById('confirmPasswordFeedback');
                if (this.value !== password.value) {
                    feedback.textContent = 'Passwords do not match';
                    feedback.style.display = 'block';
                } else {
                    feedback.style.display = 'none';
                }
            });

            phone.addEventListener('blur', function() {
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

            form.addEventListener('submit', function(e) {
                if (!validateName(firstName.value) || !validateName(lastName.value) || !validateEmail(email.value) || !validatePassword(password.value) || !validatePhone(phone.value)) {
                    e.preventDefault();
                    alert('Please fill up all the information before submitting.');
                }
            });
        });
    </script>
</body>
</html>
