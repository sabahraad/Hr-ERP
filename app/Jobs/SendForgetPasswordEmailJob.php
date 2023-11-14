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
            'subject' => 'Important Notification from MUTHOFUN Regarding SMS Circulation',
            'body' => 'Dear Partner,
        <br><br>
        <h1>'.$this->OTP.'</h1>
        Greetings from MUTHOFUN!
        <br><br>
        We hope this message finds you well. We would like to inform you that starting from May 9, 2023, our MUTHOFUN SMS platform has been successfully integrated with the MNP Dipping System. This integration is now mandatory for all our masking and non-masking services. As a result, there will be a price increase of 0.06 BDT per SMS, including VAT and tax, effective from May 16, 2023, at 00:00:01.
        <br><br>
        Please note that this price increase is in accordance with the BTRC Guideline and will apply to all our customers. The additional charge of 0.06 BDT per SMS will be paid to the MNP Dipping Service provider.
        <br><br>
        We appreciate your continued support. If you have any questions or concerns, please don\'t hesitate to reach out to us at support@muthofun.com.
        <br><br>
        Best regards,
        <br>
        MUTHOFUN Support Team',
        'from_email' => 'aamarPay <no-reply@aamarpay.com>',
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
