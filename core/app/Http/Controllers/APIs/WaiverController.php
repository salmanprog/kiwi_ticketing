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
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'qr_code' => 'required',
            'waiver_type' => 'required',
            'email' => 'required',
            'name' => 'required',
            'dob' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'photo' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl   = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode  = Helper::GeneralSiteSettings('auth_code_en');
        $current_date = now()->format('Y-m-d');
        // $waiver = Waiver::where('auth_code', $authCode)
        //     ->where('order_id', $request->order_id)
        //     ->where('qr_code', $request->qr_code)
        //     ->where('status', '1')
        //     ->first();

        // if ($waiver) {
        //     return $this->sendResponse(400, 'Waiver Error', [
        //         'coupon_code' => 'Waiver already exist in our record'
        //     ]);
        // }

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
                'parentPhone' => $request->parent_phone,
                'emergencyNumber' => $request->phone,

                'permission' => 0,
                'assumptionandacknowledgmentofallrisks' => 0,
                'releaseandwaiverofallclaims' => 0,
                'indemnity' => 0,
                'recreationalservices' => 0,
                'postedsignsandsafetyrules' => 0,
                'waiverandrelease' => 0,

                'date' => $current_date
            ],
            'signatureImage' => $photoBase64
        ];

        $body = json_encode($requestPayload);
        $response = Http::post('https://dynamicpricing-api.dynamicpricingbuilder.com/SeasonPassDashboardAPIs/AddWavierForm?authCode='.$authCode,$body);
        $data = $response->json();
        if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
            return $this->sendResponse(400, 'Order Error', ['error' => $data['status']['errorMessage']]);
        }else{
            return response()->json([
                'status' => $response->status(),
                'success' => $response->successful(),
                'data' => $response,
            ]);
        }
    }
}
