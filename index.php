<?php
include 'header.php';
include 'controllers/UserController.php';


if(UserController::auth())header("Location: products.php");
$login = true;
$sc = false;
$not_email = false;
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new UserController();
    $response = json_decode($user->login($email,$password));
        if($response->error == true) {
            if ($response->sc == true) $sc = true;
            else if ($response->not_email == true) $not_email = true;
            else $login = false;
        }else header("Location: products.php");
}
?>

<title>Login</title>
<div class="login">
    <div class="login-form">
        <div class="user">
            <i class="icon-user-o"></i>
        </div>
        <h1 class="title">Sign In</h1>
        <form action="index.php" method="post" onsubmit="return validate()">
            <div class="input-icon">
                <i class="icon-user"></i>
                <input type="text" name="email" id="email" placeholder="Email">
                <p id="e-error" style="display: none;color: white">Please Enter A Valid Email</p>
            </div>
            <div class="input-icon">
                <i class="icon-lock-open-alt"></i>
                <input type="password" name="password" id="password" placeholder="Password">
                <p id="p-error" style="display: none;color: white">Password min 6 characters</p>
            </div>
            <?php if(!$login || $sc || $not_email) echo "<p  style=\"color: white\">Wrong email or password</p>";?>
            <button name="submit" type="submit">Login</button>
           <!-- <div class="btm">
                <a href="#" class="float-right">forgot password?</a>
            </div>-->
        </form>
    </div>
    <div class="register">
        <hr>
        <p>Don't have an account? <a href="signup.php">Register here</a></p>
    </div>
</div>

<script>
    function validate() {
        document.getElementById("e-error").style.display = "none";
        document.getElementById("p-error").style.display = "none";
        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email").value))
        {
            if (document.getElementById("password").value.length >= 6) {
                return true;
            }
            else {
                document.getElementById("p-error").style.display = "block";
            }
        }
        else {
            document.getElementById("e-error").style.display = "block";
        }
        return false;
    }
</script>
</body>
</html>