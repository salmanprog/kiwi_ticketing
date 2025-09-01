<?php

namespace App\Http\Controllers\APIs;

use App\Models\Setting;
use App\Http\Resources\SettingResource;

class GeneralController extends BaseAPIController
{
    public function website_setting()
    {
        $setting = Setting::find(1);
        if (!$setting) {
            return $this->sendResponse(404, 'General Setting not found', null);
        }
        $resource = new SettingResource($setting);
        return $this->sendResponse(200, 'Retrieved General Setting', $resource);
    }
}
