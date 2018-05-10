<?php
include 'header.php';
include "controllers/UserController.php";
include "controllers/ProductController.php";

$products = ProductController::get_products();
//update product
$uploaded = false;
$sc = false;
if(isset($_POST['submit'])){
    $uploaded = false;
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];
    $images = ($_FILES['imgs']['size'] != 0 )?$_FILES['imgs']:null;
    $id = $_POST['pid'];
    $product = new ProductController();
    $response = json_decode(ProductController::update_product($name,$desc,$price,$images, $id));
    if($response->sc == true) {
        $uploaded = false;
        $sc = true;
    }
       else{
           $uploaded = true;
           header("Location: edit_product.php?uploaded=true");
       }
}//delete product
elseif (isset($_POST['delete'])){
    if(ProductController::delete_product($_POST['pid']))
        header("Location: edit_product.php?deleted=true");
    else header("Location: edit_product.php?deleted=true");
}

?>
<title>Edit Product</title>
<?php if(isset($_GET['uploaded'])) {
    if ($_GET['uploaded'] == "true") {
        header("Location: products.php");
        exit;
    }
}
if(isset($_GET['deleted'])) {
    if ($_GET['deleted'] == "true") {
        header("Location: products.php");
        exit;
    }else {
        echo "<h1 >Something went wrong</h1>";
        exit;
    }
}
 if(isset($_GET['pid'])){
    $product = ProductController::view_product($_GET['pid']);
    $product = mysqli_fetch_assoc($product);
    ?>
<div class="product-add">
        <form action="edit_product.php" class="delete-product" method="post">
            <input type="hidden" name="pid" value="<?php echo $_GET['pid']?>">
            <button name="delete" >Delete Product</button>
        </form>
        <div class="images" style="width: 100%">
            <img class="default"
                 src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAADgCAMAAAAt85rTAAAAYFBMVEXy8vJ2dnb19fX5+flxcXFzc3Pq6urMzMyurq6FhYV+fn5ubm5ra2vi4uLu7u7n5+e+vr6VlZW3t7eQkJDT09PFxcXe3t6ampqhoaF6enqTk5OmpqbY2NiKioq7u7vDw8PWXlGGAAAE3ElEQVR4nO3c63aqOhQFYFghkgS5XwIi+v5vuRMQitW9lX3OaUnP/P5oEcfIbBYJUFLPAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgC+QHDbx6LsbvE0SDtFxAyVitxJeOPc3ifL0u9u8ATUD94NoA/PruLDvbvb7qPZ9VYTvq0vO9cGdIqUs4kNK72OCc5dqlLKAD/OoQW90DBOBcwFlPL5jcXV+PQU4G5DiVipVhq/GD2cDnmXAfc6P4kUfuhowOd1mwy78c0JHA9I5uk3jvH/Y5e4nVwMWwRzwofX3p2aOBjTNXgLGdzskeZSvEzoakKqlRNtk/XmqzZncaZXQ0YBeIudBpl51F6Wt3Ryc0mWjqwG9UI0Jo3UHUqJvFxB66UNnA3qhmQiDQKw/o3y+kuJ67kN3AxJr6tBbncjQIZ9HHv/jOHQ3oO2x9XxAh3Z9JTwndDngJ+L+Qj6YqtT1gKvR8vOtiuk4JLcD0jVPpowi+hzQD+yM73gPXs1Eb/qJEvHsTlSkE8cDXs1kaBMmonuSz+fy4HRAuo6TPdfe83yuB6T6doeUy6fx5oDu3XS6Bbwu0/rv7gTbgO6OolN9/pHTJfpGPpcDUvjbuvwhAUXwOh8C7tH/IGC0LaCj0wQVnXpD6WIPTtMEsbe4eT04PL/gfc69gOYYbN7rvhH1nJ9cCmhvFyq5Qefz3p2/YHveoTXjJ+f2T2fv8bmqXApIjebBJjJzKZ+9PV+LLbLmu1u8GSVbuPYkFwAAAAAAAAAAAAD8ZOTQ0s6/UuvDdzfhP5UI37EV1lul5+T1TpP9/iIYEYuXg80+fPDRVruZ7A52G01PHUzbk8Runr/BKJ1W4d1/fReYLKpSyXasRUqKUubLqk+qZUpVHwtZXjw6t7IsxiftqS6V0nGjY/P9sx6GNtTabk9FKdsXCw6/GuOD7DOhZGMaeJZSZG3X34YWugYxC7tOZ6Jr605UFzmY3eKTL8K67bRdzXzx86JoVWBiUeWXxaXvTrsamVigQlOFzaCZl+anlBEL1WX6jK48pfDYm22VL2tTlI0UnidUZeuz5mVMtcpMkbNx+0H1B/P+rIo99SHjrX0h0TGq/WZsWjE/1HzlpgePZ/t2GMb1BUJ6sbxMAXITUE4LRFmtGnZRMRtX1qs9TZ8sELY5rDgyVvBxE4V8Cjr1YGB/YKdy2o17TXR7qiIzJXospjBJVzFdnkcF32FAMgFpCRg9BizLaTfunW8BqTYBg6kcqTEBc39+hm1nAceB0QY0FTk2N1NLiT4GTGJ1nXrQDjKlHiuX+iFmbf7xCNt+MD4GtCVKaakP5hiqpJjm98cStQGTXtpjjYW+OQZDKc6M4osZqagJMs9sP9S7+t8dq2PQjPMqz8JC6mVlSBA/9iA15ZBVVaG0fWqvlkqbmbCyHwrVZnWRDzubJpZj0LzEbeery3x+ZgKm9oD07gKaM7i26zpZV9JWdBL2fd7p8TtVrvxBbHlY8Qvcjpj1edfy2XSq9rgboyq1VUr2H5aYFxYP+uPreyrQvzaHqKbx1pwcOPVE3tuonFZpmxO6Hxqw5oWty3go376wcopdWaj6tuz2NXL+m6gRbdvXO77g/adohxe5AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAzvoFm79QQHfHsvAAAAAASUVORK5CYII=">
            <div class="carousel">
            </div>
        </div>
        <div class="add-form">
            <form action="edit_product.php" method="post" onsubmit="return validate()" enctype='multipart/form-data'>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Name" value="<?php echo $product['name']?>">
                <label for="desc">Description</label>
                <input type="text" name="desc" id="desc" placeholder="Description" value="<?php echo $product['description']?>">
                <label for="price">Price</label>
                <input type="text" name="price" id="price" placeholder="Price" value="<?php echo $product['price']?>">
                <p id="i-error" style="display:  <?php if(!$sc)echo "none";else echo "block";?>;">Please enter name and description without special characters</p>
                <input type="hidden" name="pid" value="<?php echo $product['id']?>">
                <p id="p-error" style="display: none;">Price needs to be a number</p>
                <p>*In order to update the product you have to add its photos again</p>
                <label for="imgs">Images</label>
                <input type="file" name="imgs[]" id="imgs" class="custom-file-input" multiple onchange="loadImages()">
                <button type="submit" name="submit">update</button>
            </form>
        </div>
</div>
<?php }else {?>
<div class="product-view">
    <?php while($product = mysqli_fetch_assoc($products)){
        $pid = $product['id'];
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
                <a href="edit_product.php?pid=<?php echo $pid?>"><img src="./photos/<?php echo $image_name?>"></a>
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
<?php }?>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
<script>
    $carousel = $('.carousel');
    $image = $('.images .default');
    function loadImages() {
        $carousel.hide();
        $carousel.empty();
        $image.show();
        var imgs = $('#imgs')[0].files.length;
        if (imgs > 0) {
            $image.hide();
            for (var i = 0; i < imgs; i++) {
                $carousel.append("<a class=\"carousel-item\" href=\"#" + i + "!\"><img src=\"" + URL.createObjectURL(event.target.files[i]) + "\"></a>");
            }
            $carousel.show();
            $carousel.carousel();
        }
    }

    function validate() {
        var name = document.getElementById("name").value;
        var desc = document.getElementById("desc").value;
        var price = document.getElementById("price").value;
        var regex = /[^A-Za-z0-9\-]/;
        if (name && desc && price) {
            if(regex.test(name) || regex.test(desc))
            {
                document.getElementById("i-error").style.display = "block";
                return false;
            }
            if (isNaN(price)) {
                document.getElementById("p-error").style.display = "block";
                return false;
            }
            return true;
        }
        return false;
    }
</script>
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