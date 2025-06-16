<?php
$conn=mysqli_connect('localhost:3307','root','','photobase');
if(!$conn){
    die(mysqli_error($conn));
}
?>