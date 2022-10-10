<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST['email']) && isset($_POST['password'])){
            $email = $_POST['email'];
            $password = $_POST['password'];

            require_once $_SERVER['DOCUMENT_ROOT'] . '/db/conn.php';
            $db = new connection();
            $conn = $db->getConnection();
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = $conn->query($sql);
            $user = $result->fetch_assoc();

            if($user){
                if(password_verify($password, $user['password'])){
                    $_SESSION['user'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    header("Location: /admin/dashboard");
                } else {
                    echo "Incorrect email or password";
                }
            } else {
                echo "Incorrect email or password";
            }
        }
    }
?>
<div class="admin-login-signup-form">
    <h3 class="admin-form-title">Login</h3>
    <form action="" method="post">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="admin-submit-form-btn-container">
            <button type="submit" class="btn btn-success admin-submit-form-btn">Login</button>
        </div>
        <div class="admin-login-signup-form-footer">
            <p>Don't have an account? <a href="/admin/signup">Sign up</a></p>
        </div>
    </form>

</div>