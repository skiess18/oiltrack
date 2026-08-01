<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountingReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;
    public $collections;
    public $totalLiters;
    public $totalAmount;

    public function __construct(
        $report,
        $collections,
        $totalLiters,
        $totalAmount
    ) {
        $this->report = $report;
        $this->collections = $collections;
        $this->totalLiters = $totalLiters;
        $this->totalAmount = $totalAmount;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Дневен отчет - ' . now()->format('d.m.Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.accounting-report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}