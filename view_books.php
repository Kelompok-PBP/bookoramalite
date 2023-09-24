<?php $title = "View Customer" ?>
<?php include('./layout.php') ?>
<div class="card mt-5">
    <div class="card-header d-flex justify-content-between">
        <p>Test</p>
        <div class="input-group mb-3 w-50">
  <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button">Search</button>
  </div>
</div>
    </div>
    <!-- <div class="card-body">
        <a href="add_customer.php" class="btn btn-primary mb-4">+ Add Customer Data</a> -->


        <table class="table table-striped">
            <tr>
                <th>ISBN</th>
                <th>Author</th>
                <th>Title</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
            <?php
            // Include our login information
            require_once('./lib/db_login.php');

            // Execute the query
            $query = "SELECT isbn AS ID, author AS Author, title AS Title, price as Price FROM books ORDER BY isbn";
            $result = $db->query($query);

            if (!$result) {
                die("Could not query the database: <br />" . $db->error . "<br>Query: " . $query);
            }

            // Fetch and display the results
            $i = 1;
            while ($row = $result->fetch_object()) {
                echo '<tr>';
                echo '<td>' . $row->ID . '</td>';
                echo '<td>' . $row->Author . '</td>';
                echo '<td>' . $row->Title . '</td>';
                echo '<td>' . $row->Price . '</td>';
                echo '<td><a class="btn btn-warning btn-sm" href="edit_customer.php?id=' . $row->ID . '">Edit</a>&nbsp;<a class="btn btn-danger btn-sm" href="confirm_delete_customer.php?id=' . $row->ID . '">Delete</a></td>';
                echo '</tr>';
                $i++;
            }
            echo '</table>';
            echo '<br />';
            echo 'Total Rows = ' . $result->num_rows;

            $result->free();
            $db->close();
            ?>
    </div>
</div>

