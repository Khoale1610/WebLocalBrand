<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromAddress = config('mail.from.address') ?: 'shop@weblocalbrand.vn';
        $fromName = config('mail.from.name') ?: config('app.name', 'WebLocalBrand');

        return $this->from($fromAddress, $fromName)
                    ->subject('Xác nhận đặt hàng thành công #' . $this->order->order_code . ' - WebLocalBrand')
                    ->view('emails.order-confirmation');
    }
}
