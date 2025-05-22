<?php
session_start();
require_once 'classes/database.php';
$con = new database();
$sweetAlertConfig = "";
if (isset($_POST['Login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    

    // Try admin login first
    if (method_exists($con, 'loginAdmin')) {
        $admin = $con->loginAdmin($email, $password);
    } else {
        $admin = false;
    }
    if ($admin) {
        $_SESSION['admin_ID'] = $admin['admin_id'];
        $_SESSION['admin_FN'] = $admin['admin_FN'];
        $_SESSION['account_type'] = 1;
        $sweetAlertConfig = '<script>
        Swal.fire({
            icon: "success",
            title: "Admin Login Successful",
            text: "Welcome, ' . addslashes(htmlspecialchars($admin['admin_FN'])) . '!",
            confirmButtonText: "Continue"
        }).then(() => {
            window.location.href = "admin_homepage.php";
        });
        </script>';
    } else {
        // Try user login
        $user = $con->loginUser($email, $password);
        if ($user) {

            $_SESSION['user_FN'] = $user['user_FN'] . ' ' . $user['user_LN'];
            $_SESSION['account_type'] = 0;
            $sweetAlertConfig = '<script>
            Swal.fire({
                icon: "success",
                title: "Login Successful",
                text: "Welcome, ' . addslashes(htmlspecialchars($_SESSION['user_FN'])) . '!",
                confirmButtonText: "Continue"
            }).then(() => {
                window.location.href = "admin_homepage.php";
            });
            </script>';
        } else {
            $sweetAlertConfig = '<script>
            Swal.fire({
                icon: "error",
                title: "Login Failed",
                text: "Invalid email or password."
            });
            </script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Login</title>
  <link rel="stylesheet" href="./bootstrap-5.3.3-dist/css/bootstrap.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
 
    </div>
  </div>
</nav>
  <div class="container py-5">
    <h2 class="mb-4 text-center">User Login</h2>
    <form method="POST" action="" class="bg-white p-4 rounded shadow-sm">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
      </div>
      <button type="submit" name="Login" class="btn btn-primary w-100">Login</button>
    </form>
    <?php echo $sweetAlertConfig; ?>
    
  </div>

  <script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>