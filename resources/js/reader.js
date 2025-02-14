document.addEventListener("DOMContentLoaded", function () {
    const readerContainer = document.querySelector(".reader-container");
    const settingsButton = document.querySelector(".bi-gear");

    const settingsPanel = document.createElement("div");
    settingsPanel.style.position = "fixed";
    settingsPanel.style.top = "50%";
    settingsPanel.style.left = "50%";
    settingsPanel.style.transform = "translate(-50%, -50%)";
    settingsPanel.style.background = "#212121";
    settingsPanel.style.padding = "20px";
    settingsPanel.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
    settingsPanel.style.display = "none";

    settingsPanel.innerHTML = `
        <label for="imageWidth">Max Image Width (px):</label>
        <input type="number" id="imageWidth" value="700" min="100">
        <button id="applySettings">Apply</button>
        <button id="resetSettings">Reset</button>
        <button id="closeSettings">Close</button>
    `;
    
    document.body.appendChild(settingsPanel);

    function applyImageWidthLimit(width) {
        const images = readerContainer.querySelectorAll("img");
        images.forEach((img) => {
            img.style.maxWidth = width + "px";
        });
    }

    settingsButton.addEventListener("click", function () {
        settingsPanel.style.display = "block";
    });

    document
        .getElementById("applySettings")
        .addEventListener("click", function () {
            const newWidth = document.getElementById("imageWidth").value;
            if (newWidth && !isNaN(newWidth) && newWidth > 0) {
                applyImageWidthLimit(newWidth);
                localStorage.setItem("readerImageWidth", newWidth);
            }
        });

    document
        .getElementById("resetSettings")
        .addEventListener("click", function () {
            applyImageWidthLimit("100%");
            localStorage.removeItem("readerImageWidth");
            document.getElementById("imageWidth").value = "700";
            window.location.reload();
        });

    document
        .getElementById("closeSettings")
        .addEventListener("click", function () {
            settingsPanel.style.display = "none";
        });

    const savedWidth = localStorage.getItem("readerImageWidth");
    if (savedWidth) {
        applyImageWidthLimit(savedWidth);
        document.getElementById("imageWidth").value = savedWidth;
    }

    const backToTopBtn = document.getElementById("backToTop");

    window.addEventListener("scroll", function () {
        if (window.scrollY > 300) {
            backToTopBtn.style.display = "block";
        } else {
            backToTopBtn.style.display = "none";
        }
    });

    backToTopBtn.addEventListener("click", function () {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
});
