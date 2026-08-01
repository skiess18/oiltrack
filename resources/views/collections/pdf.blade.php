<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 22mm 18mm; }

        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; }
        .header { border-bottom: 2px solid #1d4ed8; padding-bottom: 14px; margin-bottom: 24px; }
        .brand { color: #1d4ed8; font-size: 20px; font-weight: bold; }
        .title { font-size: 18px; font-weight: bold; margin-top: 8px; }
        .protocol-number { float: right; text-align: right; color: #4b5563; line-height: 1.7; }
        .clear { clear: both; }
        .section-title { background: #eff6ff; color: #1e3a8a; font-weight: bold; padding: 8px 10px; margin-top: 18px; }
        table { width: 100%; border-collapse: collapse; }
        .details td { border: 1px solid #d1d5db; padding: 9px 10px; vertical-align: top; }
        .details .label { width: 31%; background: #f9fafb; font-weight: bold; color: #374151; }
        .amount { color: #047857; font-size: 14px; font-weight: bold; }
        .bank-transfer { color: #1d4ed8; font-weight: bold; }
        .signatures { margin-top: 62px; }
        .signatures td { width: 50%; text-align: center; padding: 0 20px; vertical-align: bottom; }
        .signature-line { border-top: 1px solid #374151; padding-top: 8px; margin-top: 40px; }
        .customer-signature { max-height: 80px; max-width: 220px; margin-bottom: 8px; }
        .footer { position: fixed; bottom: -12mm; left: 0; right: 0; text-align: center; color: #6b7280; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="protocol-number">
            Протокол № {{ $collection->id }}<br>
            Дата: {{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}
        </div>
        <div class="brand">OilTrack</div>
        <div class="title">Приемо-предавателен протокол</div>
        <div class="clear"></div>
    </div>

    <div class="section-title">Данни за клиента</div>
    <table class="details">
        <tr>
            <td class="label">Име на фирма</td>
            <td>{{ $collection->client->company_name }}</td>
        </tr>
        <tr>
            <td class="label">Булстат</td>
            <td>{{ $collection->client->bulstat ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Обект / адрес</td>
            <td>{{ $collection->client->name }}<br>{{ $collection->client->address }}</td>
        </tr>
    </table>

    <div class="section-title">Данни за събирането</div>
    <table class="details">
        <tr>
            <td class="label">Дата на събиране</td>
            <td>{{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}</td>
        </tr>
        <tr>
            <td class="label">Шофьор</td>
            <td>{{ $collection->user?->name ?: '-' }}</td>
        </tr>
        <tr>
            <td class="label">Автомобил</td>
            <td>
                @if($collection->transportReport?->vehicle)
                    {{ $collection->transportReport->vehicle->brand }} {{ $collection->transportReport->vehicle->model }} ({{ $collection->transportReport->vehicle->registration }})
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Събрано количество</td>
            <td>{{ number_format($collection->liters, 2) }} литра</td>
        </tr>
        <tr>
            <td class="label">Цена за литър</td>
            <td>{{ number_format($collection->price_per_liter, 2) }} лв.</td>
        </tr>
        <tr>
            <td class="label">Обща сума</td>
            <td class="amount">{{ number_format($collection->total_price, 2) }} лв.</td>
        </tr>
        <tr>
            <td class="label">Начин на плащане</td>
            <td class="{{ $collection->payment_method === 'bank_transfer' ? 'bank-transfer' : '' }}">
                {{ $collection->payment_method === 'cash' ? 'В брой' : 'По банков път' }}
            </td>
        </tr>
        <tr>
            <td class="label">Бележки</td>
            <td>{{ $collection->notes ?: '-' }}</td>
        </tr>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div class="signature-line">Подпис на представител на OilTrack</div>
            </td>
            <td>
                @if($collection->signature)
                    <img class="customer-signature" src="{{ public_path('storage/' . $collection->signature) }}" alt="Подпис на клиента">
                @endif
                <div class="signature-line">Подпис на клиента</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Документът е генериран от OilTrack на {{ now()->format('d.m.Y H:i') }}.
    </div>
</body>
</html>
