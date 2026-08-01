<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Дневен отчет</title>
</head>
<body>

<h2>Дневен отчет</h2>

<p><strong>Дата:</strong> {{ \Carbon\Carbon::parse($report->date)->format('d.m.Y') }}</p>

<p><strong>Шофьор:</strong> {{ $report->user->name }}</p>

<p><strong>Общо литри:</strong> {{ number_format($totalLiters,2) }}</p>

<p><strong>Обща сума:</strong> {{ number_format($totalAmount,2) }} лв.</p>

<table border="1" cellpadding="6" cellspacing="0" width="100%">
    <tr>
        <th>Клиент</th>
        <th>Литри</th>
        <th>Цена</th>
        <th>Общо</th>
    </tr>

    @foreach($collections as $collection)
        <tr>
            <td>{{ $collection->client->name }}</td>
            <td>{{ number_format($collection->liters,2) }}</td>
            <td>{{ number_format($collection->price_per_liter,2) }}</td>
            <td>{{ number_format($collection->total_price,2) }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>