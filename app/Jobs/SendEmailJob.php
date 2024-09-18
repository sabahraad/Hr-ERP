<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $adminEmail;
    protected $directorEmail;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->directorEmail = User::where('role', 6)->value('email');
        $this->adminEmail = User::where('role', 4)->value('email');
        // $this->directorEmail = "rayhan@aamarpay.com";
        // $this->adminEmail = "raad@aamarpay.com";
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //admin mail

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
            'subject' => 'Requistion Request Email',
            'body' => 'Dear Admin,
            <br><br>
            Greetings from Time Wise!
            <br>
            <p>A new requisition request has been submitted by a user in the system.</p>
            <br>
            <p>Please review the details of the requisition in the dashboard and take the necessary actions.</p>
            <br>
            We appreciate your continued support. If you have any questions or concerns, please don\'t hesitate to reach out to us at support@timewise.com.
            <br>
            Best regards,
            <br>
            Time Wise Support Team',
            'from_email' => 'aamarPay <no-reply@aamarPay.com>',
            'to' => $this->adminEmail,
            'template' => '8'
        ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token 85381a6763049657a2bdcb3ecafcc8822258cffe'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            //director mail

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
                'subject' => 'Requistion Request Email',
                'body' => 'Dear Admin,
                <br><br>
                Greetings from Time Wise!
                <br>
                <p>A new requisition request has been submitted by a user in the system.</p>
                <br>
                <p>Please review the details of the requisition in the dashboard and take the necessary actions.</p>
                <br>
                We appreciate your continued support. If you have any questions or concerns, please don\'t hesitate to reach out to us at support@timewise.com.
                <br>
                Best regards,
                <br>
                Time Wise Support Team',
                'from_email' => 'aamarPay <no-reply@aamarPay.com>',
                'to' => $this->directorEmail,
                'template' => '8'
            ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token 85381a6763049657a2bdcb3ecafcc8822258cffe'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
    }
}
