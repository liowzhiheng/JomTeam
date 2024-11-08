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
                Discover teammates<br><br>

                Let's turn every game into a winning experience!<br>
                ⚽🏀🏈
            </p>
            <form method="post" action="check_login.php" style="margin-left: 10%">
                <div class="key_in">
                    <input type="text" name="email" placeholder="Email" style="border: none; margin-left:5%; margin-top:1.5%"/>
                </div>
                <div class="key_in">
                    <input type="password" name="password" placeholder="Password" style="border: none; margin-left:5%; margin-top:1.5%"/>
                </div>
                <div>
                    <p class="register">
                    Not registered yet? <a href="register.php" class="register">Create a new account</a>
                    </p>
                </div>
				<p><input type="submit" value="Login" class="button"/></p>
            </form>
        </div>
        <div class="picture_box">
            <img src=badminton.png class="picture">
        </div>

    </div>
</body>
