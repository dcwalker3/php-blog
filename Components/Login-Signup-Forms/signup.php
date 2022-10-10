<?php
$msg = '';
$err = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once('db/conn.php');
    $db = new connection();
    $dbConnection = $db->getConnection();

    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if($password == $confirmPassword) {
        $email = mysqli_real_escape_string($dbConnection, $email);
        $password = mysqli_real_escape_string($dbConnection, $password);
        $firstName = mysqli_real_escape_string($dbConnection, $firstName);
        $lastName = mysqli_real_escape_string($dbConnection, $lastName);

        $sql = "SELECT * FROM users WHERE email = '$email'";

        $result = $dbConnection->query($sql);

        # If the query returns a result
        if (mysqli_num_rows($result) > 0) {
            $err = 'Username or Email already exists';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (first_name, last_name, email, password, role) VALUES ('$firstName', '$lastName', '$email','$hashedPassword', 'subscriber')";

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
    } else {
        $err = 'Passwords do not match';
    }

}
?>
<form class="login-signup-form" action="" method="post" >
    <h1 class="form-title">Sign Up</h1>
    <div class="form-group">
        <label for="emailInput">Email</label>
        <input type="email" class="form-control" id="emailInput" name="email" required>
    </div>
    <div class="row">
        <div class="col">
            <input type="text" class="form-control" placeholder="First name" name="first_name">
        </div>
        <div class="col">
            <input type="text" class="form-control" placeholder="Last name" name="last_name">
        </div>
    </div>
    <div class="form-group">
        <label for="passwordInput">Password</label>
        <input type="password" class="form-control" id="passwordInput" name="password" required>
    </div>
    <div class="form-group">
        <label for="confirmPasswordInput">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPasswordInput" name="confirmPassword" required>
    </div>
    <div class="form-footer">
        <input type="submit" class="btn btn-success" value="Submit"></input>
        <p>Already have an Account yet? <a href="/login" ">Login</a></p>
        <?php
        $errMsg = "<div class='alert alert-danger' role='alert'>$err</div>";
        $userMsg = "<div class='alert alert-success' role='alert'>$msg</div>";


        if($err != ''){
            echo $errMsg;
        }
        else if($msg != ''){
            echo $userMsg;
        }
        ?>
    </div>
</form>