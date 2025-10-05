<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\OfferCreation;
use App\Models\OfferAddon;
use App\Http\Resources\OfferCreationResource;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;

class OfferCreationController extends BaseAPIController
{
    public function index()
    {
        
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = OfferCreation::with(['media_slider','addons'])->where('auth_code',$authCode)->where('status','1')->get();
         if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Offers Listing', []);
        }
        $resource = OfferCreationResource::collection($generalTicket);
        return $this->sendResponse(200, 'Retrieved Offers Listing', $resource);
    }

    public function offerAddon($slug)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = OfferAddon::with(['media_slider'])->where('offerSlug', $slug)->where('status', '1')->where('auth_code',$authCode)->get();
        
        if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Offers Addon Listing', []);
        }
        try {
            $response = Http::get("{$baseUrl}/Pricing/GetAllProductPrice", [
                'authcode' => $authCode,
                'date' => $date,
            ]);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] === 1) {
                return $this->sendResponse(400, 'Offers Error', ['error' => $data['status']['errorMessage']]);
            }
            $tickets = $data['getAllProductPrice']['data'] ?? [];
            $filteredTickets = [];
            if (count($tickets) > 0) {
                $ticketSlugs = array_column($tickets, 'ticketSlug');
                $generalTickets = OfferAddon::with(['media_slider'])
                    ->where('auth_code', $authCode)
                    ->where('offerSlug', $slug)
                    ->whereIn('ticketSlug', $ticketSlugs)
                    ->get()
                    ->keyBy('ticketSlug');
                
                foreach ($tickets as &$ticket) {
                    if (
                        isset($generalTickets[$ticket['ticketSlug']])
                    ) {
                        $generalTicket = $generalTickets[$ticket['ticketSlug']];
                        $ticket['description'] = $generalTicket->description;
                        $ticket['is_offer'] = ($generalTicket->is_offer == '0') ? 'specific_date_offer' : 'any_day';
                        $ticket['is_featured'] = $generalTicket->is_featured;
                        if ($generalTicket->media_slider && $generalTicket->media_slider->isNotEmpty()) {
                            $ticket['image_url'] = url($generalTicket->media_slider->first()->file_url);
                        }

                        $filteredTickets[] = $ticket;
                    }
                }
            }
            return $this->sendResponse(200, 'Offers Addon fetched successfully', $filteredTickets);
        } catch (\Exception $e) {
            return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }
    
}
