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

    public function __construct(public Protocol $protocol)
    {
        $this->protocol->loadMissing([
            'collection.client.emailRecipients',
            'collection.user',
            'collection.transportReport.vehicle.assignedDriver',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Документ за извършено събиране #' . $this->protocol->id);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.waste-collection-protocol');
    }

    public function attachments(): array
    {
        $attachments = [Attachment::fromStorageDisk('public', $this->protocol->pdf_path)
            ->as('protocol-' . $this->protocol->collection_id . '.pdf')
            ->withMime('application/pdf')];

        if ($this->protocol->collection?->cash_receipt_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->protocol->collection->cash_receipt_path)
                ->as('cash-receipt-' . $this->protocol->collection_id . '.pdf')
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
