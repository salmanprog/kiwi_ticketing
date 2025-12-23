<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use App\Models\Email;
use App\Models\EmailLogs;
use Carbon\Carbon;

class MailHelper
{
    public static function orderConfirmationEmail($data,$identifier)
    {

        $allowedTypes = ['cabana', 'birthday', 'general_ticket', 'season_pass','offer_creation', 'product_sale'];
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
                <table border="1" width="100%" cellspacing="0" cellpadding="0" bordercolor="#cbcbcb">
                    <tr>
                        <td style="padding:5px">Product(s)</td>
                        <td style="padding:5px">Quantity</td>
                        <td style="padding:5px">Price</td>
                    </tr>';

                foreach ($data->purchases as $purchase) {
                    $ticketsHtml .= '
                    <tr>
                        <td style="padding:5px">' . ($purchase->ticketType ?? $purchase->description) . '</td>
                        <td style="padding:5px">' . $purchase->quantity . '</td>
                        <td style="padding:5px">$' . number_format($purchase->price, 2) . '</td>
                    </tr>';
                }
                $ticketsHtml .= '
                        <tr>
                            <td colspan="2" style="padding:5px">
                                <strong>Tax 8.25%</strong>
                            </td>
                            <td style="padding:5px">
                                $'.number_format($data->tax, 2).'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:5px">
                                <strong>Service Fee 2.75%</strong>
                            </td>
                            <td style="padding:5px">
                                $'.number_format($data->serviceCharges, 2).'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding:5px">
                                <strong>Total:</strong>
                            </td>
                            <td style="padding:5px">
                                $'.$data->orderTotal.'
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:5px" colspan="3">
                                <ul class="_ticket_code" style="text-align:center;padding: 0;list-style: none;">';
                                 foreach ($data->purchases as $purchase) {
                                    $ticketsHtml .= '
                                    <li style="margin: 0; border-bottom: 1px solid #b2b2b2; padding-bottom: 5px;">
                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                        <h5>Ticket
                                            - '.$purchase->ticketType.'
                                        </h5>
                                        <img src="https://quickchart.io/qr?text='.$purchase->visualId.'&margin=2&size=100"
                                                        title="Bolder Ticket QR Code" class="cabana_qr_code" />
                                        <p style="margin: 0;">'.$purchase->visualId.' </p>
                                        
                                            '.$purchase->ticketType.' - '.$purchase->ticketDisplayDate.'

                                        <br />
                                        <br />
                                        <br />
                                        <br />
                                    </li>';
                                }   
                                '</ul>
                            </td>
                        </tr>
                ';
            $ticketsHtml .= '</table>';
            $customerHtml = '
                <table border="0" style="border: 1px solid #ececec;" width="100%">
                <h4>Customer Information</h4>
                    <p>
                        ' . $data->customer->name . ' <br />
                        ' . $data->customer->email . ' <br />
                        ' . $data->customer->phone . ' <br />
                        ' . $data->customerAddress . '<br />
                    </p>';
            $customerHtml .= '</table>';
            $billingHtml = '
                <table border="0" style="border: 1px solid #ececec;" width="100%">
                <h4>Billing Information</h4>
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
            $billingHtml .= '</table><table border="0" style="border: 1px solid #ececec;" width="100%">
                                <tr>
                                    <td>
                                        <h3>Terms & Conditions</h3>
                                        <p style="font-size: 10px;">
                                            1. No Refunds. You fully ASSUME ALL RISK whatsoever in relation to your park activities and experience. By entering Park, you automatically and fully waive any and all claims of any nature whatsoever, including without limitation for personal injuries, inadequate security or warnings, theft/damage of property, weather-related claims, acts of 3rd parties, and illnesses (air, food, water-borne etc…)
                                        </p>
                                        <p style="font-size: 10px;">
                                            2. You (including anyone under your care/supervision) must comply with all safety rules, warning signs and instructions. Parents/Guardians are solely responsible for supervision of minors or others needing special care. No resale, ”rain checks”, exchanges or refunds (including for loss, theft or expired date). SAFETY 1st! Act responsibly.
                                        </p>
                                        <p style="font-size: 10px;">
                                            3. Days/hours and ride/attraction availability are subject to change without notice. Certain rides, shows, attractions and special events, may require an additional charge or be subject to heigh, weight, or other restrictions at Park’s discretion. Park may deny admission or expel anyone, without refund, for: illegality, aggressive/disorderly conduct or threats, Park rule violation, drugs/alcohol (including marijuana), offensive conduct (e.g., inappropriate or inadequate clothing, markings/tattoos, gestures, language, etc…), possession of a firearm or any weapon-like device, line-cutting, stealing, property damage, or anything else which the park deems a threat to the welfare/safety of people, wildlife/animals or Park property.
                                        </p>
                                        <p style="font-size: 10px;">
                                            4. This ticket is valid in 2025 only.
                                        </p>
                                    </td>
                                </tr>
                            </table>';
            $placeholders = [
                '{USER_NAME}'    => $data->customer->name ?? 'Customer',
                '{ORDER_NUMBER}' => $data->slug ?? '',
                '{ORDER_DATE}'   => Carbon::parse($orderDate)->format('Y-m-d'),
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
            $from_name = 'BolderAdventurePark';
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
            EmailLogs::create([
                'order_number' => strtoupper($data->slug),
                'email'        => $data->customer->email,
                'identifier'   => $identifier,
                'subject'      => $email_subject,
                'content'      => $parsed_content,
                'status'       => '1',
            ]);
            return true;
        } catch (\Exception $e) {
            $email_subject = $get_mail_content->subject;
            \Log::error('Mail send failed: ' . $e->getMessage());
            EmailLogs::create([
                'order_number' => strtoupper($data->slug),
                'email'        => $data->customer->email,
                'identifier'   => $identifier,
                'subject'      => $email_subject,
                'content'      => $parsed_content,
                'status'       => '0',
            ]);
            return false;
        }
    }

}
