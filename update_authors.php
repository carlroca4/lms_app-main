<?php 
require_once 'classes/database.php';
$con = new database();
session_start();
$sweetAlertConfig = '';
if (empty($id = $_POST['id']) && empty($_GET['id'])) {
    header('Location: admin_homepage.php');
    exit;
}


$authorId = isset($_POST['id']) ? $_POST['id'] : $_GET['id'];


if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['authorFirstName'], $_POST['authorLastName'], $_POST['authorBirthYear'], $_POST['authorNationality'])
) {
    $con->updateAuthor(
        $authorId,
        $_POST['authorFirstName'],
        $_POST['authorLastName'],
        $_POST['authorBirthYear'],
        $_POST['authorNationality']
    );
 
    $sweetAlertConfig = "<script>
        Swal.fire({
          icon: 'success',
          title: 'Author Updated',
          text: 'The author has been updated successfully!',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'admin_homepage.php';
        });
    </script>";
}


$data = $con->viewAuthorsID($authorId);


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

  <h4 class="mt-5">Update Existing Author</h4>
  <form method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($authorId); ?>">
    <div class="mb-3">
      <label for="authorFirstName" class="form-label">First Name</label>
      <input type="text" value="<?php echo isset($data['author_FN']) ? htmlspecialchars($data['author_FN']) : '' ?>" class="form-control" name="authorFirstName" id="authorFirstName" required>
    </div>
    <div class="mb-3">
      <label for="authorLastName" class="form-label">Last Name</label>
      <input type="text"  value="<?php echo isset($data['author_LN']) ? htmlspecialchars($data['author_LN']) : '' ?>" class="form-control" name="authorLastName" id="authorLastName" required>
    </div>
    <div class="mb-3">
      <label for="authorBirthYear" class="form-label">Birth Date</label>
      <input type="date"  value="<?php echo isset($data['author_birthday']) ? date('Y-m-d', strtotime($data['author_birthday'])) : ''; ?>" class="form-control" name="authorBirthYear" id="authorBirthYear" max="<?= date('Y-m-d') ?>" required>
    </div>
    <div class="mb-3">
      <label for="authorNationality" class="form-label">Nationality</label>
      <select class="form-select" name="authorNationality" id="authorNationality" required>
        <option value="" disabled>Select Nationality</option>
        <?php
        $nationalities = [
          "Filipino", "American", "British", "Canadian", "Chinese", "French", "German",
          "Indian", "Japanese", "Mexican", "Russian", "South African", "Spanish", "Other"
        ];
        $selectedNat = isset($data['author_nat']) ? $data['author_nat'] : '';
        foreach ($nationalities as $nat) {
          $selected = ($selectedNat === $nat) ? 'selected' : '';
          echo "<option value=\"$nat\" $selected>$nat</option>";
        }
        ?>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Author</button>
    <?php echo $sweetAlertConfig; ?>
  </form>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> <!-- Add Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script> <!-- Correct Bootstrap
</div>
<?php