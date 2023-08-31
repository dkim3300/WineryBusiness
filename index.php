<!-- index.php -->

<?php
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['login'] === 'loginOwner' && $username === 'owner' && $password === '1234') {
        header('Location: businesshome.php?user=owner');
        exit();
    } elseif ($_POST['login'] === 'loginCustomer' && $username === 'customer' && $password === '5678') {
        header('Location: customerhome.php?user=customer');
        exit();
    } else {
        $loginError = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body class="bg-grey container my-5">

    <div class="login-form">
        <h1 class="my-5">Wine Business Database</h1> <!-- Added title here -->
        <h2 class="mb-4">Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" name="login" value="loginOwner" class="btn btn-primary">Login as Owner</button>
            <button type="submit" name="login" value="loginCustomer" class="btn btn-secondary">Login as Customer</button>
        </form>
        <p class="text-danger"><?php echo $loginError; ?></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>