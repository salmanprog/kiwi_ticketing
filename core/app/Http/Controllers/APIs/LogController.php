<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ApiLog;
use App\Http\Resources\LogResource;
use Carbon\Carbon;
use Helper;
use App\Helpers\ApiHelper;
use Illuminate\Support\Facades\Http;

class LogController extends BaseAPIController
{
    public function index()
    {
        
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'order_number' => 'required',
            'endpoint' => 'required',
            'request' => 'required',
            'response' => 'required',
            'message' => 'required',
            'status' => 'required|in:failed,success',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        try {
            $apiLog = new ApiLog;
            $apiLog->type = $request->type;
            $apiLog->order_number = $request->order_number;
            $apiLog->endpoint = $request->endpoint;
            $apiLog->request = $request->request;
            $apiLog->response = $request->response;
            $apiLog->message = $request->message;
            $apiLog->status = $request->status;
            $apiLog->save();

            $resource = LogResource::make($apiLog);
            return $this->sendResponse(200, 'Generate Log successfully', $resource);
        } catch (\Exception $e) {
            return $this->sendResponse(400, 'Server Error', $e->getMessage());
        }
    }
}
