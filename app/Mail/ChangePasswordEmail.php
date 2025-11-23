<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangePasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $company_profile,$customer;

    public function __construct($customer, $company_profile)
    {
        $this->company_profile = $company_profile;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('frontend.email.change-password')
        ->subject('Hello, your account password has changed.')
        ->with([
            'company_profile' => $this->company_profile,
            'customer' => $this->customer,
        ]);
    }
}
