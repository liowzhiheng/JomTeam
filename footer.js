
function openModal(type) {
    var modal = document.getElementById(type + "Modal");
    modal.style.display = "block";
}

function closeModal(type) {
    var modal = document.getElementById(type + "Modal");
    modal.style.display = "none";
}

// Close modal if the user clicks outside of it
window.onclick = function (event) {
    if (event.target.classList.contains('modal')) {
        closeModal('terms');
        closeModal('privacy');
    }
}

