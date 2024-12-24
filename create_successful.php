<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="check_login.css">
    <link rel="stylesheet" href="animation.css">
    <style>
        .background {
            position: fixed;
            /* Stays fixed in place behind content */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            /* Pushes it behind everything else */
            pointer-events: none;
            /* Prevent interaction with shapes */
        }

        .container {
            position: relative;
            z-index: 1;
            /* Ensures content stays above the background */
            padding: 20px;
        }
    </style>

</head>

<body>
<div class="background"></div>
    <div class="container">
        <h2>You created a match successfully!</h2>
        <img id="randomImage" src="IMAGE/login_done_1.jpg" alt="Create Successful" class="login-image" />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageFolder = 'IMAGE/'; // Set the path to your folder containing images
            const images = ['login_done_1.jpg', 'login_done_2.jpg', 'login_done_3.jpg', 'login_done_4.jpg']; // List of images in the folder
            const randomImageElement = document.getElementById('randomImage');

            // Function to pick a random image
            function setRandomImage() {
                const randomIndex = Math.floor(Math.random() * images.length); // Get a random index
                const randomImage = images[randomIndex]; // Get the image name at the random index
                randomImageElement.src = imageFolder + randomImage; // Set the src of the image element
            }

            setRandomImage(); // Call the function to set a random image on page load

            // Set the redirect using JavaScript after 3 seconds
            setTimeout(function () {
                window.location.href = 'main.php'; // Redirect to the home page (main.php) after 3 seconds
            }, 3000); // 3000 milliseconds = 3 seconds
        });
    </script>
    <script src="background_effect.js" defer></script>
</body>

</html>
