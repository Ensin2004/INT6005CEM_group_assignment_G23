// Timeout minutes
const timeoutMinutes = 5;

// Timeout seconds
const timeoutSeconds = timeoutMinutes * 60;

// Idle time counter in seconds
let idleTime = 0;

// Session flag
let sessionExpired = false;

// Reset idle time counter
function resetIdleTime() {
    idleTime = 0;
}

// Increment idle time every 10 seconds
const idleInterval = setInterval(() => {
    if (sessionExpired) return;

    idleTime += 10;

    // Log out if idle time = timeout time
    if (idleTime >= timeoutSeconds) {
        sessionExpired = true;
        window.location.href = "includes/logoutAccount.php?timeout=1";
    }
}, 10000);

// Detect user activity and reset idle time counter
document.addEventListener("mousemove", resetIdleTime);
document.addEventListener("click", resetIdleTime);
document.addEventListener("scroll", resetIdleTime);
document.addEventListener("keydown", resetIdleTime);