<?php
include 'header.php';
include 'controllers/UserController.php';

$email_error = false;
if(isset($_POST['submit'])){
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $response = json_decode(UserController::update_user($name,$email,$password));
    if($response->error == true){
        if($response->email_exist == true) $email_error = true;
    }else header("Location: products.php");
}

?>
<title>Edit Profile</title>
<div class="profile">
    <div class="info">
        <h1><?php echo $_SESSION['username'];?></h1>
        <h3><?php echo $_SESSION['email'];?></h3>
        <ul>
            <li><a href="Profile.php">Overview</a></li>
            <li><a href="Likes.php">All Likes</a></li>
            <li><a href="Ratings.php">All Rates</a></li>
            <li class="active"><a href="EditProfile.php">Edit Profile</a></li>
        </ul>
    </div>
    <div class="edit-form">
        <form action="EditProfile.php" method="post" onsubmit="return validate()">
            <label>Username</label>
            <input type="text" name="username" id="username" placeholder="Username" value="<?php echo $_SESSION['username'];?>">
            <p id="i-error" style="display: none;">Username shouldn't include special characters</p>
            <label>Email</label>
            <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $_SESSION['email'];?>">
            <p id="e-error" style="display: <?php if($email_error)echo "block";else echo "none"?>"><?php if($email_error) echo "Email is taken ";else echo "Please Enter A Valid Email"?></p>
            <label>Password</label>
            <input type="password" name="password" id="password" placeholder="Password">
            <p id="p-error" style="display: none;">Password min 6 characters</p>
            <label>Confirm Password</label>
            <input type="password" name="password" id="confirm" placeholder="Confirm Password">
            <p id="c-error" style="display: none;">Passwords doesn't match</p>
            <button type="submit" value="submit" name="submit">Edit</button>
        </form>
    </div>
</div>
<script>
    function validate() {
        document.getElementById("e-error").style.display = "none";
        document.getElementById("p-error").style.display = "none";
        var username = document.getElementById("username").value;
        var regex = /[^A-Za-z0-9\-]/;
        if(regex.test(username)){
            document.getElementById("i-error").style.display = "block";
            return false;
        }
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