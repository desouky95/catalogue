<?php

require "header.php";
require "controllers/ProductController.php";
$res = ProductController::get_user_liked_products();
$liked_ids = array();
$c = 0;
while($ress = mysqli_fetch_assoc($res)){
    if($c > 1)break;
    $liked_ids [] = mysqli_fetch_assoc(ProductController::view_product($ress['product_id']));
    $c++;
}
$res = ProductController::get_user_reviewed_products();
$reviewed_products = array();
$r = 0;
while($ress = mysqli_fetch_assoc($res)){
    if($r > 1 )break;
    $reviewed_products [] = mysqli_fetch_assoc(ProductController::view_product($ress['product_id']));
    $r++;
}
?>
<title>Profile</title>
<div class="profile">
    <div class="info">

        <h1><?php echo $_SESSION['username'];?></h1>
        <h3><?php echo $_SESSION['email'];?></h3>
        <ul>
            <li class="active"><a href="Profile.php">Overview</a></li>
            <li><a href="Likes.php">All Likes</a></li>
            <li><a href="Ratings.php">All Rates</a></li>
            <li><a href="EditProfile.php">Edit Profile</a></li>
        </ul>
    </div>
    <?php if($c > 0){?>
    <div class="likes">
        <h1>Your Likes</h1>
        <?php
        $x = 0;
        while ($x < $c){
            $query = "select name from images where product_id = ".$liked_ids[$x]['id']." and main= 1";
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
            <p class="description"><?php echo $liked_ids[$x]['description']?></p>
            <div class="like-rate">
                <i onclick="like(this)" name="<?php echo $liked_ids[$x]['id'];?>" class="icon-heart"
                   style="cursor: pointer; color: #ED4956;"></i>
                <input id="<?php echo $liked_ids[$x]['id'];?>" type="hidden" value="">
            </div>
        </div>
        <?php $x++;}?>

        <a href="Likes.php" class="more">More Likes</a>
    </div>
    <?php }?>
    <?php if($r > 0){?>
    <div class="ratings">
        <h1>Your Ratings</h1>
        <?php
        $x = 0;
        while ($x < $r){
        $query = "select name from images where product_id = ".$reviewed_products[$x]['id']." and main= 1";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        $image_name = "default.jpg";
        if($res != null)
            $image_name = ($res['name'] == "")? "default.jpg":$res['name'];
        $review = (int)$reviewed_products[$x]['review'];
        ?>
        <div class="product">
            <div class="photo">
                <img src="./photos/<?php echo $image_name?>">
            </div>
            <p class="description"><?php echo $reviewed_products[$x]['description']?></p>
            <div class="like-rate">
                <?php for($i = 0 ;$i < 5; $i++){
                    $s = "";
                    if($review > $i)$s= "active";
                    echo '<i id="'.$reviewed_products[$x]['id'].'" onclick="rate(this)" name="'.($i+1).'" 
                    style="cursor: pointer"class="icon-star '.$s.'" curr="'.$review.'"></i>';
                }?>
            </div>
        </div>
        <?php $x++;}?>
        <a href="Ratings.php" class="more">More Ratings</a>
    </div>
    <?php }?>
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
    function rate(el){

        review = parseInt(el.getAttribute("name"));
        curr = parseInt(el.getAttribute("curr"));
        pid = el.getAttribute("id");
        var i = review;
        $(el).addClass("active");
        x=$(el);
        for(i ; i >= 0; i--){
            x = $(x).prev();
            $(x).addClass("active");
        }
        var i = review;
        y= $(el);
        for(i ; i <= 5; i++){
            y = $(y).next();
            $(y).removeClass("active");
        }
        $.ajax({
            url: "controllers/product_review.php",
            data: {'pid': pid, 'review':review},
            method: 'POST',
            success: function () {
            }
        })
    }
</script>