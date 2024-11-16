<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="container">
        <div class="text_box">
            <h1 class="welcome_text">Welcome</h1>
            <p class="match_detail">
                Find Your Match<br>
                Discover teammates<br>
                Let's turn every game into a winning experience!<br>
                ⚽🏀🏈
            </p>
            <form method="post" action="check_login.php">
                <div class="key_in">
                    <input type="text" name="email" placeholder="Email" />
                </div>
                <div class="key_in">
                    <input type="password" name="password" id="password" placeholder="Password" required />
                    <button type="button" id="revealPassword">Show</button>

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
            <img src=IMAGE/badminton.png class="picture">
        </div>

    </div>

    <script>
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
    </script> 
</body>