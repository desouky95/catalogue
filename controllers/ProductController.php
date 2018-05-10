<?php
include_once "Database.php";

class ProductController{

    public static function get_products(){
        $query = "select * from products ORDER BY id DESC";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        return $res;
    }
    public static function view_product($id){
        $query = "select * from products where id = $id";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        return $res;
    }
    public function add_product($name,$desc,$price,$images){
        if( preg_match('/[^A-Za-z0-9\-]/', $desc) || preg_match('/[^A-Za-z0-9\-]/', $name)){
            return json_encode(['error' => true, "sc" => true]);
        }
        $query = "insert into products(name, description, price) values('$name','$desc', '$price')";
        $db = new Database();
        $db->query($db->connect(), $query);
        $query = "select id from products where name = '$name'";

        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        $this->add_images($images, $res['id']);

        return true;
    }
    public static function update_product($name, $desc, $price, $images, $id){
        if( preg_match('/[^A-Za-z0-9\-]/', $desc) || preg_match('/[^A-Za-z0-9\-]/', $name)){
            return json_encode(['error' => true, "sc" => true]);
        }
        $name = str_replace("'","\'",$name);
        $desc = str_replace("'","\'",$desc);
        $query = "update products set name = '$name', description = '$desc', price = $price where id = $id ";
        $db = new Database();
        $db->query($db->connect(), $query);
        $p = new ProductController();
        $p->add_images($images,$id);
        return true;
    }
    public static function delete_product($id){
        $query = "delete from products where id=$id";
        $db = new Database();
        $db->query($db->connect(), $query);
        $query = "delete  from images where product_id = $id";
        $db->query($db->connect(), $query);
        $query = "delete  from product_reviews where product_id = $id";
        $db->query($db->connect(), $query);
        $query = "delete  from user_likes where product_id = $id";
        $db->query($db->connect(), $query);
        return true;
    }

    public static function get_user_liked_products(){
        $uid = $_SESSION['user_id'];
        $query = "select product_id from user_likes where user_id = $uid";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        return $res;
    }
    public static function get_user_reviewed_products(){
        $uid = $_SESSION['user_id'];
        $query = "select product_id from product_reviews where user_id = $uid";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        return $res;
    }
    public function add_images($images, $id){
        $query = "delete  from images where product_id = $id";
        $db = new Database();
        $db->query($db->connect(), $query);
        $x = 0;
        if($images != null) {
            foreach ($images["name"] as $key => $file) {
                $tmp_name = $images["tmp_name"][$key];
                $name = basename($images["name"][$key]);
                if($name == "") break;
                move_uploaded_file($tmp_name, "photos/$name");
                $query = "insert into images(name, product_id) values('$name','$id')";
                if ($x == 0)
                    $query = "insert into images(name, product_id, main) values('$name','$id',1)";
                $db = new Database();
                $db->query($db->connect(), $query);
                $x++;
            }
        }

    }
}