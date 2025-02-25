<?php
// Function to generate a unique short link key
function generateUniqueKey($existingKeys, $customKey = null)
{
    if ($customKey && !in_array($customKey, $existingKeys)) {
        return $customKey;
    }
    do {
        $key = substr(md5(uniqid(rand(), true)), 0, 6);
    } while (in_array($key, $existingKeys));
    return $key;
}

// Function to load JSON data from file
function loadJsonData($filePath)
{
    if (file_exists($filePath)) {
        $jsonData = file_get_contents($filePath);
        return json_decode($jsonData, true);
    }
    return [];
}

// Function to handle rate limiting
function handleRateLimiting($ipAddress, $rateLimitWindow, $rateLimitMaxRequests)
{
    $currentTime = time();
    if (!isset($_SESSION['rate_limit'])) {
        $_SESSION['rate_limit'] = [];
    }
    if (!isset($_SESSION['rate_limit'][$ipAddress])) {
        $_SESSION['rate_limit'][$ipAddress] = [];
    }
    $_SESSION['rate_limit'][$ipAddress] = array_filter($_SESSION['rate_limit'][$ipAddress], function ($timestamp) use ($currentTime, $rateLimitWindow) {
        return ($currentTime - $timestamp) < $rateLimitWindow;
    });

    if (count($_SESSION['rate_limit'][$ipAddress]) >= $rateLimitMaxRequests) {
        $errorCode = 429;
        $errorMessage = "Too Many Requests";
        $errorDescription = "You have exceeded the maximum number of requests allowed per minute.";
        header("Location: ?error_code=$errorCode&error_message=$errorMessage&error_description=$errorDescription");
        exit;
    }

    $_SESSION['rate_limit'][$ipAddress][] = $currentTime;
}

// Function to save short link data to JSON file
function saveShortLinkData($filePath, $data)
{
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}

function sendEmail($to, $subject, $message, $headers)
{
    return mail($to, $subject, $message, $headers);
}