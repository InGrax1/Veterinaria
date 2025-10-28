document.addEventListener("DOMContentLoaded", function() {
    const images = document.querySelectorAll(".fade-in");

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, { threshold: 0.3 });

    images.forEach(image => {
        observer.observe(image);
    });
});
