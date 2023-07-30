<?php

$conn=new mysqli('localhost', 'root', 'password', 'cinema');

if($conn->connect_error){
    die("Error de conexxion" . $conn->connect_error);
}
?>