function setLoading(isLoading) {
    document.querySelector(".loading-overlay").style.display = isLoading ? "flex" : "none";
}

function showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 5000);
}

const Utils = (() => {
    return {
        showError: (input, message) => {
            input.classList.add("error");
            input.nextElementSibling.textContent = message;
            input.nextElementSibling.style.display = "block";
        },

        clearErrors: (form) => {
            form.querySelectorAll(".error").forEach(input => {
                input.classList.remove("error");
                input.nextElementSibling.textContent = "";
                input.nextElementSibling.style.display = "none";
            });
        },

        fetchJSON: async (url, data) => {
            try {
                const response = await axios.post(url, data, {
                    headers: { "Content-Type": "application/json" }
                });
                return response.data;
            } catch (error) {
                console.error("Fetch Error:", error);
                return { error: error.response?.data?.error || "Network error. Please try again." };
            }
        }
    };
})();
