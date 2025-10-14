<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Models\Email;

class MailHelper
{
    public static function orderConfirmationEmail($data,$identifier)
    {

        $allowedTypes = ['cabana', 'birthday', 'general_ticket', 'season_pass','offer_creation'];
        $get_mail_content = Email::where('identifier', $identifier)
            ->where('status', '1')
            ->first();

        if (!$get_mail_content) {
            \Log::error('Email template not found for identifier: ' . $data->identifier);
            return false;
        }
        
        try {
            $raw_content = $get_mail_content->content;
            $package = in_array($data->type, $allowedTypes) ? $data->{$data->type} : '';
            $ticketsHtml = '
                <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb" class="_table_with_border">
                    <tr>
                        <td style="padding:5px">Ticket</td>
                        <td style="padding:5px">Price</td>
                        <td style="padding:5px">Valid</td>
                        <td style="padding:5px">Scan</td>
                    </tr>';

                foreach ($data->purchases as $purchase) {
                    $ticketsHtml .= '
                    <tr>
                        <td style="padding:5px">' . ($purchase->ticketType ?? $purchase->description) . '</td>
                        <td style="padding:5px">$' . number_format($purchase->price, 2) . '</td>
                        <td style="padding:5px">' . ($purchase->valid_until ?? '-') . '</td>
                        <td style="padding:5px"> <img src="https://quickchart.io/qr?text='.$purchase->visualId.'&margin=2&size=150"
                                                        title="WildRiver Ticket QR Code" class="cabana_qr_code" /></td>
                    </tr>';
                }
            $ticketsHtml .= '</table>';
            $customerHtml = '
                <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb" class="_table_with_border">
                    <tr>
                        <td style="padding:5px">Name</td>
                        <td style="padding:5px">Email</td>
                        <td style="padding:5px">Phone</td>
                        <td style="padding:5px">Address</td>
                    </tr>
                    <tr>
                        <td style="padding:5px">' . $data->customer->name . '</td>
                        <td style="padding:5px">' . $data->customer->email . '</td>
                        <td style="padding:5px">' . $data->customer->phone . '</td>
                        <td style="padding:5px">' . $data->customerAddress . '</td>
                    </tr>';
            $customerHtml .= '</table>';
            $billingHtml = '
                <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb" class="_table_with_border">
                    <tr>
                        <td style="padding:5px">Gatway</td>
                        <td style="padding:5px">Transaction Id</td>
                        <td style="padding:5px">Date</td>
                        <td style="padding:5px">Amount</td>
                    </tr>
                    <tr>
                        <td style="padding:5px">Strip</td>
                        <td style="padding:5px">' . $data->transaction->transactionId . '</td>
                        <td style="padding:5px">' . $data->transaction->transactionDate . '</td>
                        <td style="padding:5px">$'.number_format($data->transaction->amount, 2).'</td>
                    </tr>';
            $billingHtml .= '</table>';
            $placeholders = [
                '{USER_NAME}'    => $data->customer->name ?? 'Customer',
                '{ORDER_NUMBER}' => $data->slug ?? '',
                '{SITE_NAME}'    => config('app.name'),
                '{PACKAGE}' => '<table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb"
                                        class="_table_with_border">
                                        <tr>
                                            <td style="padding:5px">Package</td>
                                            <td style="padding:5px">Total Ticket</td>
                                            <td style="padding:5px">Total</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:5px">'.$package->title.'</td>
                                            <td style="padding:5px">'.count($data->purchases).'</td>
                                            <td style="padding:5px">$'.$data->orderTotal.'</td>
                                        </tr>
                                    </table>',
                '{TICKETS}' => $ticketsHtml,
                '{CUSTOMER_INFORMATION}' => $customerHtml,
                '{BILLING_INFORMATION}' => $billingHtml
            ];
            $raw_content = preg_replace('/\{(?:<[^>]+>)*(\w+)(?:<\/[^>]+>)*\}/', '{$1}', $raw_content);
            $parsed_content = str_replace(array_keys($placeholders), array_values($placeholders), $raw_content);
            
            $email_subject = $get_mail_content->subject;
            $to_email = $data->customer->email;
            $from_email = config('mail.from.address');
            $from_name = 'WildRivers';
            $get_emails = array_map('trim', explode(',', $get_mail_content->to_reciever));
            if (!in_array($to_email, $get_emails)) {
                $get_emails[] = $to_email;
            }
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
            return true;
        } catch (\Exception $e) {
            \Log::error('Mail send failed: ' . $e->getMessage());
            return false;
        }
    }

}
