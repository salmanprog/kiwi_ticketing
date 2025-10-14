<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;

class MailHelper
{
    public static function sendEmail($data)
    {
        try {
            $email_subject = 'Hello - ' . config('app.name');
            $email_body = 'this is testing';

            $to_email = $data->mail_to;
            $from_email = $data->mail_to;
            $from_name = config('app.name');

            Mail::send('emails.template', [
                'title' => $email_subject,
                'details' => $email_body
            ], function ($message) use ($email_subject, $to_email, $from_email, $from_name) {
                $message->from($from_email, $from_name);
                $message->to($to_email);
                $message->replyTo($from_email, $from_name);
                $message->subject($email_subject);
            });

            return true;
        } catch (\Exception $e) {
            \Log::error('Mail send failed: ' . $e->getMessage());
            return false;
        }
    }
}
