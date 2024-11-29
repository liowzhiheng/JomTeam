document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;

    function createShape() {
        const shape = document.createElement("div");
        const shapeType = Math.random() > 0.5 ? "circle" : "square";

        shape.classList.add("shape", shapeType);

        // Random size
        const size = Math.random() * 150 + 80; // Size between 20px and 80px
        shape.style.width = size + "px";
        shape.style.height = size + "px";

        // Random position
        shape.style.top = Math.random() * window.innerHeight + "px";
        shape.style.left = Math.random() * window.innerWidth + "px";

        // Append shape to the body
        body.appendChild(shape);

        // Remove the shape after its animation ends
        setTimeout(() => shape.remove(), 5000);
    }

    // Add a new shape every 500ms
    setInterval(createShape, 500);
});