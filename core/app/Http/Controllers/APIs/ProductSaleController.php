<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ProductSale;
use App\Models\ProductSaleAddon;
use App\Http\Resources\ProductSaleResource;
use Carbon\Carbon;
use Helper;
use Illuminate\Support\Facades\Http;

class ProductSaleController extends BaseAPIController
{
    public function index()
    {
        
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $today = Carbon::today();
        //$generalTicket = OfferCreation::with(['media_slider','addons'])->where('auth_code',$authCode)->where('status','1')->get();
        $generalTicket = ProductSale::with(['media_slider', 'addons'])
    ->where('auth_code', $authCode)
    ->where('status', '1')
    ->where(function ($query) use ($today) {
        $query->whereNull('from_date')
              ->orWhere('from_date', '<=', $today);
    })
    ->where(function ($query) use ($today) {
        $query->whereNull('to_date')
              ->orWhere('to_date', '>=', $today);
    })
    ->get();
         if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Product Sale Listing', []);
        }
        $resource = ProductSaleResource::collection($generalTicket);
        return $this->sendResponse(200, 'Retrieved Product Sale Listing', $resource);
    }

    public function offerAddon(Request $request,$slug)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }
        $params = $request->all();
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = ProductSaleAddon::with(['media_slider'])->where('offerSlug', $slug)->where('status', '1')->where('auth_code',$authCode)->get();
        
        if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Product Sale Addon Listing', []);
        }
        try {
            $response = Http::get("{$baseUrl}/Pricing/GetAllProductPrice", [
                'authcode' => $authCode,
                'date' => $params['date'],
            ]);
            $data = $response->json();
            if (isset($data['status']['errorCode']) && $data['status']['errorCode'] === 1) {
                return $this->sendResponse(400, 'Offers Error', ['error' => $data['status']['errorMessage']]);
            }
            $tickets = $data['getAllProductPrice']['data'] ?? [];
            $tickets = array_map('mapTicketName', $tickets);
            $filteredTickets = [];
            if (count($tickets) > 0) {
                $ticketSlugs = array_column($tickets, 'ticketSlug');
                $generalTickets = ProductSaleAddon::with(['media_slider'])
                    ->where('auth_code', $authCode)
                    ->where('offerSlug', $slug)
                    ->whereIn('ticketSlug', $ticketSlugs)
                    ->where('status', '1')
                    ->get()
                    ->keyBy('ticketSlug');
                
                foreach ($tickets as &$ticket) {
                    if (
                        isset($generalTickets[$ticket['ticketSlug']])
                    ) {
                        $generalTicket = $generalTickets[$ticket['ticketSlug']];
                        $ticket['description'] = $generalTicket->description;
                        $ticket['is_featured'] = $generalTicket->is_featured;
                        if ($generalTicket->media_slider && $generalTicket->media_slider->isNotEmpty()) {
                            $ticket['image_url'] = url($generalTicket->media_slider->first()->file_url);
                        }

                        $filteredTickets[] = $ticket;
                    }
                }
            }
            return $this->sendResponse(200, 'Product Sale Addon fetched successfully', $filteredTickets);
        } catch (\Exception $e) {
            return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }
    
}
