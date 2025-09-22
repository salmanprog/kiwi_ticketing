<?php

namespace App\Http\Controllers\APIs;

use App\Models\Setting;
use App\Models\SeasonPass;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;

class SeasonPassController extends BaseAPIController
{
    public function index()
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = SeasonPass::with(['media_slider'])->where('auth_code',$authCode)->get();
        
        if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Season Pass Listing', []);
        }

        try {
            $response = Http::get("{$baseUrl}/Pricing/GetAllProductPrice", [
                'authcode' => $authCode,
                'date' => $date,
            ]);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] === 1) {
                return $this->sendResponse(400, 'Season Pass Error', ['error' => $data['status']['errorMessage']]);
            }
            $tickets = $data['getAllProductPrice']['data'] ?? [];
            $filteredTickets = [];
            if (count($tickets) > 0) {
                $ticketSlugs = array_column($tickets, 'ticketSlug');
                $generalTickets = SeasonPass::with(['media_slider'])
                    ->where('auth_code', $authCode)
                    ->whereIn('ticketSlug', $ticketSlugs)
                    ->get()
                    ->keyBy('ticketSlug');
                
                foreach ($tickets as &$ticket) {
                    $generalTicket = $generalTickets->get($ticket['ticketSlug']);
                    if ($generalTicket) {
                        $ticket['description'] = $generalTicket->description;
                        $ticket['is_parking_pass'] = $generalTicket->is_parking_pass;
                        if ($generalTicket->media_slider && $generalTicket->media_slider->count() > 0) {
                            $ticket['image_url'] = url($generalTicket->media_slider->first()->file_url);
                        }

                        $filteredTickets[] = $ticket;
                    }
                }

            }
            return $this->sendResponse(200, 'Season Pass fetched successfully', $filteredTickets);
        } catch (\Exception $e) {
            return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }
}
