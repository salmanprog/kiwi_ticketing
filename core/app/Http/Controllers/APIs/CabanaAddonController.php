<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Cabana;
use App\Models\CabanaAddon;
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
        $resource = CabanaAddonResource::collection($cabana_addon);
        return $this->sendResponse(200, 'Retrieved Cabana Addon Listing', $resource);
    }
}
