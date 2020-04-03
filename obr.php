<?php
session_start();
#SEARCH ####################################################################################################################
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "weatherone";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error.'<br>');
}
echo "Connected successfully".'<br>';
$conn = mysqli_connect($servername, $username, $password, $dbname);

$search = $_POST['city'];

$sql3 = "SELECT id FROM weatherdata WHERE city='$search'";
$result = mysqli_query($conn, $sql3);
$getId = mysqli_fetch_assoc($result);
echo '<br>' . $getId['id'];
if(!empty($getId['id'])){
    $_SESSION['thecity'] = $getId['id'];
}else{
    unset($_SESSION['thecity']);
}


mysqli_close($conn);
header('Location: /weather/index.php', true, 301);
exit;



