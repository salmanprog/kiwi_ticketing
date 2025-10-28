<?php
namespace App\Helpers;

use App;
use App\Models\Menu;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupons;
use App\Models\OfferCreation;
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
        //$get_customer_obj = User::where('id',$requestPayload['user_id'])->first();
        $get_customer_obj = User::firstOrCreate(
                ['email' => $requestPayload['email']],
                [
                    'name' => $requestPayload['first_name'] . ' ' . $requestPayload['last_name'],
                    'phone' => $requestPayload['phone'],
                    'password' => bcrypt('Test@123'),
                    'permissions_id' => 3,
                    'status' => 0,
                    'created_by' => 1
                ]
            );
        $get_package = OrdersHelper::getPackagesByType($requestPayload['type'],$requestPayload['package_id']);
        $package_initials = substr($get_package->name, 0, 2);
        $prefixMap = [
            'birthday' => 'bd-'.$get_package->id.date("y").'_',
            'cabana' => 'ca-'.$get_package->id.date("y").'_',
            'general_ticket' => 'ge-'.$get_package->id.date("y").'_',
            'season_pass' => 'sp-'.$get_package->id.date("y").'_',
            'offer_creation' => 'of-'.$get_package->id.date("y").'_'
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
        //  print_r($requestPayload);
        // die();
        if($requestPayload['type'] == 'general_ticket'){
            $response = Http::post($baseUrl.'/Pricing/AddOrder',$requestPayload);
        }elseif($requestPayload['type'] == 'season_pass'){
            unset($requestPayload['isOfficeUse']);
            $response = Http::post($baseUrl.'/Pricing/SeasonPassAddOrder',$requestPayload);
        }elseif($requestPayload['type'] == 'offer_creation'){
            //  echo json_encode($requestPayload, true);
            //  die();
             $getType = OfferCreation::find($requestPayload['package_id']);
             if($getType->offerType == 'specifc_date'){
                $response = Http::post($baseUrl.'/Pricing/AddOrder',$requestPayload);
             }else{
                $response = Http::post($baseUrl.'/Pricing/AnyDayAddOrder',$requestPayload);
             }
            
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
        $get_order = Order::where('slug',$requestPayload['previousOrderNumber'])->first();
        $get_package = OrdersHelper::getPackagesByType($get_order->type,$get_order->package_id);
        $package_initials = substr($get_package->name, 0, 2);
        $prefixMap = [
            'birthday' => 'bd-'.$get_package->id.date("y").'_',
            'cabana' => 'ca-'.$get_package->id.date("y").'_',
            'general_ticket' => 'ge-'.$get_package->id.date("y").'_',
            'season_pass' => 'sp='.$get_package->id.date("y").'_',
            'offer_creation' => 'of='.$get_package->id.date("y").'_'
        ];

        $prefix = $prefixMap[$requestPayload['type']] ?? 'any_';
        $order_number = Order::generateUniqueSlug($prefix . date('Y') . rand(10000, 99999));
        
        $requestPayload['authCode'] = $authCode;
        $requestPayload['orderId'] = $order_number;
        $total_amount = $requestPayload['totalAmount'];
        $payment = [
                'cardholerName' => "Omitted",
                'billingStreet'  => "",
                'billingZipCode'     => "",
                'cvn'     => "Omitted",
                'expDate' => "Omitted",
                'ccNumber'  => "Omitted",
                'paymentCode'     => "32",
                'amount'     => $total_amount,
            ];
        $payment = json_decode(json_encode($payment));
        $requestPayload['payment'] = $payment;
        $requestPayload['isterminalPayment'] = filter_var($requestPayload['isterminalPayment'], FILTER_VALIDATE_BOOLEAN);
        $requestPayload['makeThisAddonsAsChild'] = filter_var($requestPayload['makeThisAddonsAsChild'], FILTER_VALIDATE_BOOLEAN);
        if (isset($requestPayload['ticketChanges']) && is_array($requestPayload['ticketChanges'])) {
            $requestPayload['ticketChanges'] = array_map(function ($item) {
                
                if (is_string($item)) {
                    $item = json_decode($item, true);
                }
                if (isset($item['sectionId'])) {
                    $item['sectionId'] = (string) $item['sectionId'];
                }
                return $item;
            }, $requestPayload['ticketChanges']);
        }
        if (isset($requestPayload['installmentType'])) {
            $val = $requestPayload['installmentType'];
            if ($val === null || $val === '') {
                $requestPayload['installmentType'] = '';
            } else {
                $requestPayload['installmentType'] = filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }
        } else {
            $requestPayload['installmentType'] = '';
        }
        unset($requestPayload['type']);
        unset($requestPayload['totalAmount']);
        unset($requestPayload['order_status']);
        unset($requestPayload['orderId']);
        //  echo json_encode($requestPayload, true);   
        //      die();
        $response = Http::post($baseUrl.'/Pricing/UpdateOrder',$requestPayload);
        //echo json_decode($response, true);
        
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

    public static function getPackagesByType($type,$package_id)
    {
        switch ($type) {
            case 'cabana':
                $packages = \App\Models\CabanaPackages::select('id', 'ticketType as name')->where('id',$package_id)->first();
                break;
            case 'birthday':
                $packages = \App\Models\BirthdayPackages::select('id', 'title as name')->where('id',$package_id)->first();
                break;
            case 'general_ticket':
                $packages = \App\Models\GeneralTicketPackages::select('id', 'title as name')->where('id',$package_id)->first();
                break;
            case 'season_pass':
                $packages = \App\Models\SeasonPass::select('id', 'title as name')->where('id',$package_id)->first();
                break;
            case 'offer_creation':
                $packages = \App\Models\OfferCreation::select('id', 'title as name')->where('id',$package_id)->first();
                break;
            default:
                $packages = [];
        }

        return $packages;
    }
}

?>
