import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('input[type="number"]').forEach((input) => {
        input.addEventListener("wheel", (e) => {
            e.preventDefault();
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const toast = document.getElementById("toast-success");

    if (toast) {
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateY(-20px)";

            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 3000); // 3 detik tampil
    }
});