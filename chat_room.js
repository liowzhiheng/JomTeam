document.addEventListener("DOMContentLoaded", function () {
    const chatForm = document.getElementById("chatForm");
    const chatMessages = document.getElementById("chatMessages");
    const matchId = document.getElementById("match_id").value;

    // Fetch messages
    function fetchMessages() {
        fetch("fetch_messages.php?match_id=" + matchId)
            .then(response => response.text())
            .then(data => {
                chatMessages.innerHTML = data;
                chatMessages.scrollTop = chatMessages.scrollHeight; // Auto-scroll to bottom
            });
    }

    // Send message
    chatForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const chatInput = document.getElementById("chatInput").value;
        const userId = document.getElementById("user_id").value;

        fetch("send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `match_id=${matchId}&user_id=${userId}&message=${encodeURIComponent(chatInput)}`
        })
            .then(response => response.text())
            .then(() => {
                document.getElementById("chatInput").value = ""; // Clear input
                fetchMessages(); // Refresh messages
            });
    });

    // Refresh messages every 2 seconds
    setInterval(fetchMessages, 2000);
    fetchMessages(); // Initial load
});
