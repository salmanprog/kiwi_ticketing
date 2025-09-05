<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\BirthdayPackages;
use App\Http\Resources\BirthdayPackagesResource;

class BirthdayController extends BaseAPIController
{
    public function index()
    {
        $birthday = BirthdayPackages::where('status','1')->get();
        if ($birthday->isEmpty()) {
            return $this->sendResponse(200, 'Retrieved Birthday Listing', []);
        }
        $resource = BirthdayPackagesResource::collection($birthday);
        return $this->sendResponse(200, 'Retrieved Birthday Listing', $resource);
    }

    public function store(Request $request)
    {
    }
}
