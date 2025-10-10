<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BirthdayPackages;
use App\Models\BirthdayAddon;
use App\Helpers\ApiHelper;
use App\Http\Resources\BirthdayAddonResource;

class BirthdayAddonController extends BaseAPIController
{
    public function index()
    {
        $birthday = BirthdayAddon::where('status','1')->get();
        if ($birthday->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Birthday Addons Listing', []);
        }
        $resource = BirthdayAddonResource::collection($birthday);
        return $this->sendResponse(200, 'Retrieved Birthday Addon Listing', $resource);
    }

    public function store(Request $request)
    {
       
    }

    public function show(Request $request,$slug)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }
        $params = $request->all();
        $birthday_addon = BirthdayAddon::where('birthday_slug', $slug)->get();
        if ($birthday_addon->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Birthday Addons Listing', []);
        }
        $externalProducts = ApiHelper::getAddonWithoutCategory($birthday_addon,$params['date']);
        $externalMap = collect($externalProducts)->keyBy('ticketSlug');
        $birthday_addon->transform(function ($item) use ($externalMap) {
            $external = $externalMap[$item->ticketSlug] ?? null;
            $item->external_price = $external['price'] ?? null;
            $item->external_name = $external['ticketType'] ?? null;
            return $item;
        });
        $resource = BirthdayAddonResource::collection($birthday_addon);
        return $this->sendResponse(200, 'Retrieved Birthday Addon Listing', $resource);
    }
}
