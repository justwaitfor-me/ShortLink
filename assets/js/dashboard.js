const protectedCheckbox = document.getElementById('protected')
const protectedCheckboxLabel = document.getElementById('protected-label')

const monitoringCheckbox = document.getElementById('monitoring')
const monitoringCheckboxLabel = document.getElementById('monitoring-label')

addEventListener('DOMContentLoaded', function () {
    protectedCheckbox.addEventListener('change', function () {
        if (this.checked) {
            showModal('Password Protection', `
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" minlength="8" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm-password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm-password" name="confirm-password" minlength="8" required>
                                    </div>
                                    <button id="save-password-btn" class="btn btn-primary mt-3">Save</button>
                                `, 'info');

            document.getElementById('save-password-btn').addEventListener('click', function (event) {
                event.preventDefault();
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirm-password').value;

                if (password === confirmPassword && password.length >= 8) {
                    hideModal();
                    const hiddenPasswordInput = document.createElement('input');
                    hiddenPasswordInput.type = 'hidden';
                    hiddenPasswordInput.name = 'password';
                    hiddenPasswordInput.value = password;
                    document.getElementById('manage-shortlink-form').appendChild(hiddenPasswordInput);

                    protectedCheckbox.checked = true;
                    protectedCheckboxLabel.innerHTML += '<i class="bi bi-asterisk" style="margin-left:7px;"></i>';

                } else {
                    showToast('Passwords do not match or are too short!', 'error');
                }
            });
        }
    })

    monitoringCheckbox.addEventListener('change', function () {
        if (this.checked) {
            showModal('Monitoring', `
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required> 
                                    </div>
                                    <button id="save-email-btn" class="btn btn-primary mt-3">Save</button>
                                `, 'info');

            document.getElementById('save-email-btn').addEventListener('click', function (event) {
                event.preventDefault();
                const email = document.getElementById('email').value;

                hideModal();

                const hiddenEmailInput = document.createElement('input');
                hiddenEmailInput.type = 'hidden';
                hiddenEmailInput.name = 'email';
                hiddenEmailInput.value = email;
                document.getElementById('manage-shortlink-form').appendChild(hiddenEmailInput);

                monitoringCheckbox.checked = true;
                monitoringCheckboxLabel.innerHTML += '<i class="bi bi-asterisk" style="margin-left:7px;"></i>';
            });
        }

    })

});

