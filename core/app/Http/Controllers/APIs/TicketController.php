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
    public function index(Request $request)
    {

    }

    public function ticketHold(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticketType' => 'required|string|max:255',
            'quantity' => 'required|integer|max:255',
            'seat' => 'required|integer|max:255',
            'date' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        print_r($request->all());
        die();
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();

        try {
            $response = Http::post($baseUrl.'/Pricing/TicketHold?authcode='.$authCode.'&date='.$request->date, [
                'SessionId' => '',
                'TicketHoldItem' => [
                    [
                        'ticketType' => $request->ticketType,
                        'quantity' => $request->quantity,
                        'seat' => $request->seat
                    ]
                ]
            ]);
            if ($response->successful()) {
                $apiData = $response->json();
                $sessionId = $apiData['sessionId'] ?? 0;
                $tickets = $apiData['data'] ?? [];
                $tickets = collect($tickets)->map(function ($ticket) use ($sessionId) {
                    $ticket['sessionId'] = $sessionId;
                    return $ticket;
                })->all();
            }
            if(count($tickets) > 0){
                return $this->sendResponse(200, 'Ticket hold successfully', $tickets);
            }else{
                return $this->sendResponse(400, 'Validation Error', ['seat' => ['No ticket available for this date occupancy is full']]);
            }

        } catch (\Exception $e) {
             return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }

        
    }
}
