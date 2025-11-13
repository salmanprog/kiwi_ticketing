<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BirthdayPackages;
use App\Models\BirthdayCabanas;
use App\Http\Resources\BirthdayPackagesResource;
use Carbon\Carbon;
use Helper;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Http;

class BirthdayController extends BaseAPIController
{
    public function index()
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $birthday = BirthdayPackages::with(['cabanas','media_slider','media_cover', 'addons'])->where('status','1')->get();
        
        if ($birthday->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Birthday Listing', []);
        }

        $response = Http::get($baseUrl.'/Pricing/GetAllProductPrice?authcode='.$authCode.'&date='.$date);
        $filteredTickets = [];

        if ($response->successful()) {
            $apiData = $response->json();
            $tickets = $apiData['getAllProductPrice']['data'] ?? [];

            foreach ($birthday as $package) {
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

        $resource = BirthdayPackagesResource::collection($birthday->load(['cabanas', 'media_slider', 'media_cover', 'addons']))
    ->additional(['tickets' => $filteredTickets]);
        return $this->sendResponse(200, 'Retrieved Birthday Listing', $resource);
        
    }

    public function store(Request $request)
    {
    }
}
