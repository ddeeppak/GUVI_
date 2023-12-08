<?php
require_once __DIR__ . '/../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // MySQL

    $conn = new mysqli('localhost', 'root', '', 'passkey');

    if ($conn->connect_error) {
        die("Failed: " . $conn->connect_error);
    }
    $st = $conn->prepare("SELECT * FROM `userpass` WHERE `username` = ? and `password`=?");
    if (!$st) {
        die("Prepare failed: " . $conn->error);
    }
    $st->bind_param('ss', $username,$password);
    $st->execute();
    if ($st->error) {
        die("Error executing query: " . $st->error);
    }


    // Redis
    
    $redis = new Predis\Client();
    $redis->connect('127.0.0.1', 6379);
    $redis->set('LoginStatus', 'true');


    $result = $st->get_result();
    
    if ($result->num_rows > 0) {
        echo "<script>window.location.href='../profile.html';</script>";
        exit();
    } 
    else {
        echo "<script>alert('Invalid UserName Or Password'); window.location.href='../login.html';</script>";
        exit();
    }
    $st->close();
    $conn->close();

}
?>