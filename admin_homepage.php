<?php
session_start();
require_once('classes/database.php');
$con = new database();

// Handle author deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id'])) {
    $con->deleteAuthor($_POST['id']);

    header("Location: admin_homepage.php");
    exit;
}

// Fetch authors from the database
$authors = [];
try {
    $pdo = $con->opencon();
    $stmt = $pdo->query("SELECT author_id, author_firstname, author_lastname, author_birthdate, author_nationality FROM authors");
    $authors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $authors = [];
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"> <!-- Correct Bootstrap Icons CSS -->
  <title>Borrowers</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">Library Management System (Admin)</a>
          <a class="btn btn-outline-light ms-auto" href="add_authors.php">Add Authors</a>
          <a class="btn btn-outline-light ms-2" href="add_genres.php">Add Genres</a>
          <a class="btn btn-outline-light ms-2" href="add_books.html">Add Books</a>
          <div class="dropdown ms-2">
            <a href ="logout.php" class="btn btn-outline-light">Logout</a>
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
<div class="container my-5">
  <!-- Borrowers Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">Borrowers</h5>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
              <tr class="text-center">
                <th>Borrower ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <td>1</td>
                <td>John Doe</td>
                <td>johndoe@example.com</td>
                <td>johndoe</td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                    <i class="bi bi-x-square"></i>
                  </button>
                </td>
              </tr>
              <tr class="text-center">
                <td>2</td>
                <td>Jane Smith</td>
                <td>janesmith@example.com</td>
                <td>janesmith</td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">
                    <i class="bi bi-x-square"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Authors Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-success text-white">
          <h5 class="card-title mb-0">Authors</h5>
        </div>
        <div class="card-body">
          <table class="table table-bordered text-center">
            <thead >
              <tr>
                <th>Author ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Year</th>
                <th>Nationality</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php
            $authors = $con->getAuthors();
            if (!empty($authors)):
              foreach ($authors as $author):
            ?>
              <tr>
                <td><?= htmlspecialchars($author['author_id']) ?></td>
                <td><?= htmlspecialchars($author['author_FN']) ?></td>
                <td><?= htmlspecialchars($author['author_LN']) ?></td>
                <td><?= htmlspecialchars($author['author_birthday']) ?></td>
                <td><?= htmlspecialchars($author['author_nat']) ?></td>
                <td>
                  <div class="btn-group" role="group">
                    <form action="update_authors.php" method="post" style="display:inline;">
                      <input type="hidden" name="id" value="<?= htmlspecialchars($author['author_id']) ?>">
                      <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i>
                      </button>
                    </form>
                    <form method="POST" class="mx-1" style="display:inline;">
                      <input type="hidden" name="id" value="<?= htmlspecialchars($author['author_id']) ?>">
                      <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this author?')">
                        <i class="bi bi-x-square"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php
              endforeach;
            else:
            ?>
              <tr>
                <td colspan="6">No authors found.</td>
              </tr>
            <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Genres Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-warning text-dark">
          <h5 class="card-title mb-0">Genres</h5>
        </div>
        <div class="card-body">
          <table class="table table-bordered text-center">
            <thead>
              <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Fetch genres from the database
              $genres = $con->getGenres(); // Assumes this method exists
              if (!empty($genres)):
                foreach ($genres as $genre):
              ?>
              <tr>
                <td><?= htmlspecialchars($genre['genre_id']) ?></td>
                <td><?= htmlspecialchars($genre['genre_name']) ?></td>
                <td>
                  <a href="update_genre.php?id=<?= htmlspecialchars($genre['genre_id']) ?>" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </a>
                  <!-- You can add a delete button here if needed -->
                </td>
              </tr>
              <?php
                endforeach;
              else:
              ?>
              <tr>
                <td colspan="3">No genres found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Books Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-danger text-white">
          <h5 class="card-title mb-0">Books</h5>
        </div>
        <div class="card-body">
          <table class="table table-bordered text-center">
            <thead>
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Publication Year</th>
                <th>Quantity Available</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>The Adventures of Tom Sawyer</td>
                <td>978-0-123456-47-2</td>
                <td>1876</td>
                <td>5</td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">
                    <i class="bi bi-x-square"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Pride and Prejudice</td>
                <td>978-0-123456-48-9</td>
                <td>1813</td>
                <td>3</td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">
                    <i class="bi bi-x-square"></i>
                  </button>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Dune</td>
                <td>978-0-123456-49-6</td>
                <td>1965</td>
                <td>7</td>
                <td>
                  <button type="submit" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">
                    <i class="bi bi-x-square"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script> <!-- Add Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script> <!-- Correct Bootstrap JS -->
</body>
</html>
