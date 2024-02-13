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
    protected $OTP;
    /**
     * Create a new job instance.
     */
    public function __construct($customerEmail,$OTP)
    {
        $this->customerEmail = $customerEmail;
        $this->OTP = $OTP;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // $data = [
        //     'OTP' => $this->OTP,
        //     'customerEmail' => $this->customerEmail,
        // ];
        // Mail::to($this->customerEmail)->send(new forgetPasswordEmail($data));

        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://esm.aamarpay.com/email/api/v1/send-email/AWSAAMARPAY/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'subject' => 'OTP Email',
            'body' => 'Dear User,
        <br><br>
        Greetings from Time Wise!
        <br>
        <p>Your One-Time Password (OTP) is: <strong>'.$this->OTP.'</strong></p>
        <br>
        <p>This OTP is valid for<strong> 2 minutes</strong></p>
        <br>
        We appreciate your continued support. If you have any questions or concerns, please don\'t hesitate to reach out to us at support@timewise.com.
        <br>
        Best regards,
        <br>
        Time Wise Support Team',
        'from_email' => 'aamarPay <no-reply@aamarPay.com>',
        'to' => $this->customerEmail,
        'template' => '8'
    ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Token 85381a6763049657a2bdcb3ecafcc8822258cffe'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // $response = json_decode($response,true);
        
        // echo $response;
    // $response = 'This is the response from the job'; // Example response

    // return $response;
    }
}
