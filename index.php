<?php
// index.php
require_once __DIR__ . '/env.php';
require 'vendor/autoload.php';

session_start();

$debug = false;
$details = false;

$request = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$userAgent = filter_input(INPUT_SERVER, 'HTTP_USER_AGENT', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$remoteAddr = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


$parts = explode("/", $request);
$request = array_pop($parts);

function isRobot($userAgent)
{
    $bots = [
        'Googlebot',
        'Bingbot',
        'Slurp',
        'DuckDuckBot',
        'Baiduspider',
        'YandexBot',
        'Sogou',
        'Exabot',
        'facebot',
        'ia_archiver'
    ];

    foreach ($bots as $bot) {
        if (stripos($userAgent, $bot) !== false) {
            return true;
        }
    }

    return false;
}

include 'assets/php/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Short Link</title>
    <link rel="icon" type="image/svg+xml" href="assets/icon.svg">

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/ldrs/dist/auto/hatch.js"></script>

    <!--Google reCAPTCHA Api-->
    <script src="https://www.google.com/recaptcha/enterprise.js?render=6LcSeuEqAAAAAD9KiUCUBpLgwRsAfRpL4dKcUHil&badge=bottomleft" async defer></script>
    <script>
        const siteKey = "<?php echo getenv('GOOGLE_recaptcha_key'); ?>"; // Dein reCAPTCHA v3 Site Key
    </script>
</head>

<body class="dark-mode">
    <?php
    include 'assets/php/spinner.php';
    include 'assets/php/header.php';
    if (isset($_GET['error_code'])) {
        $errorCode = filter_input(INPUT_GET, 'error_code', FILTER_SANITIZE_NUMBER_INT);
        if ($_GET['error_message'] && $_GET['error_description']) {
            $errorMessage = filter_input(INPUT_GET, 'error_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $errorDescription = filter_input(INPUT_GET, 'error_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        } else {
            $errorMessage = 'An error occurred';
            $errorDescription = 'An error occurred while processing the request.';
        }

        include 'assets/php/error.php';
    } else if (isset($_GET['uid'])) {
        $uid64 = $_GET['uid'];
        $_SESSION['key'] = $uid64;

        header("Location: dashboard");
        exit;
    } else {
        include 'assets/php/main.php';
    }
    include 'assets/php/footer.php';
    ?>

    <!-- ReCaptcha Script -->
    <script src="assets/js/reCaptcha.js"></script>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

    <!-- Cutom JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/toast.js"></script>
    <script src="assets/js/modal.js"></script>
</body>

</html>