document.querySelectorAll(".transaction-form").forEach((form) => {
    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const sanitizedData = sanitizeInputs(Object.fromEntries(formData.entries()));

        try {
            setLoading(true);
            const response = await axios.post("/api/transaction", sanitizedData, {
                headers: { "X-CSRF-Token": getCSRFToken() },
            });

            if (response.data.success) {
                showNotification("Transaction successful!", "success");
                form.reset();
                updateBalance(response.data.newBalance);
            }
        } catch (error) {
            showNotification("Transaction failed. Try again.", "error");
        } finally {
            setLoading(false);
        }
    });
});
