@php
    $collection = $protocol->collection;
    $vehicle = $collection->transportReport?->vehicle;
    $driver = $vehicle?->assignedDriver?->name ?: $vehicle?->driver ?: $collection->user?->name ?: '—';
@endphp
<p>Здравейте,</p>
<p>Извършено е събиране на отпадъчно олио от <strong>{{ $collection->client->company_name ?: $collection->client->name }}</strong>.</p>
<ul>
    <li><strong>Дата и час:</strong> {{ $collection->created_at->format('d.m.Y H:i') }}</li>
    <li><strong>Количество:</strong> {{ number_format($collection->liters, 2) }} л.</li>
    <li><strong>Цена за литър:</strong> {{ number_format($collection->price_per_liter, 2) }} лв.</li>
    <li><strong>Обща сума:</strong> {{ number_format($collection->total_price, 2) }} лв.</li>
    <li><strong>Шофьор:</strong> {{ $driver }}</li>
    <li><strong>Превозно средство:</strong> {{ $vehicle?->registration ?: '—' }}</li>
    <li><strong>Документ №:</strong> {{ $protocol->id }}</li>
</ul>
<p>Прикачен е PDF приемо-предавателният протокол за конкретното събиране.</p>
<p>Поздрави,<br>Бул Рос Груп ЕООД</p>
