document.addEventListener("DOMContentLoaded", function () {
    const chatForm = document.getElementById("chatForm");
    const chatMessages = document.getElementById("chatMessages");
    const matchId = document.getElementById("match_id").value;

    let userScrolling = false; // Tracks if the user is scrolling up
    let previousScrollTop = 0; // Keeps track of previous scroll position

    // Fetch messages
    function fetchMessages() {
        const currentScrollTop = chatMessages.scrollTop;
        const isAtBottom = currentScrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight;

        fetch("fetch_messages.php?match_id=" + matchId)
            .then(response => response.text())
            .then(data => {
                const currentContent = chatMessages.innerHTML;

                if (data !== currentContent) {
                    const previousHeight = chatMessages.scrollHeight;

                    chatMessages.innerHTML = data;

                    if (!userScrolling) {
                        // Auto-scroll to the bottom if the user is at the bottom
                        if (isAtBottom) {
                            chatMessages.scrollTop = chatMessages.scrollHeight;
                        }
                    } else {
                        // Preserve scroll position when user is viewing older messages
                        const newHeight = chatMessages.scrollHeight;
                        chatMessages.scrollTop = currentScrollTop + (newHeight - previousHeight);
                    }
                }
            });
    }

    // Handle new message submission
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
                userScrolling = false; // Reset scrolling state
                fetchMessages(); // Refresh messages
            });
    });

    // Detect user scroll activity
    chatMessages.addEventListener("scroll", function () {
        // Check if user has scrolled up
        if (chatMessages.scrollTop < previousScrollTop) {
            userScrolling = true;
        } else if (chatMessages.scrollTop + chatMessages.clientHeight >= chatMessages.scrollHeight) {
            userScrolling = false; // User is back at the bottom
        }

        previousScrollTop = chatMessages.scrollTop; // Update previous scroll position
    });

    // Refresh messages every 2 seconds
    setInterval(fetchMessages, 2000);
    fetchMessages(); // Initial load
});
