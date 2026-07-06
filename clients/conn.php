

<?php

$db_name ="vhudi";
$mysql_username = "user";
$mysql_passsword = 2022;
$server_name = 'vhudiDB';

$conn = mysqli_connect($server_name, $mysql_username , $mysql_passsword, $db_name);

if($conn){
ini_set('memory_limit', '-1');
//echo 'connected';
}
else{


echo "Database connection fail";
}




?>