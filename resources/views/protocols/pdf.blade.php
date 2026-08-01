<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 10mm 11mm 12mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 9.2px; line-height: 1.22; }
        table { width: 100%; border-collapse: collapse; }
        .header td { border: 1px solid #111; vertical-align: top; padding: 6px; }
        .receiver { width: 30%; font-size: 8.4px; }
        .logo { width: 32%; text-align: center; vertical-align: middle !important; }
        .logo-name { font-size: 25px; font-weight: bold; letter-spacing: -2px; color: #444; }
        .logo-subtitle { font-size: 7px; letter-spacing: 1.4px; }
        .supplier { width: 38%; }
        .supplier-title, .receiver-title { font-weight: bold; }
        .field { border-bottom: 1px dotted #222; min-height: 13px; padding: 1px 2px; }
        .title { margin: 10px 0 6px; text-align: center; font-weight: bold; font-size: 15px; }
        .subtitle { text-align: center; font-size: 12px; margin-bottom: 12px; }
        .body-field { margin: 5px 0; white-space: nowrap; }
        .line { display: inline-block; border-bottom: 1px dotted #222; min-width: 210px; height: 12px; vertical-align: bottom; }
        .line.short { min-width: 70px; }
        .line.medium { min-width: 135px; }
        .waste-table { margin-top: 9px; }
        .waste-table th, .waste-table td { border: 1px solid #111; padding: 5px; text-align: center; }
        .waste-table th { font-size: 8px; background: #f2f2f2; }
        .legal { margin-top: 11px; font-size: 8.7px; text-align: justify; }
        .checkbox { display: inline-block; width: 10px; height: 10px; border: 1px solid #111; margin: 0 4px -2px 0; }
        .declaration { margin-top: 8px; font-weight: bold; }
        .footer-note { margin-top: 10px; font-size: 8.8px; }
        .signatures { margin-top: 28px; }
        .signatures td { width: 50%; padding: 0 24px; text-align: center; vertical-align: bottom; }
        .signature-image { max-width: 140px; max-height: 42px; }
        .signature-line { border-top: 1px solid #111; margin-top: 30px; padding-top: 4px; }
    </style>
</head>
<body>
    <table class="header">
        <tr>
            <td class="receiver">
                <span class="receiver-title">Получател:</span><br>
                БУЛ РОС ГРУП ЕООД<br>
                България, с. Говедаре, п.к. 4453<br>
                Стопански двор находящ се в имот №015013<br>
                МОЛ: Мария Димитрова<br>
                ЕИК: 204140044
            </td>
            <td class="logo">
                <div class="logo-name">BRG</div>
                <div class="logo-subtitle">BUL ROS GROUP LTD</div>
                <div style="margin-top:7px; font-size:8px;">№ {{ $collection->id }}</div>
            </td>
            <td class="supplier">
                <span class="supplier-title">Доставчик:</span>
                <div class="field">{{ $collection->client->company_name }}</div>
                Адрес:<div class="field">{{ $collection->client->address }}</div>
                МОЛ:<div class="field">{{ $collection->client->representative ?: $collection->client->contact_person }}</div>
                ЕИК:<div class="field">{{ $collection->client->bulstat }}</div>
                Тел:<div class="field">{{ $collection->client->phone }}</div>
                Email:<div class="field">{{ $collection->client->email }}</div>
            </td>
        </tr>
    </table>

    <div class="title">ПРИЕМО-ПРЕДАВАТЕЛЕН ПРОТОКОЛ</div>
    <div class="subtitle">СОБСТВЕНА ДЕКЛАРАЦИЯ ЗА ДОСТАВКА НА ОТПАДЪЦИ</div>

    <div class="body-field">Днес <span class="line short">{{ \Carbon\Carbon::parse($collection->collection_date)->format('d.m.Y') }}</span> в гр. <span class="line medium"></span></div>
    <div class="body-field">и <span class="line medium">{{ $collection->client->representative ?: $collection->client->contact_person }}</span> представител на фирма <span class="line medium">{{ $collection->client->company_name }}</span></div>
    <div class="body-field">представи на фирма <strong>БУЛ РОС ГРУП ЕООД</strong> следния отпадък:</div>

    <table class="waste-table">
        <tr><th>Код на отпадъка</th><th>Наименование</th><th>Количество</th><th>Ед. цена</th><th>Обща стойност</th><th>Плащане</th></tr>
        <tr><td>20 01 25</td><td>Растителни мазнини / Used Cooking Oil</td><td>{{ number_format($collection->liters, 2) }} л.</td><td>{{ number_format($collection->price_per_liter, 2) }} лв.</td><td>{{ number_format($collection->total_price, 2) }} лв.</td><td>{{ $collection->payment_method === 'cash' ? 'В брой' : 'Банков превод' }}</td></tr>
    </table>

    <div class="legal">
        Се състави приемо-предавателен протокол за събиране на отпадъци с код 20 01 25 – растителни масла и мазнини. Получателят притежава регистрационни документи № 09-РД-56001/18.04.2017 г. и № 07-РД-325-01/18.04.2017 г. за дейности по събиране, транспортиране и съхраняване на отпадъци.
    </div>

    <div class="declaration"><span class="checkbox"></span> Продавачът издава фактура в съответствие с действащото законодателство.</div>
    <div class="declaration"><span class="checkbox"></span> Продавачът не издава фактура.</div>

    <div class="footer-note">
        Настоящият протокол се състави в два еднообразни екземпляра – по един за страните.<br><br>
        Запознах се с приложената на гърба собствена декларация за доставка на отпадъци и я приемам.
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div class="signature-line">Приел / Бул Рос Груп ЕООД</div>
            </td>
            <td>
                @if($collection->signature)<img class="signature-image" src="{{ public_path('storage/' . $collection->signature) }}" alt="Подпис">@endif
                <div class="signature-line">Предал / доставчик</div>
            </td>
        </tr>
    </table>
</body>
</html>
