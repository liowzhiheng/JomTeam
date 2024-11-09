<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
</head>

<body>
        <!-- background-->
        <div
            style="width: 1920px; height: 1080px; left: 0px; top: 0px; position: absolute; background: linear-gradient(63deg, rgba(82, 82, 212, 0.86) 28%, #B7DFFD 100%)">
        </div>
        <!--end background-->
    <div style="width:1920p; height: 1080px; position: reSlative; background: white">
<!-- background-->
<div
            style="width: 100%; height: 100%; left: 0px; top: 0px; position: absolute; background: linear-gradient(63deg, rgba(82, 82, 212, 0.86) 28%, #B7DFFD 100%)">
        </div>
        <!--end background-->

        <div class="main-container"></div>

        <div class="register-prompt">
            <span class="text">Not registered yet?</span>
            <span class="text highlight">Create a new account</span>
        </div>

        <div class="match-details">
            Find Your Match <br>
            Discover teammates <br><br>
            Let's turn every game into a winning experience!<br>⚽🏀🏈
        </div>

        <div class="welcome-text">Welcome</div>

        <img class="placeholder-image" src="https://via.placeholder.com/587x551" alt="Placeholder Image">


        <!-- start fill-->
        <form method="post" action="check_login.php">
            <div class="form-container">
                <!-- email-->
                <div>
                    <input type="text" name="username" placeholder="Email" class="input-field email-field" />
                </div>

                <!-- password-->
                <div>
                    <input type="password" name="password" placeholder="Password" class="input-field password-field" />
                </div>
            </div>

            <!-- end fill-->


            <!-- start login-->
            <div class="login-button-wrapper">
                <div class="login-button-container">
                    <p>
                        <input type="submit" value="Login" class="login-button" />
                    </p>
                </div>
            </div>
            <!-- end login-->

        </form>

        <!-- zuishangmian
        
            <div
                style="width: 1920px; height: 70px; left: 0px; top: 0px; position: absolute; background: white; box-shadow: 0px 5px 0px rgba(0, 0, 0, 0.10)">
            </div>
            
            <div style="width: 624px; height: 35px; left: 70px; top: 18px; position: absolute">
                <div
                    style="left: 547px; top: 4px; position: absolute; color: black; font-size: 20px; font-family: PT Sans; font-weight: 400; word-wrap: break-word">
                    Premium</div>
                <div
                    style="left: 53px; top: 4px; position: absolute; color: #FF0000; font-size: 20px; font-family: PT Sans; font-weight: 400; word-wrap: break-word">
                    Home</div>
                <div
                    style="left: 149px; top: 4px; position: absolute; color: black; font-size: 20px; font-family: PT Sans; font-weight: 400; word-wrap: break-word">
                    Find Match</div>
                <div
                    style="left: 447px; top: 4px; position: absolute; color: black; font-size: 20px; font-family: PT Sans; font-weight: 400; word-wrap: break-word">
                    Profile</div>
                <div
                    style="left: 289px; top: 4px; position: absolute; color: black; font-size: 20px; font-family: PT Sans; font-weight: 400; word-wrap: break-word">
                    Create Match</div>
                <div
                    style="left: 0px; top: 0px; position: absolute; color: #EB1436; font-size: 30px; font-family: Raleway; font-style: italic; font-weight: 700; word-wrap: break-word">
                    S</div>
            </div>
            <div style="width: 118.09px; height: 35px; left: 1732px; top: 18px; position: absolute">
                <div
                    style="width: 69px; height: 25px; left: 0px; top: 6px; position: absolute; color: black; font-size: 20px; font-family: PT Sans; font-weight: 400; word-wrap: break-word">
                    Log Out </div>
                <img style="width: 35px; height: 35px; left: 83.09px; top: 0px; position: absolute"
                    src="https://via.placeholder.com/35x35" />
            </div>
            <div style="width: 35px; height: 38.89px; left: 1677px; top: 18px; position: absolute; background: #2A343D">
            </div>
        
         end zuishangmian-->

        <!-- Navbar -->
        <div class="navbar">
            <div class="navbar-menu">
                <span class="navbar-item logo">S</span>
                <span class="navbar-item" style="color: #FF0000;">Home</span>
                <span class="navbar-item"> Find Match</span>
                <span class="navbar-item">Create Match</span>
                <span class="navbar-item">Profile</span>
                <span class="navbar-item">Premium</span>
            </div>
            <div class="navbar-user navbar-item logout">
                <span>Log Out</span>
                <img src="https://via.placeholder.com/35x35" alt="User">
            </div>
        </div>

    </div>
</body>

</html>