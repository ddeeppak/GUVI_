<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    $mongoDbUrl = "mongodb+srv://prajapathideepak4980:GvdFM28dHAJLVOVa@productdata.v84pxad.mongodb.net/?retryWrites=true&w=majority";
    $mongoDbName = "userdata";
    $collectionName = "profiledata";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fullname = $_POST["firstName"].$_POST['lastName'];
        $gender = $_POST["gender"];
        $dob = $_POST["dob"];
        $phone = $_POST["phone"];
        $mail=$_POST["mail"];
        $current_location = $_POST["address"];
        $passkey=$_POST["password"];
        // MongoDB

        $client = new MongoDB\Client($mongoDbUrl);
        $database = $client->$mongoDbName;
        $collection = $database->$collectionName;

        $dataToInsert = [
            [   "fullname"    => $fullname,
                "DOB"         => $dob,
                "Gender"      => $gender,
                "Current"     => $current_location,
                "mail"        => $mail,
                "passkey"     => $passkey,
                "contact"     => $phone

              ]
        ];
        $collection->insertMany($dataToInsert);

        // MySQL
        $conn = new mysqli('localhost', 'root', '', 'passkey');

        $stv = $conn->prepare("SELECT COUNT(`USERNAME`) FROM `USERPASS` WHERE `USERNAME`=? ");
        $stv->bind_param('s', $mail);
        $stv->execute();
        $stv->bind_result($count);
        $stv->fetch();
        if ($count == 1) {
            echo "<script>alert('User is already available'); window.location.href='../register.html';</script>";
            exit();
        }

        if ($conn->connect_error) {
            die("Failed: " . $conn->connect_error);
        }
        $st = $conn->prepare("INSERT INTO `USERPASS`(`USERNAME`,`PASSWORD`) VALUES (?,?)" );
        if (!$st) {
            die("Prepare failed: " . $conn->error);
        }
        $st->bind_param('ss', $mail,$passkey);
        $st->execute();
        if ($st->error) {
            die("Error executing query: " . $st->error);
        }

        header("Location: profile.html");
        exit();
 
    } else {
        // Handle cases where the form was not submitted via POST
        echo "Form not submitted.";
    } 


    
?>


