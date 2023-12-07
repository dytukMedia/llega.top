<?php
$_SERVER['HTTP_HOST'] === "localhost" ? $db = new mysqli('localhost', 'root', '', 'llegatop') : $db = new mysqli('localhost', 'llegatop', 'REDACTED', 'llegatop');
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
