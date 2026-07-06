<?php

function get_patient($id){
  require "conn.php";
  $sql = "SELECT * FROM `doctors` WHERE `pk`='".$id."'";
$res = mysqli_query($conn,$sql);



while($row = mysqli_fetch_array($res)){

}
}












 ?>
