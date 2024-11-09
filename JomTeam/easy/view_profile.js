const form = document.getElementById("profileForm");
const nameDisplay = document.getElementById("nameDisplay");
const nameInput = document.getElementById("nameInput");
const editIcon = document.querySelector(".edit");
const messageDiv = document.getElementById("message");

// Toggle name display/input when edit icon is clicked
editIcon.addEventListener("click", () => {
    if (nameInput.style.display === "none") {
        nameDisplay.style.display = "none";
        nameInput.style.display = "inline-block";
        nameInput.focus();
    } else {
        nameDisplay.style.display = "inline-block";
        nameInput.style.display = "none";
    }
});

// Handle Enter key press for the name input field
nameInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
        e.preventDefault(); // Prevent form submission
        saveName();
    }
});

// Save the profile (including name) when the form is submitted
form.addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission
    saveProfile();
});

// Save name update in the database and update the name display
function saveName() {
    const newName = nameInput.value.trim();
    if (newName !== "") {
        const formData = new FormData();
        formData.append("name", newName);

        // Send AJAX request to save the name
        fetch("update_profile.php", {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(responseText => {
                if (responseText.trim() === "success") {
                    nameDisplay.innerText = newName; // Update the name display
                    toggleNameEdit(false); // Toggle input back to display
                    showMessage("Name updated successfully!", "success");
                } else {
                    showMessage("Failed to update name. Please try again.", "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                showMessage("An error occurred. Please try again later.", "error");
            });
    } else {
        showMessage("Name cannot be empty.", "error");
    }
}

// Save profile (other details) in the database
function saveProfile() {
    const formData = new FormData(form);

    fetch("update_profile.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.text())
        .then(responseText => {
            if (responseText.trim() === "success") {
                showMessage("Profile updated successfully!", "success");
            } else {
                showMessage("Failed to update profile. Please try again.", "error");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showMessage("An error occurred. Please try again later.", "error");
        });
}

// Show success or error messages
function showMessage(message, type) {
    messageDiv.innerText = message;
    messageDiv.style.display = "block";
    messageDiv.style.backgroundColor = type === "success" ? "#4CAF50" : "#f44336";

    setTimeout(() => {
        messageDiv.style.display = "none";
    }, 2000);
}

// Helper function to toggle name edit mode (display/input)
function toggleNameEdit(editMode) {
    if (editMode) {
        nameDisplay.style.display = "none";
        nameInput.style.display = "inline-block";
        nameInput.focus();
    } else {
        nameDisplay.style.display = "inline-block";
        nameInput.style.display = "none";
    }
}

function triggerFileInput() {
    document.getElementById('fileInput').click();
}

// Preview the selected image before uploading
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('profilePic').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
