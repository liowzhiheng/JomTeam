
document.addEventListener('DOMContentLoaded', function () {
    const imageFolder = 'IMAGE/'; // Set the path to your folder containing images
    const images = ['login_done_1.jpg', 'login_done_2.jpg', 'login_done_3.jpg', 'login_done_4.jpg', 'login_done_6.jpg']; // List of images in the folder
    const randomImageElement = document.getElementById('randomImage');

    // Function to pick a random image
    function setRandomImage() {
        const randomIndex = Math.floor(Math.random() * images.length); // Get a random index
        const randomImage = images[randomIndex]; // Get the image name at the random index
        randomImageElement.src = imageFolder + randomImage; // Set the src of the image element
    }

    setRandomImage(); // Call the function to set a random image on page load

    // Optionally, set the image to change every few seconds:
    // setInterval(setRandomImage, 5000); // Uncomment this to change the image every 5 seconds
});

