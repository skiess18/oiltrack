<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Пътен лист</title>
</head>
<body>

<h2>Пътен лист</h2>

<p><strong>Дата:</strong> {{ \Carbon\Carbon::parse($report->date)->format('d.m.Y') }}</p>

<p><strong>Шофьор:</strong> {{ $report->user->name }}</p>

<p><strong>Автомобил:</strong> {{ $report->vehicle->registration }}</p>

<p><strong>Начални километри:</strong> {{ $report->start_km }}</p>

<p><strong>Крайни километри:</strong> {{ $report->end_km }}</p>

<p><strong>Изминати километри:</strong> {{ $report->end_km - $report->start_km }}</p>

<p><strong>Начално гориво:</strong> {{ $report->start_fuel }}%</p>

<p><strong>Крайно гориво:</strong> {{ $report->end_fuel }}%</p>

@if($report->notes)
    <p><strong>Бележки:</strong> {{ $report->notes }}</p>
@endif

</body>
</html>