<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $charge;
    protected $invoiceNumber;

    /**
     * Create a new message instance.
     */
    public function __construct($charge)
    {
        $this->charge = $charge;
        
        // Generate a unique invoice number (timestamp + last 4 digits of charge ID)
        $chargeIdLastPart = substr($charge->id, -4);
        $this->invoiceNumber = date('Ymd') . '-' . $chargeIdLastPart;
        
        // Add the invoice number to the charge object so it can be accessed in the view
        $this->charge->invoice_number = $this->invoiceNumber;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $companyName = config('app.name', 'SinzoleDesigns');
        
        return new Envelope(
            subject: "Receipt #{$this->invoiceNumber} for your payment to {$companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'charge' => $this->charge,
                'receiptUrl' => $this->charge->receipt_url ?? null,
                'customerName' => $this->charge->customer_name ?? 'Valued Customer',
                'companyName' => config('app.name', 'SinzoleDesigns'),
                'companyEmail' => config('mail.from.address', 'support@sinzoledesigns.com'),
                'companyLogo' => asset('images/logo.png'),
                'paymentDate' => \Carbon\Carbon::createFromTimestamp($this->charge->created)->format('F j, Y'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // If we have a PDF invoice, we could attach it here
        return [];
    }
}
