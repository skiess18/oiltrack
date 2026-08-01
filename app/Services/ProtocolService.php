<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Protocol;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ProtocolService
{
    public function generate(Collection $collection): Protocol
    {
        $collection->loadMissing(['client', 'user', 'transportReport.vehicle']);

        if (!$collection->client || !$collection->liters || !$collection->transportReport) {
            throw new \RuntimeException('Протокол не може да бъде генериран без клиент, количество и транспортен отчет.');
        }

        $path = 'protocols/protocol_' . now()->format('Y-m-d') . '_client_' . $collection->client_id . '.pdf';

        Storage::disk('public')->put(
            $path,
            Pdf::loadView('protocols.pdf', compact('collection'))
                ->setPaper('a4')
                ->output()
        );

        $protocol = Protocol::updateOrCreate(
            ['collection_id' => $collection->id],
            [
                'client_id' => $collection->client_id,
                'user_id' => $collection->user_id,
                'pdf_path' => $path,
            ]
        );

        if ($collection->payment_method === 'cash') {
            $receiptPath = 'protocols/cash-receipt-' . $collection->id . '.pdf';
            Storage::disk('public')->put(
                $receiptPath,
                Pdf::loadView('protocols.cash-receipt', [
                    'collection' => $collection,
                    'amountInWords' => $this->amountInWords((float) $collection->total_price),
                ])->setPaper('a4')->output()
            );
            $collection->forceFill(['cash_receipt_path' => $receiptPath])->save();
        }

        return $protocol;
    }

    private function amountInWords(float $amount): string
    {
        $whole = (int) floor($amount);
        $stotinki = (int) round(($amount - $whole) * 100);

        return $this->numberInWords($whole) . ' лева и ' . $this->numberInWords($stotinki) . ' стотинки';
    }

    private function numberInWords(int $number): string
    {
        $ones = [0 => 'нула', 1 => 'един', 2 => 'два', 3 => 'три', 4 => 'четири', 5 => 'пет', 6 => 'шест', 7 => 'седем', 8 => 'осем', 9 => 'девет', 10 => 'десет', 11 => 'единадесет', 12 => 'дванадесет', 13 => 'тринадесет', 14 => 'четиринадесет', 15 => 'петнадесет', 16 => 'шестнадесет', 17 => 'седемнадесет', 18 => 'осемнадесет', 19 => 'деветнадесет'];
        $tens = [2 => 'двадесет', 3 => 'тридесет', 4 => 'четиридесет', 5 => 'петдесет', 6 => 'шестдесет', 7 => 'седемдесет', 8 => 'осемдесет', 9 => 'деветдесет'];

        if ($number < 20) return $ones[$number];
        if ($number < 100) return $tens[intdiv($number, 10)] . ($number % 10 ? ' и ' . $ones[$number % 10] : '');
        if ($number < 1000) return $ones[intdiv($number, 100)] . 'стотин' . ($number % 100 ? ' ' . $this->numberInWords($number % 100) : '');

        return (string) $number;
    }
}
