<?php
if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/db/conn.php';
    $db = new connection();
    $dbConnection = $db->getConnection();

    $err = '';

    if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST["confirmPassword"])){
        $email = mysqli_real_escape_string($dbConnection, $_POST['email']);
        $password = mysqli_real_escape_string($dbConnection, $_POST['password']);
        $confirmPassword = mysqli_real_escape_string($dbConnection, $_POST['confirmPassword']);
        $firstName = mysqli_real_escape_string($dbConnection, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($dbConnection, $_POST['lastName']);

        if($password != $confirmPassword){
            $err = 'Passwords do not match';
        } else {
            $sql = "SELECT * FROM users WHERE email = '$email'";

            $result = $dbConnection->query($sql);

            # If the query returns a result
            if (mysqli_num_rows($result) > 0) {
                $err = 'Username or Email already exists';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES ('$firstName', '$lastName', '$email','$hashedPassword', 'admin')";

                if ($dbConnection->query($sql) === TRUE) {
                    $msg = 'Account created successfully';

                    # Add the user to the session

                    $_SESSION['email'] = $email;
                    $_SESSION['firstName'] = $firstName;
                    $_SESSION['lastName'] = $lastName;
                    $_SESSION['role'] = 'subscriber';
                    $_SESSION['id'] = $dbConnection->query("SELECT id FROM users WHERE email = '$email'")->fetch_assoc()['id'];

                    header('Location: /');
                } else {
                    $err = 'Error creating account';
                }
            }
        }
    } else {
        $err = 'Please fill out all fields';

    }
}
?>
<div class="admin-login-signup-form">
    <form action="" method="post">
        <h3 class="admin-form-title">Sign Up</h3>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="row">
            <div class="col">
                <input type="text" class="form-control" id="firstName" placeholder="First name" name="firstName" required>
            </div>
            <div class="col">
                <input type="text" class="form-control" id="lastName" placeholder="Last name" name="lastName" required>
            </div>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="confirm-password">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>
        </div>
        <div class="admin-submit-form-btn-container">
            <button type="submit" class="btn btn-success admin-submit-form-btn">Login</button>
        </div>
        <div class="admin-login-signup-form-footer">
            <p>Already have an account? <a href="/admin/login">Login</a></p>
        </div>
        <?php
        if(isset($err)){
            if($err != ''){
                echo "<div class='alert alert-danger' role='alert' style='text-align: center'>$err</div>";
            }
        }
        ?>
    </form>
</div>
