function openModal(id, name, createdAt, title, description, image) {
    document.getElementById('modalName').innerText = name;
    document.getElementById('modalDate').innerText = createdAt;
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalDescription').innerText = description;
    document.getElementById('modalImage').src = image;

    // Show the modal and overlay
    document.getElementById('infoModal').style.display = 'block';
    document.getElementById('modalOverlay').style.display = 'block';
}

function closeModal() {
    // Hide the modal and overlay
    document.getElementById('infoModal').style.display = 'none';
    document.getElementById('modalOverlay').style.display = 'none';
}
