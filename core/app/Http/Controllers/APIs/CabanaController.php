<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CabanaPackages;
use App\Http\Resources\CabanaResource;
use Carbon\Carbon;
use Helper;

class CabanaController extends BaseAPIController
{
    public function index()
    {   
         $authCode = Helper::GeneralSiteSettings('auth_code_en');
        $cabana = CabanaPackages::with(['media_slider'])->where('is_featured', '=', '1')->where('status', '=', '1')->where('auth_code', $authCode)->get();
        if ($cabana->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Cabana Listing', []);
        }
        $resource = CabanaResource::collection($cabana);
        return $this->sendResponse(200, 'Retrieved Cabana Listing', $resource);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'venueId' => 'required|integer',
            'ticketType' => 'required|string|max:255',
            'ticketSlug' => 'required|string|max:255',
            'ticketCategory' => 'required|string|max:255',
            'price' => 'required|numeric',
            'featured' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        $check_cabana_product = Cabana::where('venueId', $request->venueId)->where('ticketType', $request->ticketType)->where('ticketSlug', $request->ticketSlug)->where('ticketCategory', $request->ticketCategory)->first();

        if ($check_cabana_product) {
            Cabana::where('venueId', $request->venueId)->where('ticketType', $request->ticketType)->where('ticketSlug', $request->ticketSlug)->where('ticketCategory', $request->ticketCategory)->delete();
        }
        $cabana = Cabana::create([
            'venueId' => $request->venueId,
            'ticketType' => $request->ticketType,
            'ticketSlug' => $request->ticketSlug,
            'ticketCategory' => $request->ticketCategory,
            'price' => $request->price,
            'featured' => $request->featured ?? 0,
        ]);
        $get_last_cabana = Cabana::where('id', $cabana->id)->where('featured', '0')->first();
        if ($check_cabana_product) {
            Cabana::where('id', $cabana->id)->where('featured', '0')->delete();
        }
        return $this->sendResponse(201, 'Cabana created successfully', new CabanaResource($cabana));
    }
}
