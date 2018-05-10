<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 4/21/2018
 * Time: 3:17 PM
 */
include_once 'Database.php';

class UserController
{
    public function login($email, $password){
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            return json_encode(['error' => true, "not_email" => true]);
        if( preg_match('/[^A-Za-z0-9\-]/', $password)){
            return json_encode(['error' => true, "sc" => true]);
        }

        $password = hash('sha512', $password);

        $query = "select * from users where email = '$email' AND password = '$password'";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        if($res == null)
            return false;

        session_start();
        $_SESSION['username'] = $res['username'];
        $_SESSION['user_id'] =$res['id'];
        $_SESSION['role'] = $res['role'];
        $_SESSION['email'] = $res['email'];
        return true;
    }
    public function signup($name, $email, $password){
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            return json_encode(['error' => true, "not_email" => true]);
        if( preg_match('/[^A-Za-z0-9\-]/', $password) || preg_match('/[^A-Za-z0-9\-]/', $name)){
            return json_encode(['error' => true, "sc" => true]);
        }

        $password = hash('sha512', $password);
        $query = "select * from users where email = '$email'";
        $db = new Database();
        $res = $db->query($db->connect(), $query);
        $res = mysqli_fetch_assoc($res);
        if($res != null)
            return json_encode(['error' => true, "email_exist" => true]);

        $password = preg_replace('/[^A-Za-z0-9\-]/', '', $password);
        $password = hash('sha512', $password);
        $query = "insert into users(email, username, password, role) values('$email', '$name', '$password', 'normal')";
        $db->query($db->connect(), $query);
        $res = $db->query($db->connect(), "select * from users where email = '$email' AND password = '$password'");
        $res = mysqli_fetch_assoc($res);

        session_start();
        $_SESSION['username'] = $res['username'];
        $_SESSION['user_id'] =$res['id'];
        $_SESSION['role'] = $res['role'];
        $_SESSION['email'] = $res['email'];
        return json_encode(['error' => false]);
    }
    public static function auth(){
        return isset($_SESSION['user_id'])?true:false;
    }
    public static function logout(){
        session_start();
        session_unset();
        session_destroy();
        header("Location: index.php");
    }
    public static function update_user($name, $email, $password){
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            return json_encode(['error' => true, "not_email" => true]);
        if( preg_match('/[^A-Za-z0-9\-]/', $password) || preg_match('/[^A-Za-z0-9\-]/', $name)){
            return json_encode(['error' => true, "sc" => true]);
        }

        $id = $_SESSION['user_id'];
        $db = new Database();
        if ($_SESSION['email'] != $email){
            $query = "select * from users where email = '$email'";
            $res = $db->query($db->connect(), $query);
            $res = mysqli_fetch_assoc($res);
            if($res != null)
                return json_encode(['error' => true, "email_exist" => true]);
        }

        $password = preg_replace('/[^A-Za-z0-9\-]/', '', $password);
        $password = hash('sha512', $password);
        $query = "update users set email = '$email', username = '$name', password = '$password' where id = $id";
        $db->query($db->connect(), $query);
        $res = $db->query($db->connect(), "select * from users where email = '$email' AND password = '$password'");
        $res = mysqli_fetch_assoc($res);

        session_start();
        $_SESSION['username'] = $res['username'];
        $_SESSION['user_id'] =$res['id'];
        $_SESSION['role'] = $res['role'];
        $_SESSION['email'] = $res['email'];
        return json_encode(['error' => false]);
    }
}