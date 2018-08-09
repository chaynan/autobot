<?php 
$host = 'ec2-23-23-242-163.compute-1.amazonaws.com';
$dbname = 'dfitqn78lbn0av';
$user = 'gwuaimhybkhmyz';
$pass = 'cb37b0b2797f5e53a4eb419c7fdabbd347a988bb3f5cec004ba794a2d71f8b7e';
$connection = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass); 
$result = $connection->query("SELECT * FROM polls"); 
if($result !== null) { 
    echo $result->rowCount(); 
}
?>