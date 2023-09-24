<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Book</title>
  <!-- Include any necessary CSS or Bootstrap stylesheets here -->
</head>

<body>
  <h1>Update Book</h1>
  <?php
  // Include your login information
  require_once('./lib/db_login.php');

  // Check if the 'id' parameter is set in the URL
  if (isset($_GET['id'])) {
    // Sanitize the 'id' parameter
    $isbn = $db->real_escape_string($_GET['id']);

    // Retrieve the existing book record from the database
    $selectQuery = "SELECT author, title, price, category_id FROM books WHERE isbn = '$isbn'";
    $result = $db->query($selectQuery);

    if ($result && $result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $author = $row['author'];
      $title = $row['title'];
      $price = $row['price'];
      $category_id = $row['category_id'];
    } else {
      echo "Book not found.";
      exit;
    }
  } else {
    echo "Invalid request.";
    exit;
  }

  // Fetch categories from the database
  $categoryQuery = "SELECT id, title FROM category";
  $categoryResult = $db->query($categoryQuery);

  if (!$categoryResult) {
    echo "Error fetching categories: " . $db->error;
    exit;
  }
  ?>
  <form method="POST" action="update_book.php">
    <input type="hidden" name="isbn" value="<?= $isbn ?>">

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" value="<?= $author ?>" required>
    <br><br>

    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="<?= $title ?>" required>
    <br><br>

    <label for="price">Price:</label>
    <input type="number" name="price" id="price" value="<?= $price ?>" required>
    <br><br>

    <label for="category_id">Category:</label>
    <select name="category_id" id="category_id" required>
      <?php
      // Dynamically generate category options based on database data
      while ($categoryRow = $categoryResult->fetch_assoc()) {
        $categoryId = $categoryRow['id'];
        $categoryTitle = $categoryRow['title'];
        $selected = $categoryId == $category_id ? 'selected' : '';

        echo "<option value='$categoryId' $selected>$categoryTitle</option>";
      }
      ?>
    </select>
    <br><br>

    <button type="submit">Update</button>
  </form>

</body>

</html>