<?php 

session_start();

if(isset($_SESSION['role']) && $_SESSION['role'] != "admin") {
     $url = $_SERVER['REQUEST_URI'];
    if($url == '/add_product.php' || strpos($url, "edit_product") )header("Location: products.php");
 }	

 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="apptrana" content="71a99cd5100943999af5d304fd0b87cc">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/fontello/fontello.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
<?php
$url = $_SERVER['REQUEST_URI'];

if($url != '/' && $url != '/index.php' && $url != '/signup.php'){?>
<nav class="clearfix">
    <ul>
        <li><a href="products.php" <?php if($url == '/products.php') echo "class='active'";?>>Products</a></li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {?>
            <li><a href="add_product.php" <?php if($url == '/add_product.php') echo "class='active'";?>>Add Product</a></li>
            <li><a href="edit_product.php" <?php if(strpos($url, "edit_product")) echo "class='active'";?>>Edit Products</a></li>
        <?php }?>
            <li><a href="Profile.php" <?php if($url == '/Profile.php' || $url
                    == '/likes.php' || $url == '/Ratings.php'
                    || $url == '/EditProfile.php' ) echo "class='active'";?>>Profile</a></li>
        <li class="right"><a href="logout.php">Logout</a></li>
    </ul>
</nav>
<?php }?>