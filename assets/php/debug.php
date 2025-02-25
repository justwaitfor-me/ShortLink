<?php
include '_cross_validation.php';
?>
<br>
<?php if ($debug): ?>
    <div class="alert alert-warning text-left">
        <h4>Debug Information</h4>
        <?php
        $errorLog = ini_get('error_log');
        if ($errorLog && file_exists($errorLog)) {
            $errors = file($errorLog);
            $latestErrors = array_slice($errors, -15);
            echo '<pre>' . htmlspecialchars(implode("", $latestErrors)) . '</pre>';
        } else {
            echo '<p>No error log found or error logging is disabled.</p>';
        }

        if ($details) {
            echo '<h5>Request Information</h5>';
            echo '<pre>' . htmlspecialchars(print_r($_REQUEST, true)) . '</pre>';

            echo '<h5>POST Information</h5>';
            echo '<pre>' . htmlspecialchars(print_r($_POST, true)) . '</pre>';

            echo '<h5>GET Information</h5>';
            echo '<pre>' . htmlspecialchars(print_r($_GET, true)) . '</pre>';

            echo '<h5>File Information</h5>';
            echo '<pre>' . htmlspecialchars(print_r($_FILES, true)) . '</pre>';
        }
        ?>
    </div>
<?php endif; ?>