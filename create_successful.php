<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="check_login.css">
    <link rel="stylesheet" href="animation.css">
</head>

<body>
<div class="background"></div>
    <div class="container">
        <h2>You created a match successfully!</h2>
        <img id="randomImage" src="image/login_done_1.jpg" alt="Login Successful" class="login-image" />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const imageFolder = 'image/'; // Set the path to your folder containing images
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
