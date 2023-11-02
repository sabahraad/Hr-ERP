<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\forgetPasswordEmail;
use Illuminate\Support\Facades\Mail;

class SendForgetPasswordEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerEmail;
    protected $customerName;
    /**
     * Create a new job instance.
     */
    public function __construct($customerEmail,$customerName)
    {
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $data = [
            'customerName' => $this->customerName,
            'customerEmail' => $this->customerEmail,
        ];
        Mail::to($this->customerEmail)->send(new forgetPasswordEmail($data));
    }
}
