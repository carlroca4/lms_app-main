<?php 
require_once 'classes/database.php';
$con = new database();
session_start();
$sweetAlertConfig = '';

// Fix: Check if 'id' is set in POST or GET before using
$genreId = null;
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $genreId = $_POST['id'];
} elseif (isset($_GET['id']) && !empty($_GET['id'])) {
    $genreId = $_GET['id'];
} else {
    header('Location: admin_homepage.php');
    exit;
}

// Fetch current genre data for the form
$genreData = null;
try {
    $pdo = $con->opencon();
    $stmt = $pdo->prepare("SELECT * FROM genres WHERE genre_id = ?");
    $stmt->execute([$genreId]);
    $genreData = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $genreData = [];
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {
    // Update genre in the database
    try {
        $pdo = $con->opencon();
        $stmt = $pdo->prepare("UPDATE genres SET genre_name = ? WHERE genre_id = ?");
        $stmt->execute([$_POST['genreName'], $genreId]);
        $sweetAlertConfig = "<script>
            Swal.fire({
              icon: 'success',
              title: 'Genre Updated',
              text: 'The genre has been updated successfully!',
              confirmButtonText: 'OK'
            }).then(() => {
              window.location.href = 'admin_homepage.php';
            });
        </script>";
    } catch (Exception $e) {
        $sweetAlertConfig = "<script>
            Swal.fire({
              icon: 'error',
              title: 'Update Failed',
              text: 'There was an error updating the genre.',
              confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <title>Authors</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="admin_homepage.php">Library Management System (Admin)</a>
      <a class="btn btn-outline-light ms-auto active" href="add_authors.php">Add Authors</a>
      <a class="btn btn-outline-light ms-2" href="add_genres.php">Add Genres</a>
      <a class="btn btn-outline-light ms-2" href="add_books.php">Add Books</a>
      <a class="btn btn-outline-light ms-2" href="logout.php">Logout</a>
      <div class="dropdown ms-2">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle"></i>
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
  <h2>Update Genre</h2>
  <form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($genreId); ?>">
    <div class="mb-3">
      <label for="genreName" class="form-label">Genre Name</label>
      <input type="text" class="form-control" id="genreName" name="genreName" value="<?php echo htmlspecialchars($genreData['genre_name'] ?? ''); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Genre</button>
    <a href="admin_homepage.php" class="btn btn-secondary">Cancel</a>
  </form>
  <?php echo $sweetAlertConfig; ?>
</div>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
