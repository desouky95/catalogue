<?php

require "header.php";
require "controllers/ProductController.php";
$res = ProductController::get_user_reviewed_products();
$reviewed_products = array();
$r = 0;
while($ress = mysqli_fetch_assoc($res)){
    $id = (int)$ress['product_id'];
    $reviewed_products [] = mysqli_fetch_assoc(ProductController::view_product($id));
    $r++;
}
?>
<title>Ratings</title>
<div class="profile">
    <div class="info">

        <h1><?php echo $_SESSION['username'];?></h1>
        <h3><?php echo $_SESSION['email'];?></h3>
        <ul>
            <li><a href="Profile.php">Overview</a></li>
            <li><a href="Likes.php">All Likes</a></li>
            <li class="active"><a href="Ratings.php">All Rates</a></li>
            <li><a href="EditProfile.php">Edit Profile</a></li>
        </ul>
    </div>
    <div class="ratings">
        <h1>Your Ratings</h1>
        <div class="products">
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
        </div>
    </div>
</div>

</body>
</html>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>
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