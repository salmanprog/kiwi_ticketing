<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Waiver;
use App\Http\Resources\WaiverResource;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;

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
        // 1️⃣ Validate request
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

        // 2️⃣ Config values
        $baseUrl  = 'https://dynamicpricing-api.dynamicpricingbuilder.com';
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $currentDate = now()->format('Y-m-d');

        // 3️⃣ Convert image to Base64
        $photoBase64 = base64_encode(
            file_get_contents($request->file('photo')->getRealPath())
        );
        // 4️⃣ Prepare payload (DO NOT json_encode)
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

                //$entriesWaiver->photo = $fileFinalName;
            }
        }
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
        $resource = WaiverResource::make($waiver);
        return $this->sendResponse(200, 'Waiver Form Submited successfully', $resource);
        // 6️⃣ Handle API validation errors (.NET style)
        // if ($response->failed()) {

        //     $messages = [];

        //     if (!empty($data['errors']) && is_array($data['errors'])) {
        //         foreach ($data['errors'] as $errors) {
        //             foreach ($errors as $error) {
        //                 $messages[] = $error;
        //             }
        //         }
        //     }

        //     return response()->json([
        //         'status' => $response->status(),
        //         'success' => false,
        //         'message' => 'Validation error',
        //         'errors' => $messages
        //     ], $response->status());
        // }

        // // 7️⃣ Success response
        // return response()->json([
        //     'status' => $response->status(),
        //     'success' => true,
        //     'data' => $data
        // ]);
    }
}
