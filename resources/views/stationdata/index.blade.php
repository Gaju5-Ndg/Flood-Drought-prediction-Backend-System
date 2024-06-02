
<?php


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'http://localhost:8001/floods_detector',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "WaterLevel": 0,
  "SoilMoisture": 0,
  "Humidity": 0,
  "Temperature": 0,
  "created_at": "2024-05-26T15:31:59.924991"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response, true);
echo $response["result"]["prediction"];


?>


@extends('layouts.stations')

@section('content')
<div class="card card-table-border-none dt-responsive nowrap" style="width:100%" id="recent-orders">
  <div class="card-header justify-content-between bg-light text-center">
    <h2>Data Management</h2>
    <div class="date-range-report">
      <span></span>
    </div>
  </div>
  <div class="pull-left">
    <a href="{{ route('stationdata.create') }}" class="dropdown-item">
      <i class="nav-icon fas fa-plus"></i> Add
    </a>
  </div>
  @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ $message }}</p>
    </div>
  @endif
</div>

<div class="small-box bg-white">
  <a href="{{'/stationdata'}}" class="badge bg-white text-center">Data collected on {{ Auth::user()->station->name }} station</a>
  <div class="container">
    <div class="table-responsive">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Time</th>
            <th>Water Level</th>
            <th>Temperature</th>
            <th>Humidity</th>
            <th>Soil Moisture</th>
            <th>Prediction</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($data as $key)
            <tr>
              <td>{{ $key->created_at }}</td>
              <td>{{ $key->water_level }}</td>
              <td>{{ $key->temperature }}</td>
              <td>{{ $key->hummidity }}</td>
              <td>{{ $key->soil_moisture }}</td>
              <td>{{$response["result"]["prediction"]}} </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#example1').DataTable({
      responsive: true,
      paging: true,
      pageLength: 5
    });
  });
</script>
@endsection
