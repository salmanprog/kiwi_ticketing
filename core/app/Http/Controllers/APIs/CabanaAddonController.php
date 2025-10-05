<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cabana;
use App\Models\CabanaAddon;
use App\Helpers\ApiHelper;
use App\Http\Resources\CabanaAddonResource;

class CabanaAddonController extends BaseAPIController
{
    public function index()
    {
        $cabana = CabanaAddon::get();
        if ($cabana->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Cabana Addons Listing', []);
        }
        $resource = CabanaAddonResource::collection($cabana);
        return $this->sendResponse(200, 'Retrieved Cabana Addon Listing', $resource);
    }

    public function store(Request $request)
    {
       
    }

    public function show($slug)
    {
        $cabana_addon = CabanaAddon::where('cabanaSlug', $slug)->get();
        if ($cabana_addon->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Cabana Addons Listing', []);
        }
        $externalProducts = ApiHelper::getAddonWithoutCategory($cabana_addon);
        $externalMap = collect($externalProducts)->keyBy('ticketSlug');
        $cabana_addon->transform(function ($item) use ($externalMap) {
            $external = $externalMap[$item->ticketSlug] ?? null;
            $item->external_price = $external['price'] ?? null;
            $item->external_name = $external['ticketType'] ?? null;
            return $item;
        });
        $resource = CabanaAddonResource::collection($cabana_addon);
        return $this->sendResponse(200, 'Retrieved Cabana Addon Listing', $resource);
    }
}
