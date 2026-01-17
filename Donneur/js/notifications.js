document.addEventListener("DOMContentLoaded", () => {

    const notifData = document.getElementById("notif-data");
    if (!notifData) return;

    let lastNotifCount = parseInt(notifData.dataset.count);
    const sound = document.getElementById("notifSound");

    let audioUnlocked = false;

    // 🔓 Débloquer l’audio sans jouer le son
    const unlockAudio = () => {
        if (audioUnlocked) return;

        sound.muted = true;
        sound.play().then(() => {
            sound.pause();
            sound.currentTime = 0;
            sound.muted = false;
            audioUnlocked = true;
        }).catch(() => {});
    };

    document.addEventListener("click", unlockAudio, { once: true });

    // 🔔 Vérification des notifications
    setInterval(() => {
        fetch("../php/check_notifications.php")
            .then(res => res.json())
            .then(data => {

                if (audioUnlocked && data.count > lastNotifCount) {
                    sound.play();
                    lastNotifCount = data.count;
                }

            })
            .catch(err => console.error("Notif error:", err));
    }, 5000);
});
document.addEventListener("DOMContentLoaded", () => {

    const circle = document.getElementById("notif-circle");
    const number = document.getElementById("notif-number");

    setInterval(() => {
        fetch("../php/check_notifications.php")
            .then(res => res.json())
            .then(data => {

                if (data.count > 0) {
                    circle.style.display = "flex";
                    number.textContent = data.count > 99 ? "99+" : data.count;
                } else {
                    circle.style.display = "none";
                }

            })
            .catch(err => console.error(err));
    }, 4000);
});

