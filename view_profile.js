// Preview image on file selection
function previewImage() {
    const file = document.getElementById('imageInput').files[0];
    const reader = new FileReader();

    reader.onloadend = function () {
        // Display the preview image
        document.getElementById('imagePreview').src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const message = document.getElementById('message');
    if (message) {
        setTimeout(() => {
            message.style.display = 'none';
        }, 2000);
    }
});