<?php

require "header.php";
require "controllers/ProductController.php";
$res = ProductController::get_user_liked_products();
$liked_products = array();
$c = 0;
while($ress = mysqli_fetch_assoc($res)){
    $c++;
    $liked_products [] = mysqli_fetch_assoc(ProductController::view_product($ress['product_id']));
}
?>
<title>Likes</title>
<div class="profile">
    <div class="info">

        <h1><?php echo $_SESSION['username'];?></h1>
        <h3><?php echo $_SESSION['email'];?></h3>
        <ul>
            <li><a href="Profile.php">Overview</a></li>
            <li class="active"><a href="Likes.php">All Likes</a></li>
            <li><a href="Ratings.php">All Rates</a></li>
            <li><a href="EditProfile.php">Edit Profile</a></li>
        </ul>
    </div>
    <div class="likes">
        <h1>Your Likes</h1>
        <div class="products">
            <?php
            $x = 0;
            while ($x < $c){
            $query = "select name from images where product_id = ".$liked_products[$x]['id']." and main= 1";
            $db = new Database();
            $res = $db->query($db->connect(), $query);
            $res = mysqli_fetch_assoc($res);
            $image_name = "default.jpg";
            if($res != null)
            $image_name = ($res['name'] == "")? "default.jpg":$res['name'];
            ?>
            <div class="product">
                <div class="photo">
                    <img src="./photos/<?php echo $image_name?>">
                </div>
                <p class="description"><?php echo $liked_products[$x]['description']?></p>
                <div class="like-rate">
                    <i onclick="like(this)" name="<?php echo $liked_products[$x]['id'];?>" class="icon-heart"
                       style="cursor: pointer; color: #ED4956;"></i>
                    <input id="<?php echo $liked_products[$x]['id'];?>" type="hidden" value="">
                </div>
            </div>
            <?php $x++;}?>
        </div>
    </div>
</div>

</body>
</html>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>
    function like(el)
    {
        pid = el.getAttribute("name");
        if (el.style.color == "rgb(237, 73, 86)") {
            el.style.color = "grey";
        } else {
            el.style.color = "rgb(237, 73, 86)"
        }
        $.ajax({
            url: "controllers/likeController.php",
            data: {'pid': pid},
            method: 'POST',
            success: function () {
            }
        })
    }
    </script>