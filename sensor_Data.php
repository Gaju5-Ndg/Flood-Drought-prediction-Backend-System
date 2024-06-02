<?php
include ('database.php')

function fetchData() {
    global $conn;
    $water_level;
    $humidity;
    $temperature;
    $soil_moisture

    $curl = curl_init();

    curl_close($curl);
    $responses = json_decode($response, true);
    $temperature = $responses['main']['temp'] - 273.15;
    $humidity = $responses['main']['humidity'];
    $water_level = $responses['water']['level'];
    $soil_moisture = $responses['soil']['moisture'];

    
    $water_level;
    $soil_moisture;
    $temperature;
    $humidity

    $sql = "SELECT * FROM sensor_data ORDER BY Data_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $water_level = $row['Water_level'];
            $soil_moisture = $row['Soil_moisture'];
            $temperature = $row['temperature'];
            $humidity = $row['humidity'];
        }
    }

    $curl1 = curl_init();

    curl_setopt_array($curl1, array(
      CURLOPT_URL => 'http://localhost:8001/floods_detector',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode([
          'water_level' => $water_level,
          'temperature' => $temperature,
          'humidity' => $humidity,
          'soil_moisture' => $soil_moisture
      ]),
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
      ),
    ));

    $response1 = curl_exec($curl1);

    curl_close($curl1);
    $responses1 = json_decode($response1, true);

    return [
        'water_level' => $water_level,
        'soil_moisture' => $soil_moisture,
        'predictions' => $responses1['predictions'],
        'temperature' => $temperature,
        'humidity' => $humidity,
    ];
}

header('Content-Type: application/json');
echo json_encode(fetchData());
?>


