<?php
$remoteAddr = $_SERVER['REMOTE_ADDR'];
$jsonFile = ($remoteAddr === '127.0.0.1' || $remoteAddr === '::1') ? 'shortlink-data.json' : '/home/sites/site100035052/web/justwaitforme.de/content/data/json/shortlink-data.json';
$shortLinks = loadJsonData($jsonFile);

$ipAddress = $_SERVER['REMOTE_ADDR'];
handleRateLimiting($ipAddress, 60, 5);

$uid = bin2hex(random_bytes(16));
if (!isset($_SESSION['uid'])) {
    $_SESSION['uid'] = $uid;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'assets/php/reCaptcha.php';

    $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
    $expiryDate = filter_input(INPUT_POST, 'expiry_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $neverExpire = filter_input(INPUT_POST, 'never_expire', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $customKey = filter_input(INPUT_POST, 'custom_key', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($url && $author) {
        if ($neverExpire) {
            $expiryDate = '9999-12-31';
        }

        $newKey = generateUniqueKey(array_keys($shortLinks), $customKey);
        $shortLinks[$newKey] = [
            'uid' => $uid,
            'active' => true,
            'protected' => [
                'active' => false,
                'password' => null
            ],
            'public' => false,
            'url' => $url,
            'expires' => $expiryDate,
            'author' => $author,
            'created' => date('Y-m-d'),
            'clicks' => 0,
            'monitoring' => [
                'active' => false,
                'email' => null,
                'data' => []
            ]
        ];

        if (saveShortLinkData($jsonFile, $shortLinks)) {
            $key64 = base64_encode($newKey);
            $_SESSION['key'] = $key64;

            header("Location: dashboard");
            exit;
        } else {
            $errorCode = 500;
            $errorMessage = "Internal Server Error";
            $errorDescription = "An error occurred while saving the data.";
            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
            exit;
        }
    } else {
        $errorCode = 400;
        $errorMessage = "Invalid Input";
        $errorDescription = "Please provide all required fields.";
        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
        exit;
    }
}

?>

<!-- filepath: /assets/php/new.php -->
<main class="container mt-5">
    <h1 class="mb-4">New Short Link</h1>
    <form id="shortlink-form" method="POST" class="mb-4">
        <div class="form-group">
            <label for="url">URL</label>
            <input type="url" class="form-control" id="url" name="url" required>
        </div>
        <div class="form-group">
            <label for="expiry_date">Expiry Date</label>
            <input type="date" class="form-control" id="expiry_date" name="expiry_date">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="never_expire" name="never_expire">
            <label class="form-check-label" for="never_expire">Never Expire</label>
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label for="custom_key">Custom Key (optional)</label>
            <input type="text" class="form-control" id="custom_key" name="custom_key">
        </div>
        <br>
        <button class="btn btn-primary">Submit</button>
    </form>

    <?php include 'assets/php/public.php'; ?>
</main>