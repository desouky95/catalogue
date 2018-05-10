<?php
include 'header.php';
include 'controllers/UserController.php';

$email_error = false;
$sc = false;
$not_email = false;
if(UserController::auth())header("Location: products.php");
if(isset($_POST['submit'])){
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new UserController();
    $response = json_decode($user->signup($name,$email,$password));
    if($response->error == true){
        if($response->email_exist == true) $email_error = true;
        if($response->sc == true) $sc = true;
        if($response->not_email == true) $not_email = true;
    }else header("Location: products.php");

}

?>
<title>Signup</title>
<div class="login">
    <div class="login-form">
        <div class="user">
            <i class="icon-user-o"></i>
        </div>
        <h1 class="title">Sign Up</h1>
        <form method="post" action="signup.php" onsubmit="return validate()">
            <div class="input-icon">
                <i class="icon-user"></i>
                <input type="text" name="username" id="username" placeholder="Username">
            </div>
            <div class="input-icon">
                <i class="icon-mail"></i>
                <input type="email" name="email" id="email" placeholder="Email">
                <p id="e-error" style="display: none;color: white">Please Enter A Valid Email</p>
                <?php if($email_error) echo "<h3>Email already exists</h3>"?>
            </div>
            <div class="input-icon">
                <i class="icon-lock-open-alt"></i>
                <input type="password" name="password" id="password" placeholder="Password">
                <p id="p-error" style="display: none;color: white">Password min 6 characters</p>
            </div>
            <div class="input-icon">
                <i class="icon-lock-open-alt"></i>
                <input type="password" name="cpassword" id="confirm" placeholder="Confirm Password">
                <p id="c-error" style="display: none;color: white">Passwords doesn't match</p>
            </div>
            <?php if( $sc || $not_email) echo "<p  style=\"color: white\">Please enter inputs without special characters</p>";?>
            <button name="submit" type="submit">Sign Up</button>
        </form>
    </div>
    <div class="register">
        <hr>
        <p>Don't have an account? <a href="#">Register here</a></p>
    </div>
</div>

<script>
    function validate() {
        document.getElementById("e-error").style.display = "none";
        document.getElementById("p-error").style.display = "none";

        if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.getElementById("email").value)) {
            if (document.getElementById("password").value.length >= 6) {
                if (document.getElementById("confirm").value === document.getElementById("password").value) {
                    return true;
                }
                else {
                    document.getElementById("c-error").style.display = "block";
                }
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