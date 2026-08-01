<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransportReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Пътен лист - ' . now()->format('d.m.Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.transport-report',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}