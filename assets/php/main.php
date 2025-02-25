<!-- filepath: /assets/php/main.php -->
<?php
$shortLinks = [];
if (!isRobot($userAgent)) {
    // Load the JSON data from the file
    if (
        $remoteAddr === '127.0.0.1' || $remoteAddr === '::1'
    ) {
        $jsonFile = 'shortlink-data.json';
    } else {
        $jsonFile = '/home/sites/site100035052/web/justwaitforme.de/content/data/json/shortlink-data.json';
    }
    if (file_exists($jsonFile)) {
        $jsonData = file_get_contents($jsonFile);
        $shortLinks = json_decode($jsonData, true);

        // Check if the JSON data is valid
        if (json_last_error() === JSON_ERROR_NONE) {
            switch ($request) {
                case '':
                    include 'assets/php/new.php';
                    break;
                case 'dashboard':
                    include 'assets/php/dashboard.php';
                    break;
                default:
                    if (array_key_exists($request, $shortLinks)) {
                        $linkData = $shortLinks[$request];
                        $currentDate = new DateTime();
                        $expiryDate = new DateTime($linkData['expires']);
                        $shortLink = $shortLinks[$request];

                        if (
                            $shortLink['active'] && $currentDate < $expiryDate
                        ) {
                            include 'assets/php/password.php';
                        } else {
                            $errorCode = 404;
                            $errorMessage = "Page Not Found";
                            $errorDescription = "The page you are looking for might have been removed or is temporarily unavailable.";
                            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
                            exit;
                        }

                        if ($valid) {
                            $shortLink['clicks'] += +1;

                            if ($shortLink['monitoring']['active']) {
                                if ($shortLink['monitoring']['email']) {
                                    $headers = "From: Short Link < ";
                                    sendEmail(
                                        $shortLink['monitoring']['email'],
                                        'Short Link Clicked',
                                        "Your short link ($key) has been clicked by $remoteAddr.",
                                        $headers
                                    );
                                }
                                $shortLink['monitoring']['data'][] = [
                                    'ip' => $remoteAddr,
                                    'time' => date('Y-m-d H:i:s'),
                                    'user_agent' => $userAgent,
                                    'unique_id' => $uid
                                ];
                            }

                            $shortLinks[$request] = $shortLink;
                            saveShortLinkData($jsonFile, $shortLinks);

                            header("Location: " . $linkData['url']);
                            exit();
                        }
                    } else if ($shortLink['active'] && $currentDate >= $expiryDate) {
                        $errorCode = 410;
                        $errorMessage = " Link Expired";
                        $errorDescription = "The link you are trying to access has expired.";
                        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
                    } else {
                        $errorCode = 404;
                        $errorMessage = "Not Found";
                        $errorDescription = "The short link does not exist.";
                        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
                    }
                    break;
            }
        } else {
            $errorCode = 501;
            $errorMessage = "Internal Server Error";
            $errorDescription = "The server encountered an error while processing the request.";
            header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
        }
    } else {
        $errorCode = 501;
        $errorMessage = "Internal Server Error";
        $errorDescription = "The server encountered an error while processing the request.";
        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
    }
} else {
    $errorCode = 401;
    $errorMessage = "Unauthorized Access";
    $errorDescription = "You are not authorized to access this page.";
    header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
}

// Debug output for localhost
if ($remoteAddr === '127.0.0.1' || $remoteAddr === '::1') {
    echo '<pre style="padding: 10px; border-radius: 5px; width: 60%; margin: 0 auto;">';
    echo '<code>Request URI: ' . htmlspecialchars($request) . PHP_EOL . '</code>';
    echo '<code>User Agent: ' . htmlspecialchars($userAgent) . PHP_EOL . '</code>';
    echo '<code>Remote Address: ' . htmlspecialchars($remoteAddr) . PHP_EOL . '</code>';
    echo '<code>Short Links Data: ' . print_r($shortLinks, true) . PHP_EOL . '</code>';
    echo '</pre>';
}
?>