<?php
include '_cross_validation.php';

$valid = false;
if ($shortLink['protected']['active'] && $shortLink['protected']['password']) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST['password'];
        $password_hash = $shortLink['protected']['password'];
        if (password_verify($password, $password_hash)) {
            $valid = true;
        } else {
            $valid = false;
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        showToast('The password you entered is incorrect.', 'error');
                    });
                </script>";
        }
    }
} else {
    $valid = true;
}
?>

<!-- filepath: /assets/php/password.php -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Enter Password</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group text-center mt-3">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>