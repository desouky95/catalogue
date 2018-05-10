<?php
include 'Database.php';
session_start();
$db = new Database();
$pid = $_POST['pid'];
$uid = $_SESSION['user_id'];
$query = "select * from user_likes where product_id = $pid and user_id = $uid";
$res = $db->query($db->connect(), $query);
$res = mysqli_fetch_assoc($res);
if($res == null) {
    $query = "insert into user_likes(product_id, user_id) values($pid, ".$_SESSION['user_id'].")";
    $res = $db->query($db->connect(), $query);
}else{
    $query = "delete from user_likes where id = ".$res['id'];
    $res = $db->query($db->connect(), $query);
}
