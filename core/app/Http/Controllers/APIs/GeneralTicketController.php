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
        $generalTicket = GeneralTickets::with(['media_slider'])->where('auth_code',$authCode)->where('status','1')->get();
        
        if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved General Tickets Listing', []);
        }

        $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
        $filteredTickets = [];

        if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];

            foreach ($generalTicket as $package) {
                $ticketSlugs = BirthdayCabanas::where('birthday_package_id', $package->id)
                    ->pluck('ticketSlug')
                    ->toArray();
                $matchedTickets = collect($tickets)->filter(function ($ticket) use ($ticketSlugs) {
                    return in_array($ticket['ticketSlug'], $ticketSlugs);
                })->values()->all();
                $package->filteredTickets = $matchedTickets;
            }
        }

        if (empty($filteredTickets)) {
            $filteredTickets = [];
        }

        $resource = BirthdayPackagesResource::collection($birthday->load(['cabanas', 'media_slider', 'media_cover']))
    ->additional(['tickets' => $filteredTickets]);
        return $this->sendResponse(200, 'Retrieved Birthday Listing', $resource);
        
    }

    public function store(Request $request)
    {
    }
}
