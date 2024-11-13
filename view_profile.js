// Function to enable the upload button when a file is selected
function enableSubmitButton() {
    var fileInput = document.getElementById('imageInput');
    var submitButton = document.getElementById('uploadButton');

    // Check if a file is selected
    if (fileInput.files.length > 0) {
        submitButton.disabled = false;  // Enable the button
    } else {
        submitButton.disabled = true;  // Disable the button if no file is selected
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Check if there's a message container
    const message = document.querySelector('.message');
    if (message) {
        setTimeout(function () {
            message.style.display = 'none';
        }, 2000);
    }
});
