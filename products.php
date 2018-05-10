<?php
include 'header.php';
include "controllers/UserController.php";
include "controllers/ProductController.php";

if(!UserController::auth())header("Location: index.php");

$products = ProductController::get_products();
?>

<title>Products</title>

<div class="product-view">
    <?php while($product = mysqli_fetch_assoc($products)){
        $query = "select name from images where product_id = ".$product['id']." and main= 1";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        $image_name = "default.jpg";
        if($res != null)
            $image_name = ($res['name'] == "")? "default.jpg":$res['name'];
        ?>
    <div class="card">
        <div class="header">
            <a href="single-page.php?pid=<?php echo $product['id']?>"><img src="./photos/<?php echo $image_name?>"></a>
        </div>
        <div class="body">
            <div class="title">
                <?php echo $product['name'];?>
            </div>
            <div class="price">
                <?php echo "$".$product['price'];?>
            </div>
            <div class="description">
                <?php echo $product['description'];?>
            </div>
        </div>
        <?php
        $pid = $product['id'];
        $uid = $_SESSION['user_id'];
        $query = "select * from user_likes where product_id = $pid and user_id = $uid";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        $like = false;
        if($res != null)
            $like = true;
        $query = "select review from products where id = $pid";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        $review = (int)$res['review'];
        ?>
        <div class="footer">
            <div class="rate <?php echo $pid?>">
                <?php for($i = 0 ;$i < 5; $i++){
                    $s = "";
                    if($review > $i)$s= "active";
                    echo '<i id="'.$pid.'" onclick="rate(this)" name="'.($i+1).'" 
                    style="cursor: pointer"class="icon-star '.$s.'" curr="'.$review.'"></i>';
                }?>
                <i onclick="like(this)" name="<?php echo $product['id'];?>" class="icon-heart"
                   style="float: right;cursor: pointer; <?php if(!$like) echo "color: grey;"; else echo "color: #ED4956;";?> "></i>
                <input id="<?php echo $product['id'];?>" type="hidden" value="">
            </div>
        </div>
    </div>
    <?php }?>
</div>
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
</body>
</html>