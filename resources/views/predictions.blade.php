<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predictions</title>
</head>
<body>
    <h1>Predictions</h1>
    <ul>
        @foreach ($predictions as $stationID => $prediction)
            <li>
                Station ID: {{ $stationID }} -
                @if (isset($prediction['error']))
                    Error: {{ $prediction['error'] }}
                @else
                    Prediction: {{ $prediction }}
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>
