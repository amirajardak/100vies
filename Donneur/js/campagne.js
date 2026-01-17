document.addEventListener("DOMContentLoaded", function () {
    const resetBtn = document.getElementById("resetFilter");

    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            // Recharge la page sans ?search=&lieu=&date=
            window.location.href = window.location.pathname;
        });
    }
});
