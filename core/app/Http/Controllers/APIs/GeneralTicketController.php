<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GeneralTickets;
use App\Models\BirthdayCabanas;
use App\Http\Resources\GeneralTicketsResource;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;

class GeneralTicketController extends BaseAPIController
{
    public function index()
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = GeneralTickets::with(['media_slider','addons','cabanas'])->where('auth_code',$authCode)->get();
        
        if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved General Tickets Listing', []);
        }

        try {
            $response = Http::get("{$baseUrl}/Pricing/GetAllProductPrice", [
                'authcode' => $authCode,
                'date' => $date,
            ]);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] === 1) {
                return $this->sendResponse(400, 'General Ticket Error', ['error' => $data['status']['errorMessage']]);
            }
            $tickets = $data['getAllProductPrice']['data'] ?? [];
            $filteredTickets = [];
            if (count($tickets) > 0) {
                $ticketSlugs = array_column($tickets, 'ticketSlug');
                $generalTickets = GeneralTickets::with(['media_slider','addons','cabanas'])
                    ->where('auth_code', $authCode)
                    ->whereIn('ticketSlug', $ticketSlugs)
                    ->get()
                    ->keyBy('ticketSlug');
                
                foreach ($tickets as &$ticket) {
                    if ($ticket['ticketCategory'] === 'Ticket') {
                        $generalTicket = $generalTickets->get($ticket['ticketSlug']);
                        if ($generalTicket) {
                            $ticket['description'] = $generalTicket->description;
                            if ($generalTicket->media_slider && count($generalTicket->media_slider) > 0) {
                                $ticket['image_url'] =  url($generalTicket->media_slider->first()->file_url);
                            }
                        }
                        $filteredTickets[] = $ticket;
                    }
                }
            }
            return $this->sendResponse(200, 'General Ticket fetched successfully', $filteredTickets);
        } catch (\Exception $e) {
            return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }
}
