<?php
require __DIR__ . '/Bootstrap.php';

const PANADURA_LAT = 6.713;
const PANADURA_LNG = 79.906;

$requestedDate = $_GET['date'] ?? date('Y-m-d');

// basic validation: must look like YYYY-MM-DD
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestedDate)) {
    handleError('Invalid date format, expected YYYY-MM-DD', 400);
}

$url = sprintf(
    'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=weathercode,temperature_2m_max,temperature_2m_min&timezone=auto&start_date=%s&end_date=%s',
    PANADURA_LAT,
    PANADURA_LNG,
    $requestedDate,
    $requestedDate
);

$response = @file_get_contents($url);

if ($response === false) {
    error_log('DayQuest weather.php: could not reach Open-Meteo');
    handleError('Could not load weather data', 500);
}

$data = json_decode($response, true);

// date is outside the forecast horizon, or API returned no daily data
if (empty($data['daily']['time']) || empty($data['daily']['weathercode'])) {
    helperJsonEndpoint([
        'available' => false,
        'message' => 'Forecast not yet available for this date.'
    ]);
}

$code = $data['daily']['weathercode'][0];
$maxTemp = $data['daily']['temperature_2m_max'][0];
$minTemp = $data['daily']['temperature_2m_min'][0];

$badWeatherCodes = array_merge(range(51, 67), range(80, 82), range(95, 99));
$isBadWeather = in_array($code, $badWeatherCodes, true);

helperJsonEndpoint([
    'available' => true,
    'date' => $requestedDate,
    'weathercode' => $code,
    'temp_max' => $maxTemp,
    'temp_min' => $minTemp,
    'is_bad_weather' => $isBadWeather
]);