<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\GeneralTicketPackages;
use App\Models\GeneralTicketAddon;
use App\Models\GeneralTicketCabana;
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
        $generalTicket = GeneralTicketPackages::with(['media_slider','general_addons'])->where('auth_code',$authCode)->where('status','1')->get();
         if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved General Packages Listing', []);
        }
        $resource = GeneralTicketsResource::collection($generalTicket);
        return $this->sendResponse(200, 'Retrieved General Packages Listing', $resource);


        // $generalTicket = GeneralTickets::with(['media_slider','addons','cabanas'])->where('auth_code',$authCode)->get();
        
        // if ($generalTicket->isEmpty()) {
        //     return $this->sendResponse(200, 'Retrieved General Tickets Listing', []);
        // }

        // try {
        //     $response = Http::get("{$baseUrl}/Pricing/GetAllProductPrice", [
        //         'authcode' => $authCode,
        //         'date' => $date,
        //     ]);
        //     $data = $response->json();
        //     if (isset($data['status']['errorCode']) && $data['status']['errorCode'] === 1) {
        //         return $this->sendResponse(400, 'General Ticket Error', ['error' => $data['status']['errorMessage']]);
        //     }
        //     $tickets = $data['getAllProductPrice']['data'] ?? [];
        //     $filteredTickets = [];
        //     if (count($tickets) > 0) {
        //         $ticketSlugs = array_column($tickets, 'ticketSlug');
        //         $generalTickets = GeneralTickets::with(['media_slider','addons.media_slider'])
        //             ->where('auth_code', $authCode)
        //             ->whereIn('ticketSlug', $ticketSlugs)
        //             ->get()
        //             ->keyBy('ticketSlug');
                
        //         foreach ($tickets as &$ticket) {
        //             if ($ticket['venueId'] != 0) {
        //                 $generalTicket = $generalTickets->get($ticket['ticketSlug']);
        //                 if ($generalTicket) {
        //                     $ticket['description'] = $generalTicket->description;

        //                     if ($generalTicket->media_slider && $generalTicket->media_slider->count() > 0) {
        //                         $ticket['image_url'] = url($generalTicket->media_slider->first()->file_url);
        //                     }

        //                     // Add addons as array
        //                     $ticket['addons'] = $generalTicket->addons->map(function($addon) {
        //                         return [
        //                             'venueId' => $addon->venueId,
        //                             'ticketType' => $addon->ticketType,
        //                             'ticketSlug' => $addon->ticketSlug,
        //                             'ticketCategory' => $addon->ticketCategory,
        //                             'price' => $addon->price,
        //                             'new_price' => $addon->new_price,
        //                             'description' => $addon->description,
        //                             'image_url' => ($addon->media_slider && $addon->media_slider->count() > 0)
        //                                             ? url($addon->media_slider->first()->file_url)
        //                                             : null,
        //                         ];
        //                     })->toArray();

        //                     $filteredTickets[] = $ticket;
        //                 }
        //             }
        //         }

        //     }
        //     return $this->sendResponse(200, 'General Ticket fetched successfully', $filteredTickets);
        // } catch (\Exception $e) {
        //     return $this->sendResponse(401, 'Server Error', $e->getMessage());
        // }
    }

    public function generalTicketAddon($slug)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = GeneralTicketAddon::with(['media_slider'])->where('generalTicketSlug', $slug)->where('status', '1')->where('auth_code',$authCode)->get();
        
        if ($generalTicket->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved General Package Addon Listing', []);
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
                $generalTickets = GeneralTicketAddon::with(['media_slider'])
                    ->where('auth_code', $authCode)
                    ->where('generalTicketSlug', $slug)
                    ->whereIn('ticketSlug', $ticketSlugs)
                    ->get()
                    ->keyBy('ticketSlug');
                
                foreach ($tickets as &$ticket) {
                    if (
                        isset($generalTickets[$ticket['ticketSlug']])
                    ) {
                        $generalTicket = $generalTickets[$ticket['ticketSlug']];
                        $ticket['description'] = $generalTicket->description;
                        $ticket['is_primary'] = $generalTicket->is_primary;
                        $ticket['new_price'] = $generalTicket->new_price;
                        $ticket['is_new_price_show'] = $generalTicket->is_new_price_show;
                        if ($generalTicket->media_slider && $generalTicket->media_slider->isNotEmpty()) {
                            $ticket['image_url'] = url($generalTicket->media_slider->first()->file_url);
                        }

                        $filteredTickets[] = $ticket;
                    }
                }
            }
            return $this->sendResponse(200, 'General Package Addon fetched successfully', $filteredTickets);
        } catch (\Exception $e) {
            return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }

    public function generalTicketCabana($slug)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $date = Carbon::today()->toDateString();
        $generalTicket = GeneralTicketCabana::with(['media_slider'])->where('generalTicketSlug', $slug)->where('auth_code',$authCode)->get();
        
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
                $generalTickets = GeneralTicketCabana::with(['media_slider'])
                    ->where('auth_code', $authCode)
                    ->where('generalTicketSlug', $slug)
                    ->whereIn('ticketSlug', $ticketSlugs)
                    ->get()
                    ->keyBy('ticketSlug');
                
                foreach ($tickets as &$ticket) {
                    if (
                        ($ticket['ticketCategory'] === 'Cabanas') &&
                        isset($generalTickets[$ticket['ticketSlug']])
                    ) {
                        $generalTicket = $generalTickets[$ticket['ticketSlug']];
                        $ticket['description'] = $generalTicket->description;

                        if ($generalTicket->media_slider && $generalTicket->media_slider->isNotEmpty()) {
                            $ticket['image_url'] = url($generalTicket->media_slider->first()->file_url);
                        }

                        $filteredTickets[] = $ticket;
                    }
                }
            }
            return $this->sendResponse(200, 'General Ticket fetcheds successfully', $filteredTickets);
        } catch (\Exception $e) {
            return $this->sendResponse(401, 'Server Error', $e->getMessage());
        }
    }
}
