<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CabanaResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Helper;
use Illuminate\Support\Facades\Mail;
use App\Helpers\MailHelper;
use App\Models\Email;

class EmailController extends BaseAPIController
{
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:255',
            'body_content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        try {
            $get_mail_content = Email::where('identifier', $request->identifier)
            ->where('status', '1')
            ->first();
            $raw_content = $get_mail_content->content;
            $placeholders = [
                '{ERROR_INFORMATION}'    => $request->body_content ?? 'No Issue Found'
            ];
            $raw_content = preg_replace('/\{(?:<[^>]+>)*(\w+)(?:<\/[^>]+>)*\}/', '{$1}', $raw_content);
            $parsed_content = str_replace(array_keys($placeholders), array_values($placeholders), $raw_content);

            $email_subject = $get_mail_content->subject;
            $from_email = config('mail.from.address');
            $from_name = 'WildRivers';
            $get_emails = array_map('trim', explode(',', $get_mail_content->to_reciever));
            foreach ($get_emails as $email) {
                Mail::send('emails.template', [
                    'title' => $email_subject,
                    'details' => $parsed_content
                ], function ($message) use ($email_subject, $email, $from_email, $from_name) {
                    $message->from($from_email, $from_name);
                    $message->to($email);
                    $message->replyTo($from_email, $from_name);
                    $message->subject($email_subject);
                });
            }

            return $this->sendResponse(200, 'Email sent successfully.');

        } catch (\Exception $e) {
            return $this->sendResponse(500, 'Server Error', $e->getMessage());
        }
    }
}
