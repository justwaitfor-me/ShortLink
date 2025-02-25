<?php
// assets/php/handleDashboardPost.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'assets/php/reCaptcha.php';
    
    if (isset($_SESSION['key']) && isset($_POST['clearlog'])) {

        $key64 = $_SESSION['key'];
        $key = base64_decode($key64);

        $jsonFile = ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1') ? 'shortlink-data.json' : '/home/sites/site100035052/web/justwaitforme.de/content/data/json/shortlink-data.json';

        $shortLinks = json_decode(file_get_contents($jsonFile), true);

        if (isset($shortLinks[$key])) {
            $shortLinks[$key]['monitoring']['data'] = [];

            if (file_put_contents($jsonFile, json_encode($shortLinks))) {
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
            $errorCode = 404;
            $errorMessage = "Not Found";
            $errorDescription = "The short link does not exist.";
            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
            exit;
        }
    } else {
        $re_url = isset($_POST['url']) ? $_POST['url'] : $shortLink['url'];
        $re_expiryDate = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : $shortLink['expires'];
        $re_neverExpire = isset($_POST['never_expire']) ? $_POST['never_expire'] === 'on' : ($shortLink['expires'] === '9999-12-31');
        $re_active = isset($_POST['active']) ? $_POST['active'] === 'on' : false;
        $re_protected = isset($_POST['protected']) ? $_POST['protected'] === 'on' : false;
        $re_public = isset($_POST['public']) ? $_POST['public'] === 'on' : false;
        $re_monitoring = isset($_POST['monitoring']) ? $_POST['monitoring'] === 'on' : false;
        $re_password = isset($_POST['password']) ? $_POST['password'] : $shortLink['protected']['password'];
        $re_email = isset($_POST['email']) ? $_POST['email'] : $shortLink['monitoring']['email'];


        $shortLinks[$key] = [
            'uid' => $shortLink['uid'],
            'active' => (bool)$re_active,
            'protected' => [
                'active' => (bool) $re_protected,
                'password' => null
            ],
            'public' => (bool)$re_public,
            'url' => $re_url,
            'expires' => $re_neverExpire ? '9999-12-31' : $re_expiryDate,
            'author' => $shortLink['author'],
            'created' => $shortLink['created'],
            'clicks' => $shortLink['clicks'],
            'monitoring' => [
                'active' => (bool)$re_monitoring,
                'email' => null,
                'data' => $shortLink['monitoring']['data']
            ]
        ];

        if ($re_protected && $re_password) {
            $shortLinks[$key]['protected']['active'] = true;
            $shortLinks[$key]['protected']['password'] = password_hash((string) $re_password, PASSWORD_DEFAULT);
        }

        if ($re_monitoring && $re_email) {
            $shortLinks[$key]['monitoring']['active'] = true;
            $shortLinks[$key]['monitoring']['email'] = $re_email;
        }

        if (saveShortLinkData($jsonFile, $shortLinks)) {
            header("Location: dashboard");
            exit;
        } else {
            $errorCode = 500;
            $errorMessage = "Internal Server Error";
            $errorDescription = "An error occurred while saving the data.";
            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
            exit;
        }
    }
}
