document.getElementById("registrationForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target); //  Collect form data

    try {
        const response = await fetch('http://localhost/Digital_Wallet/wallet-server/api/register.php', {
            method: "POST",
            body: formData  //  Ensure FormData is sent properly
        });

        const result = await response.json();

        if (response.ok) {
            alert("Registration successful!");
            console.log("Verification Document URL:", result.verification_document_url);
            window.location.href = "/profile.html";
        } else {
            console.error("Server Error:", result);
            alert("Registration failed: " + (result.error || "Unknown error"));
        }

    } catch (error) {
        console.error("Fetch Error:", error);
        alert("Registration failed. Please try again.");
    }
});
