/* Multi-Tab Restriction */
const sessionKey = "active_tab";
const uniqueID = Date.now().toString(); // Unique session ID for this tab

// Set this tab's unique session ID in localStorage
localStorage.setItem(sessionKey, uniqueID);

console.log("Session initialized: ", uniqueID);

window.addEventListener("storage", function (event) {
    if (event.key === sessionKey) {
        if (event.newValue !== uniqueID) {
            // Another tab has taken over the session
            showTabWarning();
        }
    }
});

// Function to show a warning modal
function showTabWarning() {
    const overlay = document.createElement("div");
    overlay.id = "tab-warning-overlay";
    overlay.style.position = "fixed";
    overlay.style.top = "0";
    overlay.style.left = "0";
    overlay.style.width = "100%";
    overlay.style.height = "100%";
    overlay.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
    overlay.style.color = "white";
    overlay.style.display = "flex";
    overlay.style.justifyContent = "center";
    overlay.style.alignItems = "center";
    overlay.style.zIndex = "9999";
    overlay.innerHTML = `
        <div style="background: white; color: black; padding: 20px; border-radius: 8px; text-align: center;">
            <h2>⚠️ Multiple Active Tabs Detected</h2>
            <p>You have an active session in another tab.</p>
            <p>Please close this tab and continue in the other one.</p>
            <button id="close-tab-btn" style="margin-top: 10px; padding: 8px 16px; background: red; color: white; border: none; cursor: pointer;">Close Tab</button>
        </div>
    `;

    document.body.appendChild(overlay);

    // Add event listener to close tab button
    document.getElementById("close-tab-btn").addEventListener("click", function () {
        alert("Please close this tab manually if it does not close automatically.");
        window.close(); // Attempt to close the tab
    });
}

// Handle when the user closes the tab
window.addEventListener("beforeunload", function () {
    // Clear session only if this tab was the last active one
    if (localStorage.getItem(sessionKey) === uniqueID) {
        localStorage.removeItem(sessionKey);
    }
});
