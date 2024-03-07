<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaid extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Event The event for which tickets were purchased.
     */
    public Event $event;

    /**
     * @var \App\Models\Order The order containing the tickets purchased.
     */
    public Order $order;

    public $url;
    public $room_url;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Event $event
     * @param \App\Models\Order $order
     */
    public function __construct(Event $event, Order $order)
    {
        $this->event    = $event;
        $this->order    = $order;
        $this->url      = route('orders.show', $order);
        $this->room_url = "https://www.hilton.com/en/attend-my-event/edidudi-90t-38063552-4d54-440b-960f-0e6cff53899d/";
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('president@scottishskiclub.org.uk', 'Scottish Ski Club'),
            subject: 'Your Tickets for ' . $this->event->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.orders.paid',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
