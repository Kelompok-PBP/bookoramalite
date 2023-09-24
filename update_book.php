<?php
// Include your login information
require_once('./lib/db_login.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validate and sanitize user inputs
  $isbn = $db->real_escape_string($_POST['isbn']);
  $author = $db->real_escape_string($_POST['author']);
  $title = $db->real_escape_string($_POST['title']);
  $price = $db->real_escape_string($_POST['price']);
  $category_id = $db->real_escape_string($_POST['category_id']);

  // Perform the UPDATE query to modify the existing record in the 'books' table
  $updateQuery = "UPDATE books SET author = '$author', title = '$title', price = '$price', category_id = '$category_id' WHERE isbn = '$isbn'";

  if ($db->query($updateQuery)) {
    // Record successfully updated, redirect to the read page
    header("Location: index.php");
    exit;
  } else {
    // Error occurred, display an error message
    echo "Error: " . $db->error;
  }
}

// Include the form HTML for updating a customer record
include('./update_book_form.php');

// Close the database connection
$db->close();
