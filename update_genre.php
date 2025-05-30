<?php

require_once('classes/database.php');
$con = new database();
session_start();
$sweetAlertConfig = "";


if(empty($id = $_POST['id'])) {
    header('location:index.php');
} else {
    $id = $_POST['id'];
    $data = $con->viewGenreID($id);
}


if (isset($_POST['adds_genres'])) {
    $id = $_POST['id'];
    $genreName = $_POST['genre_name'];
    $genreID = $con->updateGenre($genreName, $id);

    if ($genreID) {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Genre updated successfully!',
            text: 'The genre has been successfully updated.',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'admin_homepage.php';
            }
        });
        </script>";
    } else {
        $_SESSION['error'] = "Sorry, there was an error updating the genre.";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
  <title>Genres</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto" href="add_authors.html">Add Authors</a>
      <a class="btn btn-outline-light ms-2 active" href="add_genres.html">Add Genres</a>
      <a class="btn btn-outline-light ms-2" href="add_books.html">Add Books</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i> <!-- Bootstrap icon -->
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
          <li>
              <a class="dropdown-item" href="profile.html">
                  <i class="bi bi-person-circle me-2"></i> See Profile Information
              </a>
            </li>
          <li>
            <button class="dropdown-item" onclick="updatePersonalInfo()">
              <i class="bi bi-pencil-square me-2"></i> Update Personal Information
            </button>
          </li>
          <li>
            <button class="dropdown-item" onclick="updatePassword()">
              <i class="bi bi-key me-2"></i> Update Password
            </button>
          </li>
          <li>
            <button class="dropdown-item text-danger" onclick="logout()">
              <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
<div class="container my-5 border border-2 rounded-3 shadow p-4 bg-light">

  <h4 class="mt-5">Update Existing Genre</h4>
  <form id="registrationForm" method="post" action="" novalidate>
    <div class="mb-3">
      <label for="genreName" class="form-label">Genre Name</label>
      <input type="text" value="<?php echo $data['genre_name']?>" name="genre_name" class="form-control" id="genreName" required>
    </div>
    <input type="hidden" name="id" value="<?php echo $data['genre_id']; ?>"> 
    <button type="submit" name="adds_genres" class="btn btn-primary">Update Genre</button>
  </form>
  <script src="./package/dist/sweetalert2.js"></script>
  <?php echo $sweetAlertConfig; ?>
</div>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>

</body>
</html>