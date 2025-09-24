<?php
namespace App\Helpers;

use App;
use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use URL;
use Helper;
use Illuminate\Support\Facades\Http;

class OrdersHelper
{
    //Add Order
    public static function birthDayOrder($requestPayload)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');

        if (is_string($requestPayload)) {
            $requestPayload = json_decode($requestPayload, true);
        }
        $get_customer_obj = User::where('id',$requestPayload['user_id'])->first();
        $prefixMap = [
            'birthday' => 'bd_',
            'cabana' => 'ca_',
            'general_ticket' => 'ge_',
            'season_pass' => 'sp_'
        ];

        $prefix = $prefixMap[$requestPayload['type']] ?? 'any_';
        $order_number = Order::generateUniqueSlug($prefix . date('Y') . rand(10000, 99999));
        if (isset($get_customer_obj) && isset($get_customer_obj->name)) {
            $nameParts = explode(' ', trim($get_customer_obj->name));
            $customer = [
                'FirstName' => $nameParts[0] ?? '',
                'LastName'  => isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '',
                'Email'     => $get_customer_obj->email ?? '',
                'Phone'     => $get_customer_obj->phone ?? '',
            ];
        }
        $customer_object = json_decode(json_encode($customer));
        $requestPayload['customer'] = $customer_object;
        $requestPayload['authCode'] = $authCode;
        $requestPayload['orderId'] = $order_number;
        $payment = [
                'cardholerName' => "Omitted",
                'billingStreet'  => "Teststreet123",
                'billingZipCode'     => "12345",
                'cvn'     => "Omitted",
                'expDate' => "Omitted",
                'ccNumber'  => "Omitted",
                'paymentCode'     => "32",
                'amount'     => $requestPayload['totalAmount'],
            ];
        $payment = json_decode(json_encode($payment));
        $requestPayload['payment'] = $payment;
         $requestPayload['isterminalPayment'] = filter_var($requestPayload['isterminalPayment'], FILTER_VALIDATE_BOOLEAN);
        if (isset($requestPayload['isOfficeUse'])) {
            $val = $requestPayload['isOfficeUse'];
            if ($val === null || $val === '') {
                $requestPayload['isOfficeUse'] = null;
            } else {
                $requestPayload['isOfficeUse'] = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }
        } else {
            $requestPayload['isOfficeUse'] = null;
        }
        if (isset($requestPayload['purchases']) && is_array($requestPayload['purchases'])) {
            $requestPayload['purchases'] = array_map(function ($item) {
                if (is_string($item)) {
                    $item = json_decode($item, true);
                }
                if (isset($item['sectionId'])) {
                    $item['sectionId'] = (string) $item['sectionId'];
                }
                return $item;
            }, $requestPayload['purchases']);
        }
        
        if($requestPayload['type'] == 'general_ticket'){
            $response = Http::post($baseUrl.'/Pricing/AddOrder',$requestPayload);
        }elseif($requestPayload['type'] == 'season_pass'){
            unset($requestPayload['isOfficeUse']);
            $response = Http::post($baseUrl.'/Pricing/SeasonPassAddOrder',$requestPayload);
        }else{  
            $response = Http::post($baseUrl.'/Pricing/BirthdayPackageAddOrder',$requestPayload);
        }
        
        return $response;
    }

    static function format_currency($amount, $currency = 'USD')
    {
        return '$ ' . number_format($amount, 2);
    }
}

?>
