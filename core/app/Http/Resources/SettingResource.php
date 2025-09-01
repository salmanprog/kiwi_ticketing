<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'app_title' => $this->site_title_en,
            'app_description' => $this->site_desc_en,
            'app_url' => $this->site_url,
            'logo_url' => $this->style_logo_en ? url('uploads/settings/' . $this->style_logo_en) : null,
            'contact_info' => [
                'address' => $this->contact_t1_en,
                'phone' => $this->contact_t3,
                'fax' => $this->contact_t4,
                'mobile' => $this->contact_t5,
                'email' => $this->contact_t6,
                'working_time' => $this->contact_t7_en
            ],
            'social_info' => [
                'facebook_url' => $this->social_link1,
                'twitter_url' => $this->social_link2,
                'linkedin_url' => $this->social_link3,
                'youtube_url' => $this->social_link4,
                'instagram_url' => $this->social_link5,
                'pinterest_url' => $this->social_link6,
                'threads_url' => $this->social_link7,
                'snapchat_url' => $this->social_link8,
                'whatsapp_url' => $this->social_link9,
            ],
        ];
    }
}
