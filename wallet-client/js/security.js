function sanitizeInputs(data) {
    return Object.fromEntries(
        Object.entries(data).map(([key, value]) => [key, value.replace(/<script.*?>.*?<\/script>/gi, "").trim()])
    );
}

function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute("content");
}
