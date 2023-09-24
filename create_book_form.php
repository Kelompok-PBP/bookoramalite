<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Customer</title>
  <!-- Include any necessary CSS or Bootstrap stylesheets here -->
</head>

<body>
  <h1>Create Customer</h1>
  <form method="POST" action="create_book.php">
    <label for="isbn">ISBN:</label>
    <input type="text" name="isbn" id="isbn" required>
    <br><br>

    <label for="author">Author:</label>
    <input type="text" name="author" id="author" required>
    <br><br>

    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required>
    <br><br>

    <label for="price">Price:</label>
    <input type="number" name="price" id="price" required>
    <br><br>

    <label for="category_id">Category:</label>
    <select name="category_id" class="form-control w-25">
      <option value="">Select category</option>
      <?= $categoryOptions ?>
    </select>
    <br><br>

    <button type="submit">Create</button>
  </form>
</body>

</html>