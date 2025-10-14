<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CabanaResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Helper;
use App\Helpers\MailHelper;

class EmailController extends BaseAPIController
{
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:255',
            'mail_to' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }

        try {
            $emailSent = MailHelper::sendEmail((object)[
                'mail_to' => $request->mail_to,
                'identifier' => $request->identifier
            ]);

            if ($emailSent) {
                return $this->sendResponse(200, 'Email sent successfully.');
            } else {
                return $this->sendResponse(500, 'Failed to send email.');
            }

        } catch (\Exception $e) {
            return $this->sendResponse(500, 'Server Error', $e->getMessage());
        }
    }
}
