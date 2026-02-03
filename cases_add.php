<?php

require_once 'connect.php';

if (isset($_POST['save'])) {
  $title = $_POST['title'];
  $date  = $_POST['date_filed'];
  $status = $_POST['status'];

  mysqli_query($conn,
    "INSERT INTO cases (title, date_filed, status)
     VALUES ('$title','$date','$status')"
  );

  header("Location: index.php");
}
?>

<!doctype html>
<html>
<head>
  <title>Add Case</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h4>Add Case</h4>

  <form method="post">
    <div class="mb-2">
      <label>Case Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-2">
      <label>Date Filed</label>
      <input type="date" name="date_filed" class="form-control" required>
    </div>

    <div class="mb-2">
      <label>Status</label>
      <select name="status" class="form-control">
        <option>Open</option>
        <option>Pending</option>
        <option>Closed</option>
      </select>
    </div>

    <button name="save" class="btn btn-primary">Save Case</button>
  </form>
</body>
</html>
