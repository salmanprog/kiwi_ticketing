<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\WebmasterSection;
use Illuminate\Pagination\LengthAwarePaginator;
use Auth;
use File;
use Helper;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Stripe\Stripe;
use Stripe\PaymentIntent;


class StripeController extends Controller
{
    private $uploadPath = "uploads/sections/";

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function createPaymentIntent(Request $request)
    {
        if (Helper::GeneralSiteSettings('stripe_live_activate_en') == '0') {
            Stripe::setApiKey(Helper::GeneralSiteSettings('stripe_test_sk_en'));
        } else {
            Stripe::setApiKey(Helper::GeneralSiteSettings('stripe_live_sk_en'));
        }

        $paymentIntent = PaymentIntent::create([
            'amount' => 1999, // amount in cents ($19.99)
            'currency' => 'usd',
            'payment_method_types' => ['card'],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

}
