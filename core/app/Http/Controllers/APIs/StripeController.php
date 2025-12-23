<?php

namespace App\Http\Controllers\APIs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use App\Models\User;
use App\Http\Resources\StripePaymentIntentResource;
use Helper;

class StripeController extends BaseAPIController
{
    public function createPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(400, 'Validation Error', $validator->errors());
        }
        
        try {
            if (Helper::GeneralSiteSettings('stripe_live_activate_en',true) == '0') {
                Stripe::setApiKey(Helper::GeneralSiteSettings('stripe_test_sk_en',true));
            } else {
                Stripe::setApiKey(Helper::GeneralSiteSettings('stripe_live_sk_en',true));
            }
            $get_user = User::firstOrCreate(
                ['email' => $request->email],
                [
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'phone' => $request->phone,
                    'password' => bcrypt('Test@123'),
                    'permissions_id' => 3,
                    'status' => 0,
                    'created_by' => 1
                ]
            );

            // $paymentIntent = PaymentIntent::create([
            //     'amount' => $request->amount * 100,
            //     'currency' => 'usd',
            //     'payment_method_types' => ['card'],
            // ]);

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($request->amount * 100),
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'description' => 'Kiwi Ticketing | Bolder - Order #' . ($request->order_number ?? 'N/A') . ' from '. $request->first_name . ' ' . $request->last_name,

                'metadata' => [
                    'order_number'  => $request->order_number ?? '',
                    'customer_name' => $request->first_name . ' ' . $request->last_name,
                    'customer_email'=> $request->email,
                    'phone'         => $request->phone,
                ],
            ]);

            return response()->json([
                'code' => 200,
                'message' => 'Payment Intent created successfully.',
                'data' => [
                    'id' => $get_user->id,
                    'name' => $get_user->name,
                    'email' => $get_user->email,
                    'phone' => $get_user->phone,
                    'clientSecret' => $paymentIntent->client_secret
                ]
            ], 200);

        } catch (ApiErrorException $e) {
            return $this->sendResponse(400, 'Stripe Error', [
                'stripe' => [$e->getMessage()]
            ]);
        } catch (\Exception $e) {
            return $this->sendResponse(500, 'Server Error', [
                'error' => [$e->getMessage()]
            ]);
        }
    }
   
}
