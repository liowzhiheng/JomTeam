function toggleFilters() {
    const filterSection = document.getElementById('filterSection');
    filterSection.style.display = filterSection.style.display === 'none' ? 'block' : 'none';
}

function clearFilters() {
    const form = document.querySelector('.search-form');
    const inputs = form.querySelectorAll('input:not([type="submit"]), select');
    inputs.forEach(input => {
        input.value = '';
    });
}

function applyFilters() {
    // You can add additional logic here if needed
    document.querySelector('.search-form').submit();
}

// Optional: Add event listeners for real-time filtering
document.querySelectorAll('.filter-group select, .filter-group input').forEach(element => {
    element.addEventListener('change', () => {
        // Add your real-time filtering logic here if desired
        console.log('Filter changed:', element.name, element.value);
    });
});
