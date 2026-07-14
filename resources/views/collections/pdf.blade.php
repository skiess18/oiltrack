<!DOCTYPE html>
<html lang="bg">

<head>
<meta charset="UTF-8">

<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size:13px;
    color:#222;
    margin:30px;
}

.header{
    text-align:center;
    margin-bottom:35px;
}

.header h1{
    margin:0;
    font-size:28px;
}

.header p{
    margin:4px 0;
    color:#666;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#1e40af;
    color:white;
    padding:10px;
    text-align:left;
}

td{
    border:1px solid #dcdcdc;
    padding:10px;
}

.label{
    width:220px;
    background:#f3f4f6;
    font-weight:bold;
}

.total{
    font-size:18px;
    font-weight:bold;
    color:#15803d;
}

.signature-box{
    margin-top:50px;
}

.signature-box img{
    width:260px;
    border:1px solid #999;
    padding:10px;
}

.signatures{
    margin-top:60px;
    width:100%;
}

.signatures td{
    border:none;
    text-align:center;
}

.footer{
    margin-top:70px;
    text-align:center;
    color:#777;
    font-size:11px;
}

</style>

</head>

<body>

<div class="header">

    <h1>🛢 OilTrack CRM</h1>

    <p>Приемо-предавателен протокол</p>

    <p>№ {{ $collection->id }}</p>

</div>

<table>

<tr>
<td class="label">Обект</td>
<td>{{ $collection->client->name }}</td>
</tr>

<tr>
<td class="label">Адрес</td>
<td>{{ $collection->client->address }}</td>
</tr>

<tr>
<td class="label">Телефон</td>
<td>{{ $collection->client->phone ?: '-' }}</td>
</tr>

<tr>
<td class="label">Дата</td>
<td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}</td>
</tr>

<tr>
<td class="label">Количество</td>
<td>{{ number_format($collection->liters,2) }} литра</td>
</tr>

<tr>
<td class="label">Цена за литър</td>
<td>{{ number_format($collection->price_per_liter,2) }} лв.</td>
</tr>

<tr>
<td class="label">Обща стойност</td>
<td class="total">{{ number_format($collection->total_price,2) }} лв.</td>
</tr>

<tr>
<td class="label">GPS координати</td>

<td>

@if($collection->latitude)

{{ $collection->latitude }},
{{ $collection->longitude }}

@else

Няма GPS

@endif

</td>

</tr>

<tr>

<td class="label">Бележки</td>

<td>

{{ $collection->notes ?: '-' }}

</td>

</tr>

</table>

@if($collection->signature)

<div class="signature-box">

<h3>Подпис на клиента</h3>

<img src="{{ public_path('storage/'.$collection->signature) }}">

</div>

@endif

<table class="signatures">

<tr>

<td>

<br><br>

............................................

<br>

Подпис на шофьора

</td>

<td>

<br><br>

............................................

<br>

Подпис на клиента

</td>

</tr>

</table>

<div class="footer">

Документът е генериран автоматично от OilTrack CRM.

<br>

{{ now()->format('d.m.Y H:i') }}

</div>

</body>

</html>