<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $url,$company_profile,$customer;

    public function __construct($customer, $url, $company_profile)
    {
        $this->url = $url;
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
        return $this->view('backend.email.forgot-password')
        ->subject('Hello, your account will be back soon')
        ->with([
            'url' => $this->url,
            'company_profile' => $this->company_profile,
            'customer' => $this->customer,
        ]);
    }
}
