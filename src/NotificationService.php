<?php

namespace Saasscaleup\NPlusOneDetector;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class NotificationService
{

    /**
     * Send notification to Slack, Webhook and email.
     *
     * @param string $message The message to be sent.
     * @return void
     */
    public static function send($message){
        // Send Slack notification
        /**
         * Send a notification to Slack.
         *
         * @param string $message The message to be sent.
         * @return void
         */
        self::sendSlackNotification($message);

        // Send Webhook notification
        /**
         * Send a notification to Slack.
         *
         * @param string $message The message to be sent.
         * @return void
         */
        self::sendWebhookNotification($message);
        
        // Send email notification
        /**
         * Send a notification to email.
         *
         * @param string $message The message to be sent.
         * @return void
         */
        self::sendEmailNotification($message);

        
    }


    /**
     * Send a notification to Slack.
     *
     * This function sends a message to a Slack channel via a webhook URL.
     *
     * @param string $message The message to be sent.
     * @return void
     */
    public static function sendSlackNotification($message)
    {
        // Get the webhook URL from the configuration
        $webhookUrl = config('n-plus-one.slack_webhook_url');

        // If the webhook URL is not configured, return without sending the notification
        if (empty($webhookUrl)) {
            return;
        }

        // Prepare the data to be sent in the request body
        // $data = [
        //     'subject' => config('n-plus-one.notification_email_subject'),
        //     'text' => $message
        // ];

        $data = [
            'text' => config('n-plus-one.notification_email_subject') ,
            'attachments' => [
                [
                    'title' => config('n-plus-one.notification_email_subject'),
                    'text' => $message,
                    'color' => '#FFA500',
                    'fields' => [
                        [
                            'title' => 'Priority',
                            'value' => 'High',
                            'short' => true
                        ]
                    ]
                ]
            ]
        ];

        // Initialize the cURL session
        $ch = curl_init($webhookUrl);

        // Set the request method, request body, and options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);

        // Execute the request and get the response
        $result = curl_exec($ch);

        // Close the cURL session
        curl_close($ch);

        // If the request failed, log the error
        if ($result === false) {
            Log::info("NPlusOneDetector::sendSlackNotification->error: " . curl_error($ch));
        }
    }

    /**
     * Send a notification to Slack.
     *
     * This function sends a message to a Slack channel via a webhook URL.
     *
     * @param string $message The message to be sent.
     * @return void
     */
    public static function sendWebhookNotification($message)
    {
        // Get the webhook URL from the configuration
        $webhookUrl = config('n-plus-one.custom_webhook_url');

        // If the webhook URL is not configured, return without sending the notification
        if (empty($webhookUrl)) {
            return;
        }

        // Prepare the data to be sent in the request body
        $data = [
            'subject' => config('n-plus-one.notification_email_subject'),
            'message' => $message
        ];

        // Initialize the cURL session
        $ch = curl_init($webhookUrl);

        // Set the request method, request body, and options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);

        // Execute the request and get the response
        $result = curl_exec($ch);

        // Close the cURL session
        curl_close($ch);

        // If the request failed, log the error
        if ($result === false) {
            Log::info("NPlusOneDetector::sendWebhookNotification->error: " . curl_error($ch));
        }
    }

    /**
     * Send a notification via email.
     *
     * This function sends a message via email to the recipients specified in the configuration.
     *
     * @param string $message The message to be sent.
     * @return void
     */
    public static function sendEmailNotification($message)
    {
        // Get the email addresses to send the notification to
        $to = config('n-plus-one.notification_email');

        // If no email addresses are configured, return without sending the notification
        if (empty($to)) {
            return;
        }

        // Split the email addresses into an array
        $to_emails = explode(',', $to);

        try {
            // Send the email notification
            Mail::send('', ['message' => $message], function ($msg) use ($to_emails) {
                // Set the recipients and subject of the email
                $msg->to($to_emails)->subject(config('n-plus-one.notification_email_subject'));
            });
        } catch (\Exception $e) {
            // If the email sending fails, log the error
            Log::info("NPlusOneDetector::sendEmailNotification->error: " . $e->getMessage());
        }
    }
}