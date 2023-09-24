<?php
// Include your login information
require_once('./lib/db_login.php');

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
  // Sanitize the 'id' parameter
  $id = $db->real_escape_string($_GET['id']);

  // Perform the DELETE query to remove the record from the 'books' table
  $deleteQuery = "DELETE FROM books WHERE isbn = '$id'";

  if ($db->query($deleteQuery)) {
    // Record successfully deleted, redirect to the read page
    header("Location: index.php");
    exit;
  } else {
    // Error occurred, display an error message
    echo "Error: " . $db->error;
  }
}

// Close the database connection
$db->close();
