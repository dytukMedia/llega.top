<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$_SERVER['HTTP_HOST'] === "localhost" ? $db = new mysqli('localhost', 'root', '', 'llegatop') : $db = new mysqli('localhost', 'llegatop', 'REDACTED', 'llegatop');

if ($db->connect_error) die("Connection failed: " . $db->connect_error);
if ($_SERVER["REQUEST_METHOD"] === "GET") isset($_GET["code"]) ? handleRedirectRequest($db, $_GET["code"]) : (isset($_GET["url"]) ? handleShortenRequest($db, $_GET["url"]) : include_once('./homepage.php'));
exit;

// Handle redirection request
function handleRedirectRequest($db, $code) {
    $originalUrl = null;
    $temp = null;
    $code = sanitizeInput($code);
    $result = $db->prepare("SELECT original, temp FROM links WHERE short = ?");
    $result->bind_param("s", $code);
    $result->execute();
    $result->store_result();

    if ($result->num_rows === 1) {
        $result->bind_result($originalUrl, $temp);
        $result->fetch();
        $result->close();
        
        if ($temp == 1) {
            deleteURL($db, $code);
        } else {
            updateLastVisit($db, $code);
        }
        
        redirectToUrl($originalUrl);
    }
    elseif (isset($_GET['url'])) {
        handleShortenRequest($db, $_GET["url"]);
    } else {
        http_response_code(410);
    }
}


// Handle URL shortening request
function handleShortenRequest($db, $url)
{
    $url = sanitizeInput($url);
    $shortCode = isset($_GET["code"]) && !empty($_GET["code"]) ? sanitizeInput($_GET["code"]) : generateUniqueCode(7);
    $isTemp = isset($_GET['temp']);

    // Add a default scheme if the URL lacks one
    if (!preg_match("~^(?:https?)://~i", $url)) {
        $url = "http://" . $url;
    }

    // Remove www. from the beginning of the URL
    $url = preg_replace("~^https?://(www\.)?~i", "http://", $url);

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        sendResponse("Invalid URL");
    }

    $existingUrlResult = $db->prepare("SELECT short FROM links WHERE original = ?");
    $existingUrlResult->bind_param("s", $url);
    $existingUrlResult->execute();
    $existingUrlResult->store_result();

    if ($existingUrlResult->num_rows === 1) {
        $existingUrlResult->bind_result($shortCode);
        $existingUrlResult->fetch();
        $existingUrlResult->close();
        sendResponse("https://{$_SERVER['HTTP_HOST']}/{$shortCode}");
    }

    $insertStatement = $db->prepare("INSERT INTO links (original, short, temp) VALUES (?, ?, ?)");
    $insertStatement->bind_param("sss", $url, $shortCode, $isTemp);

    if ($insertStatement->execute()) {
        $insertStatement->close();
        sendResponse("https://{$_SERVER['HTTP_HOST']}/$shortCode");
    } else {
        sendResponse("Failed to save URL; sorry.");
    }
}


// Delete URL from the database
function deleteURL($db, $code) {
    $deleteStatement = $db->prepare("DELETE FROM links WHERE short = ?");
    $deleteStatement->bind_param("s", $code);
    $deleteStatement->execute();
    $deleteStatement->close();
}

// Update the lastvisit field in the database
function updateLastVisit($db, $code)
{
    $code = sanitizeInput($code);
    $updateStatement = $db->prepare("UPDATE links SET lastvisit = NOW() WHERE short = ?");
    $updateStatement->bind_param("s", $code);
    $updateStatement->execute();
    $updateStatement->close();
}

// Redirect to a given URL
function redirectToUrl($url)
{
    header("Location: $url");
    exit();
}

// Send a response
function sendResponse($message)
{
    echo $message;
    exit();
}

// Sanitize input data
function sanitizeInput($input)
{
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Generate a unique code for short URLs
function generateUniqueCode($length)
{
    return substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', ceil($length / 62))), 0, $length);
}
