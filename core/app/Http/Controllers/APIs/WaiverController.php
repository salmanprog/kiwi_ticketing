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
        $waiver = Waiver::where('auth_code',$authCode)->where('status','1')->get();
        
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
            'zip_code' => 'required',
            'photo' => 'required|image'
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

        // 5️⃣ Send request (IMPORTANT PART)
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post(
            $baseUrl . '/SeasonPassDashboardAPIs/AddWavierForm?authCode=' . $authCode,
            $requestPayload
        );

        $data = $response->json();

        // 6️⃣ Handle API validation errors (.NET style)
        if ($response->failed()) {

            $messages = [];

            if (!empty($data['errors']) && is_array($data['errors'])) {
                foreach ($data['errors'] as $errors) {
                    foreach ($errors as $error) {
                        $messages[] = $error;
                    }
                }
            }

            return response()->json([
                'status' => $response->status(),
                'success' => false,
                'message' => 'Validation error',
                'errors' => $messages
            ], $response->status());
        }

        // 7️⃣ Success response
        return response()->json([
            'status' => $response->status(),
            'success' => true,
            'data' => $data
        ]);
    }
}
