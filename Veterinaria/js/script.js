document.addEventListener("DOMContentLoaded", function() {
    const body = document.querySelector("body");

    for (let i = 0; i < 10; i++) { // Crea 10 burbujas
        let bubble = document.createElement("div");
        bubble.classList.add("bubble");
        
        // Posición aleatoria
        bubble.style.left = Math.random() * window.innerWidth + "px";
        bubble.style.top = Math.random() * window.innerHeight + "px";

        // Tamaño aleatorio
        let size = Math.random() * 50 + 30;
        bubble.style.width = size + "px";
        bubble.style.height = size + "px";

        // Velocidad diferente para cada burbuja
        bubble.style.animationDuration = Math.random() * 6 + 4 + "s";

        body.appendChild(bubble);
    }
});
