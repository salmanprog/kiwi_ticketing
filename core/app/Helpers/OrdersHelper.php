<?php
namespace App\Helpers;

use App;
use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupons;
use URL;
use Helper;
use Illuminate\Support\Facades\Http;

class OrdersHelper
{
    //Add Order
    public static function generateOrder($requestPayload)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');

        if (is_string($requestPayload)) {
            $requestPayload = json_decode($requestPayload, true);
        }
        $get_customer_obj = User::where('id',$requestPayload['user_id'])->first();
        $prefixMap = [
            'birthday' => 'bd'.date("y").'_',
            'cabana' => 'ca'.date("y").'_',
            'general_ticket' => 'ge'.date("y").'_',
            'season_pass' => 'sp'.date("y").'_',
            'offer_creation' => 'of'.date("y").'_'
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
        $total_amount = $requestPayload['totalAmount'];
        if (isset($requestPayload['promoCode']) && $requestPayload['promoCode'] > 0) {
            $coupons = Coupons::find($requestPayload['promoCode']);
                    
                if ($coupons->discount_type === 'percentage') {
                    $discount = ($total_amount * $coupons->discount) / 100;
                } elseif ($coupons->discount_type === 'flat_rate') {
                    $discount = $coupons->discount;
                }
                $discount = min($discount, $total_amount);
                $total_amount = $total_amount - $discount;
        }
        $payment = [
                'cardholerName' => "Omitted",
                'billingStreet'  => "Teststreet123",
                'billingZipCode'     => "12345",
                'cvn'     => "Omitted",
                'expDate' => "Omitted",
                'ccNumber'  => "Omitted",
                'paymentCode'     => "32",
                'amount'     => $total_amount,
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
        }elseif($requestPayload['type'] == 'offer_creation'){
            $response = Http::post($baseUrl.'/Pricing/AnyDayAddOrder',$requestPayload);
        }else{  
            $response = Http::post($baseUrl.'/Pricing/BirthdayPackageAddOrder',$requestPayload);
        }
        
        return $response;
    }

    //Update or Upgrade Order
    public static function updateOrUpgradeOrder($requestPayload)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');

        if (is_string($requestPayload)) {
            $requestPayload = json_decode($requestPayload, true);
        }
        $get_customer_obj = User::where('id',$requestPayload['user_id'])->first();
        $prefixMap = [
            'birthday' => 'bd'.date("y").'_',
            'cabana' => 'ca'.date("y").'_',
            'general_ticket' => 'ge'.date("y").'_',
            'season_pass' => 'sp'.date("y").'_',
            'offer_creation' => 'of'.date("y").'_'
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
        $total_amount = $requestPayload['totalAmount'];
        if (isset($requestPayload['promoCode']) && $requestPayload['promoCode'] > 0) {
            $coupons = Coupons::find($requestPayload['promoCode']);
                    
                if ($coupons->discount_type === 'percentage') {
                    $discount = ($total_amount * $coupons->discount) / 100;
                } elseif ($coupons->discount_type === 'flat_rate') {
                    $discount = $coupons->discount;
                }
                $discount = min($discount, $total_amount);
                $total_amount = $total_amount - $discount;
        }
        $payment = [
                'cardholerName' => "Omitted",
                'billingStreet'  => "Teststreet123",
                'billingZipCode'     => "12345",
                'cvn'     => "Omitted",
                'expDate' => "Omitted",
                'ccNumber'  => "Omitted",
                'paymentCode'     => "32",
                'amount'     => $total_amount,
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
            $response = Http::post($baseUrl.'/Pricing/UpdateOrder',$requestPayload);
        }elseif($requestPayload['type'] == 'season_pass'){
            unset($requestPayload['isOfficeUse']);
            $response = Http::post($baseUrl.'/Pricing/UpdateOrder',$requestPayload);
        }elseif($requestPayload['type'] == 'offer_creation'){
            $response = Http::post($baseUrl.'/Pricing/UpdateOrder',$requestPayload);
        }else{  
            $response = Http::post($baseUrl.'/Pricing/UpdateOrder',$requestPayload);
        }
        
        return $response;
    }

    static function format_currency($amount, $currency = 'USD')
    {
        return '$ ' . number_format($amount, 2);
    }

    public static function order_types($type)
    {
        $orderTypes = ['birthday', 'cabana', 'general_ticket', 'season_pass', 'offer_creation'];

        return in_array($type, $orderTypes) ? $type : null;
    }
}

?>
