document.addEventListener("DOMContentLoaded", function () {
    const statusMessage = document.getElementById("statusMessage");
    const shiftStatus = document.getElementById("shiftStatus");
    const currentDateDisplay = document.getElementById("currentDate");
    const liveTimeDisplay = document.getElementById("liveTime");
    const shiftHistory = document.getElementById('shiftHistory');
    
    let shiftState = "off";

    let shiftId = null;
    let pauseId = null;
    let snoozeId = null;
    let theDate = moment().format("YYYY-MM-DD");

    // Set Current Date
    currentDateDisplay.textContent = moment().format("dddd, MMMM D, YYYY");

    // Update live time
    function updateLiveTime() {
        liveTimeDisplay.textContent = moment().format('HH:mm:ss');
    }

    // Run immediately on page load
    updateLiveTime();

    // Update live time every second
    setInterval(updateLiveTime, 1000);

    // Fetch current active shift for state persistance and shift history
    async function fetchCurrentShift() {
        try {
            const response = await fetch('/current-shift');
            const data = await response.json();

            if (response.status !== 200) {
                shiftHistory.innerHTML = "<li>No active shift found.</li>";
                return;
            }
            
            /** Shift history */
            let historyHtml = `
                <li>✅ Clocked In: ${moment(data.shift.time_in, "HH:mm:ss").format("HH:mm:ss")}</li>
            `;

            // Array of history events
            let historyEvents = [];

            // Add pauses
            data.pauses.forEach(pause => {
                let pauseTime = moment(pause.pause_on, "HH:mm:ss").format("HH:mm:ss");
                let resumeTime = pause.pause_off ? moment(pause.pause_off, "HH:mm:ss").format("HH:mm:ss") : "⏳ Ongoing";
                historyEvents.push({ type: "pause", time: pause.pause_on, layer: `<li>☕ Break: ${pauseTime} → ${resumeTime}</li>` });
            });

            // Add snoozes
            data.snoozes.forEach(snooze => {
                let snoozeTime = moment(snooze.snooze_on, "HH:mm:ss").format("HH:mm:ss");
                let wakeupTime = snooze.snooze_off ? moment(snooze.snooze_off, "HH:mm:ss").format("HH:mm:ss") : "⏳ Ongoing";
                historyEvents.push({ type: "snooze", time: snooze.snooze_on, layer: `<li>💤 Snooze: ${snoozeTime} → ${wakeupTime}</li>` });
            });

            // Sort all events by time
            historyEvents.sort((a, b) => moment(a.time, "HH:mm:ss").valueOf() - moment(b.time, "HH:mm:ss").valueOf());

            historyHtml += historyEvents.map(event => event.layer).join("");

            if (data.shift.time_out) {
                historyHtml += `<li>🔴 Clocked Out: ${moment(data.shift.time_out, "HH:mm:ss").format("HH:mm:ss")}</li>`;
            }

            // Generate history HTML
            shiftHistory.innerHTML = historyHtml;

            //Shift state
            updateUI(data);

        } catch (error) {
            console.error("Error fetching current shift:", error);
            shiftHistory.innerHTML = "<li>⚠️ Error loading shift history.</li>";
        }
    }

    function updateUI(data) {
        if (data.shift.time_out) {
            statusMessage.innerHTML = `🔴 Clocked Out: ${moment(data.shift.time_out, "HH:mm:ss").format("HH:mm:ss")}`;
            shiftState = "off";
            updateButtons();
            updateStatus();
        } else if (data.pauses.length > 0 && !data.pauses[data.pauses.length - 1].pause_off) {
            statusMessage.innerHTML = `☕ On Break since ${moment(data.pauses[data.pauses.length - 1].pause_on, "HH:mm:ss").format("HH:mm:ss")}`;
            pauseId = data.pauses[data.pauses.length - 1].id;
            shiftState = "pause";
            updateButtons();
            updateStatus();
        } else if (data.snoozes.length > 0 && !data.snoozes[data.snoozes.length - 1].snooze_off) {
            statusMessage.innerHTML = `💤 On Snooze since ${moment(data.snoozes[data.snoozes.length - 1].snooze_on, "HH:mm:ss").format("HH:mm:ss")}`;
            snoozeId = data.snoozes[data.snoozes.length - 1].id;
            shiftState = "snooze";
            updateButtons();
            updateStatus();
        } else {
            statusMessage.innerHTML = `🟢 On Shift since ${moment(data.shift.time_in, "HH:mm:ss").format("HH:mm:ss")}`;
            shiftId = data.shift.id;
            shiftState = "on";
            updateButtons();
            updateStatus();
        }
    }

    // Fetch current shift on page load
    fetchCurrentShift();

    async function sendRequest(url, method = "POST") {
        console.log(url, method);
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json"
                }
            });

            const data = await response.json();

            if (!response.ok) {
                if(url === '/shift' && method === 'POST') {
                    alert(data.error); // User already clocked in
                    return;
                }
                else throw new Error(`HTTP Error! Status: ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error("Request failed:", error);
            alert("Something went wrong! Please try again.");
            return null; // Return null to prevent breaking code
        }
    }

    function updateButtons() {
        const timeInBtn = document.getElementById("timeInBtn");
        const timeOutBtn = document.getElementById("timeOutBtn");
        const pauseBtn = document.querySelector("#pauseBtn");
        const snoozeBtn = document.querySelector("#snoozeBtn");

        timeInBtn.disabled = (shiftState !== "off");
        timeOutBtn.disabled = (shiftState !== "on");
        pauseBtn.disabled = (shiftState !== "on" && shiftState !== "pause");
        snoozeBtn.disabled = (shiftState !== "on" && shiftState !== "snooze");

        if(shiftState !== "off") {
            timeInBtn.classList.replace('hover:bg-green-600','cursor-not-allowed');
        } else {
            timeInBtn.classList.replace('cursor-not-allowed','hover:bg-green-600');
        }

        if(shiftState !== "on") {
            timeOutBtn.classList.replace('hover:bg-red-600','cursor-not-allowed');
        } else {
            timeOutBtn.classList.replace('cursor-not-allowed','hover:bg-red-600');
        }

        if(shiftState !== "on" && shiftState !== "pause") {
            pauseBtn.classList.replace('hover:bg-yellow-500','cursor-not-allowed');
        } else {
            pauseBtn.classList.replace('cursor-not-allowed','hover:bg-yellow-500');
        }

        if(shiftState !== "on" && shiftState !== "snooze") {
            snoozeBtn.classList.replace('hover:bg-blue-500','cursor-not-allowed');
        } else {
            snoozeBtn.classList.replace('cursor-not-allowed','hover:bg-blue-500');
        }
        
        pauseBtn.innerText = shiftState !== "pause" ? "⏸️ Pause" : "▶️ Resume";
        snoozeBtn.innerText = shiftState !== "snooze" ? "😴 Snooze" : "🔔 Wake Up";

        if(shiftState === "pause") {
            pauseBtn.classList.replace('bg-yellow-400','bg-yellow-600');
            pauseBtn.classList.replace('hover:bg-yellow-500','hover:bg-yellow-700');
        } else {
            pauseBtn.classList.replace('bg-yellow-600', 'bg-yellow-400');
            pauseBtn.classList.replace('hover:bg-yellow-700', 'hover:bg-yellow-500');
        }

        if(shiftState === "snooze") {
            snoozeBtn.classList.replace('bg-blue-400','bg-blue-600');
            snoozeBtn.classList.replace('hover:bg-blue-500','hover:bg-blue-700');
        } else {
            snoozeBtn.classList.replace('bg-blue-600','bg-blue-400');
            snoozeBtn.classList.replace('hover:bg-blue-700','hover:bg-blue-500');
        }
    }

    // Initial buttons style update
    updateButtons();

    function updateStatus() {
        let statusText = "";
        if (shiftState === "on") statusText = "On Shift";
        else if (shiftState === "pause") statusText = "On Break";
        else if (shiftState === "snooze") statusText = "On Nap";
        else statusText = "Off Shift";
        shiftStatus.textContent = statusText;
    }

    // Clock In
    document.getElementById("timeInBtn").addEventListener("click", async function () {
        const data = await sendRequest('/shift');
        if(data) {
            shiftId = data.shift.id;
            theDate = data.shift.the_date;
            statusMessage.textContent = `✅ Clocked In: ${moment(theDate + ' ' + data.shift.time_in).format("HH:mm:ss")}`;
            shiftState = "on";
            updateButtons();
            updateStatus();
        }
        setTimeout(fetchCurrentShift, 3000);
    });

    // Clock Out
    document.getElementById("timeOutBtn").addEventListener("click", async function () {
        let passcode = prompt("You are about to end the shift! Enter '000' to confirm:");
        if (passcode === "000") {
            const data = await sendRequest('/shift/' + shiftId + '/update', 'PATCH');
            if(data) {
                statusMessage.textContent = `🔴 Clocked Out: ${moment(theDate + ' ' + data.shift.time_out).format("HH:mm:ss")}`;
                shiftState = "off";
                updateButtons();
                updateStatus();
            }
            fetchCurrentShift();
        } else if(passcode && passcode !== "000") {
            alert("Incorrect passcode!");
        }
    });

    // Pause/Resume
    document.querySelector("#pauseBtn").addEventListener("click", async function () {
        if(shiftState === "on") {
            const data = await sendRequest('/pause/shift/' + shiftId);
            if(data) {
                pauseId = data.pause.id;
                statusMessage.textContent = `⏸️ Paused at: ${moment(theDate + ' ' + data.pause.pause_on).format("HH:mm:ss")}`;
                shiftState = "pause";
                updateButtons();
                updateStatus();
            }
            setTimeout(fetchCurrentShift, 3000);
        } else if(shiftState === "pause") {
            const data = await sendRequest('/pause/' + pauseId + '/update', 'PATCH');
            if(data) {
                statusMessage.textContent = `▶️ Resumed at: ${moment(theDate + ' ' + data.pause.pause_off).format("HH:mm:ss")}`;
                shiftState = "on";
                updateButtons();
                updateStatus();
            }
            setTimeout(fetchCurrentShift, 3000);
        }
    });

    // Snooze/Wake Up
    document.querySelector("#snoozeBtn").addEventListener("click", async function () {
        if(shiftState === "on") {
            const data = await sendRequest('/snooze/shift/' + shiftId);
            if(data) {
                snoozeId = data.snooze.id;
                statusMessage.textContent = `😴 Snoozed at: ${moment(theDate + ' ' + data.snooze.snooze_on).format("HH:mm:ss")}`;
                shiftState = "snooze";
                updateButtons();
                updateStatus();
            }
            setTimeout(fetchCurrentShift, 3000);
        } else if(shiftState === "snooze") {
            const data = await sendRequest('/snooze/' + snoozeId + '/update', 'PATCH');
            if(data) {
                statusMessage.textContent = `🔔 Woke Up at: ${moment(theDate + ' ' + data.snooze.snooze_off).format("HH:mm:ss")}`;
                shiftState = "on";
                updateButtons();
                updateStatus();
            }
            setTimeout(fetchCurrentShift, 3000);
        }
    });
});