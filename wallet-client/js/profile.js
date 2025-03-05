document.addEventListener("DOMContentLoaded", async () => {
    const token = localStorage.getItem("token");
    if (!token) {
        window.location.href = "log.html"; // Redirect 
        return;
    }

    try {
        const response = await axios.get("http://localhost/Digital_Wallet/wallet-server/api/profile.php", {
            headers: { Authorization: `Bearer ${token}` }
        });

        const user = response.data;
        document.getElementById("user-name").textContent = user.name;
        document.getElementById("display-name").textContent = user.name;
        document.getElementById("display-email").textContent = user.email;
        document.getElementById("display-phone").textContent = user.phone;
        document.getElementById("display-birthdate").textContent = user.birthdate;

        document.getElementById("name").value = user.name;
        document.getElementById("phone").value = user.phone;
        document.getElementById("birthdate").value = user.birthdate;

        document.getElementById("editProfileForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const updatedUser = {
                name: document.getElementById("name").value.trim(),
                phone: document.getElementById("phone").value.trim(),
                birthdate: document.getElementById("birthdate").value
            };

            try {
                const updateResponse = await axios.post("http://localhost/Digital_Wallet/wallet-server/api/profile.php", updatedUser, {
                    headers: { "Authorization": `Bearer ${token}`, "Content-Type": "application/json" }
                });

                alert(updateResponse.data.message);
                location.reload(); // Reload profile after update
            } catch (error) {
                alert(error.response?.data?.error || "Profile update failed.");
            }
        });

    } catch (error) {
        console.error("Error fetching profile:", error);
        window.location.href = "log.html"; // Redirect if unauthorized
    }
});

// Toggle edit mode
function toggleEdit() {
    document.querySelector(".profile-view").style.display = "none";
    document.getElementById("editProfileForm").style.display = "block";
}

// Logout function
function logout() {
    localStorage.removeItem("token");
    window.location.href = "log.html";
}
