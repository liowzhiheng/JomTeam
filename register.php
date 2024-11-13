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
            <h1 class="welcome_text">Create Account</h1>
            <form method="post" action="check_register.php">
                <div class="key_in">
                    <input type="text" name="email" placeholder="Email" />
                </div>
                <div class="key_in">
                    <input type="password" name="password" placeholder="Password" />
                </div>
                <div>
                    <p class="register">
                        Passwords must have at least 8 characters and contain at least two of the following: uppercase
                        letters, lowercase letters, numbers, and symbols.
                    </p>
                </div>
                <p><input type="submit" value="Create" class="button" /></p>
            </form>
        </div>
        <div class="picture_box">
            <img src="IMAGE/player_2.png" class="picture">
        </div>

    </div>
</body>
