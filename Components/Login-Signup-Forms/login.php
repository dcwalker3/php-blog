<?php
    $msg = '';
    $err = '';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        require_once('db/conn.php');
        $db = new connection();
        $dbConnection = $db->getConnection();

        $username = $_POST['emailUsername'];
        $password = $_POST['password'];

        $username = mysqli_real_escape_string($dbConnection, $username);
        $password = mysqli_real_escape_string($dbConnection, $password);

        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";

        $result = $dbConnection->query($sql);

        # If the query returns a result
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            $hashedPassword = $row['password'];

            # If the password is correct
            if(password_verify($password, $hashedPassword)){

                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['id'] = $row['id'];

                // Clean the output buffer to prevent any errors from being displayed
                // when the user is redirected.
                ob_clean();
                header('Location: /');
            }
            else{
                ;$err = 'Incorrect username/email or password';
            }
        }
        else{
            ;$err = 'Incorrect username/email or password';
        }

    }
?>
<form class="login-signup-form" action="" method="post" >
    <h1 class="form-title">Login</h1>
    <div class="form-group">
        <label for="emailUserNameInput">Email</label>
        <input type="email" class="form-control" id="emailUserNameInput" name="emailUsername" required>
    </div>
    <div class="form-group">
        <label for="passwordInput">Password</label>
        <input type="password" class="form-control" id="passwordInput" name="password" required>
    </div>
    <div class="form-footer">
        <input type="submit" class="btn btn-success" value="Submit"></input>
        <p>Don't have an Account yet? <a href="/signup" ">Sign Up</a></p>
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
