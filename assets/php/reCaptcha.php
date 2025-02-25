
<?php
// Your secret reCAPTCHA key (you can find the key in the Google Admin Panel)
$secretKey = getenv('GOOGLE_recaptcha_secret');

// The token sent from the frontend (reCAPTCHA)
$responseToken = $_POST['g-recaptcha-response'];

// The user's IP address (optional)
$userIP = $_SERVER['REMOTE_ADDR'];

// The request to Google to verify the token
$verificationUrl = "https://www.google.com/recaptcha/api/siteverify";

// Initialize cURL session
$ch = curl_init();

// Set the URL and other options
curl_setopt($ch, CURLOPT_URL, $verificationUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $secretKey, 'response' => $responseToken, 'remoteip' => $userIP)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set the timeout to 30 seconds
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // Set the connection timeout to 30 seconds

// Execute the request and fetch the response
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    $errorCode = 500;
    $errorMessage = "Internal Server Error";
    $errorDescription = "cURL error: " . curl_error($ch);
    header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
    exit;
}

// Close the cURL session
curl_close($ch);

// Convert Google's response to an associative array
$responseKeys = json_decode($response, true);

// Check if the validation was successful
if (intval($responseKeys["success"]) !== 1) {
    // Error handling if reCAPTCHA fails
    $errorCode = 500;
    $errorMessage = "Internal Server Error";
    $errorDescription = "reCAPTCHA validation failed.";
    header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
    exit;
} else {
    // Optional: Here you can also check the score of reCAPTCHA v3
    if (isset($responseKeys['score']) && $responseKeys['score'] < 0.5) {
        // Error handling if reCAPTCHA deems the score as unsafe
        $errorCode = 403;
        $errorMessage = "Forbidden";
        $errorDescription = "The user behavior is suspicious.";
        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
        exit;
    }
}
?>
