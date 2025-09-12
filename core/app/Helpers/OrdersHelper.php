<?php
namespace App\Helpers;

use App;
use App\Models\Menu;
use URL;
use Helper;
use Illuminate\Support\Facades\Http;

class OrdersHelper
{
    //Add Order
    public static function addOrder($requestPayload)
    {
        $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
        $authCode = Helper::GeneralSiteSettings('auth_code_en');

        if (is_string($requestPayload)) {
            $requestPayload = json_decode($requestPayload, true);
        }
        // print_r($requestPayload);
        // die();
        
        $authCode = $requestPayload['AuthCode'] ?? null;
        $sessionId = $requestPayload['SessionId'] ?? null;
        $customer = $requestPayload['Customer'] ?? [];
        $purchases = $requestPayload['Purchases'] ?? [];
        $payment = $requestPayload['Payment'] ?? [];

        \Log::info('New Order Received', [
            'authCode' => $authCode,
            'sessionId' => $sessionId,
            'customerName' => ($customer['firstName'] ?? '') . ' ' . ($customer['lastName'] ?? ''),
            'totalAmount' => $payment['amount'] ?? 0
        ]);
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
                // Decode stringified JSON if needed
                if (is_string($item)) {
                    $item = json_decode($item, true);
                }

                // Force string conversion for sectionId if it exists
                if (isset($item['sectionId'])) {
                    $item['sectionId'] = (string) $item['sectionId'];
                }

                // Do the same for any other fields .NET expects as strings
                // e.g., capacityId, transactionId, etc., if needed

                return $item;
            }, $requestPayload['purchases']);
        }
        if (isset($requestPayload['Customer']) && is_string($requestPayload['Customer'])) {
            $requestPayload['Customer'] = json_decode($requestPayload['Customer'], true);
        }

        if (isset($requestPayload['Customer']) && is_array($requestPayload['Customer'])) {
            $customer = $requestPayload['Customer'];
            $requestPayload['Customer'] = [
                'FirstName' => $customer['firstName'] ?? '',
                'LastName' => $customer['lastName'] ?? '',
                'Email' => $customer['email'] ?? '',
                'Phone' => $customer['phone'] ?? '',
            ];
        }

        if (isset($requestPayload['Payment']) && is_string($requestPayload['Payment'])) {
            $requestPayload['Payment'] = json_decode($requestPayload['Payment'], true);
        }

        if (isset($requestPayload['Payment']) && is_array($requestPayload['Payment'])) {
            $payment = $requestPayload['Payment'];

            $requestPayload['Payment'] = [
                'PaymentCode'    => $payment['paymentCode'] ?? '',
                'Amount'         => (float) ($payment['amount'] ?? 0),
                'CardholerName'  => $payment['cardholerName'] ?? '',
                'BillingStreet'  => $payment['billingStreet'] ?? '',
                'BillingZipCode' => $payment['billingZipCode'] ?? '',
                'Cvn'            => $payment['cvn'] ?? '',
                'ExpDate'        => $payment['expDate'] ?? '',
                'CcNumber'       => $payment['ccNumber'] ?? '',
                'StaffTip'       => (float) ($payment['staffTip'] ?? 0),
                'Tax'            => (float) ($payment['tax'] ?? 0),
                'ServiceCharges' => (float) ($payment['serviceCharges'] ?? 0),
            ];
        }
        print_r($requestPayload);
        die();
         try {
            $response = Http::post(
                'http://dev-dynamicpricing-env.eba-tkbnkmbm.us-east-1.elasticbeanstalk.com/Pricing/BirthdayPackageAddOrder',
                $requestPayload
            );

            return response()->json([
                'status' => 'sent',
                'external_status' => $response->status(),
                'response' => $response->json()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    static function format_currency($amount, $currency = 'USD')
    {
        return '$ ' . number_format($amount, 2);
    }
}

?>
