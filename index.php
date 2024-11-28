<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4fSU4bMcfB35jRg+iO1fF7zqJgpEfae7xIRyYObEXKstbELqAd9Q3VM4RCFfRJjdmhLg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="animation.css">
</head>

<body class="transition-container">
    <div class="container">
        <div class="text_box">
            <h1 class="welcome_text">Welcome</h1>
            <p class="match_detail">
                Find Your Match<br>
                Discover teammates<br>
                Let's turn every game into a winning experience!<br>
                ⚽🏀🏈
            </p>
            <!-- Add error message container and display PHP session errors if they exist -->
            <div id="errorMessage" class="error-message <?php if (isset($_GET['error']))
                echo 'show'; ?>">
                <?php
                if (isset($_GET['error'])) {
                    switch ($_GET['error']) {
                        case 'email':
                            echo 'Email not found. Please check your email or register a new account.';
                            break;
                        case 'password':
                            echo 'Incorrect password. Please try again.';
                            break;
                        default:
                            echo 'An error occurred. Please try again.';
                    }
                }
                ?>
            </div>

            <form method="post" action="check_login.php"> <!-- Changed to check_login.php -->
                
                <div class="key_in">
                    <input type="text" name="email" placeholder="Email" required />
                </div>

                <div class="key_in ">
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <button type="button" id="togglePassword">
                        <img src="IMAGE/close_eye.png" class="picture_password" alt="Toggle Visibility"
                            id="passwordImage" />
                    </button>
                </div>




                <div>
                    <p class="register">
                        Not registered yet? <a href="register.php" class="register1">Create a new account</a>
                    </p>
                </div>
                <p><input type="submit" value="Login" class="button" /></p>
            </form>
        </div>
        <div class="picture_box">
            <img src=IMAGE/badminton.png class="picture" alt="Badminton">
        </div>
    </div>

    <script>
        // Show password toggle functionality
        const revealPasswordButton = document.getElementById('revealPassword');

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const button = document.getElementById('revealPassword');

            if (passwordField.type === "password") {
                passwordField.type = "text";
                button.textContent = "Hide";
            } else {
                passwordField.type = "password";
                button.textContent = "Show";
            }
        }

        revealPasswordButton.addEventListener('click', togglePasswordVisibility);

        // Show error message immediately if it exists in URL parameters
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const errorMsg = document.getElementById('errorMessage');

            if (error) {
                let message = '';
                switch (error) {
                    case 'email':
                        message = 'Email not found. Please check your email or register a new account.';
                        break;
                    case 'password':
                        message = 'Incorrect password. Please try again.';
                        break;
                    default:
                        message = 'An error occurred. Please try again.';
                }
                errorMsg.textContent = message;
                errorMsg.classList.add('show');
            }
        });
    </script>


</body>

</html>


<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const passwordImage = document.getElementById('passwordImage');

        // Toggle password visibility
        const isPasswordHidden = passwordField.type === 'password';
        passwordField.type = isPasswordHidden ? 'text' : 'password';

        // Change image source
        passwordImage.src = isPasswordHidden ? 'IMAGE/open_eye.png' : 'IMAGE/close_eye.png';
    });
</script>

<script src="transition.js" defer></script>