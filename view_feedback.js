function openModal(id, name, date, title, description, rating, image) {
    // Set the modal content
    document.getElementById("modalName").textContent = name;
    document.getElementById("modalDate").textContent = date;
    document.getElementById("modalTitle").textContent = title;
    document.getElementById("modalDescription").textContent = description;
    document.getElementById("modalImage").src = image;

    var ratingFace = "";
    switch (rating) {
        case "1":
            ratingFace = "😡";
            break;
        case "2":
            ratingFace = "😟";
            break;
        case "3":
            ratingFace = "😐";
            break;
        case "4":
            ratingFace = "🙂";
            break;
        case "5":
            ratingFace = "😃";
            break;
        default:
            ratingFace = "N/A";
            break;
    }
    document.getElementById("modalRating").textContent = ratingFace;

    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('infoModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
    document.getElementById('infoModal').style.display = 'none';
}