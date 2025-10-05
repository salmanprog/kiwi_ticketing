<?php
namespace App\Helpers;

use App;
use URL;
use Helper;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class ApiHelper
{
   
   public static function getProductByCategory($query, $category)
    {
        try {
            $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
            $authCode = Helper::GeneralSiteSettings('auth_code_en');
            $date = Carbon::today()->toDateString();

            $response = Http::get($baseUrl . '/Pricing/GetAllProductPrice', [
                'authcode' => $authCode,
                'date'     => $date,
            ]);
            if ($response->successful()) {
                $apiData = $response->json();
                $products = $apiData['getAllProductPrice']['data'] ?? [];
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

    public static function getAddonWithoutCategory($query)
    {
        try {
            $baseUrl = Helper::GeneralSiteSettings('external_api_link_en');
            $authCode = Helper::GeneralSiteSettings('auth_code_en');
            $date = Carbon::today()->toDateString();

            $response = Http::get($baseUrl . '/Pricing/GetAllProductPrice', [
                'authcode' => $authCode,
                'date'     => $date,
            ]);

            if ($response->successful()) {
                $apiData = $response->json();
                $products = $apiData['getAllProductPrice']['data'] ?? [];
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
