<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "justice_institution";

$conn =  mysqli_connect($servername,$username,$password,$dbname);

if ($conn -> connect_error) {
  echo "error". + connect_error();
}else{
  // echo "successfully";
}
?>