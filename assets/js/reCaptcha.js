// Description: Generate reCaptcha v3 token and send it to the form
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", function (event) {
            event.preventDefault(); // Verhindert sofortiges Absenden

            grecaptcha.enterprise.ready(async () => {
                const token = await grecaptcha.enterprise.execute(siteKey, { action: "submit" });

                // Füge das Token als verstecktes Eingabefeld hinzu
                let recaptchaInput = form.querySelector("input[name='g-recaptcha-response']");
                if (!recaptchaInput) {
                    recaptchaInput = document.createElement("input");
                    recaptchaInput.type = "hidden";
                    recaptchaInput.name = "g-recaptcha-response";
                    form.appendChild(recaptchaInput);
                }
                recaptchaInput.value = token;

                // Formular erneut absenden
                form.submit();
            });
        });
    });
});