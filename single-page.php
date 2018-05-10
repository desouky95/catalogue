<?php
	include 'header.php';
	include "controllers/UserController.php";
	include "controllers/ProductController.php";
	session_start();
	if(!UserController::auth())header("Location: index.php");
    $prod = ProductController::view_product($_GET['pid']);
    $product = mysqli_fetch_assoc($prod);
    $query = "select name from images where product_id = ".$product['id'];
    $db = new Database();
    $res = $db->query($db->connect(), $query);
    $images = array();
    if($res != null){
        $images = array();
        while ($ress = mysqli_fetch_assoc($res))
            $images [] = $ress['name'];
    }
    ?>


<div class="single-product">
    <div class="slider">
        <div class="carousel carousel-slider">
        <?php $c = 0; while($c < count($images)){
            ?>
            <a class="carousel-item"><img src="photos/<?php echo $images[$c];?>"></a>
        <?php $c++;}
            if (count($images) == 0) {
                echo '<a class="carousel-item"><img src="photos/default.jpg"></a>';
            }
        ?>

        </div>
    </div>
    <div class="info">
        <div class="card">
            <div class="body">
                <div class="title"><?php echo $product['name'];?></div>
                <div class="price"><?php echo $product['price'];?></div>
                <div class="description"><?php echo $product['description'];?></div>
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
    </div>
</div>

<script src="./js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>
    $(document).ready(function(){
        $('.carousel').carousel({
            fullWidth: true,
            indicators: true
        });
    });
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