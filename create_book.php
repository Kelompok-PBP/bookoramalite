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

  // Perform the INSERT query to add a new record to the 'books' table
  $insertQuery = "INSERT INTO books (isbn, author, title, price, category_id) VALUES ('$isbn', '$author', '$title', '$price', '$category_id')";

  if ($db->query($insertQuery)) {
    // Record successfully added, redirect to the read page
    header("Location: index.php");
    exit;
  } else {
    // Error occurred, display an error message
    echo "Error: " . $db->error;
  }
}

// Category
// Get the list of categories from the database
$categoryQuery = "SELECT id, title FROM category";
$categoryResult = $db->query($categoryQuery);

if (!$categoryResult) {
  die("Could not fetch categories: <br />" . $db->error);
}

// Create the category select box options
$categoryOptions = ''; // Initialize an empty string to store the category options

while ($categoryRow = $categoryResult->fetch_object()) {
  $categoryId = $categoryRow->id;
  $categoryTitle = $categoryRow->title;

  // Check if the current category matches the one in the URL
  $selected = isset($_GET['category']) && $_GET['category'] == $categoryId ? 'selected' : '';

  // Append the category option to the categoryOptions string
  $categoryOptions .= '<option value="' . $categoryId . '" ' . $selected . '>' . $categoryTitle . '</option>';
}

// Free the category result set
$categoryResult->free();



// Include the form HTML for adding a new customer record
include('./create_book_form.php');

// Close the database connection
$db->close();
