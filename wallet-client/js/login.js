document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const form = e.target;
    const emailInput = form.querySelector("#email");
    const passwordInput = form.querySelector("#password");

    // Clear previous errors
    Utils.clearErrors(form);

    // Validate fields
    if (!emailInput.value.trim()) {
        Utils.showError(emailInput, "Email is required.");
        return;
    }
    if (!passwordInput.value.trim()) {
        Utils.showError(passwordInput, "Password is required.");
        return;
    }

    // Prepare data
    const data = {
        email: emailInput.value,
        password: passwordInput.value
    };

    // Send API request
    const result = await Utils.fetchJSON("http://localhost/Digital_Wallet/wallet-server/api/login.php", data);

    // Handle response
    if (result.error) {
        Utils.showError(emailInput, "Invalid email or password.");
        Utils.showError(passwordInput, "Invalid email or password.");
    } else {
        alert("Login successful!");
        localStorage.setItem("user", JSON.stringify(result.user));
        window.location.href = "/wallet-client/pages/profile.html";
    }
});
