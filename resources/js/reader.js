document.addEventListener("DOMContentLoaded", function () {
    initializeSettings();
    initializeProgressBar();
});

function initializeSettings() {
    const readerContainer = document.querySelector(".reader-container");
    const settingsButton = document.querySelector(".setting-btn");
    const settingsPanel = createSettingsPanel();

    document.body.appendChild(settingsPanel);

    settingsButton.addEventListener("click", showSettingsPanel);
    document.getElementById("applySettings").addEventListener("click", applySettings);
    document.getElementById("resetSettings").addEventListener("click", resetSettings);
    document.getElementById("closeSettings").addEventListener("click", hideSettingsPanel);

    loadSavedSettings();

    function createSettingsPanel() {
        const panel = document.createElement("div");
        panel.style.position = "fixed";
        panel.style.top = "50%";
        panel.style.left = "50%";
        panel.style.transform = "translate(-50%, -50%)";
        panel.style.background = "#212121";
        panel.style.padding = "20px";
        panel.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
        panel.style.display = "none";

        panel.innerHTML = `
            <label for="imageWidth">Max Image Width (px):</label>
            <input type="number" id="imageWidth" value="700" min="100">
            <button id="applySettings">Apply</button>
            <button id="resetSettings">Reset</button>
            <button id="closeSettings">Close</button>
        `;

        return panel;
    }

    function showSettingsPanel() {
        settingsPanel.style.display = "block";
    }

    function hideSettingsPanel() {
        settingsPanel.style.display = "none";
    }

    function applyImageWidthLimit(width) {
        const images = readerContainer.querySelectorAll("img");
        images.forEach((img) => {
            img.style.maxWidth = width + "px";
        });
    }

    function applySettings() {
        const newWidth = document.getElementById("imageWidth").value;
        if (newWidth && !isNaN(newWidth) && newWidth > 0) {
            applyImageWidthLimit(newWidth);
            localStorage.setItem("readerImageWidth", newWidth);
        }
    }

    function resetSettings() {
        applyImageWidthLimit("100%");
        localStorage.removeItem("readerImageWidth");
        document.getElementById("imageWidth").value = "700";
        window.location.reload();
    }

    function loadSavedSettings() {
        const savedWidth = localStorage.getItem("readerImageWidth");
        if (savedWidth) {
            applyImageWidthLimit(savedWidth);
            document.getElementById("imageWidth").value = savedWidth;
        }
    }
}

function initializeProgressBar() {
    const readerContainer = document.querySelector(".reader-container");
    const progressBar = document.getElementById("scrollProgressBar");

    function updateProgressBar() {
        const scrollTop = readerContainer.scrollTop || window.scrollY;
        const scrollHeight = readerContainer.scrollHeight - window.innerHeight;
        const progress = (scrollTop / scrollHeight) * 100;
        progressBar.style.width = progress + "%";
    }

    window.addEventListener("scroll", updateProgressBar);
}