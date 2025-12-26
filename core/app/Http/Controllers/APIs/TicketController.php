<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CabanaResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Helper;

class TicketController extends BaseAPIController
{
    public function GetCabanaOccupancy(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'cabanaType' => 'required|string|max:255',
            'date' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::get($baseUrl.'/Pricing/GetCabanaOccupancy?cabanaType='.$request->cabanaType.'&authcode='.$authCode.'&date='.$request->date);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
                return $this->sendResponse(400, 'Cabana occupancy Error', ['error' => $data['status']['errorMessage']]);
            }
            return $this->sendResponse(200, 'Cabana occupancy fetched successfully', $data['data']);

        } catch (\Exception $e) {
             return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }

    public function ticketHold(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticketType' => 'required|string|max:255',
            'quantity' => 'required|integer|max:255',
            //'seat' => 'required|integer|max:255',
            'date' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::post($baseUrl.'/Pricing/TicketHold?authcode='.$authCode.'&date='.$request->date, [
                'SessionId' => '',
                'OrderId' => '',
                "OrderSource" => null,
                'TicketHoldItem' => [
                    [
                        'ticketType' => $request->ticketType,
                        'quantity' => $request->quantity,
                        'seat' => isset($request->seat) ? $request->seat : ""
                    ]
                ]
            ]);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
                return $this->sendResponse(400, 'Ticket Error', ['error' => $data['status']['errorMessage']]);
            }
            $ticketData = $data['data'][0];
            $ticketData['sessionId'] = $data['sessionId'];
            return $this->sendResponse(200, 'Ticket Hold successfully', $ticketData);

        } catch (\Exception $e) {
             return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }

        
    }

    public function GetCalendar(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'venue_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::get($baseUrl.'/Pricing/GetPricingCalendar?Authcode='.$authCode.'&venue_id='.$request->venue_id);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
                return $this->sendResponse(400, 'Calendar Error', ['error' => $data['status']['errorMessage']]);
            }
            return $this->sendResponse(200, 'Calendar fetched successfully', $data['data']);

        } catch (\Exception $e) {
             return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }

    public function GetFacilitySchedule(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');

        try {
            $response = Http::get('https://dynamicpricing-api.dynamicpricingbuilder.com/Pricing/GetFacilitySchedule?Authcode='.$authCode.'&date='.$request->date);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] == 1) {
                return $this->sendResponse(400, 'FacilitySchedule Error', ['error' => $data['status']['errorMessage']]);
            }
            return $this->sendResponse(200, 'FacilitySchedule fetched successfully', $data['data']);

        } catch (\Exception $e) {
             return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }
}
