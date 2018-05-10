<?php
include "Database.php";
session_start();
$uid = $_SESSION['user_id'];
$pid = $_POST['pid'];
$review = $_POST['review'];
$db = new Database();
$conn = $db->connect();
// If user already rated the product
$query = "select * from product_reviews where user_id = $uid and product_id = $pid ";
$res = $db->query($conn, $query);
$res = mysqli_fetch_assoc($res);
if($res != null){
    $id = $res['id'];
    $query = "update product_reviews set review = $review where user_id = $uid and product_id = $pid";
    $db->query($conn, $query);
} else{
    $query = "insert into product_reviews(user_id, product_id, review) values('$uid', '$pid', $review)";
    $db->query($conn, $query);
}

$sum = 0;
$query = "select * from product_reviews where product_id = $pid ";
$ress = $db->query($conn, $query);
$count = 0;
while ($res = mysqli_fetch_assoc($ress) ){
    $sum += $res['review'];
    $count++;
}
$review = (int) $sum/$count;
if($review > 5)$review = 5;
$query = "update products set review = $review where id = $pid ";
$db->query($conn, $query);