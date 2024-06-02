<?php
include('database.php'); // Include your database connection
include('SensorDataModel.php'); 

$model = new SensorDataModel($conn);

header('Content-Type: application/json');

echo json_encode($model->fetchData());
?>
