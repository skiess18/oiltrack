<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 22mm 18mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 12px; }
        .header { border-bottom: 2px solid #1d4ed8; padding-bottom: 14px; margin-bottom: 28px; }
        h1 { margin: 0; color: #1d4ed8; font-size: 22px; }
        h2 { margin: 8px 0 0; font-size: 17px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td { border: 1px solid #d1d5db; padding: 13px; }
        td:first-child { background: #f8fafc; font-weight: bold; width: 60%; }
        .closing { font-size: 16px; color: #047857; font-weight: bold; }
        .footer { position: fixed; bottom: -12mm; left: 0; right: 0; text-align: center; color: #6b7280; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>OilTrack</h1>
        <h2>Складов отчет</h2>
        <div>Период: {{ $from->format('d.m.Y') }} – {{ $to->format('d.m.Y') }}</div>
    </div>

    <table>
        <tr><td>Начална наличност</td><td>{{ number_format($openingStock, 2) }} литра</td></tr>
        <tr><td>Събрано</td><td>{{ number_format($collected, 2) }} литра</td></tr>
        <tr><td>Предадено за рециклиране / продадено</td><td>{{ number_format($recycled, 2) }} литра</td></tr>
        <tr><td>Крайна наличност</td><td class="closing">{{ number_format($closingStock, 2) }} литра</td></tr>
    </table>

    <div class="footer">Генерирано от OilTrack на {{ now()->format('d.m.Y H:i') }}</div>
</body>
</html>
