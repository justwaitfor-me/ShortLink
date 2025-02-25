function copyToClipboard(text) {
    var tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    showToast('Copied to clipboard!', 'success');
}

function hashPassword(password) {
    return new Promise((resolve, reject) => {
        // Simulate hashing for demonstration purposes
        setTimeout(() => {
            const hashedPassword = btoa(password); // Simple base64 encoding as a placeholder
            resolve(hashedPassword);
        }, 1000);
    });
}