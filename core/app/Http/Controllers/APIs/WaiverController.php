<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Waiver;
use App\Models\OrderTickets;
use App\Http\Resources\WaiverResource;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Helpers\MailHelper;
use App\Models\Email;

class WaiverController extends BaseAPIController
{
    public function index()
    {
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $waiver = Waiver::with(['media_slider'])->where('auth_code',$authCode)->where('status','1')->get();
        
        if ($waiver->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved waiver Listing', []);
        }

        $resource = WaiverResource::collection($waiver);
        return $this->sendResponse(200, 'Retrieved waiver Listing', $resource);
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'qr_code' => 'required',
            'waiver_type' => 'required',
            'email' => 'required|email',
            'name' => 'required',
            'dob' => 'required|date',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $existingWaiver = Waiver::where('order_id', $request->order_id)
                            ->orWhere('qr_code', $request->qr_code)
                            ->first();

        // if ($existingWaiver) {
        //     return $this->sendResponse(400, 'Waiver Error', ['error' => 'Waiver for this Order ID or QR Code is already submitted']);
        // }
        $baseUrl  = 'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com';
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $currentDate = now()->format('Y-m-d');
        $photoBase64 = base64_encode(
            file_get_contents($request->file('photo')->getRealPath())
        );
        $requestPayload = [
            'organization' => [
                'formName' => $request->name,
                'organizationName' => 'BOLDER ADVENTURE PARK',
                'qrCode' => $request->qr_code,
                'name' => $request->name,
                'dob' => $request->dob,
                'parentName' => $request->parent_name,
                'parentAddress' => $request->parent_address,
                'streetAddress' => $request->street_address,
                'city' => $request->city,
                'state' => $request->state,
                'zipCode' => $request->zip_code,
                'parentPhone' => isset($request->parent_phone) ? $request->parent_phone : "",
                'emergencyNumber' => $request->phone,

                // .NET expects int, not bool
                'permission' => 0,
                'assumptionandacknowledgmentofallrisks' => 0,
                'releaseandwaiverofallclaims' => 0,
                'indemnity' => 0,
                'recreationalservices' => 0,
                'postedsignsandsafetyrules' => 0,
                'waiverandrelease' => 0,

                'date' => $currentDate
            ],
            'signatureImage' => $photoBase64
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(
            $baseUrl . '/SeasonPassDashboardAPIs/AddWavierForm?authCode=' . $authCode,
            $requestPayload
        );

        $data = $response->json();

        if ($response->failed()) {
            return response()->json([
                'code'    => 500,
                'message' => 'API Error',
                'data'    => [],
            ], 500);
        }

        if (!empty($data['status']['errorCode']) && $data['status']['errorCode'] != 0) {
            $field = 'qr_code';
            return response()->json([
                'code'    => 400,
                'message' => 'Validation Error',
                'data'    => [
                    $field => [
                        $data['status']['errorMessage']
                    ]
                ],
            ], 400);
        }

        $uploadPath = 'uploads/waivers/';

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            if ($file->isValid()) {
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $fileFinalName = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadPath, $fileFinalName);

                Helper::imageResize($uploadPath . $fileFinalName);
                Helper::imageOptimize($uploadPath . $fileFinalName);

            }
        }
        $existing_ticket = OrderTickets::where('order_id', $request->order_id)
                            ->where('visualId', $request->qr_code)
                            ->first();
        print_r($existing_ticket);
        die();
        if (!$existing_ticket) {
            return response()->json([
                'code'    => 400,
                'message' => 'Validation Error',
                'data'    => [
                    'qr_code' => [
                        'Record not found against this QrCode'
                    ]
                ],
            ], 400);
        }   
        $existing_ticket->isWavierFormSubmitted = 1;
        $existing_ticket->wavierSubmittedDateTime = now(); // better than 1
        $existing_ticket->save();
        
        $entriesWaiver = new Waiver;
        $entriesWaiver->auth_code  = Helper::GeneralSiteSettings('auth_code_en');
        $entriesWaiver->order_id  = $request->order_id;
        $entriesWaiver->qr_code = $request->qr_code;
        $entriesWaiver->waiver_type  = $request->waiver_type;
        $entriesWaiver->email = $request->email;
        $entriesWaiver->name = $request->name;
        $entriesWaiver->dob = $request->dob;
        $entriesWaiver->phone = $request->phone;
        $entriesWaiver->street_address = $request->street_address;
        $entriesWaiver->city = $request->city;
        $entriesWaiver->state = $request->state;
        $entriesWaiver->zip_code = $request->zip_code;
        $entriesWaiver->photo = $fileFinalName;
        $entriesWaiver->parent_name = $request->parent_name;
        $entriesWaiver->parent_address = $request->parent_address;
        $entriesWaiver->parent_phone = $request->parent_phone;
        $entriesWaiver->status = '1';
        $entriesWaiver->save();
        
        

        $waiver = Waiver::with(['media_slider'])->where('id',$entriesWaiver->id)->where('status','1')->first();

        //Send Email
        $get_mail_content = Email::where('identifier', 'waiver_email_confirmation')
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
        $from_name = 'BolderAdventurePark';
        $to_email = $request->email;
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
        $resource = WaiverResource::make($waiver);
        return $this->sendResponse(200, 'Waiver Form Submited successfully', $resource);
    }
}
