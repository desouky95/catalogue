<?php
include 'header.php';
include "controllers/ProductController.php";

$uploaded = false;
$sc = false;
if(isset($_POST['submit'])){
    $uploaded = false;
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];
    $images = $_FILES['imgs'];
    $product = new ProductController();
    $response = json_decode($product->add_product($name,$desc,$price,$images));
    if($response->sc == true) {
        $uploaded = false;
        $sc = true;
    }else header("Location: products.php");
}

?>
<title>Add Product</title>
<div class="product-add">
            <?php if(!$uploaded) {?>
    <div class="images" style="width: 100%">
        <img class="default"
             src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAADgCAMAAAAt85rTAAAAYFBMVEXy8vJ2dnb19fX5+flxcXFzc3Pq6urMzMyurq6FhYV+fn5ubm5ra2vi4uLu7u7n5+e+vr6VlZW3t7eQkJDT09PFxcXe3t6ampqhoaF6enqTk5OmpqbY2NiKioq7u7vDw8PWXlGGAAAE3ElEQVR4nO3c63aqOhQFYFghkgS5XwIi+v5vuRMQitW9lX3OaUnP/P5oEcfIbBYJUFLPAwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgC+QHDbx6LsbvE0SDtFxAyVitxJeOPc3ifL0u9u8ATUD94NoA/PruLDvbvb7qPZ9VYTvq0vO9cGdIqUs4kNK72OCc5dqlLKAD/OoQW90DBOBcwFlPL5jcXV+PQU4G5DiVipVhq/GD2cDnmXAfc6P4kUfuhowOd1mwy78c0JHA9I5uk3jvH/Y5e4nVwMWwRzwofX3p2aOBjTNXgLGdzskeZSvEzoakKqlRNtk/XmqzZncaZXQ0YBeIudBpl51F6Wt3Ryc0mWjqwG9UI0Jo3UHUqJvFxB66UNnA3qhmQiDQKw/o3y+kuJ67kN3AxJr6tBbncjQIZ9HHv/jOHQ3oO2x9XxAh3Z9JTwndDngJ+L+Qj6YqtT1gKvR8vOtiuk4JLcD0jVPpowi+hzQD+yM73gPXs1Eb/qJEvHsTlSkE8cDXs1kaBMmonuSz+fy4HRAuo6TPdfe83yuB6T6doeUy6fx5oDu3XS6Bbwu0/rv7gTbgO6OolN9/pHTJfpGPpcDUvjbuvwhAUXwOh8C7tH/IGC0LaCj0wQVnXpD6WIPTtMEsbe4eT04PL/gfc69gOYYbN7rvhH1nJ9cCmhvFyq5Qefz3p2/YHveoTXjJ+f2T2fv8bmqXApIjebBJjJzKZ+9PV+LLbLmu1u8GSVbuPYkFwAAAAAAAAAAAAD8ZOTQ0s6/UuvDdzfhP5UI37EV1lul5+T1TpP9/iIYEYuXg80+fPDRVruZ7A52G01PHUzbk8Runr/BKJ1W4d1/fReYLKpSyXasRUqKUubLqk+qZUpVHwtZXjw6t7IsxiftqS6V0nGjY/P9sx6GNtTabk9FKdsXCw6/GuOD7DOhZGMaeJZSZG3X34YWugYxC7tOZ6Jr605UFzmY3eKTL8K67bRdzXzx86JoVWBiUeWXxaXvTrsamVigQlOFzaCZl+anlBEL1WX6jK48pfDYm22VL2tTlI0UnidUZeuz5mVMtcpMkbNx+0H1B/P+rIo99SHjrX0h0TGq/WZsWjE/1HzlpgePZ/t2GMb1BUJ6sbxMAXITUE4LRFmtGnZRMRtX1qs9TZ8sELY5rDgyVvBxE4V8Cjr1YGB/YKdy2o17TXR7qiIzJXospjBJVzFdnkcF32FAMgFpCRg9BizLaTfunW8BqTYBg6kcqTEBc39+hm1nAceB0QY0FTk2N1NLiT4GTGJ1nXrQDjKlHiuX+iFmbf7xCNt+MD4GtCVKaakP5hiqpJjm98cStQGTXtpjjYW+OQZDKc6M4osZqagJMs9sP9S7+t8dq2PQjPMqz8JC6mVlSBA/9iA15ZBVVaG0fWqvlkqbmbCyHwrVZnWRDzubJpZj0LzEbeery3x+ZgKm9oD07gKaM7i26zpZV9JWdBL2fd7p8TtVrvxBbHlY8Qvcjpj1edfy2XSq9rgboyq1VUr2H5aYFxYP+uPreyrQvzaHqKbx1pwcOPVE3tuonFZpmxO6Hxqw5oWty3go376wcopdWaj6tuz2NXL+m6gRbdvXO77g/adohxe5AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAzvoFm79QQHfHsvAAAAAASUVORK5CYII=">
        <div class="carousel">
        </div>
    </div>
    <div class="add-form">
        <form action="add_product.php" method="post" onsubmit="return validate()" enctype='multipart/form-data'>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" placeholder="Name">
            <label for="desc">Description</label>
            <input type="text" name="desc" id="desc" placeholder="Description">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" placeholder="Price">
            <p id="p-error" style="display: none;">Price needs to be a number</p>
            <p id="i-error" style="display: <?php if(!$sc)echo "none";else echo "block";?>;">Please enter name and description without special characters</p>
            <label for="imgs">Images</label>
            <input type="file" name="imgs[]" id="imgs" class="custom-file-input" multiple onchange="loadImages()">
            <button type="submit" name="submit">Add</button>
        </form>
    </div>
    <?php }?>
</div>

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
            if(regex.test(name) || regex.test(desc)){
                document.getElementById("i-error").style.display = "block";
                return false;
            }
            if (isNaN(price)) {
                document.getElementById("p-error").style.display = "block";
            }
            else {
                return true;
            }
        }
        return false;
    }
</script>
</body>
</html>