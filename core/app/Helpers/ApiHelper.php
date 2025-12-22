<?php
namespace App\Helpers;

use App;
use URL;
use Helper;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ApiHelper
{
   
   public static function getProductByCategory($query, $category,$date)
    {
        try {
            $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
            $authCode = Helper::GeneralSiteSettings('auth_code_en');

            // $response = Http::get($baseUrl . '/Pricing/GetAllProductPrice', [
            //     'authcode' => $authCode,
            //     'date'     => $date,
            // ]);
            $filterParams = [];
            if (filter_var(env('API_FILTER'), FILTER_VALIDATE_BOOLEAN)) {
                parse_str(env('API_FILTER_PARAMS'), $filterParams);
            }
            $response = Http::get(
                $baseUrl . '/Pricing/GetAllProductPrice',
                [
                    'authcode' => $authCode,
                    'date' => $date,
                    ...$filterParams,
                ]
            );
            if ($response->successful()) {
                $apiData = $response->json();
                $products = $apiData['getAllProductPrice']['data'] ?? [];
                $products = array_map('mapTicketName', $products);
                $dbSlugs = $query->pluck('ticketSlug')->toArray();
                
                $filtered = ['ticket_addon' => []];

                foreach ($products as $product) {
                    if (
                        strcasecmp($product['ticketCategory'], $category) === 0 &&
                        in_array($product['ticketSlug'], $dbSlugs)
                    ) {
                        $filtered['ticket_addon'][] = $product;
                    }
                }

                return $filtered['ticket_addon']; // return only the list
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public static function getAddonWithoutCategory($query,$date)
    {
        try {
            $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
            $authCode = Helper::GeneralSiteSettings('auth_code_en');

            // $response = Http::get($baseUrl . '/Pricing/GetAllProductPrice', [
            //     'authcode' => $authCode,
            //     'date'     => $date,
            // ]);
            $filterParams = [];
            if (filter_var(env('API_FILTER'), FILTER_VALIDATE_BOOLEAN)) {
                parse_str(env('API_FILTER_PARAMS'), $filterParams);
            }
            $response = Http::get(
                $baseUrl . '/Pricing/GetAllProductPrice',
                [
                    'authcode' => $authCode,
                    'date' => $date,
                    ...$filterParams,
                ]
            );

            if ($response->successful()) {
                $apiData = $response->json();
                $products = $apiData['getAllProductPrice']['data'] ?? [];
                $products = array_map('mapTicketName', $products);
                $dbSlugs = $query->pluck('ticketSlug')->toArray();

                $filtered = [];

                foreach ($products as $product) {
                    if (in_array($product['ticketSlug'], $dbSlugs)) {
                        $filtered[] = $product;
                    }
                }

                return $filtered;
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}

?>
