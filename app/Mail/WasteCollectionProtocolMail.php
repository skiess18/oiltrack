<?php

namespace App\Mail;

use App\Models\Protocol;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WasteCollectionProtocolMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Protocol $protocol, public bool $forClient = false)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->forClient
            ? 'Протокол за предаване на отпадъчни масла'
            : 'Протокол за събиране на отпадъци');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.waste-collection-protocol');
    }

    public function attachments(): array
    {
        return [Attachment::fromStorageDisk('public', $this->protocol->pdf_path)
            ->as('waste-collection-protocol-' . $this->protocol->collection_id . '.pdf')
            ->withMime('application/pdf')];
    }
}
