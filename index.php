<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Document</title>
</head>
<body>
 
<?php
session_start();
# MASSIVE CREATION #########################################################################################################
$cities = json_decode(file_get_contents("json/citylist.json"));
foreach($cities as $k => $v){
    $mass[] = $v->name.' ';
    }
#CONNECT ####################################################################################################################
$servername = "localhost";
$username = "root";
$password = "";
$conn = new mysqli($servername, $username, $password);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error.'<br>');
    }
    #echo "Connected successfully".'<br>';
#CREATE DATABASE ####################################################################################################################
$sql = "CREATE DATABASE weatherone";
if(mysqli_query($conn, $sql)){
    #echo "Database created successfully".'<br>';
}else{
    #echo "Error creating database: " . mysqli_error($conn).'<br>';
}
#CREATE TABLE ####################################################################################################################
$dbname = "weatherone";
$conn = mysqli_connect($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");
$sql2 = "CREATE TABLE weatherdata (
    id INT(50),
    city TEXT(250) NOT NULL,
    country VARCHAR(250) NOT NULL,
    lon VARCHAR(250),
    lat VARCHAR(250)
    ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
if(mysqli_query($conn, $sql2)){
        #echo "Table Weather created successfully".'<br>';
    }else{
        #echo "Error creating table: " . mysqli_error($conn).'<br>';
    }
#INSERTING DATA ####################################################################################################################
ini_set('max_execution_time', 900);
// foreach($cities as $k => $v){
//     $lat = $v->coord->lat;
//     $lon = $v->coord->lon;
//     $country = addslashes($v->country);
//     $city = addslashes($v->name);
//     $sql3 = "INSERT INTO weatherdata (id, city, country, lon, lat)
//         VALUES ('$v->id', '$city', '$country', '$lon', '$lat')";
//     if (mysqli_query($conn, $sql3)) {
//     } else {
//         echo "Error: " . $sql3 . "<br>" . mysqli_error($conn);
//     }    
// }
mysqli_close($conn); ?>

<section id="wrapper">
    <h1>Weather app</h1>
    <span>City:</span>
    <form action="obr.php" method="post">
        <input type="search" name="city">
    </form>

<?php
if(!empty($_SESSION['thecity'])){
    #echo $_SESSION['thecity'];
    $apiKey = "da2e7f1d6dfb972bf0498f308b1c6dcc";
    $cityId = $_SESSION['thecity'];
    $url = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&units=metric&lang=en&units=metric&APPID=" . $apiKey;

$contents = file_get_contents($url);
$weather=json_decode($contents);

$temp_now = 't°: '.$weather->main->temp."°C";
$temp_max = "t° max: ".$weather->main->temp_max.'°C';
$temp_min = "t° min: ".$weather->main->temp_min.'°C';
$wind = "Wind: ".$weather->wind->speed.'m/s';
$clouds = "Clouds: ".$weather->clouds->all.'%';
$humidity = "Humidity: ".$weather->main->humidity.'%';
$sunriseD = $weather->sys->sunrise;
    $sunrise = "Sunrise: ".date("H:i", mktime(0, 0, $sunriseD));
$sunsetD = $weather->sys->sunset;
    $sunset = "Sunset: ".date("H:i", mktime(0, 0, $sunsetD));

$today = 'Today: '.date("d.m.y, H:i");
$cityname = 'City: '.$weather->name;
# ВЫВОД: ####################################################################################################################
echo ' '.
$today."<br />".
$cityname."<br />".
$temp_now."<br />". 
$temp_max."<br />".
$temp_min."<br />".
$wind."<br />".
$clouds."<br />".
$humidity."<br />".
$sunrise."<br />".
$sunset."<br />"."<br />"; ?>

</section>

<?php
}else{
    echo 'X';
}
#TABLE 2 SPY #################################################################################################################################
$conn = mysqli_connect($servername, $username, $password, $dbname);
$sql4 = "CREATE TABLE weatherspy (
    urlz VARCHAR(500) NOT NULL,
    timez VARCHAR(250) NOT NULL,
    timestampformat VARCHAR(250) NOT NULL,
    city VARCHAR(250),
    country VARCHAR(250),
    lon VARCHAR(250),
    lat VARCHAR(250)
    ) CHARACTER SET utf8 COLLATE utf8_general_ci;";
    if (mysqli_query($conn, $sql4)) {
        #echo "Table WeatherSpy created successfully".'<br>';
    } else {
        #echo "Error creating table: " . mysqli_error($conn).'<br>';
    }

    ini_set('max_execution_time', 900);
#TABLE 2 SPY INSERT DATA #################################################################################################################################
    $cityspy = $weather->name;  
    $sql6 = "SELECT country, lon, lat FROM weatherdata WHERE city='$cityspy'";
    $result1 = mysqli_query($conn, $sql6);
    $getspy = mysqli_fetch_assoc($result1);
    $spyCountry = $getspy['country'];
    $spyLon = $getspy['lon'];
    $spyLat = $getspy['lat'];
    $urlTime = date('H:i');
    $timestampz = time();
    $urlcatch = $url;
    $sql5 = "INSERT INTO weatherspy (urlz, timez, timestampformat, city, country, lon, lat)
       VALUES ('$urlcatch', '$urlTime', '$timestampz','$cityspy', '$spyCountry', '$spyLon', '$spyLat')";
    if(mysqli_query($conn, $sql5)){
        #echo 'V';
    }else{ 
        #echo "Error: " . $sql5 . "<br>" . mysqli_error($conn);
    }    
    mysqli_close($conn);
?>
   <script src="js/main.js"></script> 
   <script src="js/jquery-3.4.1.min.js"></script>

</body>
</html>