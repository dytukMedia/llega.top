<?php
$_SERVER['HTTP_HOST'] === "localhost" ? require_once './llega.top/shortener_config.php' : require_once '../config/llega.top/shortener_config.php';
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($db->connect_error) die("Connection failed: " . $db->connect_error);

$oneYearAgo = date('Y-m-d', strtotime('-1 year'));

$deleteStatement = $db->prepare("DELETE FROM links WHERE lastvisit IS NULL OR lastvisit < ?");
$deleteStatement->bind_param("s", $oneYearAgo);

if ($deleteStatement->execute()) {
    $deletedRowCount = $deleteStatement->affected_rows;
    $deleteStatement->close();
    echo "Deleted $deletedRowCount old links.";
} else {
    echo "Failed to delete old links.";
}

$db->close();
?>
